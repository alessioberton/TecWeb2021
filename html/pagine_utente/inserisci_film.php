<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php']."database/film_crud.php");
require_once($_SESSION['$abs_path_php']."database/immagine.php");
require_once($_SESSION['$abs_path_php']."database/categorizzazione.php");
require_once($_SESSION['$abs_path_php']."database/genereFilm.php");
require_once($_SESSION['$abs_path_php']."database/disponibilita.php");
require_once($_SESSION['$abs_path_php']."logic/functions.php");

$_POST = array_map('empty_to_null', $_POST);

$titolo = $_POST["titolo"];
$lingua_titolo = $_POST["lingua_titolo"];
$trama = $_POST["trama"];
$anno = $_POST["anno"];
$paese = $_POST["paese"];
$durata = timeToSeconds($_POST["durata"]);
$descrizione_immagine = $_POST["descrizione_immagine"];
$immagine = $_FILES["immagine"]["name"];

$tema = $_POST["tema"];
$eta_publico = $_POST["eta_pubblico"];
$livello = $_POST["livello"];
$mood = $_POST["mood"];
$riconoscimenti = filter_var($_POST["riconoscimenti"],FILTER_VALIDATE_BOOLEAN);

$genere = $_POST["genere"];

$piattaforma = $_POST["piattaforma"];
$cc = $_POST["cc"];
$sdh = $_POST["sdh"];
$ad = $_POST["ad"];
$costo_aggiuntivo = $_POST["costo_aggiuntivo"];
$giorno_entrata = array_map('empty_to_null',$_POST["giorno_entrata"]);
$giorno_uscita = array_map('empty_to_null',$_POST["giorno_uscita"]);

$film_crud = new Film_crud();

try{
    $film_crud->inserisciFilm($titolo,$lingua_titolo,$anno,$paese,$durata,$trama);
    $id_film = $film_crud->getLastInsertedFilm()["ID"];

    if(!empty($immagine)){
        upload_image($_SESSION['$abs_path_img']."film/","immagine",$_SESSION['max_dim_img']);

        $percorso_immagine = "film/" . basename($_FILES["immagine"]["name"]);
        
        $immagine = new Immagine();
        $immagine->inserisci($descrizione_immagine,$percorso_immagine);

        $id_immagine = $immagine->getLastInsertedImmagine()["ID"];
        
        $film_crud->associa_immagine($id_film,$id_immagine);
    }

    $categorizzazione = new Categorizzazione();
    $categorizzazione->inserisci($id_film,$tema,$eta_publico,$livello,$mood,$riconoscimenti);

    $genereFilm = new GenereFilm();
    foreach($genere as $nome_genere => $val_genere){
        $genereFilm->inserisci($id_film,$nome_genere);
    }

    $disponibilita = new Disponibilita();
    foreach($piattaforma as $nome_piattaforma => $val_piattaforma){
        $nome_piattaforma;
        $cc_piattaforma = filter_var($cc[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
        $sdh_piattaforma = filter_var($sdh[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
        $ad_piattaforma = filter_var($ad[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
        $costo_aggiuntivo_piattaforma = filter_var($costo_aggiuntivo[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
        $giorno_entrata_piattaforma = $giorno_entrata[$nome_piattaforma];
        $giorno_uscita_piattaforma = $giorno_uscita[$nome_piattaforma];

        $disponibilita->inserisci($nome_piattaforma,$id_film,$cc_piattaforma,$sdh_piattaforma,$ad_piattaforma,$costo_aggiuntivo_piattaforma,$giorno_entrata_piattaforma,$giorno_uscita_piattaforma);
    }
}
catch(Exception $e){
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}

?>