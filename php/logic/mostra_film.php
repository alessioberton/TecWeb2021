<?php

include '../config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."film/film.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
//    if ($_SESSION['logged'] == false) {
//        header('location: ../accesso_negato.php');
//        exit();
//    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("../../html/mostra_film.html");
$errore = '';

$film = new Film();

try {
    $_SESSION["film"] = $film->find("Il Re leone");
    if ($_SESSION["film"] != null) {
        print_r($_SESSION);
        $page = str_replace("#TITOLO#", $_SESSION["film"]['Titolo'], $page);
        $page = str_replace("#LINGUA#", $_SESSION["film"]['Lingua_titolo'], $page);
    } else {
        $errore = 'Film inesistente sorry  ;:-((';
    }
    $page = str_replace("ERRORE", $errore, $page);
} catch (Exception $e) {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}



echo $page;