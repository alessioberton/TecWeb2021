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
print_r($_SESSION);
print_r($_POST);
print_r($_GET);

if (isset($_GET["titolo"])) {
    try {
        $_SESSION["film"] = $film->find($_GET["titolo"]);
        if ($_SESSION["film"] != null) {
            $page = str_replace("#TITOLO#", $_SESSION["film"]['Titolo'], $page);
            $page = str_replace("#LINGUA#", $_SESSION["film"]['Lingua_titolo'], $page);
            echo $page;
        } else {
            $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
            $pagina_errore = str_replace("</error_message>", "Film non più esistente, very sorry", $pagina_errore);
            echo $pagina_errore;
        }
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
        $pagina_errore = str_replace("</error_message>", "Errore server...", $pagina_errore);
        echo $pagina_errore;
    }
} else {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
    $pagina_errore = str_replace("</error_message>", "Link non corretto lololol", $pagina_errore);
    echo $pagina_errore;
}