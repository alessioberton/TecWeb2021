<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php']."database/attore.php");
require_once($_SESSION['$abs_path_php']."database/immagine.php");
require_once($_SESSION['$abs_path_php']."logic/functions.php");
include_once($_SESSION['$abs_path_html']."componenti/commonPageElements.php");

$_POST = array_map('empty_to_null', $_POST);

if (isset($_POST['nome'])) {
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
            upload_image($_SESSION['$abs_path_img'] ."attori/", "immagine", $_SESSION["max_dim_img"]);

            $percorso_immagine = "attori/". basename($_FILES["immagine"]["name"]);

            $immagine = new Immagine();
            $immagine->inserisci($descrizione_immagine, $percorso_immagine);

            $id_immagine = $immagine->getLastInsertedImmagine()["ID"];

            $attore->associa_immagine($id_attore, $id_immagine);
        }
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path'] . "html/pagine_altre/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

$page = file_get_contents("inserisci_attore.html");
$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);
echo $page;
?>