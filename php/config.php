<?php
session_start();
$_SESSION['max_dim_img'] = 1300000; // Kb
$_SESSION['$abs_path'] = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/';
$_SESSION['$abs_path_php'] = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';
$_SESSION['$abs_path_img'] = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/img/';
$_SESSION['$abs_path_html'] = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/html/';
$_SESSION['$img_url'] = 'http://'.$_SERVER["SERVER_NAME"].'/TecWeb2021/img/';