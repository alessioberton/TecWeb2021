<?php
include_once '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    include_once($_SESSION['$abs_path_php']."database/utente.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    include_once($_SESSION['$abs_path_php']."database/scheda_utente.php");
    include_once($_SESSION['$abs_path_php']."database/film.php");
    include_once($_SESSION['$abs_path_php']."database/film_crud.php");
    include_once($_SESSION['$abs_path_php']."database/valutazione.php");
	include_once($_SESSION['$abs_path_html']."componenti/commonPageElements.php");
    if ($_SESSION['logged'] == false) {
        header('location: ../pagine_altre/accesso_negato.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$page = file_get_contents("film_visti.html");
$array_visto = $_SESSION["array_visto"];

$list='<dl >';

$page = str_replace("#NUMERO_FILM_VISTI#", count($array_visto), $page);

$film_crud = new Film_crud();
$immagine = new Immagine();
$valutazione_model = new Valutazione();


try {
    foreach($_SESSION["array_visto"] as $value) {
        $view_film = file_get_contents('../componenti/view_film.html');
        $film = new Film($film_crud->findById($value["ID_Film"]));
        $percorso_film = $immagine->find($film->locandina)["Percorso"];
        $percorso_film = "../../img/$percorso_film";
        $view_film = str_replace("#LOCANDINA#", $percorso_film, $view_film);
        $view_film = str_replace("#TITOLO#", $film->titolo, $view_film);
        $view_film = str_replace("#ANNO#", $film->anno, $view_film);
        $avg_valutazione = $valutazione_model->find_avg_by_film_id($film->id);
        $valutazione_obj = $valutazione_model->findByUser($_SESSION['username']);
        $view_film = str_replace("#VOTO#", $avg_valutazione["VALUTAZIONE_MEDIA"], $view_film);
        foreach ($valutazione_obj as $value_single){
        if (in_array($film->id, $value_single))
            $link_valutazione = "<a href='../pagine_altre/login.php' class='tnn'>Modifica valutazione</a>";
        else
            $link_valutazione = "<a href='../pagine_altre/login.php' class='tnn'>Valuta</a>";
        }
        $view_film = str_replace("#VALUTAZIONE#", $link_valutazione, $view_film);
        $list = $list.$view_film;
    }

}catch (Exception $e){

}

$list .= "</dl>";

$page = str_replace("#LIST#", $list, $page);
$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);

echo $page;
?>