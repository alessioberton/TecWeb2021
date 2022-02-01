<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/connectable.php');

class Disponibilita extends Connectable{

    function find_by_film($film_id): array {
		$query = "SELECT * FROM disponibilità WHERE Film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

    function find_by_platform($piattaforma): array {
        $query = "SELECT * FROM disponibilità WHERE Piattaforma = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $piattaforma);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

    function dynamic_find($sql): array {
        $query = "SELECT * FROM disponibilità ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

    function find_all(): array {
        $query = "SELECT * FROM disponibilità";
        $stmt = $this->connection->prepare($query);

//        $stmt->bind_param("s",$query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }


    function inserisci($id_film, $piattaforma, $cc,$sdh,$ad){

        $query = "INSERT INTO disponibilità(Piattaforma, Film, CC,SDH,AD)
                  VALUES(?,?,?,?,?)";
        
        $stmt = $this->connection->prepare($query);
        
        $stmt->bind_param("siiii",$piattaforma,$id_film,$cc,$sdh,$ad);
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