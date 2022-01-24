<?php
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../html/componenti/commonPageElements.php');

$page = file_get_contents(__DIR__.'/storia.html');
$commonPageElements = new CommonPageElements();
$page = str_replace("<commonPageElements />", $commonPageElements->render(), $page);
echo $page;
?>