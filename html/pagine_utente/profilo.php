<?php

include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/utente.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    include_once($_SESSION['$abs_path_php']."database/scheda_utente.php");
    if ($_SESSION['logged'] == false) {
        header('location: ../pagine_altre/accesso_negato.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("profilo.html");

$errore = '';
$percorso_immagine = '../../img/utenti/imgnotfound.jpg';
$nome_immagine_presente = 'imgnotfound.jpg';
$nuova_password = '';
$nuova_mail = '';

$img = new Immagine();
$utente = new Utente();

//print_r($_SESSION);

$page = str_replace("#EMAIL#", $_SESSION['email'], $page);
$page = str_replace("#UTENZA#", $_SESSION['permesso'], $page);
$page = str_replace("#DATA_NASCITA#", $_SESSION['data_nascita'], $page);
$page = str_replace("#VECCHIA_MAIL#", $_SESSION['email'], $page);

try {
    $query_array = $img->find($_SESSION['foto_profilo']);
    if ($query_array != null) {
        $nome_immagine_presente = $query_array['Percorso'];
        $nuovo_percorso_immagine = '../../img/'.$query_array['Percorso'];
        $page = str_replace($percorso_immagine, $nuovo_percorso_immagine, $page);
        $percorso_immagine = $nuovo_percorso_immagine;
    } else {
        $id_user_img = $img->inserisci('Foto profilo', 'utenti/imgnotfound.jpg');
            $utente->associa_immagine($_SESSION['username'], $id_user_img);
        $_SESSION['foto_profilo'] = $id_user_img;
        $errore = 'Immagine inserita';
    }
    $page = str_replace("ERRORE", $errore, $page);
} catch (Exception $e) {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
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
        $img->update($_SESSION['foto_profilo'], 'utenti/'.$nome_nuova_immagine);
        $page = str_replace($percorso_immagine, '../../img/utenti/'.$nome_nuova_immagine, $page);
        deleteImage($_SESSION['$abs_path_img'].$nome_immagine_presente);
        echo $nome_immagine_presente;
        $nome_immagine_presente = $nome_nuova_immagine;
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

if (isset($_POST['modifica_pwd_btn'])) {
    $nuova_password = $_POST["ripeti_nuova_pwd"];
    try {
        if ($_POST["nuova_pwd"] == $nuova_password) $utente->aggiorna_password($_SESSION['username'], $nuova_password);
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

if (isset($_POST['modifica_mail_btn'])) {
    $nuova_mail = $_POST["nuova_mail"];
    try {
        $utente->aggiorna_mail($_SESSION['username'], $_POST["nuova_mail"]);
        $page = str_replace($_SESSION['email'], $_POST['nuova_mail'], $page);
        $page = str_replace($_SESSION['email'], $_POST['nuova_mail'], $page);
        $_SESSION['email'] = $_POST["nuova_mail"];
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

$scheda_utente = new SchedaUtente();
$conta_visto = 0;
$conta_salvati = 0;
$conta_valutati = 0;
$array_visto = array();
$array_salvato = array();
$array_valutato = array();

try {
    $query_array_scheda_utente = $scheda_utente->findByUser($_SESSION['username']);
    foreach($query_array_scheda_utente as $value) {
        if ($value["Visto"] == true) {
            $conta_visto += 1;
            $array_visto[] = $value;
        }
        if ($value["Salvato"] == true){
            $conta_salvati += 1;
            $array_salvato[] = $value;
        }
        if ($value["Suggerito"] == true) {
            $conta_valutati += 1;
            $array_valutato[] = $value;
        }
    }
    $_SESSION["array_visto"] = $array_visto;
    $page = str_replace("#FILM_VISTI#", $conta_visto, $page);
    $page = str_replace("#FILM_SALVATI#", $conta_salvati, $page);
    $page = str_replace("#FILM_VALUTATI#", $conta_valutati, $page);
} catch (Exception $e) {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}


echo $page;