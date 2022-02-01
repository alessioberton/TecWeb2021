<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/connectable.php');

class Immagine extends Connectable{

    function find($id_immagine){
        $query = "SELECT * FROM immagini WHERE ID = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s",$id_immagine);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function inserisci($descrizione,$percorso): int {
        $query = "INSERT INTO immagini(Descrizione,Percorso) VALUES(?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $descrizione, $percorso);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1){
            throw new Exception($this->connection->error);
        }
        return $this->connection->insert_id;
    }

    function update($id, $percorso){
        $query = "UPDATE immagini SET Percorso = ? WHERE ID = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("si", $percorso, $id);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 1){
            throw new Exception($this->connection->error);
        }
    }

    function elimina($id_immagine){
        if($this->find($id_immagine)){
            $query = "DELETE FROM immagini WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i",$id_immagine);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_immagine non trovato");
        }
    }

    function getLastInsertedImmagine(){
        $query = "SELECT * FROM immagini ORDER BY ID DESC LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows <= 0){
            throw new Exception("Nessuna immagine presente");
        }

        return $result->fetch_array(MYSQLI_ASSOC);
    }

    function getNotFoundImage($type){
        $id_immagine = $type == "film" ? 112 : 41;

        $query = "SELECT * FROM immagini WHERE ID = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_immagine);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }
}