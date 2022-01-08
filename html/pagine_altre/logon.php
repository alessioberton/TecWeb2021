<?php

include '../../config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."utente/utente.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    if ($_SESSION['logged'] == true) {
        header('location: ../profilo.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents("../../../html/logon.html");

//Controllo di venire da logon.html e non tramite giri strani
if(isset($_POST['mail'])) {
    $username = $_POST["username"];
    $mail = trim($_POST["mail"]);
    $pwd = $_POST["pwd"];
    $repeat_pwd = $_POST["repeat_pwd"];
    $data_nascita = $_POST["data_nascita"];
    $utente = new Utente();
    try {
        $utente->inserisci($username, $mail, $pwd, $data_nascita, "Utente");
        header('location: login.php');
        exit;
    } catch (Exception $e) {
        $error="[Mail già in uso]";
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
        $pagina_errore = str_replace("</error_message>", $error, $pagina_errore);
        echo $pagina_errore;
    }
}

echo $page;