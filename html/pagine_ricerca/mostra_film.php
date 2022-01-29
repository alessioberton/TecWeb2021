<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void
{
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
    require_once(__DIR__.'/../../php/database/scheda_utente.php');
    require_once(__DIR__.'/../../php/database/valutazione.php');
    require_once(__DIR__.'/../../html/componenti/header.php');

    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents("mostra_film.html");
$piattaforma_section = "";
$piattaforma_component = file_get_contents(__DIR__.'/../../html/componenti/view_piattaforme.html');
$pulsanti_component = file_get_contents(__DIR__.'/../../html/componenti/pulsanti_film.html');
$errore = '';
$percorso_film = '';

$immagine = new Immagine();
$scheda_utente = new SchedaUtente();
$valutazione = new Valutazione();

if (isset($_GET["titolo"])) {
        if (isset($_SESSION["film"])) {
            foreach ($_SESSION["film"] as $value) {
                if ($_GET["titolo"] == $value["Titolo"]) {
                    $id_film = $value["ID"];
                    inserisciModificaValutazione($scheda_utente,$valutazione,$id_film);
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
                            $page = str_replace("#VALUTAZIONE#", $pulsanti_component, $page);
                            $page = str_replace("#TITOLO_FILM#", $_GET["titolo"], $page);
                            $scheda_result = $scheda_utente->findByFilmUser($id_film,$_SESSION["user"]["Username"]);
                            $page = empty($scheda_result) ? str_replace("#MODIFICA_SCHEDA#", "0", $page) : str_replace("#MODIFICA_SCHEDA#", "1", $page);
                            if($scheda_result[0]["Visto"]){               
                                $page = str_replace("#SELECTED_NON_VISTO#", "", $page);
                                $page = str_replace("#SELECTED_VISTO#", "selected", $page);
                            } else{
                                $page = str_replace("#SELECTED_NON_VISTO#", "selected", $page);
                                $page = str_replace("#SELECTED_VISTO#", "", $page);
                            }

                            if($scheda_result[0]["Salvato"]){
                                $page = str_replace("#SELECTED_NON_SALVATO#", "", $page);
                                $page = str_replace("#SELECTED_SALVATO#", "selected", $page);
                            }else{
                                $page = str_replace("#SELECTED_NON_SALVATO#", "selected", $page);
                                $page = str_replace("#SELECTED_SALVATO#", "", $page);
                            }

                            $valutazione_item = $valutazione->find($id_film,$_SESSION["user"]["Username"]);
                            $page = empty($valutazione_item) ? str_replace("#MODIFICA_VALUTAZIONE#", "0", $page) : str_replace("#MODIFICA_VALUTAZIONE#", "1", $page);
                            $page = str_replace("#SELECTED".$valutazione_item["Stelle"]."#", "selected", $page);
                        }else{
                            $page = str_replace("#VALUTAZIONE#", "", $page);
                        }

                        for($i = 1; $i <= 5; $i++)
                            $page = str_replace("#SELECTED".$i."#", "", $page);
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

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);

echo $page;

function inserisciModificaValutazione($scheda_utente, $valutazione, $id_film){
    global $errore;
    global $_POST;
    
    if($_POST["valutazione_stelle"] && $_SESSION["logged"]){
        try{
            if(empty($_POST["modifica_scheda"])){
                $scheda_utente->inserisci($_SESSION["user"]["Username"],$id_film,$_POST["visto"],$_POST["salvato"],0);
            }
            else{
                $scheda_utente->modifica($_SESSION["user"]["Username"],$id_film,$_POST["visto"],$_POST["salvato"],0);
            }

            if(empty($_POST["modifica_valutazione"])){
                $valutazione->inserisci($_SESSION["user"]["Username"],$id_film,"",0,date("Y-m-d H:i:s"),$_POST["valutazione_stelle"]);
            }
            else{
                $valutazione->modifica($_SESSION["user"]["Username"],$id_film,"",0,$_POST["valutazione_stelle"]);
            }
            header("location: mostra_film.php?titolo=".$_GET["titolo"]);
        }catch(Exception $e){
            $errore = $e;
        }
    }
}