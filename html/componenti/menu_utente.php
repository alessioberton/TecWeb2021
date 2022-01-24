<?php
	require_once(__DIR__.'/../../php/logic/error_reporting.php');
	require_once(__DIR__.'/../../php/config.php');
	
	class MenuUtente {
		public $adminMenu = <<<HTML
			<li id="adminMenu">
				<h2>Amministratore</h2>
				<ul>
					<li><a href="../pagine_utente/inserisci_film.php">Inserisci Film</a></li>
					<li><a href="../pagine_utente/inserisci_attore.php">Inserisci Attore</a></li>
				</ul>
			</li>
		HTML;

		function render(){
			$html = "";
			if ($_SESSION['logged']) {
				$html = file_get_contents(__DIR__.'/../../html/componenti/menu_utente.html');
				if($_SESSION['user']['Permessi'] == "Admin") {
					$html = str_replace("<adminMenu />", $this->adminMenu, $html);
				}
			}
			return $html;
		}
	}	
?>