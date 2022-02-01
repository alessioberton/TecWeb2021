<?php
require_once(__DIR__ . '/../../php/logic/error_reporting.php');
require_once(__DIR__ . '/../../php/config.php');
require_once(__DIR__ . '/../../html/componenti/header.php');
require_once(__DIR__ . '/../../php/database/categorizzazione.php');
require_once(__DIR__ . '/../../php/logic/functions.php');
require_once(__DIR__ . '/../../php/database/utente.php');
require_once(__DIR__ . '/../../php/database/immagine.php');
require_once(__DIR__ . '/../../php/database/disponibilita.php');
require_once(__DIR__ . '/../../php/database/genereFilm.php');
require_once(__DIR__ . '/../../php/database/film_search.php');
require_once(__DIR__ . '/../../php/database/film_crud.php');
require_once(__DIR__ . '/../../php/database/film.php');
require_once(__DIR__ . '/../../php/database/valutazione.php');

$_GET = array_map('empty_to_null', $_GET);

$page = file_get_contents(__DIR__ . '/ricerca.html');
$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);

$categorizzazione_sql = [];
$categorizizzazione = new Categorizzazione();
$disponibilita_sql = [];
$disponibilia = new Disponibilita();
$genere_sql = [];
$genere_film = new GenereFilm();
$film_crud = new Film_crud();
$valutazione_model = new Valutazione();
$immagine = new Immagine();

$lista_film = [];

$filtro_categoria = [];
$filtro_genere = [];
$filtro_disponibilita = [];

$sto_cercando = false;

$componente_lista_risultati = file_get_contents(__DIR__ . '/../' . "componenti/search_view_info.html");
$sezione_risultati = "";


if (isset($_GET["piattaforma"])) {
    for ($i = 0; $i < count($_GET["piattaforma"]); $i++) $disponibilita_sql[] = " Piattaforma = '" . $_GET["piattaforma"][$i] . "'";
    $sto_cercando = true;
}

if (isset($_GET["opzione"])) {
    for ($i = 0; $i < count($_GET["opzione"]); $i++) {
        if ($_GET["opzione"][$i] == "CC") $disponibilita_sql[] = " CC = true";
        if ($_GET["opzione"][$i] == "SDH") $disponibilita_sql[] = " SDH = true";
        if ($_GET["opzione"][$i] == "AD") $disponibilita_sql[] = " AD = true";
    }
    $sto_cercando = true;
}

if (isset($_GET["genere"])) {
    for ($i = 0; $i < count($_GET["genere"]); $i++) $genere_sql[] = " Nome_genere = '" . $_GET["genere"][$i] . "'";
    $sto_cercando = true;
}

if (isset($_GET["livello"])) {
    if ($_GET["livello"] != "tutti") {
        $categorizzazione_sql[] = " Livello = '" . $_GET["livello"] . "'";
    }
    $sto_cercando = true;
}

if (isset($_GET["eta_pubblico"])) {
    if ($_GET["eta_pubblico"] != "tutti") {
        $categorizzazione_sql[] = " Eta_pubblico = '" . $_GET["eta_pubblico"] . "'";
    }
    $sto_cercando = true;
}

if (isset($_GET["mood"])) {
    if ($_GET["mood"] != "tutti") {
        $categorizzazione_sql[] = " Mood = '" . $_GET["mood"] . "'";
    }
    $sto_cercando = true;
}

if (!isset ($_GET['page'])) {
    $numero_pagina = 1;
} else {
    $numero_pagina = $_GET['page'];
}

$results_per_page = 2;
$page_first_result = ($numero_pagina - 1) * $results_per_page;

