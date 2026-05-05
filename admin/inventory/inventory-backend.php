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