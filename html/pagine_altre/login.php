<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void {
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/utente.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
	require_once(__DIR__.'/../../html/componenti/header.php');
    if ($_SESSION['logged'] == true) {
        header('location: ../pagine_utente/profilo.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}
getAbs_path();

$page = file_get_contents(__DIR__.'/login.html');

//Controllo di venire da pagina di login e non tramite giri strani
if(isset($_POST['mail'])) {
    $mail = validate_input(trim($_POST["mail"]));
    $pwd = validate_input(trim($_POST["pwd"]));
    $utente = new Utente();
    try {
        $query_array = $utente->find($mail, $pwd);
        if ($query_array != null) {
            $_SESSION['logged'] = true;
			$_SESSION['user'] = $query_array;
			
			header('location: ../pagine_utente/profilo.php');
			exit();
        } else {
			$page = str_replace("#ERRORE_USERNAME#", " error", $page);
			$page = str_replace("#ERRORE_PASSWORD#", " error", $page);
        }
    } catch (Exception $e) {
        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
        echo $pagina_errore;
    }
}
$page = str_replace("#ERRORE_USERNAME#", "", $page);
$page = str_replace("#ERRORE_PASSWORD#", "", $page);
$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
echo $page;

?>