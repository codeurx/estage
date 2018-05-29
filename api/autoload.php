<?php
session_start();
require 'classes/db.class.php';
require 'classes/crypt.class.php';
require 'config.php';
$db = new DB(DB_HOST,DB_NAME,DB_USER,DB_PASSWORD);
$crypt = new crypt();