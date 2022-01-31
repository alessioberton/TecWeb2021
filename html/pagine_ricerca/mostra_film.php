<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void
{
    require_once(__DIR__.'/../../php/logic/functions.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
    require_once(__DIR__.'/../../php/database/scheda_utente.php');
    require_once(__DIR__.'/../../php/database/valutazione.php');
    require_once(__DIR__.'/../../php/database/film_crud.php');
    require_once(__DIR__.'/../../html/componenti/header.php');

    //$_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents("mostra_film.html");
$piattaforma_section = "";
$genere_section = "";
$attore_section = "";
$regista_section = "";
$piattaforma_component = file_get_contents(__DIR__.'/../../html/componenti/view_piattaforme.html');
$genere_component = file_get_contents(__DIR__.'/../../html/componenti/view_generi.html');
$attore_component = file_get_contents(__DIR__.'/../../html/componenti/view_attori.html');
$regista_component = file_get_contents(__DIR__.'/../../html/componenti/view_registi.html');
$pulsanti_component = file_get_contents(__DIR__.'/../../html/componenti/pulsanti_film.html');
$admin_component = file_get_contents(__DIR__.'/../../html/componenti/view_admin.html');
$errore = '';
$percorso_film = '';

$immagine = new Immagine();
$scheda_utente = new SchedaUtente();
$valutazione = new Valutazione();
$film_crud = new Film_crud();

if(isUserAdmin() && isset($_POST["elimina"])){
    try{
        $film_crud->elimina($_POST["id_film"]);
        header("Location: ricerca.php");
    }catch(Exception $e){
        $errore = $e;
    }

}

$lista_film = $film_crud->find_all();

if (isset($_GET["titolo"])) {
        if (isset($lista_film)) {
            foreach ($lista_film as $value) {
                if (strtolower($_GET["titolo"]) == strtolower($value["Titolo"])) {
                    $id_film = $value["ID"];
                    $id_immagine = $value["Locandina"];
                    $page = str_replace("#TITOLO_PAGINA#", $value['Titolo'], $page);
                    $page = str_replace("#TITOLO#", $value['Titolo'], $page);
					$page = str_replace("#TRAMA#", $value['Trama'], $page);
                    $page = str_replace("#LINGUA_TITOLO#", $value['Lingua_titolo'], $page);
                    $page = str_replace("#ANNO#", $value['Anno'], $page);
                    $page = str_replace("#PAESE#", siglaToPaese($value['Paese']), $page);
                    $page = str_replace("#DURATA#", timeToString(secondsToTime($value['Durata'])), $page);

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
							$piattaforma_section = str_replace("#SDH#", $piattaforma["SDH"] ? "SDH" : "", $piattaforma_section);
                            $piattaforma_section = str_replace("#CC#", $piattaforma["CC"] ? "CC" : "", $piattaforma_section);
                            $piattaforma_section = str_replace("#AD#", $piattaforma["AD"] ? "AD" : "", $piattaforma_section);
                        }
                        $page = str_replace("#PIATTAFORME#", $piattaforma_section, $page);

                        $generi_data = $film_crud->getGeneri($id_film);
                        foreach($generi_data as $generi_item){
                            $genere_section .= $genere_component;
                            $genere_section = str_replace("#NOME_GENERE#", $generi_item["Nome_genere"], $genere_section);
                        }
                        $page = str_replace("#GENERI#", $genere_section, $page);

                        $attori_data = $film_crud->getAttori($id_film);
                        foreach($attori_data as $attori_item){
                            $attore_section .= $attore_component;
                            $attore_section = str_replace("#NOME_ATTORE#", $attori_item["Nome"]." ".$attori_item["Cognome"], $attore_section);
                        }
                        $page = str_replace("#ATTORI#", $attore_section, $page);

                        $registi_data = $film_crud->getRegisti($id_film);
                        foreach($registi_data as $registi_item){
                            $regista_section .= $regista_component;
                            $regista_section = str_replace("#NOME_REGISTA#", $registi_item["Nome"]." ".$registi_item["Cognome"], $regista_section);
                        }
                        $page = str_replace("#REGISTI#", $regista_section, $page);

                        $page = str_replace("#STELLE#", number_format($film_crud->mediaStelle($id_film),2)." / 5", $page);

                        $categorizzazione = $film_crud->getCategorizzazione($id_film);
                        $page = str_replace("#LIVELLO_IMPEGNO#", $categorizzazione["Livello"], $page);
                        $page = str_replace("#ETA#", $categorizzazione["Eta_pubblico"], $page);
                        $page = str_replace("#MOOD#", $categorizzazione["Mood"], $page);
                        
                        if($_SESSION["logged"]){
							

                            $page = str_replace("#VALUTAZIONE#", $pulsanti_component, $page);
                            $page = str_replace("#TITOLO_FILM#", $_GET["titolo"], $page);

                            $scheda_result = $scheda_utente->findByFilmUser($id_film,$_SESSION["user"]["Username"]);
							$valutazione_item = $valutazione->find($_SESSION["user"]["Username"],$id_film);

							if(isset($scheda_result[0]) && $scheda_result[0]["Visto"]){               
                                $page = str_replace("#VISTO#", "checked", $page);
                            } else{
                                $page = str_replace("#VISTO#", "", $page);
                            }
							if(isset($scheda_result[0]) && $scheda_result[0]["Salvato"]){               
                                $page = str_replace("#SALVATO#", "checked", $page);
                            } else{
                                $page = str_replace("#SALVATO#", "", $page);
                            }
							 $page = str_replace("#SELECTED".$valutazione_item["Stelle"]."#", "selected", $page);

							if(isset($_POST)) inserisciModificaValutazione($scheda_utente, $valutazione, $id_film, $scheda_result, $valutazione_item);
                        }else{
                            $page = str_replace("#VALUTAZIONE#", "", $page);
                        }

                        for($i = 1; $i <= 5; $i++)
                            $page = str_replace("#SELECTED".$i."#", "", $page);
                        
                        if(isUserAdmin()){
                            $page = str_replace("#AREA_ADMIN#", $admin_component, $page);
                        }else{
                            $page = str_replace("#AREA_ADMIN#", "", $page);
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

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);

echo $page;

function inserisciModificaValutazione($scheda_utente, $valutazione, $id_film, $scheda_result, $valutazione_item){
    global $errore;

    if(isset($_POST["valutazione_stelle"]) && $_SESSION["logged"]){
		$visto = $_POST["visto"] ? 1 : 0;
		$salvato = $_POST["salvato"] ? 1 : 0;
		try{
            if(!$scheda_result){
                $scheda_utente->inserisci($_SESSION["user"]["Username"], $id_film, $visto, $salvato, 0);
            }
            else{
                $scheda_utente->modifica($_SESSION["user"]["Username"],$id_film,$visto,$salvato,0);
            }

            if(!$valutazione_item){
                $valutazione->inserisci($_SESSION["user"]["Username"],$id_film,"",0,date("Y-m-d H:i:s"),validate_input($_POST["valutazione_stelle"]));
            }
            else{
                $valutazione->modifica($_SESSION["user"]["Username"],$id_film,"",0,validate_input($_POST["valutazione_stelle"]));
            }
            header("location: mostra_film.php?titolo=".$_GET["titolo"]);
        }catch(Exception $e){
            $errore = $e;
        }
    }
}