<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php'].'database/connectable.php');

class Valutazione extends Connectable {

	function find($username, $film_id){
		$query = "SELECT * FROM VALUTAZIONE WHERE utente = ? AND ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ii", $username, $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function findByFilmID($film_id){
		$query = "SELECT * FROM VALUTAZIONE WHERE ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

    function find_avg_by_film_id($film_id){
        $query = "SELECT ROUND(AVG(STELLE),1) AS VALUTAZIONE_MEDIA FROM VALUTAZIONE WHERE ID_film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

	function findByUser($username){
		$query = "SELECT * FROM VALUTAZIONE WHERE utente = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$username);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

	function inserisci($username, $film_id, $commento, $in_moderazione=false, $Data_inserimento, $Stelle){
        $query = "INSERT INTO VALUTAZIONE(utente, ID_film, Commento, In_moderazione, Data_inserimento, Stelle) VALUES(?, ?, ? , ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iisssi", $username, $film_id, $commento, $in_moderazione, $Data_inserimento, $Stelle);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

	function delete($username, $film_id){
		if($this->find($username, $film_id)){
			$query = "DELETE FROM VALUTAZIONE WHERE utente = ? AND ID_film = ?";

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

}

?>