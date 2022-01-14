<?php
	include_once '../../php/config.php';

	class Header {
		public $userButton = <<<HTML
			<button id="userNavigationButton" type="button" onclick="toggleMenu('userNavigation')">
				<!--icon here-->
				<span>Menu Utente</span>
			</button>
		HTML;	

		public $loginLinkButton= <<<HTML
			<a id='loginLink' href='../pagine_altre/login.php'>
				<!--icon here-->
				<span>Login</span>
			</a>
		HTML;

		function render(){
			$html = file_get_contents($_SESSION['$abs_path_html']."componenti/header.html");
			if(__FILE__ == "homepage.php"){
				$html = str_replace("<mainNavigationSearchBar />", "", $html);	
			}else{
				$html = str_replace("<mainNavigationSearchBar />", file_get_contents($_SESSION['$abs_path_html']."componenti/searchbar.html"), $html);	
			}
			if ($_SESSION['logged']) {
				$html = str_replace("<userButton />", $this-> userButton, $html);	
			} else {
				$html = str_replace("<userButton />", $this-> loginLinkButton, $html);	
			}
			return $html;
		}
	}
?>