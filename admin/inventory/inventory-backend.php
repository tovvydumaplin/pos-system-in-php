<?php
require '../../config/function.php';

if(isset($_POST['saveItem']))
{
    $name = validate($_POST['item_name']);
    $qty = validate($_POST['quantity']);
    $price = validate($_POST['price']);

    if($name == '' || $qty == '' || $price == ''){
        echo json_encode([
            'status' => 422,
            'message' => 'All fields are required'
        ]);
        return;
    }

    $data = [
        'item_name' => $name,
        'quantity' => $qty,
        'price' => $price,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $result = insert('laundry_consumables', $data);

    if($result){
        echo json_encode([
            'status' => 200,
            'message' => 'Item Added Successfully'
        ]);
    }else{
        echo json_encode([
            'status' => 500,
            'message' => 'Something went wrong'
        ]);
    }
}

if(isset($_POST['adjustStock']))
{
    $itemId = validate($_POST['item_id']);
    $addQty = validate($_POST['add_quantity']);

    if($itemId == '' || $addQty == ''){
        echo json_encode([
            'status' => 422,
            'message' => 'All fields are required'
        ]);
        return;
    }

    // get current stock
    $checkQuery = mysqli_query($conn, "SELECT * FROM laundry_consumables WHERE id='$itemId' LIMIT 1");

    if(mysqli_num_rows($checkQuery) == 0){

        echo json_encode([
            'status' => 404,
            'message' => 'Item not found'
        ]);
        return;
    }

    $itemData = mysqli_fetch_assoc($checkQuery);

    $adjustmentType = validate($_POST['adjustment_type']);

    $currentQty = $itemData['quantity'];

    if($adjustmentType == 'ADD'){

        $newQty = $currentQty + $addQty;
        $movementType = 'IN';

    }else{

        // prevent negative stock
        if($addQty > $currentQty){

            echo json_encode([
                'status' => 422,
                'message' => 'Insufficient stock'
            ]);
            return;
        }

        $newQty = $currentQty - $addQty;
        $movementType = 'OUT';
    }

    // update stock
    $updateQuery = mysqli_query($conn, "
        UPDATE laundry_consumables 
        SET quantity='$newQty'
        WHERE id='$itemId'
    ");

    if($updateQuery){
        // stock movement log
        $movementData = [
            'consumable_id' => $itemId,
            'movement_type' => $movementType,
            'quantity' => $addQty,
            'reference_no' => 'ADJ-' . time(),
            'remarks' => 'Manual stock adjustment',
            'created_by' => $_SESSION['loggedInUser']['user_id']
        ];

        insert('stock_movement', $movementData);
        echo json_encode([
            'status' => 200,
            'message' => 'Stock Updated Successfully'
        ]);

    }else{

        echo json_encode([
            'status' => 500,
            'message' => 'Something went wrong'
        ]);

    }
}