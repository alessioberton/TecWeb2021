<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../php/database/connectable.php');
require_once(__DIR__.'/../../php/logic/functions.php');

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
        $stmt->bind_param("i", $username);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

    function findByFilmUser($id_film,$username){
        $query = "SELECT * FROM scheda_utente WHERE id_film = ? AND utente = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ii", $id_film,$username);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }
	
	function inserisci($username, $film_id, $visto, $salvato, $suggerito){
        $query = "INSERT INTO scheda_utente(utente, ID_film, Visto, Salvato, Suggerito) VALUES(?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iiiii", $username, $film_id, $visto, $salvato, $suggerito);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

    function modifica($username, $film_id, $visto, $salvato, $suggerito){
        $query = "UPDATE scheda_utente SET
                  Visto = ?,
                  Salvato = ?,
                  Suggerito = ?
                  WHERE utente = ? AND ID_film = ?
                  ";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iiiii", $visto, $salvato, $suggerito, $username, $film_id);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }
}

?>