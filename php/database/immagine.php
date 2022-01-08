<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';

require_once($abs_path.'database/connectable.php');

class Immagine extends Connectable{

    function find($id_immagine){
        $query = "SELECT * FROM Immagini WHERE ID = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s",$id_immagine);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function inserisci($descrizione,$percorso): int {
        $query = "INSERT INTO Immagini(Descrizione,Percorso) VALUES(?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $descrizione, $percorso);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 1){
            throw new Exception($this->connection->error);
        }
        echo "<script>console.log('Debug Objects: " . $this->connection->insert_id . "' );</script>";

        return $this->connection->insert_id;
    }

    function update($id, $percorso){
        $query = "UPDATE Immagini SET Percorso = ? WHERE ID = ?";
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
            $query = "DELETE FROM Immagini WHERE ID = ?";
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
        $query = "SELECT * FROM Immagini ORDER BY ID DESC LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows <= 0){
            throw new Exception("Nessuna immagine presente");
        }

        return $result->fetch_array(MYSQLI_ASSOC);
    }
}