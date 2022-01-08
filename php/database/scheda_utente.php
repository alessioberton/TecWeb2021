<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php'].'database/connectable.php');

class SchedaUtente extends Connectable{

    function find($username, $film_id){
        $query = "SELECT * FROM scheda_utente WHERE utente = ? AND ID_film = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $username, $film_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;

    }

    function findByUser($username){
        $query = "SELECT * FROM scheda_utente WHERE utente = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }
	
	function inserisci($username, $film_id, $visto, $salvato, $suggerito){
        $query = "INSERT INTO scheda_utente(utente, ID_film, Visto, Salvato, Suggerito) VALUES(?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("siiii", $username, $film_id, $visto, $salvato, $suggerito);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }
}

?>