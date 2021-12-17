<?php
require_once('../connectable/connectable.php');

class Immagine extends Connectable{

    function find($id_immagine){
        $query = "SELECT * FROM Immagini WHERE ID = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s",$id_immagine);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;

    }

    function inserisci($descrizione,$percorso){
        $query = "INSERT INTO Immagini(Descrizione,Percorso)
                  VALUES(?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss",$descrizione,$percorso);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

    function elimina($id_immagine){
        if($this->find($id_immagine)){
            $query = "DELETE FROM Immagini
                      WHERE ID = ?";

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
}

?>