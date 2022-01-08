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
$durata = $_POST["durata"];
$descrizione_immagine = $_POST["descrizione_immagine"];
$immagine = $_FILES["immagine"]["name"];

$tema = $_POST["tema"];
$eta_publico = $_POST["eta_pubblico"];
$livello = $_POST["livello"];
$mood = $_POST["mood"];
$riconoscimenti = filter_var($_POST["riconoscimenti"],FILTER_VALIDATE_BOOLEAN);

$genere = $_POST["genere"];

$piattaforma = $_POST["piattaforma"];

$cc = filter_var($_POST["cc"],FILTER_VALIDATE_BOOLEAN);
$sdh = filter_var($_POST["sdh"],FILTER_VALIDATE_BOOLEAN);
$ad = filter_var($_POST["ad"],FILTER_VALIDATE_BOOLEAN);
$costo_aggiuntivo = filter_var($_POST["costo_aggiuntivo"],FILTER_VALIDATE_BOOLEAN);
$tempo_limite = $_POST["tempo_limite"];

$film_crud = new Film_crud();

try{
    $film_crud->inserisciFilm($titolo,$lingua_titolo,$anno,$paese,$durata,$trama);
    $id_film = $film_crud->getLastInsertedFilm()["ID"];

    if(!empty($immagine)){
        upload_image($_SESSION['$abs_path_img']."film/","immagine",$_SESSION['max_dim_img']);

        $percorso_immagine = $_SESSION['$abs_path_img']."film/" . basename($_FILES["immagine"]["name"]);
        
        $immagine = new Immagine();
        $immagine->inserisci($descrizione_immagine,$percorso_immagine);

        $id_immagine = $immagine->getLastInsertedImmagine()["ID"];
        
        $film_crud->associa_immagine($id_film,$id_immagine);
    }

    $categorizzazione = new Categorizzazione();
    $categorizzazione->inserisci($id_film,$tema,$eta_publico,$livello,$mood,$riconoscimenti);

    $genereFilm = new GenereFilm();
    $genereFilm->inserisci($id_film,$genere);

    $disponibilita = new Disponibilita();
    $disponibilita->inserisci($piattaforma,$id_film,$cc,$sdh,$ad,$costo_aggiuntivo,$tempo_limite);
}
catch(Exception $e){
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}

?>