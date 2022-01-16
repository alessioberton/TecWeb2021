<?php
	include_once '../config.php';

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

	$html=suggestion("Keanuu Reeves", 5);

	echo $html . $html;
?>