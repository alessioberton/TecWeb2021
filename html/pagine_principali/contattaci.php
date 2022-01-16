<?php
include_once '../../php/config.php';
include_once($_SESSION['$abs_path_html']."componenti/commonPageElements.php");

$page = file_get_contents("contattaci.html");
$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);
echo $page;
?>