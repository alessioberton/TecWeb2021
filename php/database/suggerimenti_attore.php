<?php
	include_once '../config.php';

	include_once($_SESSION['$abs_path_php']."database/attore.php");

	$query = $_GET["q"];
	$html="";	

	function suggestion($nome_attore, $id_attore){
		return <<<HTML
		<li>
			<button onclick="insertActor('$nome_attore', $id_attore)" type="button">$nome_attore</button>
		</li>
		HTML;
	}

	//codice che fa query attori in base a $query e fa un for su quelli trovati (max 5) e costruisce $html
	//for (actors) $html = $html . suggestion(bla bla);

	$attore = new Attore();
	$attori = $attore->guessByName($query);
	foreach($attori as $attore_item){
		$html .= suggestion($attore_item["Nome"]." ".$attore_item["Cognome"], $attore_item["ID"]);
	}

	//$html=suggestion("Keanuu Reeves", 5);
	
	echo $html;// . $html;
?>