<?php

require '../config/function.php';

$paraRestultId = checkParamId('id');
if(is_numeric($paraRestultId)){

    $adminId = validate($paraRestultId);

    $admin = getById('users',$adminId);
    if($admin['status'] == 200)
    {
        $adminDeleteRes = delete('users', $adminId);
        if($adminDeleteRes)
        {
            redirect('admins.php','Admin Deleted Successfully');
        }
        else
        {
            redirect('admins.php','Something Went Wrong');
        }
    }
    else
    {
        redirect('admins.php',$admin['message']);
    }
    // echo $adminId;

}else{
    redirect('admins.php','Something Went Wrong');
}

?>
