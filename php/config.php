<?php

session_start();
if (!isset($_SESSION['logged'])) $_SESSION['logged'] = false;

$_SESSION['max_dim_img'] = 1300000; // Kb
?>