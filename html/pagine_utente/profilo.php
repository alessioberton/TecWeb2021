<?php
require_once(__DIR__ . '/../../php/logic/error_reporting.php');
require_once(__DIR__ . '/../../php/config.php');

function getAbs_path(): void
{
    require_once(__DIR__ . '/../../html/componenti/header.php');
    require_once(__DIR__ . '/../../php/logic/functions.php');
    require_once(__DIR__ . '/../../php/database/utente.php');
    require_once(__DIR__ . '/../../php/database/immagine.php');
    require_once(__DIR__ . '/../../php/database/scheda_utente.php');
    if ($_SESSION['logged'] == false) {
        header('location: ../pagine_altre/accesso_negato.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents(__DIR__ . '/profilo.html');

$errore = '';
$percorso_immagine = '../../img/utenti/imgnotfound.jpg';
$nome_immagine_presente = 'imgnotfound.jpg';
$nuova_password = '';
$nuova_mail = '';

$data_di_nascita = $_SESSION['user']['Data_nascita'];
$today = date("Y-m-d");
$diff = date_diff(date_create($data_di_nascita), date_create($today));

$page = str_replace("#EMAIL#", $_SESSION['user']['Email'], $page);

if ($_SESSION['user']['Permessi'] == "Admin") {
    $page = str_replace("#PERMESSI#", "Amministratore", $page);
} else {
    $page = str_replace("#PERMESSI#", "Utente", $page);
}
$page = str_replace("#DATA_NASCITA#", date("d-m-y", strtotime($_SESSION['user']['Data_nascita'])) . " (" . ($diff->format('%y')) . " anni)", $page);

$img = new Immagine();
$utente = new Utente();


if (isset($_POST['modifica_pwd_btn'])) {
    $nuova_password = validate_input($_POST["ripeti_nuova_pwd"]);
    try {
        if ($_POST["nuova_pwd"] == $nuova_password) $utente->aggiorna_password($_SESSION['username'], $nuova_password);
    } catch (Exception $e) {
        if (!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
        $pagina_errore = file_get_contents(__DIR__ . '/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

if (isset($_POST['modifica_mail_btn'])) {
    $nuova_mail = validate_input($_POST["nuova_mail"]);
    try {
        $utente->aggiorna_mail($_SESSION['username'], $nuova_mail);
        $page = str_replace($_SESSION['email'], $nuova_mail, $page);
        $page = str_replace($_SESSION['email'], $nuova_mail, $page);
        $_SESSION['email'] = $nuova_mail;
    } catch (Exception $e) {
        if (!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
        $pagina_errore = file_get_contents(__DIR__ . '/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

$scheda_utente = new SchedaUtente();
$conta_visto = 0;
$conta_salvati = 0;
$conta_valutati = 0;

try {
    $query_array_scheda_utente = $scheda_utente->findByUser($_SESSION['user']['Username']);
    foreach ($query_array_scheda_utente as $value) {
        if ($value["Visto"] == true) {
            $conta_visto += 1;
        }
        if ($value["Salvato"] == true) {
            $conta_salvati += 1;
        }
        if ($value["Suggerito"] == true) {
            $conta_valutati += 1;
        }
    }
    $page = str_replace("#FILM_VISTI#", $conta_visto, $page);
    $page = str_replace("#FILM_SALVATI#", $conta_salvati, $page);
    $page = str_replace("#FILM_VALUTATI#", $conta_valutati, $page);
} catch (Exception $e) {
    if (!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
    $pagina_errore = file_get_contents(__DIR__ . "/../../html/pagine_altre/errore.html");
    $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
    echo $pagina_errore;
}

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
echo $page;