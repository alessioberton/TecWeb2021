<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';

require_once('../connectable/connectable.php');

class Film extends Connectable {

	function find($titolo){
		$query = "SELECT * FROM film WHERE Titolo = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $titolo);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function find_all(){
        $query = "SELECT * FROM film";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

	function inserisci($film_id, $tema, $eta_pubblico, $livello, $riconoscimenti){
        $query = "INSERT INTO Categorizzazione(Film, Tema, Eta_pubblico, Livello, Mood, Riconoscimenti) VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isssb", $film_id, $tema, $eta_pubblico, $livello, $riconoscimenti);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

	function delete($film_id){
		if($this->find($film_id)){
			$query = "DELETE FROM Categorizzazione WHERE ID_film = ?";

            $stmt = $this->connection->prepare($query);
			$stmt->bind_param("i", $film_id);           
			$stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        } else {
            throw new Exception("Categorizzazione non trovata");
        }
    }

}