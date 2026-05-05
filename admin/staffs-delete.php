<?php

require '../config/function.php';

$paraRestultId = checkParamId('id');
if(is_numeric($paraRestultId)){

    $staffId = validate($paraRestultId);

    $staff = getById('users',$staffId);
    if($staff['status'] == 200)
    {
        $staffDeleteRes = delete('users', $staffId);
        if($staffDeleteRes)
        {
            redirect('staffs.php','Staff Deleted Successfully');
        }
        else
        {
            redirect('staffs.php','Something Went Wrong');
        }
    }
    else
    {
        redirect('staffs.php',$staff['message']);
    }
    // echo $staffId;

}else{
    redirect('staffs.php','Something Went Wrong');
}

?>
