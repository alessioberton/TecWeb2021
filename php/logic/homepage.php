<?php

/**
 * @param $abs_path
 * @return void
 */
function getAbs_path(&$abs_path): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."utente/utente.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");

//    if ($_SESSION['logged'] == false) {
//    debug_to_console(json_encode("entro"));
//        header('location: ../../html/homepage.html');
//        exit();
//    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path($abs_path);


$DOM = file_get_contents("../../html/homepage.html");
$DOM = str_replace('<title_page_to_insert/>', 'Home', $DOM);
echo($DOM);