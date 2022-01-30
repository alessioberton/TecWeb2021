<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void {
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/utente.php');
    require_once(__DIR__.'/../../php/database/immagine.php');	
	require_once(__DIR__.'/../../html/componenti/header.php');
    if ($_SESSION['logged'] == true) {
        header('location: ../profilo.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents(__DIR__.'/logon.html');

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
		$page = str_replace("#ERRORE_USERNAME#", " error", $page);
    }
}

$page = str_replace("#ERRORE_USERNAME#", "", $page);
$page = str_replace("#ERRORE_PASSWORD#", "", $page);
$page = str_replace("#ERRORE_RIPETI_PASSWORD#", "", $page);
$page = str_replace("#ERRORE_DATA_NASCITA#", "", $page);

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
echo $page;
?>