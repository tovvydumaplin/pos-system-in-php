<?php

require '../../config/function.php';

$paraRestultId = checkParamId('id');
if(is_numeric($paraRestultId)){

    $userId = validate($paraRestultId);

    $user = getById('users', $userId);
    
    if($user['status'] == 200)
    {
        $userDeleteRes = delete('users', $userId);
        if($userDeleteRes)
        {
            redirect('users.php','User Deleted Successfully');
        }
        else
        {
            redirect('users.php','Something Went Wrong');
        }
    }
    else
    {
        redirect('users.php', $user['message']);
    }

}else{
    redirect('users.php','Something Went Wrong');
}

?>
