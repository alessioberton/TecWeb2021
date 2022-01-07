<?php

include '../config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."attore/attore.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
//    if ($_SESSION['logged'] == false) {
//        header('location: ../accesso_negato.php');
//        exit();
//    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("../../html/mostra_attore.html");
$errore = '';

$attore = new Attore();

try {
    $_SESSION["attore"] = $attore->find_nome_cognome_nascita("Brad", "Pitt", "1963-12-18");
    if ($_SESSION["attore"] != null) {
        print_r($_SESSION);
        $page = str_replace("#NOME#", $_SESSION["attore"]['Nome'], $page);
        $page = str_replace("#COGNOME#", $_SESSION["attore"]['Cognome'], $page);
        $page = str_replace("#DATA_NASCITA#", $_SESSION["attore"]['Data_nascita'], $page);
    } else {
        $errore = 'Attore bhoooooooooo ahah  ;:-((';
    }
    $page = str_replace("ERRORE", $errore, $page);
} catch (Exception $e) {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}

echo $page;