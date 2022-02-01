<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/film_crud.php');
require_once(__DIR__.'/../../php/database/immagine.php');
require_once(__DIR__.'/../../php/database/categorizzazione.php');
require_once(__DIR__.'/../../php/database/genereFilm.php');
require_once(__DIR__.'/../../php/database/disponibilita.php');
require_once(__DIR__.'/../../php/database/cast_film.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../html/componenti/header.php');

$page = file_get_contents(__DIR__.'/inserisci_film.html');
$esito_inserimento = "";

if(!empty($_GET["inserted"])) $esito_inserimento = "Film inserito con successo";
if(!empty($_POST) && !empty($_POST["lingua_titolo"] && empty($_GET["inserted"])))
{   
    $_POST = array_map('empty_to_null', $_POST);

    $titolo = validate_input($_POST["titolo"]);
    $lingua_titolo = validate_input($_POST["lingua_titolo"]);
    $trama = validate_input($_POST["trama"]);
    $anno = validate_input($_POST["anno"]);
    $durata = timeToMinutes(stringToTime(validate_input($_POST["durata"])));
    $descrizione_immagine = validate_input($_POST["descrizione_immagine"]);
    $immagine = $_FILES["immagine"]["name"];

    $eta_publico = validate_input($_POST["eta_pubblico"]);
    $livello = validate_input($_POST["livello"]);
    $mood = validate_input($_POST["mood"]);

    $genere = $_POST["genere"];

    $piattaforma = $_POST["piattaforma"];
    $cc = $_POST["cc"];
    $sdh = $_POST["sdh"];
    $ad = $_POST["ad"];

    $attori = validate_input($_POST["attori"]);
    $registi = validate_input($_POST["registi"]);

    $error_check = validateFields();
    
    if(!$error_check)
    {
        $film_crud = new Film_crud();

        try{
            $film_crud->inserisciFilm($titolo,$lingua_titolo,$anno,"IT",$durata,$trama,$attori,$registi);
            $id_film = $film_crud->getLastInsertedFilm()["ID"];

            if(!empty($immagine)){
                $_FILES["immagine"]["name"] = renameImage($_FILES["immagine"]["name"]);
                upload_image(__DIR__.'/../../img/film/',"immagine",$_SESSION['max_dim_img']);

                $percorso_immagine = "film/" . basename($_FILES["immagine"]["name"]);
                
                $immagine = new Immagine();
                $immagine->inserisci($descrizione_immagine,$percorso_immagine);

                $id_immagine = $immagine->getLastInsertedImmagine()["ID"];
                
                $film_crud->associa_immagine($id_film,$id_immagine);
            }

            $categorizzazione = new Categorizzazione();
            $categorizzazione->inserisci($id_film,$eta_publico,$livello,$mood, true);

            $genereFilm = new GenereFilm();
            foreach($genere as $nome_genere => $val_genere){
                $nome_genere = validate_input($nome_genere);
                $genereFilm->inserisci($id_film,$nome_genere);
            }

            $disponibilita = new Disponibilita();
            foreach($piattaforma as $nome_piattaforma => $val_piattaforma){
                $nome_piattaforma = validate_input($nome_piattaforma);
                $cc_piattaforma = filter_var(validate_input($cc[$nome_piattaforma]),FILTER_VALIDATE_BOOLEAN);
                $sdh_piattaforma = filter_var(validate_input($sdh[$nome_piattaforma]),FILTER_VALIDATE_BOOLEAN);
                $ad_piattaforma = filter_var(validate_input($ad[$nome_piattaforma]),FILTER_VALIDATE_BOOLEAN);
                var_dump($nome_piattaforma);
                $disponibilita->inserisci($id_film,$nome_piattaforma,$cc_piattaforma,$sdh_piattaforma,$ad_piattaforma);
            }

            header("Location: ../pagine_ricerca/mostra_film.php?titolo=".rawurlencode($titolo));
        }
        catch(Exception $e){
            if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.html");
            $esito_inserimento = $e;
        }
    }
    else{
        $page = str_replace("#TITOLO_INITIAL#", $titolo, $page);
        $page = str_replace("#SELECTED_lingua_titolo_$lingua_titolo#", "selected", $page);
        $page = str_replace("#SELECTED_lingua_titolo_it#", "", $page);
        $page = str_replace("#SELECTED_lingua_titolo_en#", "", $page);
        $page = str_replace("#TRAMA_INITIAL#", $trama, $page);
        $page = str_replace("#DECRIZIONE_IMMAGINE_INITIAL#", $descrizione_immagine, $page);
        $page = str_replace("#ANNO_INITIAL#", $anno, $page);
        $page = str_replace("#ATTORI_INITIAL#", $attori, $page);
        $page = str_replace("#REGISTI_INITIAL#", $registi, $page);
        $page = str_replace("#DURATA_INITIAL#", timeToString(minutesToTime($durata)), $page);
        $page = str_replace("#SELECTED$livello#", "selected", $page);
        $page = str_replace("#SELECTEDdemenziale#", "", $page);
        $page = str_replace("#SELECTEDbasso#", "", $page);
        $page = str_replace("#SELECTEDmedio#", "", $page);
        $page = str_replace("#SELECTEDalto#", "", $page);
        $page = str_replace("#SELECTED$mood#", "selected", $page);
        $page = str_replace("#SELECTEDsuspense#", "", $page);
        $page = str_replace("#SELECTEDprotesta#", "", $page);
        $page = str_replace("#SELECTEDcommovente#", "", $page);
        $page = str_replace("#SELECTEDdivertente#", "", $page);
        $page = str_replace("#SELECTEDottimista#", "", $page);
        $page = str_replace("#SELECTEDsorprendente#", "", $page);
        $page = str_replace("#SELECTED$eta_publico#", "selected", $page);
        $page = str_replace("#SELECTEDT#", "", $page);
        $page = str_replace("#SELECTEDVM14#", "", $page);
        $page = str_replace("#SELECTEDVM18#", "", $page);
        foreach($genere as $nome_genere => $val_genere){
            $page = str_replace("#CHECKED_genere_$nome_genere#", "checked", $page);
        }
        $page = str_replace("#CHECKED_genere_anime#", "", $page);
        $page = str_replace("#CHECKED_genere_animazione#", "", $page);
        $page = str_replace("#CHECKED_genere_avventura#", "", $page);
        $page = str_replace("#CHECKED_genere_azione#", "", $page);
        $page = str_replace("#CHECKED_genere_biografico#", "", $page);
        $page = str_replace("#CHECKED_genere_commedia#", "", $page);
        $page = str_replace("#CHECKED_genere_documentario#", "", $page);
        $page = str_replace("#CHECKED_genere_drammatico#", "", $page);
        foreach($piattaforma as $nome_piattaforma => $val_piattaforma){
            $cc_piattaforma = filter_var($cc[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
            $sdh_piattaforma = filter_var($sdh[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
            $ad_piattaforma = filter_var($ad[$nome_piattaforma],FILTER_VALIDATE_BOOLEAN);
            
            $page = str_replace("#CHECKED$nome_piattaforma#", "checked", $page);
            $page = $cc_piattaforma ? str_replace("#CHECKED_".$nome_piattaforma."_cc#", "checked", $page) : str_replace("#CHECKED_".$nome_piattaforma."_cc#", "", $page);
            $page = $sdh_piattaforma ? str_replace("#CHECKED_".$nome_piattaforma."_sdh#", "checked", $page) : str_replace("#CHECKED_".$nome_piattaforma."sdh#", "", $page);
            $page = $ad_piattaforma ? str_replace("#CHECKED_".$nome_piattaforma."_ad#", "checked", $page) : str_replace("#CHECKED_".$nome_piattaforma."_ad#", "", $page);
        }
    }
}else{
    $page = str_replace("#TITOLO_INITIAL#", "", $page);
    $page = str_replace("#SELECTED_lingua_titolo_it#", "", $page);
    $page = str_replace("#SELECTED_lingua_titolo_en#", "", $page);
    $page = str_replace("#TRAMA_INITIAL#", "", $page);
    $page = str_replace("#DECRIZIONE_IMMAGINE_INITIAL#", "", $page);
    $page = str_replace("#ANNO_INITIAL#", "", $page);
    $page = str_replace("#ATTORI_INITIAL#", "", $page);
    $page = str_replace("#REGISTI_INITIAL#", "", $page);
    $page = str_replace("#DURATA_INITIAL#", "", $page);
    $page = str_replace("#SELECTEDdemenziale#", "", $page);
    $page = str_replace("#SELECTEDbasso#", "", $page);
    $page = str_replace("#SELECTEDmedio#", "", $page);
    $page = str_replace("#SELECTEDalto#", "", $page);
    $page = str_replace("#SELECTEDsuspense#", "", $page);
    $page = str_replace("#SELECTEDprotesta#", "", $page);
    $page = str_replace("#SELECTEDcommovente#", "", $page);
    $page = str_replace("#SELECTEDdivertente#", "", $page);
    $page = str_replace("#SELECTEDottimista#", "", $page);
    $page = str_replace("#SELECTEDsorprendente#", "", $page);
    $page = str_replace("#SELECTEDT#", "", $page);
    $page = str_replace("#SELECTEDVM14#", "", $page);
    $page = str_replace("#SELECTEDVM18#", "", $page);
    $page = str_replace("#CHECKED_genere_anime#", "", $page);
    $page = str_replace("#CHECKED_genere_animazione#", "", $page);
    $page = str_replace("#CHECKED_genere_avventura#", "", $page);
    $page = str_replace("#CHECKED_genere_azione#", "", $page);
    $page = str_replace("#CHECKED_genere_biografico#", "", $page);
    $page = str_replace("#CHECKED_genere_commedia#", "", $page);
    $page = str_replace("#CHECKED_genere_documentario#", "", $page);
    $page = str_replace("#CHECKED_genere_drammatico#", "", $page);
    $page = str_replace("#CHECKEDnetflix#", "", $page);
    $page = str_replace("#CHECKED_netflix_cc#", "", $page);
    $page = str_replace("#CHECKED_netflix_sdh#", "", $page);
    $page = str_replace("#CHECKED_netflix_ad#", "", $page);
    $page = str_replace("#CHECKEDprime video#", "", $page);
    $page = str_replace("#CHECKED_prime video_cc#", "", $page);
    $page = str_replace("#CHECKED_prime video_sdh#", "", $page);
    $page = str_replace("#CHECKED_prime video_ad#", "", $page);
    $page = str_replace("#CHECKEDdisney+#", "", $page);
    $page = str_replace("#CHECKED_disney+_cc#", "", $page);
    $page = str_replace("#CHECKED_disney+_sdh#", "", $page);
    $page = str_replace("#CHECKED_disney+_ad#", "", $page);
    $page = str_replace("#CHECKEDdiscovery+#", "", $page);
    $page = str_replace("#CHECKED_discovery+_cc#", "", $page);
    $page = str_replace("#CHECKED_discovery+_sdh#", "", $page);
    $page = str_replace("#CHECKED_discovery+_ad#", "", $page);

    $page = str_replace("#ERRORE_TITOLO#", "", $page);
    $page = str_replace("#ERRORE_TRAMA#", "", $page);
    $page = str_replace("#ERRORE_ANNO#", "", $page);
    $page = str_replace("#ERRORE_ATTORI#", "", $page);
    $page = str_replace("#ERRORE_REGISTI#", "", $page);
	$page = str_replace("#ERRORE_IMMAGINE#", "", $page);
}

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
$page = str_replace("#ESITO_INSERIMENTO#", $esito_inserimento, $page);

echo $page;

function validateFields(){
    global $page;
    global $titolo;
    global $trama;
    global $anno;
    global $attori;
    global $registi;

    $film_crud = new Film_crud();
    $error = false;

    if(empty($film_crud->find($titolo))) $page = str_replace("#ERRORE_TITOLO#", "", $page);
    else { $page = str_replace("#ERRORE_TITOLO#", " error", $page); $error = true; }

    if(strlen($trama) <= 500) $page = str_replace("#ERRORE_TRAMA#", "", $page);
    else{ $page = str_replace("#ERRORE_TRAMA#", " error", $page); $error = true; }

    if($anno >= 1900 && $anno <= 2023) $page = str_replace("#ERRORE_ANNO#", "", $page);
    else{str_replace("#ERRORE_ANNO#", " error", $page); $error = true; }

	if($error) {
		$page = str_replace("#ERRORE_IMMAGINE#", " error", $page);
		$page = str_replace("#ERRORE_IMMAGINE_DESC#", "Re-inserire immagine", $page);
	}
	else $page = str_replace("#ERRORE_IMMAGINE#", "", $page);
   /* 	if(preg_match_all("/[^,\s][^\,]*[^,\s]*", $attori)) $page = str_replace("#ERRORE_ATTORI#", "", $page);
   	else{$page = str_replace("#ERRORE_ATTORI#", " error", $page); $error = true;}

   	if(preg_match_all("/[^,\s][^\,]*[^,\s]*", $registi)) $page = str_replace("#ERRORE_REGISTI#", "", $page);
	else{$page = str_replace("#ERRORE_REGISTI#", " error", $page); $error = true;} 
	*/

    return $error;
}
?>