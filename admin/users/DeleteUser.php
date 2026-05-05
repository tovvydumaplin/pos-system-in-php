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
            redirect('users/users.php','User Deleted Successfully');
        }
        else
        {
            redirect('users/users.php','Something Went Wrong');
        }
    }
    else
    {
        redirect('users/users.php', $user['message']);
    }

}else{
    redirect('users/users.php','Something Went Wrong');
}

?>
