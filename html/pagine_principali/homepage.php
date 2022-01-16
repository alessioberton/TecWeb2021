<?php
include_once '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
	include_once($_SESSION['$abs_path_html']."componenti/commonPageElements.php");
    $_POST = array_map('empty_to_null', $_POST);
}
getAbs_path();

$page = file_get_contents("homepage.html");

$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);

/* try {
    $lista_film = $film_crud->find_all();
    $_SESSION["film"] = $lista_film;
    $film_netflix = $disponibilita->find_by_film($lista_film[1]["ID"]);
    $film_titolo = $disponibilita->find_by_platform("Netflix");

    for ($i = 0; $i < count($lista_film); $i++) {
        $film = new Film($lista_film[$i]);
        $percorso_film = $img_crud->find($film->locandina)["Percorso"];
        $page = str_replace("../../img/film/imgnotfound".$i.".jpg", "../../img/".$percorso_film, $page);
    }

}catch (Exception $e){} */

$searchBar = file_get_contents($_SESSION['$abs_path_html']."componenti/searchbar_film.html");
$page = str_replace("<searchbarFilm />", $searchBar, $page);
$searchBarAttore = file_get_contents($_SESSION['$abs_path_html']."componenti/searchbar_attore.html");
$page = str_replace("<searchbarAttore />", $searchBarAttore, $page);

echo $page;
?>