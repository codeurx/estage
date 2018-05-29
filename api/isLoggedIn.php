<?php
require 'autoload.php';
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json');
if((!isset($_SESSION['id']))||(empty($_SESSION['id']))){
    $auth = array('isAuth'=>false);
}else{
    $auth = array('isAuth'=>true);
}
echo json_encode($auth);