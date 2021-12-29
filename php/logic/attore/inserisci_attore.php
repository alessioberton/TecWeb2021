<?php

$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';
$max_dim_img = 1300000; // Kb

include_once($abs_path."attore/attore.php");
include_once($abs_path."immagine/immagine.php");
include_once($abs_path."functions/functions.php");

$_POST = array_map('empty_to_null', $_POST);

$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$data_nascita = $_POST["data_nascita"];
$data_morte = $_POST["data_morte"];
$note_carriera = $_POST["note_carriera"];
$descrizione_immagine = $_POST["descrizione_immagine"];
$immagine = $_FILES["immagine"]["name"];

$attore = new Attore();

try{
    $attore->inserisci($nome,$cognome,$data_nascita,$data_morte,$note_carriera);
    $id_attore = $attore->getLastInsertedAttore()["ID"];

    if(!empty($immagine)){
        upload_image($abs_path."../img/","immagine",$max_dim_img);

        $percorso_immagine = $abs_path."../img/" . basename($_FILES["immagine"]["name"]);
        
        $immagine = new Immagine();
        $immagine->inserisci($descrizione_immagine,$percorso_immagine);

        $id_immagine = $immagine->getLastInsertedImmagine()["ID"];
        
        $attore->associa_immagine($id_attore,$id_immagine);
    }
}
catch(Exception $e){
    $pagina_errore = file_get_contents($abs_path."../html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}

?>