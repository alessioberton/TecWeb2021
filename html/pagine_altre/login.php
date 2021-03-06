<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../html/componenti/header.php');
require_once(__DIR__.'/../../php/database/utente.php');

if ($_SESSION['logged'] == true) {
	header('location: ../pagine_utente/profilo.php');
	exit();
}
$_POST = array_map('empty_to_null', $_POST);

$page = file_get_contents(__DIR__.'/login.html');

$_SESSION['pagina_corrente'] = basename($_SERVER["REQUEST_URI"]);

//Controllo di venire da pagina di login e non tramite giri strani
if(isset($_POST['user'])) {
    $user = validate_input(trim($_POST["user"]));
    $pwd = validate_input(trim($_POST["pwd"]));
    $utente = new Utente();
    try {
        $query_array = $utente->find($user, $pwd);
        if ($query_array != null) {
            $_SESSION['logged'] = true;
			$_SESSION['user'] = $query_array;
			
			header('location: ../pagine_utente/profilo.php');
			exit();
        } else {
			$page = str_replace("#ERRORE_USERNAME#", " error", $page);
			$page = str_replace("#ERRORE_PASSWORD#", " error", $page);
            $page = str_replace("#USERNAME_INITIAL#", $user, $page);
        }
    } catch (Exception $e) {
        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.php");
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
        echo $pagina_errore;
    }
}else{
    $page = str_replace("#USERNAME_INITIAL#", "", $page);
}

$page = str_replace("#ERRORE_USERNAME#", "", $page);
$page = str_replace("#ERRORE_PASSWORD#", "", $page);

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);

echo $page;
?>