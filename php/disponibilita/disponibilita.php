<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';

require_once('../connectable/connectable.php');

class Disponibilita extends Connectable {

	function find_by_film($film_id){
		$query = "SELECT * FROM disponibilità WHERE Film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        return convertQuery($stmt->get_result());

    }

    function find_by_platform($piattaforma){
        $query = "SELECT * FROM disponibilità WHERE Piattaforma = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $piattaforma);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

	function inserisci($film_id, $piattaforma, $cc, $sdh, $ad, $costo_aggiuntivo, $tempo_limite){
        $query = "INSERT INTO disponibilità(Piattaforma, Film, CC, SDH, AD, CostoAggiuntivo, TempoLimite) VALUES(?,?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sibbbbs", $piattaforma, $film_id, $cc, $sdh, $ad, $costo_aggiuntivo, $tempo_limite);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

	function delete($film_id, $piattaforma){
		if($this->find($film_id, $piattaforma)){
			$query = "DELETE FROM disponibilità WHERE Film  = ? AND Piattaforma = ?";

            $stmt = $this->connection->prepare($query);
			$stmt->bind_param("is",$film_id, $piattaforma);           
			$stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        } else {
            throw new Exception("Disponibilità non trovata");
        }
    }

}

?>