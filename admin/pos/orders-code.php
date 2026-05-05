<?php

include('../../config/function.php');

if(!isset($_SESSION['orderItems'])){
    $_SESSION['orderItems'] = [];
}

/*
|--------------------------------------------------------------------------
| ADD SERVICE
|--------------------------------------------------------------------------
*/
if(isset($_POST['addService']))
{
    $serviceId = $_POST['service_id'];
    $qty = $_POST['service_qty'];

    if(empty($serviceId)){
        redirect('order-create.php','Select service first');
    }

    $query = mysqli_query($conn, "SELECT * FROM services WHERE id='$serviceId' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    // prevent duplicate
    $found = false;

    foreach($_SESSION['orderItems'] as $key => $item){
        if($item['type'] == 'service' && $item['id'] == $row['id']){
            $_SESSION['orderItems'][$key]['quantity'] += $qty;
            $found = true;
        }
    }

    if(!$found){
        $_SESSION['orderItems'][] = [
            'type' => 'service',
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $qty
        ];
    }

    redirect('order-create.php','Service Added');
}

/*
|--------------------------------------------------------------------------
| ADD CONSUMABLE
|--------------------------------------------------------------------------
*/
if(isset($_POST['addConsumable']))
{
    $itemId = $_POST['consumable_id'];
    $qty = $_POST['item_qty'];

    if(empty($itemId)){
        redirect('order-create.php','Select item first');
    }

    $query = mysqli_query($conn, "SELECT * FROM laundry_consumables WHERE id='$itemId' LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    $_SESSION['orderItems'][] = [
        'type' => 'item',
        'id' => $row['id'],
        'name' => $row['item_name'],
        'price' => $row['price'] ?? 0,
        'quantity' => $qty
    ];

    redirect('order-create.php','Item Added');
}

/*
|--------------------------------------------------------------------------
| UPDATE QUANTITY
|--------------------------------------------------------------------------
*/
if(isset($_POST['serviceIncDec']))
{
    $serviceId = validate($_POST['service_Id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;

    foreach($_SESSION['orderItems'] as $key => $item){
        if($item['type'] == 'service' && $item['id'] == $serviceId){

            $_SESSION['orderItems'][$key]['quantity'] = $quantity;
            $flag = true;
        }
    }

    if($flag){
        jsonResponse(200, 'success', 'Quantity Updated');
    }else{
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
}

/*
|--------------------------------------------------------------------------
| PROCEED CUSTOMER
|--------------------------------------------------------------------------
*/
if(isset($_POST['proceedToPlaceBtn']))
{
    // prevent double process for backend
    if(isset($_SESSION['proceed_lock']) && $_SESSION['proceed_lock'] === true){
        jsonResponse(409, 'warning', 'Already processing, please wait...');
    }

    $_SESSION['proceed_lock'] = true;

    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");

    if($checkCustomer){
        if(mysqli_num_rows($checkCustomer) > 0)
        {
            $_SESSION['invoice_no'] = "INV-".rand(111111,999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;

            unset($_SESSION['proceed_lock']); //  release lock

            jsonResponse(200, 'success', 'Customer Found');
        }
        else
        {
            $_SESSION['cphone'] = $phone;

            unset($_SESSION['proceed_lock']); //  release lock
            jsonResponse(404, 'warning', 'Customer Not Found');
        }
    }
    else
    {
        unset($_SESSION['proceed_lock']); //  release lock
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
}

/*
|--------------------------------------------------------------------------
| SAVE CUSTOMER
|--------------------------------------------------------------------------
*/
if(isset($_POST['saveCustomerBtn']))
{
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);

    if($name != '' && $phone != ''){

        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ];

        $result = insert('customers', $data);

        if($result){
            jsonResponse(200, 'success', 'Customer Created Successfully');
        }else{
            jsonResponse(500, 'error', 'Something Went Wrong');
        }

    }else{
        jsonResponse(422, 'warning', 'Please fill required fields');
    }
}

/*
|--------------------------------------------------------------------------
| SAVE ORDER
|--------------------------------------------------------------------------
*/
if(isset($_POST['saveOrder']))
{
    if(empty($_SESSION['orderItems'])){
        jsonResponse(404,'warning', 'No Items to place order!');
    }

    $phone = validate($_SESSION['cphone']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");

    if(!$checkCustomer){
        jsonResponse(500,'error', 'Something Went Wrong!');
    }

    if(mysqli_num_rows($checkCustomer) > 0)
    {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        $sessionItems = $_SESSION['orderItems'];

        $totalAmount = 0;
        foreach($sessionItems as $item){
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $data = [
            'customer_id' => $customerData['id'],
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,
            'order_placed_by_id' => $order_placed_by_id
        ];

        $result = insert('orders', $data);
        $lastOrderId = mysqli_insert_id($conn);

        // OR number
        $trackingNo = 'OR-' . str_pad($lastOrderId, 5, '0', STR_PAD_LEFT);
        mysqli_query($conn, "UPDATE orders SET tracking_no='$trackingNo' WHERE id='$lastOrderId'");

        foreach($sessionItems as $item){

            if($item['type'] == 'service'){
                $dataOrderItem = [
                    'order_id' => $lastOrderId,
                    'service_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ];
            } else {
                $dataOrderItem = [
                    'order_id' => $lastOrderId,
                    'consumable_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ];
            }

            insert('order_items', $dataOrderItem);
        }

        unset($_SESSION['orderItems']);
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['invoice_no']);

        jsonResponse(200, 'success', 'Order Placed Successfully');
    }
    else
    {
        jsonResponse(404, 'warning', 'No Customer Found!');
    }
}

?>