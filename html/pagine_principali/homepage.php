<?php

include '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
    require_once($_SESSION['$abs_path_php']."database/connectable.php");
    include_once($_SESSION['$abs_path_php']."database/film.php");
    include_once($_SESSION['$abs_path_php']."database/film_crud.php");
    include_once($_SESSION['$abs_path_php']."database/immagine.php");
    include_once($_SESSION['$abs_path_php']."database/disponibilita.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("homepage.html");

$disponibilita = new Disponibilita();

$film_crud = new Film_crud();
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


//    print_r($film_netflix);
//    print_r($film_titolo);

}catch (Exception $e){

}

echo $page;