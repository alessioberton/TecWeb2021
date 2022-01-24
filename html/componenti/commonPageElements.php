<?php
	require_once(__DIR__.'/../../php/config.php');
	require_once(__DIR__.'/../../html/componenti/header.php');
	require_once(__DIR__.'/../../html/componenti/menu_utente.php');

	class CommonPageElements {
		function render(){
			$header = new Header();
			$mainMenu = file_get_contents(__DIR__.'/../../html/componenti/menu.html');
			$userMenu = new MenuUtente();

			$html = $header->render() . $mainMenu . $userMenu->render();
			return $html;
		}
	}
?>