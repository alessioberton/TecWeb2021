<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void {
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/utente.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
    if ($_SESSION['logged'] == true) {
        header('location: ../profilo.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();


//Controllo di venire da logon.html e non tramite giri strani
if(isset($_POST['mail'])) {
    $mail = trim($_POST["mail"]);
    $pwd = $_POST["pwd"];
    $repeat_pwd = $_POST["repeat_pwd"];
    $data_nascita = $_POST["data_nascita"];
    $utente = new Utente();
    try {
        $utente->inserisci($mail, $pwd, $data_nascita, "Utente");
        header('location: login.php');
        exit;
    } catch (Exception $e) {
        $error="[Mail già in uso]";
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", $error, $pagina_errore);
        echo $pagina_errore;
    }
}

$page = file_get_contents(__DIR__.'/logon.html');
echo $page;
?>