<?php

session_start();
if (!isset($_SESSION['logged'])) $_SESSION['logged'] = false;

$_SESSION['max_dim_img'] = 1300000; // Kb
$_SESSION['$img_url'] = 'http://'.$_SERVER["SERVER_NAME"].'/TecWeb2021/img/';
$_SESSION['$img_not_found_url'] = 'http://'.$_SERVER["SERVER_NAME"].'/TecWeb2021/img/imgnotfound.jpg';
?>