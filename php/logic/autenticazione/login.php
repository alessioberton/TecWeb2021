<?php

include '../../config.php';

function getAbs_path(): void {
    include_once($_SESSION['$abs_path_php']."functions/functions.php");
    include_once($_SESSION['$abs_path_php']."utente/utente.php");
    include_once($_SESSION['$abs_path_php']."immagine/immagine.php");
    include_once($_SESSION['$abs_path_php']."logic/sessione.php");
    if ($_SESSION['logged'] == true) {
        header('location: ../profilo.php');
        exit();
    }
    $_POST = array_map('empty_to_null', $_POST);
}

getAbs_path();
$page = file_get_contents("../../../html/login.html");

//Controllo di venire da pagina di login e non tramite giri strani
if(isset($_POST['mail'])) {
    $mail = trim($_POST["mail"]);
    $pwd = trim($_POST["pwd"]);
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
            $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
            $pagina_errore = str_replace("</error_message>", $error, $pagina_errore);
            echo $pagina_errore;
        }
    } catch (Exception $e) {
        $pagina_errore = file_get_contents($_SESSION['$abs_path']."html/errore.html");
        $pagina_errore = str_replace("</error_message>", $e, $pagina_errore);
        echo $pagina_errore;
    }
}

echo $page;