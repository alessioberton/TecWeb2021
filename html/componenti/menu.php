<?php
	require_once(__DIR__.'/../../php/logic/error_reporting.php');
	require_once(__DIR__.'/../../php/config.php');
	
	class Menu {
		public $adminMenu = <<<HTML
			<li id="adminMenu" class="has-submenu" aria-labelledby="adminMenuHeading">
				<a href="…" id="adminMenuHeading"aria-haspopup="true" aria-expanded="false">Amministratore</a>
				<ul>
					<li><a href="../pagine_utente/inserisci_film.php">Inserisci Film</a></li>
					<li><a href="../pagine_utente/inserisci_attore.php">Inserisci Attore</a></li>
				</ul>
			</li>
		HTML;

		public $userMenu = <<<HTML
			<li id="userMenu" class="has-submenu" aria-labelledby="userMenuHeading"> 
				<a href="…" id="userMenuHeading" aria-haspopup="true" aria-expanded="false">Utente</a>
				<ul>
					<li><a href="../pagine_utente/profilo.php">Profilo</a></li>
					<li><a href="../pagine_utente/film_visti.php">Film Visti</a></li>
					<li><a href="../pagine_utente/film_visti.php">Film Salvati</a></li>
					<li><a href="../pagine_utente/film_valutati.php">Film Valutati</a></li>
				</ul>
			</li>
		HTML;

		function render(){
			$html = file_get_contents(__DIR__.'/../../html/componenti/menu.html');
			$menu = "";
			if ($_SESSION['logged']) {
				$menu = $this->userMenu;
				if($_SESSION['user']['Permessi'] == "Admin") {
					$menu = $menu . $this->adminMenu;	
				}
			}
			$html = str_replace("<userMenu />", $menu, $html);
			return $html;
		}
	}	
?>