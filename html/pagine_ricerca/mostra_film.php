<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void
{
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
    require_once(__DIR__.'/../../php/database/scheda_utente.php');
    require_once(__DIR__.'/../../html/componenti/commonPageElements.php');

    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("mostra_film.html");
$piattaforma_section = "";
$piattaforma_component = file_get_contents(__DIR__.'/../../html/componenti/view_piattaforme.html');
$errore = '';
$percorso_film = '';

$immagine = new Immagine();

if (isset($_GET["titolo"])) {
        if (isset($_SESSION["film"])) {
            foreach ($_SESSION["film"] as $value) {
                if ($_GET["titolo"] == $value["Titolo"]) {
                    $id_film = $value["ID"];
                    $id_immagine = $value["Locandina"];
                    $page = str_replace("#TITOLO_PAGINA#", $value['Titolo'], $page);
                    $page = str_replace("#TITOLO#", $value['Titolo'], $page);
                    $page = str_replace("#LINGUA_TITOLO#", $value['Lingua_titolo'], $page);
                    $page = str_replace("#ANNO#", $value['Anno'], $page);
                    $page = str_replace("#PAESE#", siglaToPaese($value['Paese']), $page);
                    $page = str_replace("#DURATA#", secondsToTime($value['Durata']), $page);
                    try{
                        $percorso_immagine = '../../img/'.$immagine->find($id_immagine)["Percorso"];
                        $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"];
                        $page = str_replace("#URL_IMG_FILM#", $percorso_immagine, $page);
                        $page = str_replace("#ALT_IMG_FILM#", $descrizione_immagine, $page);
                        $page = str_replace("#ERROR_MESSAGE#", $errore, $page);
                        
                        foreach($value["Piattaforme"] as $piattaforma)
                        {
                            $piattaforma_section .= $piattaforma_component;
                            $piattaforma_section = str_replace("#NOME_PIATTAFORMA#", $piattaforma["Nome"], $piattaforma_section);
                            $piattaforma_section = str_replace("#GIORNO_USCITA#", 
                                                !empty($piattaforma["Giorno_uscita"])? " disponibile fino a ".dateUsaToEur($piattaforma["Giorno_uscita"]):"", 
                                                $piattaforma_section);
                            $piattaforma_section = str_replace("#CC#", $piattaforma["CC"] ? "CC" : "", $piattaforma_section);
                            $piattaforma_section = str_replace("#AD#", $piattaforma["AD"] ? "AD" : "", $piattaforma_section);
                        }

                        $page = str_replace("#PIATTAFORME#", $piattaforma_section, $page);
                        if($_SESSION["logged"]){
                            $scheda_utente = new SchedaUtente();
                            $scheda_result = $scheda_utente->findByFilmUser($id_film,$_SESSION["user"]["Username"]);
                            if($scheda_result[0]["Visto"])                     
                                $page = str_replace("#VISTO#", "VISTO", $page);
                            if($scheda_result[0]["Salvato"])
                                $page = str_replace("#SALVATO#", "SALVATO", $page);
                        }

                        $page = str_replace("#ID_FILM#", $id_film, $page);

                        break;
                    } catch (Exception $e) {
                        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
                        $pagina_errore = str_replace("#ERROR_MESSAGE#", "Errore server...", $pagina_errore);
                        echo $pagina_errore;
                    }
                }
            }
        } else {
            $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
            $pagina_errore = str_replace("#ERROR_MESSAGE#", "Film non più esistente", $pagina_errore);
            echo $pagina_errore;
        } 
    } else {
        $pagina_errore = file_get_contents(__DIR__.'/../../html/pagine_altre/errore.html');
        $pagina_errore = str_replace("#ERROR_MESSAGE#", "Link non corretto", $pagina_errore);
        echo $pagina_errore;
    }

$page = str_replace("#VISTO#", "", $page);
$page = str_replace("#SALVATO#", "", $page);

$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);

echo $page;