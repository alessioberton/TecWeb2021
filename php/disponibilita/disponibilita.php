<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';

require_once('../connectable/connectable.php');

class Dispobilita extends Connectable {

	function find($film_id, $piattaforma){
		$query = "SELECT * FROM disponibilità WHERE Film  = ? AND Piattaforma = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("is", $film_id, $piattaforma);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
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