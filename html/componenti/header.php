<?php
	require_once(__DIR__.'/../../php/logic/error_reporting.php');
	require_once(__DIR__.'/../../php/config.php');
	require_once(__DIR__.'/../../html/componenti/menu.php');

	class Header {
		public $loginLinkButton= <<<HTML
			<a class="userStatus loginLink" href='../pagine_altre/login.php'>
				<span lang="en">Login</span>
			</a>
		HTML;

		public $logoutLinkButton= <<<HTML
			<a class="userStatus logoutLink" href="../../php/logic/logout.php">
				<span lang="en">Logout</span>
			</a>
		HTML;

		function render(){
			$html = file_get_contents(__DIR__.'/../../html/componenti/header.html');
			$menu = new Menu();
			$html = str_replace("<searchbarFilm />", file_get_contents(__DIR__.'/../../html/componenti/searchbar_film.html'), $html);	
		
			if ($_SESSION['logged']) {
				$html = str_replace("<userButton />", $this -> logoutLinkButton, $html);	
			} else {
				$html = str_replace("<userButton />", $this-> loginLinkButton, $html);	
			}
			$html = str_replace("<mainMenu />", $menu->render(), $html);	

			return $html;
		}
	}
?>