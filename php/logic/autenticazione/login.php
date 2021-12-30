<?php

/**
 * @param $abs_path
 * @return void
 */
function getAbs_path(&$abs_path): void {
    $abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';
    include_once($abs_path."functions/functions.php");
    include_once($abs_path."utente/utente.php");
    include_once($abs_path."immagine/immagine.php");
//    include_once($abs_path."logic/sessione.php");
    debug_to_console(json_encode("ciao"));

    if ($_SESSION['logged'] == true) {
        header('location: ../../../html/homepage.html');
        exit();
    }

    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path($abs_path);


$mail = $_POST["mail"];
$pwd = $_POST["pwd"];
$utente = new Utente();

try {
//    debug_to_console(json_encode("ciao2"));
    $test = $utente->find($mail, $pwd);
    $_SESSION['logged'] = true;
    if (isset($_POST['remember'])) {
        setcookie("mail", $mail, 86400);
        setcookie("pwd", $pwd, 86400);
    }

} catch (Exception $e) {
    $pagina_errore = file_get_contents($abs_path . "../html/errore.html");
    $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
    echo $pagina_errore;
}

//function debug_to_console($data)
//{
//    $output = $data;
//    if (is_array($output))
//        $output = implode(',', $output);
//
//    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
//}