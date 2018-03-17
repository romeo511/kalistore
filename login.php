<?php
include('config.php');

if (!get_magic_quotes_gpc() && $_POST) {
	$_POST = array_map('addslashes', $_POST);
}

$_POST = array_map('trim', $_POST);

extract($_POST);

if ($action == 'Login') {
    if ($username == $Username && $pwd == $Password) {
        setcookie ('User', md5($Username.$Salt), ($_SERVER['REQUEST_TIME']+31104000),'/', $meta['domain']);
        setcookie ('PWD', md5($Password.$Salt), ($_SERVER['REQUEST_TIME']+31104000),'/', $meta['domain']);
        
        $_COOKIE['User'] = md5($Username.$Salt);
        $_COOKIE['PWD']  = md5($Password.$Salt);
        
        echo 'done';
        
    }else{
        echo 'error';
    }
    
}else{
    setcookie ('User', '', ($_SERVER['REQUEST_TIME']+31104000),'/', $meta['domain']);
    setcookie ('PWD', '', ($_SERVER['REQUEST_TIME']+31104000),'/', $meta['domain']);
    
    $_COOKIE['User'] = '';
    $_COOKIE['PWD']  = '';
    
    echo 'done';
}
?>