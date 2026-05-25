<?php
require '../../config/function.php';

$branchId = $_SESSION['loggedInUser']['branch_id'];
$userId = $_SESSION['loggedInUser']['user_id'];

if(isset($_POST['saveItem']))
{
    $name = validate($_POST['item_name']);
    $branch_id = validate($_POST['branch_id']);
    $qty = validate($_POST['quantity']);
    $price = validate($_POST['price']);

    if($name == '' || $branch_id == '' || $qty == '' || $price == ''){
        echo json_encode([
            'status' => 422,
            'message' => 'All fields are required'
        ]);
        return;
    }

    $data = [
        'item_name' => $name,
        'branch_id' => $branch_id,
        'quantity' => $qty,
        'price' => $price,
        'status' => 1,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $result = insert('laundry_consumables', $data);

    if($result){

        $itemId = mysqli_insert_id($conn);

        // Initial stock movement
        $movementData = [
            'consumable_id' => $itemId,
            'movement_type' => 'IN',
            'quantity' => $qty,
            'reference_no' => 'NEW-' . time(),
            'remarks' => 'Initial inventory stock',
            'created_by' => $userId,
            'branch_id' => $branch_id
        ];

        insert('stock_movement', $movementData);

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
    $adjustQty = (int) validate($_POST['add_quantity']);

    if($itemId == '' || $adjustQty <= 0){

        echo json_encode([
            'status' => 422,
            'message' => 'All fields are required'
        ]);

        return;
    }

    $checkQuery = mysqli_query(
        $conn,
        "SELECT * FROM laundry_consumables 
         WHERE id='$itemId' 
         LIMIT 1"
    );

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
    $itemBranchId = $itemData['branch_id'];

    if($adjustmentType == 'ADD')
    {
        $newQty = $currentQty + $adjustQty;

        $remarks = 'Manual stock adjustment addition';
    }
    else
    {
        if($adjustQty > $currentQty){

            echo json_encode([
                'status' => 422,
                'message' => 'Insufficient stock'
            ]);

            return;
        }

        $newQty = $currentQty - $adjustQty;

        $remarks = 'Manual stock adjustment deduction';
    }

    $updateQuery = mysqli_query($conn,"
        UPDATE laundry_consumables
        SET quantity='$newQty'
        WHERE id='$itemId'
    ");

    if($updateQuery){

        $movementData = [

            'consumable_id' => $itemId,

            // actual type used in filtering
            'movement_type' => 'ADJUSTMENT',

            'quantity' => $adjustQty,

            'reference_no' => 'ADJ-' . time(),

            'remarks' => $remarks,

            'created_by' => $userId,

            'branch_id' => $itemBranchId
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

if(isset($_POST['updateItem']))
{
    $itemId = validate($_POST['item_id']);
    $name = validate($_POST['item_name']);
    $price = validate($_POST['price']);

    if($itemId=='' || $name=='' || $price=='')
    {
        echo json_encode([
            'status'=>422,
            'message'=>'All fields required'
        ]);

        return;
    }

    $query=mysqli_query($conn,"
        UPDATE laundry_consumables
        SET
            item_name='$name',
            price='$price'
        WHERE id='$itemId'
    ");

    if($query){

        echo json_encode([
            'status'=>200,
            'message'=>'Updated Successfully'
        ]);

    }else{

        echo json_encode([
            'status'=>500,
            'message'=>'Something went wrong'
        ]);
    }
}

if(isset($_POST['deleteItem']))
{
    $itemId = validate($_POST['item_id']);

    if($itemId=='')
    {
        echo json_encode([
            'status'=>422,
            'message'=>'Invalid item'
        ]);

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | GET ITEM DATA
    |--------------------------------------------------------------------------
    */
    $checkItem = mysqli_query($conn,"
        SELECT *
        FROM laundry_consumables
        WHERE id='$itemId'
        AND branch_id='$branchId'
        LIMIT 1
    ");

    if(mysqli_num_rows($checkItem)==0)
    {
        echo json_encode([
            'status'=>404,
            'message'=>'Item not found'
        ]);

        return;
    }

    $itemData = mysqli_fetch_assoc($checkItem);

    /*
    |--------------------------------------------------------------------------
    | SOFT DELETE
    |--------------------------------------------------------------------------
    */
    $updateQuery=mysqli_query($conn,"
        UPDATE laundry_consumables
        SET status='0'
        WHERE id='$itemId'
        AND branch_id='$branchId'
    ");

    if($updateQuery){

        /*
        |--------------------------------------------------------------------------
        | STOCK MOVEMENT LOG
        |--------------------------------------------------------------------------
        */
        $movementData = [

            'consumable_id' => $itemId,

            'movement_type' => 'DELETED',

            'quantity' => $itemData['quantity'],

            'reference_no' => 'DEL-' . time(),

            'remarks' => 'Item deleted with remaining stock of: '.$itemData['quantity'],

            'created_by' => $userId,

            'branch_id' => $branchId
        ];

        insert('stock_movement',$movementData);

        echo json_encode([
            'status'=>200,
            'message'=>'Item deleted successfully'
        ]);

    }else{

        echo json_encode([
            'status'=>500,
            'message'=>'Something went wrong'
        ]);
    }
}