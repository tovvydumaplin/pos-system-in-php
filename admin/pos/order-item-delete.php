<?php

require '../../config/function.php';

$paramResult = checkParamId('index');

if(is_numeric($paramResult)){

    $indexValue = validate($paramResult);

    if(isset($_SESSION['orderItems'][$indexValue])){

        unset($_SESSION['orderItems'][$indexValue]);

        // reindex array (VERY IMPORTANT)
        $_SESSION['orderItems'] = array_values($_SESSION['orderItems']);

        redirect('order-create.php', 'Item Removed');

    }else{

        redirect('order-create.php', 'Item not found');
    }

}else{
    redirect('order-create.php', 'Invalid index');
}
?>
