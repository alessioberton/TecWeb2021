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
    include_once($abs_path."logic/sessione.php");
    if ($_SESSION['logged'] == true) {
        header('location: ../profilo.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

//Incluso file e logica di entrata
getAbs_path($abs_path);
// Mostro la pagina
$page = file_get_contents("../../../html/login.html");
echo $page;

//Controllo di venire da pagina di login e non tramite giri strani
if(isset($_POST['mail'])) {
    $mail = $_POST["mail"];
    $pwd = $_POST["pwd"];
    $utente = new Utente();
    try {
        $query_array = $utente->find($mail, $pwd);
        if ($query_array != null) {
            $_SESSION['logged'] = true;
            $_SESSION['email'] = $mail;
            $_SESSION['username'] = $query_array['Username'];
            $_SESSION['data_nascita'] = $query_array['Data_nascita'];
            $_SESSION['foto_profilo'] = $query_array['foto_profilo'];
            $_SESSION['permesso'] = $query_array['Permessi'];
            if (isset($_POST['remember'])) {
                setcookie("mail", $mail, 86400);
                setcookie("pwd", $pwd, 86400);
            }
        header('location: ../profilo.php');
        exit();
        } else {
            $error="[Mail o password errate]";
            $pagina_errore = file_get_contents($abs_path . "../html/errore.html");
            $pagina_errore = str_replace("</error_message>", $error, $pagina_errore);
            echo $pagina_errore;
        }
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($abs_path . "../html/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}