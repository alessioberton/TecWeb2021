<?php

/**
 * @param $abs_path
 * @return void
 */
function getAbs_path(&$abs_path): void {
    $abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';
    include_once($abs_path."functions/functions.php");
    include_once($abs_path."utente/utente.php");
    include_once($abs_path."immagine/immagine.php");
    include_once($abs_path."logic/sessione.php");

    if ($_SESSION['logged'] == false) {
    debug_to_console(json_encode("entro"));
        header('location: homepage.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path($abs_path);


$DOM = file_get_contents("../../html/profilo.html");

echo($DOM);