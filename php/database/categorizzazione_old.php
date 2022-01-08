<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';

require_once('../connectable/connectable.php');

class Categorizzazione extends Connectable {

	function find($film_id){
		$query = "SELECT * FROM Categorizzazione WHERE AND ID_film = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

	function inserisci($film_id, $tema, $eta_pubblico, $livello, $riconoscimenti){
        $query = "INSERT INTO Categorizzazione(Film, Tema, Eta_pubblico, Livello, Mood, Riconoscimenti) VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isssi", $film_id, $tema, $eta_pubblico, $livello, $riconoscimenti);
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

?>