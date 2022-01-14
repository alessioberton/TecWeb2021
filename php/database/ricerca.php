<?php
	include_once '../config.php';

	$query = $_GET["q"];
	$html="";	

	function suggestion($immagine_film, $descrizione_immagine_film, $titolo_film){
		return <<<HTML
		<li>
			<img src='$immagine_film' alt='$descrizione_immagine_film'/>
			<span>$titolo_film</span>
		</li>
		HTML;
	}

	//codice che fa query film in base a $query e fa un for su quelli trovati (max 5) e costruisce $html
	//for (films) $html = $html . suggestion(bla bla);

	$html=suggestion($_SESSION['$abs_path_img']."/film/imgnotfound.jpg","descrizione immagine", "Matrix 5");

	echo $html;
?>