<?php
	include_once '../config.php';

	$query = $_GET["q"];
	$html="";	

	function suggestion($immagine_film, $descrizione_immagine_film, $link_pagina_film, $titolo_film){
		return <<<HTML
		<li>
			<a href="">
				<img src='$immagine_film' alt='$descrizione_immagine_film'/>
				$titolo_film
			</a>
		</li>
		HTML;
	}

	//codice che fa query film in base a $query e fa un for su quelli trovati (max 5) e costruisce $html
	//for (films) $html = $html . suggestion(bla bla);

	$html=suggestion(
		$_SESSION['$img_url']."film/imgnotfound.jpg",
		"descrizione immagine", 
		$_SESSION['$public']."/pagine_princiapli/homepage.php", 
		"Matrix 5"
	);

	echo $html . $html;
?>