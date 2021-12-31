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
        header('location: acess_denied.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path($abs_path);


$page = file_get_contents("../../html/profilo.html");
print_r($_SESSION);

try {
    $page = str_replace("%EMAIL%",$_SESSION['email'],$page);
    $page = str_replace("%UTENZA%",$_SESSION['permesso'],$page);
//    $page = str_replace("%NOME%",$_SESSION['Nome'],$page);
//    $page = str_replace("%COGNOME%",$_SESSION['Cognome'],$page);
    $page = str_replace("%DATA_NASCITA%",$_SESSION['data_nascita'],$page);
} catch (Exception $e) {
    $pagina_errore = file_get_contents($abs_path . "../html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}


echo($page);