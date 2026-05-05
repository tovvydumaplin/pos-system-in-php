<?php

include('../config/function.php');

// ============================================
// UNIFIED USER MANAGEMENT (Admin + Staff)
// ============================================

if(isset($_POST['saveUser']))
{
    $name = validate($_POST['name']);
    $user_type = validate($_POST['user_type']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0;

    if($name != '' && $user_type != '' && $email != '' && $password != ''){

        // Check if email already exists
        $emailCheck = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('users/CreateUser.php','Email Already used by another user.');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'user_type' => $user_type,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = insert('users', $data);

        if($result){
            redirect('users/users.php','User Created Successfully!');
        }else{
            redirect('users/CreateUser.php','Something Went Wrong!');
        }

    }else{
        redirect('users/CreateUser.php','Please fill all required fields.');
    }

}

if(isset($_POST['updateUser']))
{
    $userId = validate($_POST['userId']);

    $userData = getById('users', $userId);
    if($userData['status'] != 200){
        redirect('users/EditUser.php?id='.$userId,'User not found.');
    }
    
    $name = validate($_POST['name']);
    $user_type = validate($_POST['user_type']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0;

    // Check if email is already used by another user
    $EmailCheckQuery = "SELECT * FROM users WHERE email='$email' AND id!='$userId'";
    $checkResult = mysqli_query($conn, $EmailCheckQuery);
    if($checkResult){
        if(mysqli_num_rows($checkResult) > 0){
            redirect('users/EditUser.php?id='.$userId,'Email Already used by another user');
        }
    }
    
    // Only update password if provided
    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }else{
        $hashedPassword = $userData['data']['password'];
    }

    if($name != '' && $user_type != '' && $email != '')
    {
        $data = [
            'name' => $name,
            'user_type' => $user_type,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = update('users', $userId, $data);

        if($result){
            redirect('users/EditUser.php?id='.$userId,'User Updated Successfully!');
        }else{
            redirect('users/EditUser.php?id='.$userId,'Something Went Wrong!');
        }
    }
    else
    {
        redirect('users/EditUser.php?id='.$userId,'Please fill all required fields.');
    }
}

// ============================================
// LEGACY ADMIN MANAGEMENT (Deprecated - use Users instead)
// ============================================

if(isset($_POST['saveAdmin']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0;

    if($name != '' && $email != '' && $password != ''){

        $emailCheck = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('admins-create.php','Email Already used by another user.');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'user_type' => 'admin',
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = insert('users',$data);

        if($result){
            redirect('admins.php','Admin Created Successfully!');
        }else{
            redirect('admins-create.php','Something Went Wrong!');
        }

    }else{
        redirect('admins-create.php','Please fill required fields.');
    }

}

if(isset($_POST['updateAdmin']))
{
    $adminId = validate($_POST['adminId']);

    $adminData = getById('users',$adminId);
    if($adminData['status'] != 200){
        redirect('admins-edit.php?id='.$adminId,'Please fill required fields.');
    }
    
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0;

    $EmailCheckQuery = "SELECT * FROM users WHERE email='$email' AND id!='$adminId'";
    $checkResult = mysqli_query($conn, $EmailCheckQuery);
    if($checkResult){
        if(mysqli_num_rows($checkResult) > 0){
            redirect('admins-edit.php?id='.$adminId,'Email Already used by another user');
        }
    }
    
    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }else{
        $hashedPassword = $adminData['data']['password'];
    }

    if($name != '' && $email != '')
    {
        $data = [
            'name' => $name,
            'user_type' => 'admin',
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = update('users', $adminId, $data);

        if($result){
            redirect('admins-edit.php?id='.$adminId,'Admin Updated Successfully!');
        }else{
            redirect('admins-edit.php?id='.$adminId,'Something Went Wrong!');
        }
    }
    else
    {
        redirect('admins-create.php','Please fill required fields.');
    }
}

if(isset($_POST['saveStaff']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0;

    if($name != '' && $email != '' && $password != ''){

        $emailCheck = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('staffs-create.php','Email Already used by another user.');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'user_type' => 'staff',
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = insert('users',$data);

        if($result){
            redirect('staffs.php','Staff Created Successfully!');
        }else{
            redirect('staffs-create.php','Something Went Wrong!');
        }

    }else{
        redirect('staffs-create.php','Please fill required fields.');
    }

}

if(isset($_POST['updateStaff']))
{
    $staffId = validate($_POST['staffId']);

    $staffData = getById('users',$staffId);
    if($staffData['status'] != 200){
        redirect('staffs-edit.php?id='.$staffId,'Please fill required fields.');
    }
    
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0;

    $EmailCheckQuery = "SELECT * FROM users WHERE email='$email' AND id!='$staffId'";
    $checkResult = mysqli_query($conn, $EmailCheckQuery);
    if($checkResult){
        if(mysqli_num_rows($checkResult) > 0){
            redirect('staffs-edit.php?id='.$staffId,'Email Already used by another user');
        }
    }
    
    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }else{
        $hashedPassword = $staffData['data']['password'];
    }

    if($name != '' && $email != '')
    {
        $data = [
            'name' => $name,
            'user_type' => 'staff',
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = update('users', $staffId, $data);

        if($result){
            redirect('staffs-edit.php?id='.$staffId,'Staff Updated Successfully!');
        }else{
            redirect('staffs-edit.php?id='.$staffId,'Something Went Wrong!');
        }
    }
    else
    {
        redirect('staffs-create.php','Please fill required fields.');
    }
}


if(isset($_POST['saveCategory']))
{
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = insert('categories',$data);

    if($result){
        redirect('categories.php','Category Created Successfully!');
    }else{
        redirect('categories-create.php','Something Went Wrong!');
    }
}

if(isset($_POST['updateCategory']))
{
    $categoryId = validate($_POST['categoryId']);

    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

    $data = [
        'name' => $name,
        'description' => $description,
        'status' => $status
    ];
    $result = update('categories', $categoryId, $data);

    if($result){
        redirect('categories-edit.php?id='.$categoryId,'Category Updated Successfully!');
    }else{
        redirect('categories-edit.php?id='.$categoryId,'Something Went Wrong!');
    }
}


if(isset($_POST['saveService']))
{
    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);

    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1:0;

    if($_FILES['image']['size'] > 0)
    {
        $path = "../assets/uploads/services";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time().'.'.$image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);

        $finalImage = "assets/uploads/services/".$filename;
    }
    else
    {
        $finalImage = '';
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status
    ];

    $result = insert('services',$data);

    if($result){
        redirect('services.php','Service Created Successfully!');
    }else{
        redirect('services-create.php','Something Went Wrong!');
    }
}

if(isset($_POST['updateService']))
{
    $service_id = validate($_POST['service_id']);

    $serviceData = getById('services',$service_id);
    if(!$serviceData){
        redirect('services.php','No such service found');
    }

    $category_id = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);

    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1:0;

    if($_FILES['image']['size'] > 0)
    {
        $path = "../assets/uploads/services";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time().'.'.$image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);

        $finalImage = "assets/uploads/services/".$filename;

        $deleteImage = "../".$serviceData['data']['image'];
        if(file_exists($deleteImage)){
            unlink($deleteImage);
        }
    }
    else
    {
        $finalImage = $serviceData['data']['image'];
    }

    $data = [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $finalImage,
        'status' => $status
    ];

    $result = update('services', $service_id, $data);

    if($result){
        redirect('services-edit.php?id='.$service_id,'Service Updated Successfully!');
    }else{
        redirect('services-edit.php?id='.$service_id,'Something Went Wrong!');
    }
}



if(isset($_POST['saveCustomer']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1:0;

    if($name != '')
    {
        $emailCheck = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('customers.php','Email Already Exist');
            }
        }
        
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = insert('customers', $data);

        if($result){
            redirect('customers.php','Customer Created Successfully');
        }else{
            redirect('customers.php','Something Went Wrong');
        }
    }
    else
    {
        redirect('customers.php','Please fill required fields');
    }
}


if(isset($_POST['updateCustomer']))
{
    $customerId = validate($_POST['customerId']);
    
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1:0;

    if($name != '')
    {
        $emailCheck = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email' AND id!='$customerId'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('customers-edit.php?id='.$customerId,'Email Already Exist');
            }
        }
        
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = update('customers', $customerId, $data);

        if($result){
            redirect('customers-edit.php?id='.$customerId,'Customer Updated Successfully');
        }else{
            redirect('customers-edit.php?id='.$customerId,'Something Went Wrong');
        }
    }
    else
    {
        redirect('customers-edit.php?id='.$customerId,'Please fill required fields');
    }
}




?>