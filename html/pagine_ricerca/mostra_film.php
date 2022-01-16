<?php
include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    include_once($_SESSION['$abs_path_php']."database/scheda_utente.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("mostra_film.html");
$piattaforma_section = "";
$piattaforma_component = file_get_contents($_SESSION['$abs_path_html']."componenti/view_piattaforme.html");
$errore = '';
$percorso_film = '';

$immagine = new Immagine();

if (isset($_GET["titolo"])) {
    try {
        if (isset($_SESSION["film"])) {
            foreach ($_SESSION["film"] as $value) {
                if ($_GET["titolo"] == $value["Titolo"]) {
                    $id_immagine = $value["Locandina"];
                    $page = str_replace("</titolo_pagina>", $value['Titolo'], $page);
                    $page = str_replace("</titolo>", $value['Titolo'], $page);
                    $page = str_replace("</lingua_titolo>", $value['Lingua_titolo'], $page);
                    $page = str_replace("</anno>", $value['Anno'], $page);
                    $page = str_replace("</paese>", $value['Paese'], $page);
                    $page = str_replace("</durata>", secondsToTime($value['Durata']), $page);
                    $percorso_immagine = $_SESSION['$img_url'].$immagine->find($id_immagine)["Percorso"];
                    $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"];
                    $page = str_replace("</url_img_film>", $percorso_immagine, $page);
                    $page = str_replace("</alt_img_film>", $descrizione_immagine, $page);
                    $page = str_replace("</error_message>", $errore, $page);
                    
                    foreach($value["Piattaforme"] as $piattaforma)
                    {
                        $piattaforma_section .= $piattaforma_component;
                        $piattaforma_section = str_replace("</nome_piattaforma>", $piattaforma["Nome"], $piattaforma_section);
                        $piattaforma_section = str_replace("</giorno_uscita>", 
                                            !empty($piattaforma["Giorno_uscita"])? " disponibile fino a ".$piattaforma["Giorno_uscita"]:"", 
                                            $piattaforma_section);
                        $piattaforma_section = str_replace("</cc>", $piattaforma["CC"] ? "CC" : "", $piattaforma_section);
                        $piattaforma_section = str_replace("</ad>", $piattaforma["AD"] ? "AD" : "", $piattaforma_section);
                    }

                    $page .= $piattaforma_section;
                    if($_SESSION["logged"]){
                        $scheda_utente = new SchedaUtente();
                        $scheda_result = $scheda_utente->findByFilmUser($value["ID"],$_SESSION["username"]);
                        if($scheda_result[0]["Visto"])                     
                            $page = str_replace("</visto>", "VISTO", $page);
                        if($scheda_result[0]["Salvato"])
                            $page = str_replace("</salvato>", "SALVATO", $page);
                    }

                    break;
                }
            }
        } else {
            $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
            $pagina_errore = str_replace("</error_message>", "Film non più esistente, very sorry", $pagina_errore);
            echo $pagina_errore;
        }

    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
        $pagina_errore = str_replace("</error_message>", "Errore server...", $pagina_errore);
        echo $pagina_errore;
    }
} else {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
    $pagina_errore = str_replace("</error_message>", "Link non corretto lololol", $pagina_errore);
    echo $pagina_errore;
}

echo $page;