<?php

include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/film_crud.php");
    include_once($_SESSION['$abs_path_php']."database/valutazione.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents("commenti.html");
$view_commenti_component = file_get_contents($_SESSION['$abs_path_html'] . "componenti/view_commento.html");
$insert_commenti_component = file_get_contents($_SESSION['$abs_path_html'] . "componenti/insert_commento.html");
$commenti_section = "";
$errore = '';

$id_film = $_GET["id"];

$valutazione = new Valutazione();
$film_crud = new Film_crud();
$immagine = new Immagine();

if($_POST["commento"] && $_SESSION["logged"]){
    try{
        if(empty($_POST["modifica"])){
            $valutazione->inserisci($_SESSION["user"]["Username"],$id_film,$_POST["commento"],0,date("Y-m-d H:i:s"),$_POST["valutazione_stelle"]);
        }
        else{
            $valutazione->modifica($_SESSION["user"]["Username"],$id_film,$_POST["commento"],0,$_POST["valutazione_stelle"]);
        }
        header("location: commenti.php?id=$id_film");
    }catch(Exception $e){
        $errore = $e;
    }
}


try {
    if (!empty($id_film)) {
        $titolo_film = $film_crud->findById($id_film)["Titolo"];
        $valutazione_data = $valutazione->getByFilmIdWithUtente($id_film); 

        $page = str_replace("#TITOLO_FILM#", $titolo_film, $page);
        //TODO: Fare refactor di questa parte duplicata
        if($_SESSION["logged"]){
            $commenti_section .= $insert_commenti_component;
            $valutazione_utente = $valutazione->find($_SESSION["user"]["Username"],$id_film);

            $id_immagine = $valutazione_utente["foto_profilo"];
            $percorso_immagine = $immagine->find($id_immagine) ? $_SESSION['$img_url'].$immagine->find($id_immagine)["Percorso"] : $_SESSION['$img_not_found_url'];
            $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"] ?? "";
            $commenti_section = str_replace("#URL_IMG_UTENTE#", $percorso_immagine, $commenti_section);
            $commenti_section = str_replace("#ALT_IMG_UTENTE#", $descrizione_immagine, $commenti_section);
            $commenti_section = str_replace("#ID_FILM#", $id_film, $commenti_section);
            
            $commenti_section = str_replace("#EMAIL_UTENTE#", !empty($valutazione_utente) ? $valutazione_utente["Email"] : $_SESSION["user"]["Email"], $commenti_section);
            $commenti_section = str_replace("#SELECTED".$valutazione_utente["Stelle"]."#", "selected", $commenti_section);
            for($i = 1; $i <= 5; $i++)
                $commenti_section = str_replace("#SELECTED".$i."#", "", $commenti_section);

            $commenti_section = str_replace("#COMMENTO_UTENTE#", $valutazione_utente["Commento"], $commenti_section);
            if(!empty($valutazione_utente)){
                $commenti_section = str_replace("#MODIFICA_COMMENTO#", 1, $commenti_section);
            }else{
                $commenti_section = str_replace("#MODIFICA_COMMENTO#", 0, $commenti_section);
            }
        }

        foreach($valutazione_data as $valutazione_item){
            if(!$_SESSION["logged"] || ($_SESSION["logged"] && $_SESSION["user"]["Username"] != $valutazione_item["Utente"])) // NON E' LOGGATO OR E' LOGGATO E LA VALUTAZIONE NON E' LA SUA
            {
                $commenti_section .= $view_commenti_component;
                $id_immagine = $valutazione_item["foto_profilo"];
                $percorso_immagine = $immagine->find($id_immagine) ? $_SESSION['$img_url'].$immagine->find($id_immagine)["Percorso"] : $_SESSION['$img_not_found_url'];
                $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"] ?? "";
                $commenti_section = str_replace("#URL_IMG_UTENTE#", $percorso_immagine, $commenti_section);
                $commenti_section = str_replace("#ALT_IMG_UTENTE#", $descrizione_immagine, $commenti_section);
                $commenti_section = str_replace("#EMAIL_UTENTE#", $valutazione_item["Email"], $commenti_section);
                $commenti_section = str_replace("#SELECTED".$valutazione_item["Stelle"]."#", "selected", $commenti_section);
                for($i = 1; $i <= 5; $i++)
                    $commenti_section = str_replace("#SELECTED".$i."#", "", $commenti_section);

                $commenti_section = str_replace("#COMMENTO_UTENTE#", $valutazione_item["Commento"], $commenti_section);
            }
        }

        $page = str_replace("#COMMENTI_FILM#", $commenti_section, $page);
    } else {
        $errore = 'Parametro id non passato';
    }
} catch (Exception $e) {
    $errore = $e;
}

$page = str_replace("#ERROR_MESSAGE#", $errore, $page);

echo $page;