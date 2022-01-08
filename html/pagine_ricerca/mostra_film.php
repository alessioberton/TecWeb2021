<?php

include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("mostra_film.html");
$errore = '';
$percorso_film = '';

$immaginw = new Immagine();

if (isset($_GET["titolo"])) {
    try {
//        if (isset($_SESSION["film"])) {
            $id_film = $_SESSION["film"]["Locandina"];
            $page = str_replace("#TITOLO_PAGINA#", $_SESSION["film"]['Titolo'], $page);
            $page = str_replace("#TITOLO#", $_SESSION["film"]['Titolo'], $page);
            $page = str_replace("#LINGUA#", $_SESSION["film"]['Lingua_titolo'], $page);
            $percorso_film = $immaginw->find($id_film)["Percorso"];
            $nuovo_percorso_immagine = '../../img/' . $percorso_film;
            $page = str_replace("../../img/film/imgnotfound.jpg", $nuovo_percorso_immagine, $page);
            echo $page;
//        } else {
//            $pagina_errore = file_get_contents($_SESSION['$abs_path'] . "html/errore.html");
//            $pagina_errore = str_replace("</error_message>", "Film non più esistente, very sorry", $pagina_errore);
//            echo $pagina_errore;
//        }

    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
        $pagina_errore = str_replace("</error_message>", "Errore server...", $pagina_errore);
        echo $pagina_errore;
    }
} else {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
    $pagina_errore = str_replace("</error_message>", "Link non corretto lololol", $pagina_errore);
    echo $pagina_errore;
}