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
}

getAbs_path();

$page = file_get_contents("mostra_film.html");
$piattaforma_section = "";
$genere_section = "";
$attore_section = "";
$regista_section = "";
$piattaforma_component = file_get_contents(__DIR__.'/../../html/componenti/view_piattaforme.html');
$genere_component = "<li>#NOME_GENERE#</li>";
$attore_component = "<li>#NOME_ATTORE#</li>";
$regista_component = "<li>#NOME_REGISTA#</li>";
$pulsanti_component = file_get_contents(__DIR__.'/../../html/componenti/view_film_vote_buttons.html');
$admin_component = file_get_contents(__DIR__.'/../../html/componenti/view_film_admin_buttons.html');
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
        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
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

                    $lingua = $value['Lingua_titolo'];
                    if ($lingua != 'IT')
                        $page = str_replace("#TITOLO#", "<span lang='".$lingua."'>".$value['Titolo']. "</span>", $page);
                    else
                     $page = str_replace("#TITOLO#", $value['Titolo'], $page);

					$page = str_replace("#TRAMA#", $value['Trama'], $page);
                    $page = str_replace("#LINGUA_TITOLO#", $lingua, $page);
                    $page = str_replace("#ANNO#", $value['Anno'], $page);
                    $page = str_replace("#PAESE#", siglaToPaese($value['Paese']), $page);
                    $page = str_replace("#DURATA#", timeToString(minutesToTime($value['Durata'])), $page);

                    try{
                        $percorso_immagine = !empty($id_immagine) ? '../../img/'.$immagine->find($id_immagine)["Percorso"] : '../../img/'.$immagine->getNotFoundImage("film")["Percorso"];
                        $descrizione_immagine = !empty($id_immagine) ? $immagine->find($id_immagine)["Descrizione"] : $immagine->getNotFoundImage("film")["Descrizione"];
                        $page = str_replace("#URL_IMG_FILM#", $percorso_immagine, $page);
                        $page = str_replace("#ALT_IMG_FILM#", $descrizione_immagine, $page);
                        $page = str_replace("#ERROR_MESSAGE#", $errore, $page);
                        
						if(empty($value["Piattaforme"]))  $page = str_replace("#PIATTAFORME#","<p>Nessuna piattaforma</p>", $page);
                        foreach($value["Piattaforme"] as $piattaforma)
                        {
                            $piattaforma_section .= $piattaforma_component;
                            $piattaforma_section = str_replace("#NOME_PIATTAFORMA#", $piattaforma["Nome"], $piattaforma_section);
							$piattaforma_section = str_replace("#SDH#", $piattaforma["SDH"] ? "<li>SDH</li>" : "", $piattaforma_section);
                            $piattaforma_section = str_replace("#CC#", $piattaforma["CC"] ? "<li>CC</li>" : "", $piattaforma_section);
                            $piattaforma_section = str_replace("#AD#", $piattaforma["AD"] ? "<li>AD</li>" : "", $piattaforma_section);
                        }
                        $page = str_replace("#PIATTAFORME#", $piattaforma_section, $page);

                        $generi_data = $film_crud->getGeneri($id_film);
                        foreach($generi_data as $generi_item){
                            $genere_section .= $genere_component;
                            $genere_section = str_replace("#NOME_GENERE#", $generi_item["Nome_genere"], $genere_section);
                        }
                        $page = str_replace("#GENERI#", $genere_section, $page);

                        //$attori_data = $film_crud->getAttori($id_film);
                        $attori_data = explode(',',$value["Attori"]);
                        foreach($attori_data as $attori_item){
                            $attore_section .= $attore_component;
                            $attore_section = str_replace("#NOME_ATTORE#", $attori_item, $attore_section);
                        }
                        $page = str_replace("#ATTORI#", $attore_section, $page);

                        $registi_data = explode(',',$value["Registi"]);
                        foreach($registi_data as $registi_item){
                            $regista_section .= $regista_component;
                            $regista_section = str_replace("#NOME_REGISTA#", $registi_item, $regista_section);
                        }
                        $page = str_replace("#REGISTI#", $regista_section, $page);

                        $page = str_replace("#STELLE#", number_format($film_crud->mediaStelle($id_film),1), $page);

                        $categorizzazione = $film_crud->getCategorizzazione($id_film);
                        $page = str_replace("#LIVELLO_IMPEGNO#", $categorizzazione["Livello"], $page);
                        $page = str_replace("#ETA#", $categorizzazione["Eta_pubblico"], $page);
                        $page = str_replace("#MOOD#", $categorizzazione["Mood"], $page);
                        
                        if($_SESSION["logged"]){
							

                            $page = str_replace("#VALUTAZIONE#", $pulsanti_component, $page);
                            $page = str_replace("#TITOLO_FILM#", rawurlencode($_GET["titolo"]), $page);

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
							 $page = !empty($valutazione_item) ? str_replace("#SELECTED".$valutazione_item["Stelle"]."#", "selected", $page) : $page;

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
                        if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
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
                $valutazione->inserisci($_SESSION["user"]["Username"],$id_film,"",date("Y-m-d H:i:s"),validate_input($_POST["valutazione_stelle"]));
            }
            else{
                $valutazione->modifica($_SESSION["user"]["Username"],$id_film,"",validate_input($_POST["valutazione_stelle"]));
            }
            header("location: mostra_film.php?titolo=".rawurlencode($_GET["titolo"]));
        }catch(Exception $e){
            if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
            $errore = $e;
        }
    }
}
?>