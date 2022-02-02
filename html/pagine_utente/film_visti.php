<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../php/database/utente.php');
require_once(__DIR__.'/../../php/database/immagine.php');
require_once(__DIR__.'/../../php/database/scheda_utente.php');
require_once(__DIR__.'/../../php/database/film.php');
require_once(__DIR__.'/../../php/database/film_crud.php');
require_once(__DIR__.'/../../php/database/valutazione.php');
require_once(__DIR__.'/../../html/componenti/header.php');
if ($_SESSION['logged'] == false) {
	header('location: ../pagine_altre/accesso_negato.php');
	exit();
}
$_POST = array_map('empty_to_null', $_POST);


$page = file_get_contents(__DIR__.'/film_visti.html');
$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);

$array_visto = array();
$list = '';

$film_crud = new Film_crud();
$immagine = new Immagine();
$valutazione_model = new Valutazione();
$scheda_utente = new SchedaUtente();
$link_valutazione = "";
$titolo_selezionato = "";
$anno_selezionato = "";
$voto_selezionato = "";
$lista_film = [];

if (strpos($_SESSION['pagina_corrente'], "profilo.php") !== false){
    $breadcrumb = "<div class='breadcrumb'><a href='../pagine_principali/homepage.php'><span lang='en'>Home</span></a> &gt; <a href='../pagine_utente/profilo.php'>Profilo </a> &gt; Film Visti</div>";
} else {
    $breadcrumb = "<div class='breadcrumb'><a href='../pagine_principali/homepage.php'><span lang='en'>Home</span></a> &gt; Film Visti</div>";
}
$page = str_replace("#BREADCRUMB#", $breadcrumb, $page);

try {
    $query_array_scheda_utente = $scheda_utente->findByUser($_SESSION['user']['Username']);
    foreach($query_array_scheda_utente as $value) {
        if ($value["Visto"] == true) {
            $array_visto[] = $value;
        }
    }
    foreach ($array_visto as $value) {
        $film = new Film($film_crud->findById($value["ID_Film"]));
        $film->voto = $valutazione_model->find_avg_by_film_id($value["ID_Film"])["VALUTAZIONE_MEDIA"];
        $lista_film[] = $film;
    }
    $page = str_replace("#NUMERO_FILM_VISTI#", count($array_visto), $page);
} catch (Exception $e) {
    if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.php");
    $pagina_errore = file_get_contents(__DIR__."/../../html/pagine_altre/errore.html");
    $pagina_errore = str_replace("#ERROR_MESSAGE#", $e, $pagina_errore);
    echo $pagina_errore;
}


if (isset($_POST["option_film_visti"])) {
    if ($_POST["option_film_visti"] == "titolo") {
        sortArrayByKey($lista_film, "titolo", true);
        $titolo_selezionato = "selected";
    }
    else if ($_POST["option_film_visti"] == "anno") {
        sortArrayByKey($lista_film, "anno", false, false);
        $anno_selezionato = "selected";
    }
    else if ($_POST["option_film_visti"] == "voto") {
        sortArrayByKey($lista_film, "voto", false, false);
        $voto_selezionato = "selected";
    }
}

if (!$lista_film){
    $percorso_mancanza_film_img = '../../img/varie/coming_soon.jpg';
    $page = str_replace("#LIST#", "", $page);
    $img_non_trovata = "<img src=$percorso_mancanza_film_img alt='Indicazione mancanza risultati' /> <p>Gurda un film amico..</p>";
    $page = str_replace("#MSG_INFO#", $img_non_trovata, $page);
}else {
    $page = str_replace("#MSG_INFO#", "", $page);
    foreach ($lista_film as $value) {
        $view_film = file_get_contents(__DIR__ . '/../componenti/view_film_user.html');
        $percorso_film = $immagine->find($value->locandina)["Percorso"];
        $percorso_film = "../../img/" . $percorso_film;
        $view_film = str_replace("#LOCANDINA#", $percorso_film, $view_film);
        $view_film = str_replace("#TITOLO#", $value->titolo, $view_film);
        $view_film = str_replace("#TITOLOURL#", rawurlencode($value->titolo), $view_film);
        $view_film = str_replace("#ANNO#", $value->anno, $view_film);
        $valutazione_obj = $valutazione_model->findByUser($_SESSION['user']['Username']);
        $view_film = str_replace("#VOTO#", $value->voto, $view_film);
        $view_film = str_replace("#VALUTAZIONE#", $link_valutazione, $view_film);
        if ($value->lingua_titolo != 'IT')
            $view_film = str_replace("#TITOLO_FILM#", "<span lang='" . $value->lingua_titolo . "'>" . $value->titolo . "</span", $view_film);
        else
            $view_film = str_replace("#TITOLO_FILM#", $value->titolo, $view_film);
        $list = $list . $view_film;
    }
    $page = str_replace("#LIST#", $list, $page);
}




$filtro_selezione = "<label for='option_film_visti'>Ordina per:</label>
    <select id='option_film_visti' name='option_film_visti'>
        <option value='titolo' $titolo_selezionato>Titolo </option>
        <option value='anno' $anno_selezionato>Anno</option>
        <option value='voto' $voto_selezionato>Voto</option>
    </select> ";

$page = str_replace("<filterFilm />", $filtro_selezione, $page);

echo $page;