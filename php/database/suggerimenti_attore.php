<?php
	require_once(__DIR__.'/../../php/logic/error_reporting.php');
	require_once(__DIR__.'/../../php/config.php');

	require_once(__DIR__.'/../../php/database/attore.php');

	$query = $_GET["q"];
	$html="";	

	function suggestion($nome_attore, $id_attore){
		return <<<HTML
		<li>
			<button onclick="insertActor('$nome_attore', $id_attore)" type="button">$nome_attore</button>
		</li>
		HTML;
	}

	$attore = new Attore();
	$attori = $attore->guessByName($query);
	foreach($attori as $attore_item){
		$html .= suggestion($attore_item["Nome"]." ".$attore_item["Cognome"], $attore_item["ID"]);
	}
	
	echo $html;
?>