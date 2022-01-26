<?php
	require_once(__DIR__.'/../../php/logic/error_reporting.php');
	require_once(__DIR__.'/../../php/config.php');
	require_once(__DIR__.'/../../html/componenti/menu_utente.php');

	class Header {
		public $userButton = <<<HTML
			<button id="userNavigationButton" type="button" ontouchstart="toggleMenu(event, 'userNavigation')">
				<span>Menu Utente</span>
			</button>
		HTML;	

		public $loginLinkButton= <<<HTML
			<a id='loginLink' href='../pagine_altre/login.php'>
				<span lang="en">Login</span>
			</a>
		HTML;

		function render(){
			$html = file_get_contents(__DIR__.'/../../html/componenti/header.html');
			$mainMenu = file_get_contents(__DIR__.'/../../html/componenti/menu.html');
			$userMenu = new MenuUtente();

			if(__FILE__ == "homepage.php"){
				$html = str_replace("<searchbarFilm />", "", $html);	
			}else{
				$html = str_replace("<searchbarFilm />", file_get_contents(__DIR__.'/../../html/componenti/searchbar_film.html'), $html);	
			}
			if ($_SESSION['logged']) {
				$html = str_replace("<userButton />", $this-> userButton, $html);	
			} else {
				$html = str_replace("<userButton />", $this-> loginLinkButton, $html);	
			}
			$html = str_replace("<mainMenu />", $mainMenu . $userMenu->render(), $html);	

			return $html;
		}
	}
?>