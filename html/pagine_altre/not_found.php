<?php

require_once(__DIR__.'/../../html/componenti/header.php');

$page = file_get_contents(__DIR__.'/not_found.html');

$header = new Header();
$page = str_replace("<customHeader />", $header->render(), $page);
echo $page;
?>