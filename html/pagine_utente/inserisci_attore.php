<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/attore.php');
require_once(__DIR__.'/../../php/database/immagine.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../html/componenti/header.php');

$_POST = array_map('empty_to_null', $_POST);

$page = file_get_contents(__DIR__.'/inserisci_attore.html');
$esito_inserimento = "";

if(!empty($_GET["inserted"])) $esito_inserimento = "Attore inserito con successo";

if (!empty($_POST['nome']) && empty($_GET["inserted"])) {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $data_nascita = $_POST["data_nascita"];
    $data_morte = $_POST["data_morte"];
    $note_carriera = $_POST["note_carriera"];
    $descrizione_immagine = $_POST["descrizione_immagine"];
    $immagine = $_FILES["immagine"]["name"];

    $attore = new Attore();

    try {
        $attore->inserisci($nome, $cognome, $data_nascita, $data_morte, $note_carriera);
        $id_attore = $attore->getLastInsertedAttore()["ID"];

        if (!empty($immagine)) {
            upload_image(__DIR__."/../../img/attori/", "immagine", $_SESSION["max_dim_img"]);

            $percorso_immagine = "attori/". basename($_FILES["immagine"]["name"]);

            $immagine = new Immagine();
            $immagine->inserisci($descrizione_immagine, $percorso_immagine);

            $id_immagine = $immagine->getLastInsertedImmagine()["ID"];

            $attore->associa_immagine($id_attore, $id_immagine);
        }

        header("location: inserisci_attore.php?inserted=1");
    } catch (Exception $e) {
		$page = str_replace("#ERRORE_NOME#", " error", $page);
        $page = str_replace("#ERRORE_NOME#", " error", $page);
        $esito_inserimento = $e;
    }
}

$page = str_replace("#ERRORE_NOME#", "", $page);
$page = str_replace("#ERRORE_NOME#", "", $page);
$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
$page = str_replace("#ESITO_INSERIMENTO#", $esito_inserimento, $page);
echo $page;
?>