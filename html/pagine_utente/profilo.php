<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void {
	require_once(__DIR__.'/../../html/componenti/header.php');
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/utente.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
    require_once(__DIR__.'/../../php/database/scheda_utente.php');
    if ($_SESSION['logged'] == false) {
        header('location: ../pagine_altre/accesso_negato.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}
getAbs_path();

$page = file_get_contents(__DIR__.'/profilo.html');

$errore = '';
$percorso_immagine = '../../img/utenti/imgnotfound.jpg';
$nome_immagine_presente = 'imgnotfound.jpg';
$nuova_password = '';
$nuova_mail = '';

$page = str_replace("#EMAIL#", $_SESSION['user']['Email'], $page);
$page = str_replace("#PERMESSI#", $_SESSION['user']['Permessi'], $page);
$page = str_replace("#DATA_NASCITA#", $_SESSION['user']['Data_nascita'], $page);

$img = new Immagine();
$utente = new Utente();

try {
    $query_array = $img->find($_SESSION['user']['foto_profilo']);
    if ($query_array != null) {
        $nome_immagine_presente = $query_array['Percorso'];
        $nuovo_percorso_immagine = '../../img/'.$query_array['Percorso'];
        $page = str_replace($percorso_immagine, $nuovo_percorso_immagine, $page);
        $percorso_immagine = $nuovo_percorso_immagine;
    } else {
        $id_user_img = $img->inserisci('Foto profilo', 'utenti/imgnotfound.jpg');
        $utente->associa_immagine($_SESSION['user']['Username'], $id_user_img);
       	$_SESSION['user']['foto_profilo'] = $id_user_img;
        $errore = 'Immagine inserita';
    }
    $page = str_replace("ERRORE", $errore, $page);
} catch (Exception $e) {
    if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
    $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
    $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
    echo $pagina_errore;
}

if (isset($_POST['cambia_foto'])) {
    if (isset($_FILES["nuova_immagine"]["name"])) {
        $nome_nuova_immagine = basename($_FILES["nuova_immagine"]["name"]);
    }
}

if (!empty($nome_nuova_immagine)) {
    try {
        upload_image(__DIR__.'/../../img/utenti/', "nuova_immagine", $_SESSION['max_dim_img']);
        $img->update($_SESSION['user']['foto_profilo'], 'utenti/'.$nome_nuova_immagine);
        $page = str_replace($percorso_immagine, '../../img/utenti/'.$nome_nuova_immagine, $page);
        deleteImage(__DIR__.'/../../img/'.$nome_immagine_presente);
        echo $nome_immagine_presente;
        $nome_immagine_presente = $nome_nuova_immagine;
    } catch (Exception $e) {
        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

if (isset($_POST['modifica_pwd_btn'])) {
    $nuova_password = validate_input($_POST["ripeti_nuova_pwd"]);
    try {
        if ($_POST["nuova_pwd"] == $nuova_password) $utente->aggiorna_password($_SESSION['username'], $nuova_password);
    } catch (Exception $e) {
        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
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
        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
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
    foreach($query_array_scheda_utente as $value) {
        if ($value["Visto"] == true) {
            $conta_visto += 1;
        }
        if ($value["Salvato"] == true){
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
    if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
    $pagina_errore = file_get_contents(__DIR__."/../../html/pagine_altre/errore.html");
    $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
    echo $pagina_errore;
}

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
echo $page;