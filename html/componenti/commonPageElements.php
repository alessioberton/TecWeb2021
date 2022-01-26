<?php
	require_once(__DIR__.'/../../php/logic/error_reporting.php');
	require_once(__DIR__.'/../../php/config.php');
	require_once(__DIR__.'/../../html/componenti/header.php');
	require_once(__DIR__.'/../../html/componenti/menu_utente.php');

	class CommonPageElements {
		function render(){
			$header = new Header();

			$html = $header->render();
			return $html;
		}
	}
?>