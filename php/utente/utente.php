<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';

require_once($abs_path.'connectable/connectable.php');
require_once($abs_path.'functions/functions.php');
require_once($abs_path.'immagine/immagine.php');

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

    function inserisci($username, $email, $password, $data_nascita, $permessi = NULL) {
        $datan = $this->connection->real_escape_string(trim(htmlentities($data_nascita)));
        $psw = md5($password);
        $query = "INSERT INTO Utente(Username, Email, Password, Data_nascita, Permessi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("issss", $username, $email, $psw, $datan, $permessi);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1) {
            throw new Exception($this->connection->error);
        }
    }

    function aggiorna_mail($username, $email) {
        $query = "UPDATE Utente SET Email = ? WHERE Username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1) {
            throw new Exception($this->connection->error);
        }
    }

    function aggiorna_password($username, $password) {
        $query = "UPDATE Utente SET Password = ? WHERE Username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $password, $username);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1) {
            throw new Exception($this->connection->error);
        }
    }

    function associa_immagine($username, $id_immagine) {
        $query = "UPDATE Utente SET foto_profilo = ? WHERE Username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("is", $id_immagine, $username);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 0) {
            throw new Exception($this->connection->error);
        }
    }

}