if ($sto_cercando) {
    $page = str_replace("#INITIAL_OPEN#", "", $page);
    $page = str_replace("#INITIAL_ARIA_EXP#", "false", $page);

    if ($categorizzazione_sql) $filtro_categoria = $categorizizzazione->dynamic_find($categorizzazione_sql);
    else $filtro_categoria = $categorizizzazione->find_all();
    if ($genere_sql) $filtro_genere = $genere_film->dynamic_find_by_genere($genere_sql);
    else $filtro_genere = $genere_film->find_all();
    if ($disponibilita_sql) $filtro_disponibilita = $disponibilia->dynamic_find($disponibilita_sql);
    else $filtro_disponibilita = $disponibilia->find_all();

    if (!$filtro_categoria || !$filtro_genere || !$filtro_disponibilita) {
        $page = str_replace("#RISULTATI#", "Nessun Film Trovato", $page);
    } else {
        foreach ($filtro_categoria as $value) {
            $film = new Film_search([]);
            $film->id = $value["Film"];
            $film->eta = $value["Eta_pubblico"];
            $film->livello = $value["Livello"];
            $film->mood = $value["Mood"];
            $film->riconoscimenti = $value["Riconoscimenti"];
            $lista_film[] = $film;
        }

        for ($i = 0; $i < count($lista_film); $i++) {
            for ($j = 0; $j < count($filtro_genere); $j++) {
                if (in_array($lista_film[$i]->id, $filtro_genere[$j])) {
                    $lista_film[$i]->genere [] = $filtro_genere[$j]["Nome_genere"];
                    break;
                } else if (count($filtro_genere) <= $j + 1) {
                    $lista_film[$i] = [];
                }
            }
        }

        $lista_film = array_values(array_filter($lista_film));

        for ($i = 0; $i < count($lista_film); $i++) {
            for ($j = 0; $j < count($filtro_disponibilita); $j++) {
                if ($lista_film[$i]->id == $filtro_disponibilita[$j]["Film"]) {
                    $lista_film[$i]->piattaforma = $filtro_disponibilita[$j]["Piattaforma"];
                    $lista_film[$i]->cc = $filtro_disponibilita[$j]["CC"];
                    $lista_film[$i]->SDH = $filtro_disponibilita[$j]["SDH"];
                    $lista_film[$i]->AD = $filtro_disponibilita[$j]["AD"];
                    $lista_film[$i]->costo_aggiuntivo = $filtro_disponibilita[$j]["CostoAggiuntivo"];
                    $lista_film[$i]->giorno_entrata = $filtro_disponibilita[$j]["Giorno_entrata"];
                    $lista_film[$i]->giorno_uscita = $filtro_disponibilita[$j]["Giorno_uscita"];
                    break;
                } else if (count($filtro_disponibilita) <= $j + 1) {
                    $lista_film[$i] = [];
                }

            }
        }

        $lista_film = array_values(array_filter($lista_film));

        for ($i = 0; $i < count($lista_film); $i++) {
            $film_obj = new Film($film_crud->findById($lista_film[$i]->id));
            $lista_film[$i]->titolo = $film_obj->titolo;
            $lista_film[$i]->trama = $film_obj->trama;
            $lista_film[$i]->voto = $valutazione_model->find_avg_by_film_id($lista_film[$i]->id)["VALUTAZIONE_MEDIA"];
            $lista_film[$i]->lingua_titolo = $film_obj->lingua_titolo;
            $lista_film[$i]->durata = $film_obj->durata;
            $lista_film[$i]->paese = $film_obj->paese;
            $lista_film[$i]->anno = $film_obj->anno;
            $lista_film[$i]->locandina = $film_obj->locandina;
        }

        $ho_elenti = false;
        for ($i = 0; $i < count($lista_film); $i++) {
            if ($lista_film[$i]) {
                $ho_elenti = true;
                $sezione_risultati .= $componente_lista_risultati;
                $sezione_risultati = str_replace("#TITOLO#", $lista_film[$i]->titolo, $sezione_risultati);
                $sezione_risultati = str_replace("#TITOLOURL#", rawurlencode($lista_film[$i]->titolo), $sezione_risultati);
				$sezione_risultati = str_replace("#ANNO#", $lista_film[$i]->anno, $sezione_risultati);
				if(!isset($lista_film[$i]->voto)) $sezione_risultati = str_replace("#VOTO#", "0", $sezione_risultati);
                else $sezione_risultati = str_replace("#VOTO#", $lista_film[$i]->voto, $sezione_risultati);
                $id_immagine = $lista_film[$i]->locandina;
                $percorso_immagine =
                    $immagine->find($id_immagine) ? '../../img/' . $immagine->find($id_immagine)["Percorso"] : $_SESSION['$img_not_found_url'];
                $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"] ?? "";
                $sezione_risultati = str_replace("#LOCANDINA#", $percorso_immagine, $sezione_risultati);
                $sezione_risultati = str_replace("#DESCRIZONE#", $descrizione_immagine, $sezione_risultati);
            }
        }
        $page = str_replace("#RISULTATI#", $sezione_risultati, $page);
    }
} else {
    $page = str_replace("#INITIAL_OPEN#", " open", $page);
    $page = str_replace("#INITIAL_ARIA_EXP#", "true", $page);
    $page = str_replace("#RISULTATI#", "", $page);
}

