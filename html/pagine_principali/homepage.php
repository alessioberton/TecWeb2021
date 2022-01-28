<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

function getAbs_path(): void {
    require_once(__DIR__.'/../../php/database/film_crud.php');
    require_once(__DIR__.'/../../php/database/immagine.php');
    require_once(__DIR__.'/../../php/database/disponibilita.php');
    require_once(__DIR__.'/../../php/database/film.php');
    require_once(__DIR__.'/../../php/logic/functions.php');
	require_once(__DIR__.'/../../html/componenti/header.php');
    $_POST = array_map('empty_to_null', $_POST);
}
getAbs_path();

$page = file_get_contents(__DIR__."/homepage.html");

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
$film_crud = new Film_crud();
$disponibilita = new Disponibilita();
$img_crud = new Immagine();

try {
    $lista_film = $film_crud->find_all();
    $_SESSION["film"] = $lista_film;
    $film_netflix = $disponibilita->find_by_film($lista_film[1]["ID"]);
    $film_titolo = $disponibilita->find_by_platform("Netflix");

    for ($i = 0; $i < count($lista_film); $i++) {
        $film = new Film($lista_film[$i]);
        $percorso_film = $img_crud->find($film->locandina)["Percorso"];
        $page = str_replace("../../img/film/imgnotfound".$i.".jpg", "../../img/".$percorso_film, $page);
    }

}catch (Exception $e) {
    $searchBar = file_get_contents(__DIR__.'/../../html/componenti/searchBar.html');
    $page = str_replace("<searchBar />", $searchBar, $page);

}

echo $page;
?>