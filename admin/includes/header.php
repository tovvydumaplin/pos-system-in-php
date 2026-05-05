<?php  
require __DIR__ . '/../../config/function.php';
require __DIR__ . '/../authentication.php';

// Calculate relative path to admin folder based on current script location
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$adminPos = strpos($scriptPath, '/admin/');
if ($adminPos !== false) {
    $afterAdmin = substr($scriptPath, $adminPos + 7); // +7 for '/admin/'
    $depth = substr_count($afterAdmin, '/');
    $baseUrl = str_repeat('../', $depth);
} else {
    $baseUrl = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    
    <link href="<?= $baseUrl ?>assets/css/styles.css" rel="stylesheet" />
    
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css"/>

    <link href="<?= $baseUrl ?>assets/css/custom.css" rel="stylesheet" />

</head>
<body class="sb-nav-fixed">

    <?php include(__DIR__ . '/navbar.php'); ?>

    <div id="layoutSidenav">
        
        <?php include(__DIR__ . '/sidebar.php'); ?>

            <div id="layoutSidenav_content">

                <main>


                