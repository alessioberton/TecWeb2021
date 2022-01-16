<?php

include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/attore.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("mostra_attore.html");
$errore = '';

$id_attore = $_GET["id"];

$attore = new Attore();
$immagine = new Immagine();

try {
    if (!empty($id_attore)) {
        $attore_data = $attore->getById($id_attore);

        $page = str_replace("#NOME#", $attore_data["Nome"], $page);
        $page = str_replace("#COGNOME#", $attore_data["Cognome"], $page);
        $page = str_replace("#DATA_NASCITA#", dateUsaToEur($attore_data["Data_nascita"]), $page);
        $id_immagine = $attore_data["ID_foto"];
        $percorso_immagine = $_SESSION['$img_url'].$immagine->find($id_immagine)["Percorso"];
        $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"];
        $page = str_replace("#URL_IMG_ATTORE#", $percorso_immagine, $page);
        $page = str_replace("#ALT_IMG_ATTORE#", $descrizione_immagine, $page);
        $num_film = $attore->numeroFilm($id_attore);
        $page = str_replace("#NUMERO_FILM_TOTALI#", $num_film, $page);
    } else {
        $errore = 'Parametro id non passato';
    }
} catch (Exception $e) {
    $errore = $e;
}

$page = str_replace("#ERROR_MESSAGE#", $errore, $page);

echo $page;