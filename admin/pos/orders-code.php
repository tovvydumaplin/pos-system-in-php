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
    $qty = (int) $_POST['item_qty'];

    if(empty($itemId)){
        redirect('order-create.php','Select item first');
    }

    if($qty <= 0){
        redirect('order-create.php','Quantity must be greater than 0');
    }

    $query = mysqli_query($conn, "
        SELECT * 
        FROM laundry_consumables 
        WHERE id='$itemId' 
        LIMIT 1
    ");

    $row = mysqli_fetch_assoc($query);

    if(!$row){
        redirect('order-create.php','Item not found');
    }

    $existingQty = 0;

    foreach($_SESSION['orderItems'] as $item){
        if($item['type'] == 'item' && $item['id'] == $row['id']){
            $existingQty += $item['quantity'];
        }
    }


    $totalQty = $existingQty + $qty;


    if($totalQty > $row['quantity']){

        $remaining = $row['quantity'] - $existingQty;

        redirect(
            'order-create.php',
            'Only '.$remaining.' stock remaining for '.$row['item_name']
        );
    }

    $found = false;

    foreach($_SESSION['orderItems'] as $key => $item){

        if($item['type'] == 'item' && $item['id'] == $row['id']){

            $_SESSION['orderItems'][$key]['quantity'] += $qty;

            $found = true;
        }
    }


    if(!$found){

        $_SESSION['orderItems'][] = [
            'type' => 'item',
            'id' => $row['id'],
            'name' => $row['item_name'],
            'price' => $row['price'] ?? 0,
            'quantity' => $qty
        ];
    }

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
    $branch_id = $_SESSION['loggedInUser']['branch_id'];

    if($name != '' && $phone != ''){

        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'branch_id' => $branch_id,
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
    // CHECK IF ORDER ITEMS EXIST
    if(empty($_SESSION['orderItems'])){
        jsonResponse(404,'warning', 'No Items to place order!');
    }

    $phone = validate($_SESSION['cphone']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];
    $branch_id = $_SESSION['loggedInUser']['branch_id'];

    // CHECK CUSTOMER
    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");

    if(!$checkCustomer){
        jsonResponse(500,'error', 'Something Went Wrong!');
    }

    if(mysqli_num_rows($checkCustomer) > 0)
    {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        $sessionItems = $_SESSION['orderItems'];

        // CALCULATE TOTAL (including consumables from services)
        $totalAmount = 0;
        foreach($sessionItems as $item){
            $lineTotal = $item['price'] * $item['quantity'];
            $totalAmount += $lineTotal;
            
            // Add consumables cost for services
            if($item['type'] == 'service'){
                $serviceConsumables = mysqli_query($conn, "
                    SELECT si.quantity_required, lc.price
                    FROM service_items si
                    JOIN laundry_consumables lc ON lc.id = si.consumable_id
                    WHERE si.service_id = '{$item['id']}'
                ");
                
                if($serviceConsumables && mysqli_num_rows($serviceConsumables) > 0){
                    while($sc = mysqli_fetch_assoc($serviceConsumables)){
                        $consumablesCost = ($sc['price'] * $sc['quantity_required'] * $item['quantity']);
                        $totalAmount += $consumablesCost;
                    }
                }
            }
        }

        // INSERT ORDER
        $data = [
            'customer_id' => $customerData['id'],
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,
            'order_placed_by_id' => $order_placed_by_id,
            'branch_id' => $branch_id
        ];

        $result = insert('orders', $data);

        if(!$result){
            jsonResponse(500, 'error', 'Failed to create order');
        }

        $lastOrderId = mysqli_insert_id($conn);

        // GENERATE TRACKING NUMBER
        $trackingNo = 'OR-' . str_pad($lastOrderId, 5, '0', STR_PAD_LEFT);

        mysqli_query($conn, "
            UPDATE orders 
            SET tracking_no='$trackingNo' 
            WHERE id='$lastOrderId'
        ");

        // SAVE ORDER ITEMS
        foreach($sessionItems as $item){

            // =========================
            // SERVICE
            // =========================
            // CHECKS IF ITEM IS SERVICE TYPE OR CONSUMABLE TYPE 
            if($item['type'] == 'service'){

                $dataOrderItem = [
                    'order_id' => $lastOrderId,
                    'service_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ];

                insert('order_items', $dataOrderItem);

                // Deduct service_items consumables from stock
                $svcItems = mysqli_query($conn, "
                    SELECT si.consumable_id, si.quantity_required,
                           lc.item_name, lc.quantity as stock_qty, lc.price
                    FROM service_items si
                    JOIN laundry_consumables lc ON lc.id = si.consumable_id
                    WHERE si.service_id = '{$item['id']}'
                ");

                if ($svcItems && mysqli_num_rows($svcItems) > 0) {
                    foreach ($svcItems as $svcItem) {
                        $deductQty = $svcItem['quantity_required'] * $item['quantity'];
                        $newStock  = $svcItem['stock_qty'] - $deductQty;

                        if ($newStock < 0) {
                            jsonResponse(422, 'warning',
                                'Not enough stock for ' . $svcItem['item_name'] .
                                ' (needs ' . $deductQty . ', has ' . $svcItem['stock_qty'] . ')'
                            );
                        }

                        // Save consumable to order_items as part of service
                        insert('order_items', [
                            'order_id' => $lastOrderId,
                            'service_id' => $item['id'],
                            'consumable_id' => $svcItem['consumable_id'],
                            'price' => $svcItem['price'],
                            'quantity' => $deductQty,
                        ]);

                        mysqli_query($conn, "
                            UPDATE laundry_consumables
                            SET quantity = '$newStock'
                            WHERE id = '{$svcItem['consumable_id']}'
                        ");

                        insert('stock_movement', [
                            'consumable_id' => $svcItem['consumable_id'],
                            'movement_type' => 'OUT',
                            'quantity'      => $deductQty,
                            'reference_no'  => $trackingNo,
                            'remarks'       => 'Used in service: ' . $item['name'],
                            'created_by'    => $order_placed_by_id,
                            'branch_id'     => $branch_id,
                        ]);
                    }
                }

            }

            // =========================
            // CONSUMABLE
            // =========================
            else {

                $consumableId = $item['id'];
                $deductQty = $item['quantity'];

                $checkStock = mysqli_query($conn, "
                    SELECT * FROM laundry_consumables 
                    WHERE id='$consumableId' 
                    LIMIT 1
                ");

                if($checkStock && mysqli_num_rows($checkStock) > 0)
                {
                    $stockData = mysqli_fetch_assoc($checkStock);

                    // PREVENT NEGATIVE STOCK
                    if($stockData['quantity'] < $deductQty){

                        jsonResponse(
                            422,
                            'warning',
                            'Not enough stock for '.$stockData['item_name']
                        );
                    }

                    // SAVE
                    $dataOrderItem = [
                        'order_id' => $lastOrderId,
                        'consumable_id' => $consumableId,
                        'price' => $item['price'],
                        'quantity' => $deductQty,
                    ];

                    insert('order_items', $dataOrderItem);

                    // DEDUCT STOCK
                    $newQty = $stockData['quantity'] - $deductQty;

                    mysqli_query($conn, "
                        UPDATE laundry_consumables 
                        SET quantity='$newQty'
                        WHERE id='$consumableId'
                    ");
                    
                    $movementData = [
                        'consumable_id' => $consumableId,
                        'movement_type' => 'OUT',
                        'quantity' => $deductQty,
                        'reference_no' => $trackingNo,
                        'remarks' => 'Used in customer order',
                        'created_by' => $order_placed_by_id,
                        'branch_id' => $branch_id
                    ];

                    insert('stock_movement', $movementData);
                }
            }
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

/*
|--------------------------------------------------------------------------
| CANCEL ORDER
|--------------------------------------------------------------------------
*/
if(isset($_GET['cancel']))
{
    $trackingNo = validate($_GET['cancel']);

    $userType = $_SESSION['loggedInUser']['user_type'];
    $branchId = $_SESSION['loggedInUser']['branch_id'];
    $userId   = $_SESSION['loggedInUser']['user_id'];

    $branchClause = ($userType == 'super_admin')
        ? ""
        : "AND branch_id='$branchId'";

    // prevent duplicate restoring
    $checkOrder = mysqli_query($conn,"
        SELECT *
        FROM orders
        WHERE tracking_no='$trackingNo'
        $branchClause
        LIMIT 1
    ");

    if(mysqli_num_rows($checkOrder) == 0){
        redirect('orders.php','Order not found');
    }

    $orderData = mysqli_fetch_assoc($checkOrder);

    if(strtolower($orderData['order_status']) == 'cancelled'){
        redirect(
            "orders-view.php?track=".$trackingNo,
            "Order already cancelled"
        );
    }

    $orderId = $orderData['id'];

    // get all consumables from this order
    $orderItems = mysqli_query($conn,"
        SELECT *
        FROM order_items
        WHERE order_id='$orderId'
        AND consumable_id IS NOT NULL
    ");

    foreach($orderItems as $item){

        $consumableId = $item['consumable_id'];
        $returnQty = $item['quantity'];

        // current stock
        $stockQuery = mysqli_query($conn,"
            SELECT *
            FROM laundry_consumables
            WHERE id='$consumableId'
            LIMIT 1
        ");

        if($stockQuery && mysqli_num_rows($stockQuery)>0){

            $stock = mysqli_fetch_assoc($stockQuery);

            $newQty = $stock['quantity'] + $returnQty;

            // return stock
            mysqli_query($conn,"
                UPDATE laundry_consumables
                SET quantity='$newQty'
                WHERE id='$consumableId'
            ");

            // stock movement log
            $movementData = [
                'consumable_id' => $consumableId,
                'movement_type' => 'IN',
                'quantity' => $returnQty,
                'reference_no' => $trackingNo,
                'remarks' => 'Returned from cancelled order',
                'created_by' => $userId,
                'branch_id' => $orderData['branch_id']
            ];

            insert('stock_movement',$movementData);
        }
    }

    // update order 
    mysqli_query($conn,"
        UPDATE orders
        SET order_status='cancelled'
        WHERE id='$orderId'
    ");

    redirect(
        "orders-view.php?track=".$trackingNo,
        "Order cancelled and stock restored"
    );
}

/*
|--------------------------------------------------------------------------
| RELEASE ORDER
|--------------------------------------------------------------------------
*/
if(isset($_GET['release']))
{
    $trackingNo = validate($_GET['release']);

    $userType = $_SESSION['loggedInUser']['user_type'];
    $branchId = $_SESSION['loggedInUser']['branch_id'];

    // this one is for super admin
    $branchClause = ($userType == 'super_admin')
        ? ""
        : "AND branch_id='$branchId'";

    $checkOrder = mysqli_query($conn,"
        SELECT *
        FROM orders
        WHERE tracking_no='$trackingNo'
        $branchClause
        LIMIT 1
    ");

    if(mysqli_num_rows($checkOrder)==0){
        redirect('orders.php','Order not found');
    }

    $orderData = mysqli_fetch_assoc($checkOrder);

    if(strtolower($orderData['order_status']) != 'booked'){
        redirect(
            "orders-view.php?track=".$trackingNo,
            "Order already processed"
        );
    }

    mysqli_query($conn,"
        UPDATE orders
        SET order_status='released'
        WHERE id='".$orderData['id']."'
    ");

    redirect(
        "orders-view.php?track=".$trackingNo,
        "Order marked as released"
    );
}
?>