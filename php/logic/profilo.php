<?php

include '../config.php';

function getAbs_path(): void
{
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."utente/utente.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    if ($_SESSION['logged'] == false) {
        header('location: ../accesso_negato.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

$errore = '';
$percorso_immagine = '../../img/imgnotfound.jpg';
$nome_immagine_presente = 'imgnotfound.jpg';
$nuova_password = '';
$nuova_mail = '';

$img = new Immagine();
$utente = new Utente();

getAbs_path();

$page = file_get_contents("../../html/profilo.html");
print_r($_SESSION);

$page = str_replace("EMAIL", $_SESSION['email'], $page);
$page = str_replace("UTENZA", $_SESSION['permesso'], $page);
$page = str_replace("DATA_NASCITA", $_SESSION['data_nascita'], $page);
$page = str_replace("VECCHIA_MAIL", $_SESSION['email'], $page);

try {
    $query_array = $img->find($_SESSION['foto_profilo']);
    if ($query_array != null) {
        $nome_immagine_presente = $query_array['Nome'];
        $nuovo_percorso_immagine = '../../img/utenti/'.$query_array['Nome'];
        $page = str_replace($percorso_immagine, $nuovo_percorso_immagine, $page);
        $percorso_immagine_presente = $nuovo_percorso_immagine;
    } else {
        $errore = 'Nessuna immagine trovata lel';
    }
    $page = str_replace("ERRORE", $errore, $page);
} catch (Exception $e) {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}

if (isset($_POST['cambia_foto'])) {
    if (isset($_FILES["nuova_immagine"]["name"])) {
        $nome_nuova_immagine = basename($_FILES["nuova_immagine"]["name"]);
    }
}

if (!empty($nome_nuova_immagine)) {
    try {
        upload_image($_SESSION['$abs_path_img'].'utenti/', "nuova_immagine", $_SESSION['max_dim_img']);
        $img->update(1, $nome_nuova_immagine);
        $page = str_replace($percorso_immagine, '../../img/utenti/'.$nome_nuova_immagine, $page);
        deleteImage($_SESSION['$abs_path_img'].'utenti/'.$nome_immagine_presente);
        $nome_immagine_presente = $nome_nuova_immagine;
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

if (isset($_POST['modifica_pwd_btn'])) {
    $nuova_password = $_POST["ripeti_nuova_pwd"];
    try {
        $utente->aggiorna_password($_SESSION['username'], $_POST["ripeti_nuova_pwd"]);
    } catch (Exception $e) {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
    }
}

if (isset($_POST['modifica_mail_btn'])) {
    $nuova_mail = $_POST["nuova_mail"];
    try {
        $utente->aggiorna_mail($_SESSION['username'], $_POST["nuova_mail"]);
        $_SESSION['email'] = $_POST["nuova_mail"];
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

echo $page;