<?php

require '../config/function.php';

$paraRestultId = checkParamId('id');
if(is_numeric($paraRestultId)){

    $serviceId = validate($paraRestultId);

    $service = getById('services',$serviceId);

    if($service['status'] == 200)
    {
        $response = delete('services', $serviceId);
        if($response)
        {
            $deleteImage = "../".$service['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }

            redirect('services.php','Service Deleted Successfully');
        }
        else
        {
            redirect('services.php','Something Went Wrong');
        }
    }
    else
    {
        redirect('services.php',$service['message']);
    }
}else{
    redirect('services.php','Something Went Wrong');
}

?>
