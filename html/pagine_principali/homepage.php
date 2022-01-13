<?php
include_once '../../php/config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."logic/functions.php");
	include_once($_SESSION['$abs_path_html']."componenti/commonPageElements.php");
    $_POST = array_map('empty_to_null', $_POST);
}
getAbs_path();

$page = file_get_contents("homepage.html");

$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);

$searchBar = file_get_contents($_SESSION['$abs_path_html']."componenti/searchBar.html");
$page = str_replace("<searchBar />", $searchBar, $page);

echo $page;
?>