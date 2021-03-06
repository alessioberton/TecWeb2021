<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../php/database/film_crud.php');
require_once(__DIR__.'/../../php/database/film.php');
require_once(__DIR__.'/../../php/database/immagine.php');

$query = $_GET["q"];

function suggestion($immagine_film, $descrizione_immagine_film, $titolo_film, $anno) {
    $titolo_film_url = rawurlencode($titolo_film);
    return <<<HTML
		<li>
			<a href="../pagine_ricerca/mostra_film.php?titolo=$titolo_film_url">
				<img src="$immagine_film" alt='$descrizione_immagine_film'/>
				<div>
					<h2>$titolo_film</h2>
					<p>$anno</p>
				</div>
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
            $html =  $html.suggestion($immagine_film, $info_img_film["Descrizione"], $film->titolo, $film->anno);
        }
		$html = "<ul>" . $html . "</ul>";
    } else {
		$html = "<p>Nessun risultato trovato</p>";
	}
    return $html;

}

$html = crea_lista($query);

echo $html;