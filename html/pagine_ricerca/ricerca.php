<?php
include_once '../../php/config.php';
include_once($_SESSION['$abs_path_html'] . "componenti/commonPageElements.php");
include_once($_SESSION['$abs_path_php'] . "database/categorizzazione.php");
include_once($_SESSION['$abs_path_php'] . "logic/functions.php");
include_once($_SESSION['$abs_path_php'] . "database/utente.php");
include_once($_SESSION['$abs_path_php'] . "database/immagine.php");
$_POST = array_map('empty_to_null', $_GET);

$page = file_get_contents("ricerca.html");
$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);

$categorizizzazione = new Categorizzazione();
if (isset($_GET["piattaforma"])) {
    $piattaforme = $_GET["piattaforma"];
}

if (isset($_GET["opzione"])) {
    $opzione = $_GET["opzione"];
}

if (isset($_GET["genere"])) {
    $genere = $_GET["genere"];
}

if (isset($_GET["eta"])) {
    $eta = $_GET["eta"];
}

$sql = [];

if ($eta) {
    for ($i = 0; $i < count($eta); $i++) {
        $sql[] = " Eta_pubblico = '$eta[$i]' ";
    }
}

$test = $categorizizzazione->dynamic_find($sql);
print_r($test);


//print_r($piattaforme);
//print_r($opzione);
//print_r($genere);
//print_r($eta);


echo $page;

