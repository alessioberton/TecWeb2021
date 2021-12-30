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
    debug_to_console(json_encode("ciao"));

    if ($_SESSION['logged'] == true) {
        header('location: ../../../html/homepage.html');
        exit();
    }

    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path($abs_path);

$username = $_POST["username"];
$mail = $_POST["mail"];
$pwd = $_POST["pwd"];
$repeat_pwd = $_POST["repeat_pwd"];
$data_di_nascita = $_POST["data_nascita"];
$utente = new Utente();

try {
    $utente->inserisci($username, $mail, $pwd, $username, $data_di_nascita, "Utente");
    if (isset($_POST['remember'])) {
        setcookie("username", $username, time() + 60 * 60 * 24 * 30);
        setcookie("password", $pwd, time() + 60 * 60 * 24 * 30);
    }

    header('location: login.php');
    exit;



} catch (Exception $e) {
    $pagina_errore = file_get_contents($abs_path . "../html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}