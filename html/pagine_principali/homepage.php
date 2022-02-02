<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../html/componenti/header.php');

$page = file_get_contents(__DIR__."/homepage.html");

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
$_SESSION['pagina_corrente'] = basename($_SERVER["REQUEST_URI"]);

echo $page;
?>