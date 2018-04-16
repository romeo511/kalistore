<?php
include('config.php');

/** Check If Loged In **/
$User = (isset($_COOKIE['User'])) ? trim(addslashes($_COOKIE['User'])) : '';
$PWD  = (isset($_COOKIE['PWD'])) ? trim(addslashes($_COOKIE['PWD'])) : '';
if ($User == md5($Username.$Salt) && $PWD == md5($Password.$Salt)) {
    $Logedin = true;
    
}else{
    $Logedin = false;
    
    setcookie ('User', '', ($_SERVER['REQUEST_TIME']+31104000),'/', $meta['domain']);
    setcookie ('PWD', '', ($_SERVER['REQUEST_TIME']+31104000),'/', $meta['domain']);
}

$page = (isset($_GET['page'])) ? trim($_GET['page']).'.html' : 'form.html';

?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--[if lt IE 9]> <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> <![endif]-->
    <title><?php echo $meta['title']; ?></title>
    <meta name="Description" content="<?php echo $meta['desc']; ?>" />
    
    <!-- Enable responsive design -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <link href="css-js/style.css" rel="stylesheet" type="text/css" />
    
    <!-- GET jQuery<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <script>
        if (!window.jQuery) { document.write('<script src="css-js/jquery-1.11.3.min.js"><\/script>'); }
    </script>
    <script src="css-js/notify-rtl.js"></script>
</head>
<?php
include($page);
?>
</html>
