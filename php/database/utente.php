<?php

require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../php/database/connectable.php');
require_once(__DIR__.'/../../php/database/immagine.php');
require_once(__DIR__.'/../../php/logic/functions.php');

class Utente extends Connectable {

    function find($mail, $pwd) {
        $query = "SELECT * FROM utente WHERE Email = ? AND password = ?";
        echo($query);
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $mail, $pwd);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function inserisci($email, $password, $data_nascita, $permessi = NULL) {
        $datan = $this->connection->real_escape_string(trim(htmlentities($data_nascita)));
//        $psw = md5($password);
        $psw = $password;
        $query = "INSERT INTO utente(Email, Password, Data_nascita, Permessi) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssss", $email, $psw, $datan, $permessi);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1) {
            throw new Exception($this->connection->error);
        }
    }

    function aggiorna_mail($username, $email) {
        $query = "UPDATE utente SET Email = ? WHERE Username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $email, $username);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1) {
            throw new Exception($this->connection->error);
        }
    }

    function aggiorna_password($username, $password) {
        $query = "UPDATE utente SET Password = ? WHERE Username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $password, $username);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1) {
            throw new Exception($this->connection->error);
        }
    }

    function associa_immagine($username, $id_immagine) {
        $query = "UPDATE utente SET foto_profilo = ? WHERE Username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("is", $id_immagine, $username);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 0) {
            throw new Exception($this->connection->error);
        }
    }

}