populateFilters();

echo $page;


function populateFilters(){
    global $page;

    foreach($_GET as $key => $value){
        if($key == "eta_pubblico" || $key == "livello" || $key == "mood"){
            $page = str_replace("#SELECTED_".$key."_".$value."#", "selected", $page);
        }elseif($key == "genere" || $key =="piattaforma" || $key == "opzione"){
            foreach($value as $item){
                $page = str_replace("#CHECKED_".$key."_".$item."#", "checked", $page);
            }
        }
    }

    $page = str_replace("#SELECTED_eta_pubblico_tutti#", "", $page);
    $page = str_replace("#SELECTED_eta_pubblico_T#", "", $page);
    $page = str_replace("#SELECTED_eta_pubblico_VM14#", "", $page);
    $page = str_replace("#SELECTED_eta_pubblico_VM18#", "", $page);
    $page = str_replace("#SELECTED_livello_tutti#", "", $page);
    $page = str_replace("#SELECTED_livello_demenziale#", "", $page);
    $page = str_replace("#SELECTED_livello_basso#", "", $page);
    $page = str_replace("#SELECTED_livello_medio#", "", $page);
    $page = str_replace("#SELECTED_livello_alto#", "", $page);
    $page = str_replace("#SELECTED_mood_tutti#", "", $page);
    $page = str_replace("#SELECTED_mood_suspence#", "", $page);
    $page = str_replace("#SELECTED_mood_protesta#", "", $page);
    $page = str_replace("#SELECTED_mood_commovente#", "", $page);
    $page = str_replace("#SELECTED_mood_comico#", "", $page);
    $page = str_replace("#SELECTED_mood_sentimentale#", "", $page);
    $page = str_replace("#SELECTED_mood_sorprendente#", "", $page);
    $page = str_replace("#CHECKED_genere_Animazione#", "", $page);
    $page = str_replace("#CHECKED_genere_Avventura#", "", $page);
    $page = str_replace("#CHECKED_genere_Azione#", "", $page);
    $page = str_replace("#CHECKED_genere_Commedia#", "", $page);
    $page = str_replace("#CHECKED_genere_Documentario#", "", $page);
    $page = str_replace("#CHECKED_genere_Drammatico#", "", $page);
    $page = str_replace("#CHECKED_genere_Fantascienza#", "", $page);
    $page = str_replace("#CHECKED_genere_Horror#", "", $page);
    $page = str_replace("#CHECKED_piattaforma_Netflix#", "", $page);
    $page = str_replace("#CHECKED_piattaforma_Amazon Prime#", "", $page);
    $page = str_replace("#CHECKED_piattaforma_Disney+#", "", $page);
    $page = str_replace("#CHECKED_piattaforma_TimVision#", "", $page);
    $page = str_replace("#CHECKED_opzione_CC#", "", $page);
    $page = str_replace("#CHECKED_opzione_SDH#", "", $page);
    $page = str_replace("#CHECKED_opzione_AD#", "", $page);

}