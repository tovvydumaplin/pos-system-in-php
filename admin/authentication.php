<?php

if(isset($_SESSION['loggedIn']))
{
    $email = validate($_SESSION['loggedInUser']['email']);

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 0)
    {
        logoutSession();
        redirect('../login.php','Access Denied!');
    }
    else
    {
        $row = mysqli_fetch_assoc($result);
        if($row['is_ban'] == 1){
            logoutSession();
            redirect('../login.php','Your account has been banned! Please contact admin');
        }

        $_SESSION['loggedInUser']['user_type'] = $row['user_type'];

        if($row['user_type'] == 'staff'){
            $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
            $isDashboard = substr($scriptPath, -16) == '/admin/index.php';
            $isPosPage = strpos($scriptPath, '/admin/pos/') !== false;

            if(!$isDashboard && !$isPosPage){
                $adminPos = strpos($scriptPath, '/admin/');
                $depth = 0;

                if($adminPos !== false){
                    $afterAdmin = substr($scriptPath, $adminPos + 7);
                    $depth = substr_count($afterAdmin, '/');
                }

                redirect(str_repeat('../', $depth).'index.php','Access denied for staff account.');
            }
        }
    }
}
else
{
    redirect('../login.php','Login to continue...');
}

?>
