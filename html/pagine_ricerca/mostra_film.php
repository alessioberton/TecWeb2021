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
                    $page = str_replace("#TITOLO_PAGINA#", $value['Titolo'], $page);
                    $page = str_replace("#TITOLO#", $value['Titolo'], $page);
                    $page = str_replace("#LINGUA_TITOLO#", $value['Lingua_titolo'], $page);
                    $page = str_replace("#ANNO#", $value['Anno'], $page);
                    $page = str_replace("#PAESE#", $value['Paese'], $page);
                    $page = str_replace("#DURATA#", secondsToTime($value['Durata']), $page);
                    $percorso_immagine = $_SESSION['$img_url'].$immagine->find($id_immagine)["Percorso"];
                    $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"];
                    $page = str_replace("#URL_IMG_FILM#", $percorso_immagine, $page);
                    $page = str_replace("#ALT_IMG_FILM#", $descrizione_immagine, $page);
                    $page = str_replace("#ERROR_MESSAGE#", $errore, $page);
                    
                    foreach($value["Piattaforme"] as $piattaforma)
                    {
                        $piattaforma_section .= $piattaforma_component;
                        $piattaforma_section = str_replace("#NOME_PIATTAFORMA#", $piattaforma["Nome"], $piattaforma_section);
                        $piattaforma_section = str_replace("#GIORNO_USCITA#", 
                                            !empty($piattaforma["Giorno_uscita"])? " disponibile fino a ".$piattaforma["Giorno_uscita"]:"", 
                                            $piattaforma_section);
                        $piattaforma_section = str_replace("#CC#", $piattaforma["CC"] ? "CC" : "", $piattaforma_section);
                        $piattaforma_section = str_replace("#AD#", $piattaforma["AD"] ? "AD" : "", $piattaforma_section);
                    }

                    $page = str_replace("#PIATTAFORME#", $piattaforma_section, $page);
                    if($_SESSION["logged"]){
                        $scheda_utente = new SchedaUtente();
                        $scheda_result = $scheda_utente->findByFilmUser($value["ID"],$_SESSION["user"]["Username"]);
                        if($scheda_result[0]["Visto"])                     
                            $page = str_replace("#VISTO#", "VISTO", $page);
                        if($scheda_result[0]["Salvato"])
                            $page = str_replace("#SALVATO#", "SALVATO", $page);
                    }

                    break;
                }
            }
        } else {
            $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
            $pagina_errore = str_replace("#ERROR_MESSAGE#", "Film non più esistente, very sorry", $pagina_errore);
            echo $pagina_errore;
        }

    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
        $pagina_errore = str_replace("#ERROR_MESSAGE#", "Errore server...", $pagina_errore);
        echo $pagina_errore;
    }
} else {
    $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/pagine_altre/errore.html");
    $pagina_errore = str_replace("#ERROR_MESSAGE#", "Link non corretto lololol", $pagina_errore);
    echo $pagina_errore;
}

echo $page;