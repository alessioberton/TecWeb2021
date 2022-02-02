<?php
require_once(__DIR__ . '/../../php/logic/error_reporting.php');
require_once(__DIR__ . '/../../php/config.php');

class Menu
{
    public $adminMenu = <<<HTML
			<li id="adminMenu" class="has-submenu" aria-labelledby="adminMenuHeading">
				<a href="…" id="adminMenuHeading" aria-haspopup="true" aria-expanded="false">Amministratore</a>
				<ul>
					<li><a href="../pagine_utente/inserisci_film.php">Inserisci Film</a></li>
				</ul>
			</li>
		HTML;

    public $userMenu = <<<HTML
			<li id="userMenu" class="has-submenu" aria-labelledby="userMenuHeading"> 
				<a href="…" id="userMenuHeading" aria-haspopup="true" aria-expanded="false">Utente</a>
				<ul>
					<li><a href="../pagine_utente/profilo.php">Profilo</a></li>
					<li><a href="../pagine_utente/film_visti.php">Film Visti</a></li>
					<li><a href="../pagine_utente/film_salvati.php">Film Salvati</a></li>
					<li><a href="../pagine_utente/film_valutati.php">Film Valutati</a></li>
				</ul>
			</li>
		HTML;

    function render() {
        $html = file_get_contents(__DIR__ . '/../../html/componenti/menu.html');
        $menu = "";

        if (strpos(basename($_SERVER["REQUEST_URI"]), "approfondimenti.php") !== false) {
            $html = str_replace('><a href="../pagine_principali/approfondimenti.php">Approfondimenti</a>', ' class="active">Approfondimenti', $html);
        } else if (strpos(basename($_SERVER["REQUEST_URI"]), "homepage.php") !== false) {
            $html = str_replace('><a href="../pagine_principali/homepage.php"><span lang="en">Home</span></a>', " class='active'><span lang='en'>Home</span>", $html);
        } else if (strpos(basename($_SERVER["REQUEST_URI"]), "ricerca.php") !== false) {
            $html = str_replace('><a href="../pagine_ricerca/ricerca.php">Ricerca</a>', ' class="active">Ricerca', $html);
        }

        if ($_SESSION['logged']) {
            $menu = $this->userMenu;

            if (strpos(basename($_SERVER["REQUEST_URI"]), "profilo.php") !== false) {
                $menu = str_replace('<a href="../pagine_utente/profilo.php">Profilo</a>', 'Profilo', $menu);
            } else if (strpos(basename($_SERVER["REQUEST_URI"]), "film_visti.php") !== false) {
                $menu = str_replace('<a href="../pagine_utente/film_visti.php">Film Visti</a>', 'Film Visti', $menu);
            } else if (strpos(basename($_SERVER["REQUEST_URI"]), "film_salvati.php") !== false) {
                $menu = str_replace('<a href="../pagine_utente/film_salvati.php">Film Salvati</a>', 'Film Salvati', $menu);
            } else if (strpos(basename($_SERVER["REQUEST_URI"]), "film_valutati.php") !== false) {
                $menu = str_replace('<a href="../pagine_utente/film_valutati.php">Film Valutati</a>', 'Film Valutati', $menu);
            }

            if ($_SESSION['user']['Permessi'] == "Admin") {
                $menu = $menu . $this->adminMenu;
                if (strpos(basename($_SERVER["REQUEST_URI"]), "inserisci_film.php") !== false) {
                    $menu = str_replace('<a href="../pagine_utente/inserisci_film.php">Inserisci Film</a>', 'Inserisci Film', $menu);
                }
            }
        }
        return str_replace("<userMenu />", $menu, $html);
    }
}

?>