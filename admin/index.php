<?php
error_reporting(E_ALL);
define('BASE_DIR', '/apps/payments/admin') or die();
define('API_DIR', BASE_DIR.'/api/apu.php');
require_once '../vendor/autoload.php';
include "lib/_autoload.php";
include "../config/ky-config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments Administrator</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/jquery/cookie.jquery.min.js"></script>
</head>
<body>
    <?php
    //echo ($AuthClass->register('test@mail.com', '1234qwerty', '1234qwerty')) ? 'true' : 'false';
    //echo $AuthClass->hash('1234');

    $page = (isset($_GET['page']) ? $_GET['page'] : 'main');
    // Verificamos si tiene sesión iniciadda. En caso contrario enviamos al login
    if (!$AuthClass->checkSession($_COOKIE['KY_AuthID'])) {
    $page = 'login';
}
    switch ($page) {
        case 'login':
            require "pages/login.php";
            break;
        default:
            require "pages/index.php";
            break;
    }
    ?>

    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="dist/js/sb-admin-2.js"></script>
</body>
</html>

     