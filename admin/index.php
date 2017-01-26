<?php
    error_reporting(-1);

    define('DEBUG_MODE', true) or die();
    define('ADMIN_DIR', dirname($_SERVER['REQUEST_URI'])) or die();
    define('API_DIR', ADMIN_DIR.'/api/apu.php');
    require_once '../vendor/autoload.php';
    require_once "lib/_autoload.php";
    require_once "../config/ky-config.php";

    // Verificamos si tiene sesión iniciadda. En caso contrario enviamos al login
    if (!$dbo->AuthClass->checkSession($cookies['Auth'])) {
        $page = 'login';
        $logged = false;
    } else {
        $page = ((isset($_GET['page'])) ? $_GET['page'] : '');
        $logged = true;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Payments Administrator</title>
        <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="dist/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/jquery/cookie.jquery.min.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <?php
                //echo ($AuthClass->register('test@mail.com', '1234qwerty', '1234qwerty')) ? 'true' : 'false';

                include 'layouts/main.php';
                include 'layouts/sidebar.php';
                switch ($page) {
                    case 'logout':
                        $AuthClass->logout($cookies['Auth']);
                        echo '<script>window.location = "index.php" </script>';
                        break;
                    default:
                        if (!@include_once"pages/".$page.".php") {
                            include_once "pages/index.php";
                        }
                        break;
                }
            ?>
        </div>
        <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="../vendor/raphael/raphael.min.js"></script>
<!--        <script src="dist/js/sb-admin-2.js"></script>-->
    </body>
</html>

