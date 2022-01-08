<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';

require_once('../database/connectable.php');

class Valutazione extends Connectable {

	function find($username, $film_id){
		$query = "SELECT * FROM VALUTAZIONE WHERE utente = ? AND ID_film = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $username, $film_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

    function findByFilmID($film_id){
		$query = "SELECT * FROM VALUTAZIONE WHERE ID_film = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

	function findByUser($username){
		$query = "SELECT * FROM VALUTAZIONE WHERE utente = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

	function inserisci($username, $film_id, $commento, $in_moderazione=false, $Data_inserimento, $Stelle){
        $query = "INSERT INTO VALUTAZIONE(utente, ID_film, Commento, In_moderazione, Data_inserimento, Stelle) VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sisssi", $username, $film_id, $commento, $in_moderazione, $Data_inserimento, $Stelle);
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
			$stmt->bind_param("si",$username,$film);           
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