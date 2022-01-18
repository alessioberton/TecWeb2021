<?php

include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/film_crud.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    include_once($_SESSION['$abs_path_html']."componenti/commonPageElements.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("lista_attori.html");
$list_attori_component = file_get_contents($_SESSION['$abs_path_html'] . "componenti/list_attori.html");
$lista_attori_section = "";
$errore = '';

$id_film = $_GET["id"];

$film_crud = new Film_crud();
$immagine = new Immagine();

try {
    if (!empty($id_film)) {
        $attori_film = $film_crud->getAttori($id_film);
        $titolo_film = $film_crud->findById($id_film)["Titolo"];

        $page = str_replace("#TITOLO_FILM#", $titolo_film, $page);

        foreach($attori_film as $attore_film){
            $lista_attori_section .= $list_attori_component;
            $lista_attori_section = str_replace("#NOME_ATTORE#", $attore_film["Nome"], $lista_attori_section);
            $lista_attori_section = str_replace("#COGNOME_ATTORE#", $attore_film["Cognome"], $lista_attori_section);
            $id_immagine = $attore_film["ID_foto"];
            $percorso_immagine = $immagine->find($id_immagine) ? $_SESSION['$img_url'].$immagine->find($id_immagine)["Percorso"] : $_SESSION['$img_not_found_url'];
            $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"] ?? "";
            $lista_attori_section = str_replace("#URL_IMG_ATTORE#", $percorso_immagine, $lista_attori_section);
            $lista_attori_section = str_replace("#ALT_IMG_ATTORE#", $descrizione_immagine, $lista_attori_section);
        }

        $page = str_replace("#ATTORI#", $lista_attori_section, $page);
    } else {
        $errore = 'Parametro id non passato';
    }
} catch (Exception $e) {
    $errore = $e;
}

$page = str_replace("#ERROR_MESSAGE#", $errore, $page);

$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);

echo $page;