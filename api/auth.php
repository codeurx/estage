<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type:application/json');
$users = array('fname'=>'amdir','connected'=>true);
echo json_encode($users);
