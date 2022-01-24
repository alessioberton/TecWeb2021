<?php
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../html/componenti/commonPageElements.php');
require_once(__DIR__.'/../../php/database/categorizzazione.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../php/database/utente.php');
require_once(__DIR__.'/../../php/database/immagine.php');
$_POST = array_map('empty_to_null', $_GET);

$page = file_get_contents(__DIR__.'/ricerca.html');
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

echo $page;

