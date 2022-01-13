<?php
	header('Content-Type: application/json; charset=utf-8');

	$query = $_GET["q"];

	//codice che fa una query al database e mi da i dati in json
	
	echo json_encode($query);
?>