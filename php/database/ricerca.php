<?php
include_once '../config.php';
include_once($_SESSION['$abs_path_php']."database/film_crud.php");
include_once($_SESSION['$abs_path_php']."database/film.php");
include_once($_SESSION['$abs_path_php']."database/immagine.php");

$query = $_GET["q"];

function suggestion($immagine_film, $descrizione_immagine_film, $titolo_film) {
    return <<<HTML
		<li>
			<a href="../pagine_ricerca/mostra_film.php?titolo=$titolo_film">
				<img src="$immagine_film" alt='$descrizione_immagine_film'/>
				$titolo_film
			</a>
		</li>
		HTML;
}

function crea_lista($titolo) {
    $film_crud = new Film_crud();
    $img_crud = new Immagine();
    $lista_film = $film_crud->guessByTitle($titolo);
    $html = "";
    if ($lista_film != null) {
        for ($i = 0; $i < count($lista_film); $i++) {
            $film = new Film($lista_film[$i]);
            $info_img_film = $img_crud->find($film->locandina);
            $immagine_film = "../../img/".$info_img_film["Percorso"];
            $html = $html.suggestion($immagine_film, $info_img_film["Descrizione"], $film->titolo);
        }
    }
    return $html;

}

$html = crea_lista($query);

echo $html;