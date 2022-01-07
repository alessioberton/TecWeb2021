<?php

include '../config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."film/film.php");
    include_once($_SESSION['$abs_path_php']."disponibilita/disponibilita.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();

$film = new Film();
$disponibilita = new Disponibilita();

try {

    $lista_film = $film->find_all();
    print_r($lista_film);

    $film_netflix = $disponibilita->find_by_film($lista_film[1]["ID"]);
    $film_titolo = $disponibilita->find_by_platform("Netflix");

    print_r($film_netflix);
    print_r($film_titolo);



}catch (Exception $e){

}

$page = file_get_contents("../../html/homepage.html");
echo $page;