<?php

include '../config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."utente/utente.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents("../../html/homepage.html");
echo $page;