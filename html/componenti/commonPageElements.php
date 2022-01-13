<?php
	include_once '../../php/config.php';
	include_once($_SESSION['$abs_path_html']."componenti/header.php");
	include_once($_SESSION['$abs_path_html']."componenti/menu_utente.php");

	class CommonPageElements {
		function render(){
			$header = new Header();
			$mainMenu = file_get_contents($_SESSION['$abs_path_html']."componenti/menu.html");
			$userMenu = new MenuUtente();

			$html = $header->render() . $mainMenu . $userMenu->render();
			return $html;
		}
	}
?>