<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../html/componenti/header.php');
require_once(__DIR__.'/../../php/database/categorizzazione.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../php/database/utente.php');
require_once(__DIR__.'/../../php/database/immagine.php');
require_once(__DIR__.'/../../php/database/disponibilita.php');
require_once(__DIR__.'/../../php/database/genereFilm.php');
require_once(__DIR__.'/../../php/database/film_search.php');
require_once(__DIR__.'/../../php/database/film_crud.php');
require_once(__DIR__.'/../../php/database/film.php');
require_once(__DIR__.'/../../php/database/valutazione.php');

$_POST = array_map('empty_to_null', $_GET);

$page = file_get_contents(__DIR__.'/ricerca.html');
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

$componente_lista_risultati = file_get_contents(__DIR__.'/../'."componenti/search_view_info.html");
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
    for ($i = 0; $i < count($_GET["livello"]); $i++) $categorizzazione_sql[] = " Livello = '" . $_GET["livello"][$i] . "'";
    $sto_cercando = true;
}

if (isset($_GET["eta"])) {
    for ($i = 0; $i < count($_GET["eta"]); $i++) $categorizzazione_sql[] = " Eta_pubblico = '" . $_GET["eta"][$i] . "'";
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
    if ($categorizzazione_sql) $filtro_categoria = $categorizizzazione->dynamic_find($categorizzazione_sql);
    else $filtro_categoria = $categorizizzazione->find_all();
    if ($genere_sql) $filtro_genere = $genere_film->dynamic_find_by_genere($genere_sql);
    else $filtro_genere = $genere_film->find_all();
    if ($disponibilita_sql) $filtro_disponibilita = $disponibilia->dynamic_find($disponibilita_sql);
    else $filtro_disponibilita = $disponibilia->find_all();

    if (!$filtro_categoria || !$filtro_genere || !$filtro_disponibilita) {
        print "Nessun film trovato";
    } else {
        foreach ($filtro_categoria as $value) {
            $film = new Film_search([]);
            $film->id = $value["Film"];
            $film->tema = $value["Tema"];
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
        for ($i = $page_first_result; $i < $page_first_result + $results_per_page; $i++) {
            if ($i < count($lista_film) && $lista_film[$i]) {
                $ho_elenti = true;
                $sezione_risultati .= $componente_lista_risultati;
                $sezione_risultati = str_replace("#TITOLO#", $lista_film[$i]->titolo, $sezione_risultati);
                $sezione_risultati = str_replace("#VOTO#", $lista_film[$i]->voto, $sezione_risultati);
                $id_immagine = $lista_film[$i]->locandina;
                $percorso_immagine =
                    $immagine->find($id_immagine) ? '../../img/'.$immagine->find($id_immagine)["Percorso"] : $_SESSION['$img_not_found_url'];
                $descrizione_immagine = $immagine->find($id_immagine)["Descrizione"] ?? "";
                $sezione_risultati = str_replace("#LOCANDINA#", $percorso_immagine, $sezione_risultati);
                $sezione_risultati = str_replace("#DESCRIZONE#", $descrizione_immagine, $sezione_risultati);
            }
        }

        $query_string = $_SERVER["QUERY_STRING"];
        $s = explode("&", $query_string);
        $number_of_page = ceil(count($lista_film) / $results_per_page);

        if($ho_elenti) {
            for ($i = 1; $i <= $number_of_page; $i++) {
                if ($query_string) {
                    $new_query_string = $query_string;
                    if (strpos($new_query_string, "page")) {
                        $new_query_string = str_replace($s[count($s) - 1], "", $new_query_string);
                    }
                    if ($numero_pagina == $i) {
                        $sezione_risultati .= " $i ";
                    } else {
                        $sezione_risultati .= '<a href = "ricerca.php?' . $new_query_string . '&page=' . $i . '">' . $i . ' </a>';
                    }
                } else {
                    $sezione_risultati .= '<a href = "ricerca.php?page=' . $i . '">' . $i . ' </a>';
                }
            }
        } else {
            print "non giocare con la barra come un bambino";
        }


        $page = str_replace("#RISULTATI#", $sezione_risultati, $page);
    }
}


echo $page;

