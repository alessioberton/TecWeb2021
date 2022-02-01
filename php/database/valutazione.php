<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/connectable.php');

class Valutazione extends Connectable {

	function find($username, $film_id){
		$query = "SELECT * FROM valutazione 
                  JOIN utente ON utente.username = valutazione.utente
                  WHERE valutazione.utente = ? AND valutazione.ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ii", $username, $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function findByFilmID($film_id){
		$query = "SELECT * FROM valutazione WHERE ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

    function getByFilmIdWithUtente($film_id){
		$query = "SELECT * 
                  FROM valutazione
                  JOIN utente ON utente.username = valutazione.utente
                  WHERE valutazione.ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

    function find_avg_by_film_id($film_id){
        $query = "SELECT ROUND(AVG(STELLE),1) AS VALUTAZIONE_MEDIA FROM valutazione WHERE ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

	function findByUser($username){
		$query = "SELECT * FROM valutazione WHERE utente = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$username);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

	function inserisci($username, $film_id, $commento, $Data_inserimento, $Stelle){
        $query = "INSERT INTO valutazione(utente, ID_film, Commento, Data_inserimento, Stelle) VALUES(?, ?, ? , ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iissi", $username, $film_id, $commento, $Data_inserimento, $Stelle);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

	function delete($username, $film_id){
		if($this->find($username, $film_id)){
			$query = "DELETE FROM valutazione WHERE utente = ? AND ID_film = ?";

            $stmt = $this->connection->prepare($query);
			$stmt->bind_param("ii", $username,$film_id);
			$stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        } else {
            throw new Exception("Valutazione non trovata");
        }
    }

    function modifica($username, $film_id, $commento, $Stelle){
        $query = "UPDATE valutazione SET
                  Commento = ?,
                  Stelle = ?
                  WHERE utente = ? AND id_film = ?
                  ";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("siii", $commento, $Stelle, $username, $film_id);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }
}

?>