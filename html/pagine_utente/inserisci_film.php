<?php

require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/film_crud.php');
require_once(__DIR__.'/../../php/database/immagine.php');
require_once(__DIR__.'/../../php/database/categorizzazione.php');
require_once(__DIR__.'/../../php/database/genereFilm.php');
require_once(__DIR__.'/../../php/database/disponibilita.php');
require_once(__DIR__.'/../../php/database/cast_film.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../html/componenti/commonPageElements.php');

$page = file_get_contents(__DIR__.'/inserisci_film.html');
$searchbar_attore_component = file_get_contents(__DIR__.'/../../html/componenti/searchbar_attore.html');
$esito_inserimento = "";

if(!empty($_GET["inserted"])) $esito_inserimento = "Film inserito con successo";

if(!empty($_POST["titolo"] &&  empty($_GET["inserted"])))
{   
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

    $attori = array_map('empty_to_null',$_POST["actors"]);

    $film_crud = new Film_crud();

    try{
        $film_crud->inserisciFilm($titolo,$lingua_titolo,$anno,$paese,$durata,$trama);
        $id_film = $film_crud->getLastInsertedFilm()["ID"];

        if(!empty($immagine)){
            upload_image(__DIR__.'/../../img/film/',"immagine",$_SESSION['max_dim_img']);

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

        $cast_film = new Cast_film();
        foreach($attori as $attore_id){
            $cast_film->inserisci($id_film,$attore_id);
        }

        header("location: inserisci_film.php?inserted=1");
    }
    catch(Exception $e){
        $esito_inserimento = $e;
    }
}

$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);
$page = str_replace("#ESITO_INSERIMENTO#", $esito_inserimento, $page);
$page = str_replace("#INSERISCI_ATTORI#", $searchbar_attore_component, $page);
echo $page;
?>