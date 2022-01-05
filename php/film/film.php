<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';

require_once($abs_path.'connectable/connectable.php');
require_once($abs_path.'immagine/immagine.php');

class Film extends Connectable{

    function find($id_film){
        $query = "SELECT * FROM Film WHERE ID = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_film);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;

    }
    
    function inserisci($titolo,$lingua_titolo,$anno,$paese,$durata,$trama){
        $query = "INSERT INTO Film(Titolo,lingua_titolo,Anno,Paese,Durata,Trama)
                  VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssssss",$titolo,$lingua_titolo,$anno,$paese,$durata,$trama);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

    function associa_immagine($id_film, $id_immagine){
        $immagine = new Immagine();
        if($this->find($id_film) && $immagine->find($id_immagine))
        {
            $query = "UPDATE Film SET Locandina = ?
                      WHERE ID = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ii",$id_immagine,$id_film);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_film o id_immagine non trovati");
        }
    }

    function elimina($id_film){
        if($this->find($id_film)){
            $query = "DELETE FROM Film
                      WHERE ID = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i",$id_film);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_film non trovato");
        }
    }

    function modifica($id_film,$titolo,$lingua_titolo,$anno,$paese,$durata,$trama){
        if($this->find($id_film)){
            $query = "UPDATE Film SET
                      Titolo = ?,
                      lingua_titolo = ?,
                      Anno = ?,
                      Paese = ?,
                      Durata = ?,
                      Trama = ?
                      WHERE ID = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssssssi",$titolo,$lingua_titolo,$anno,$paese,$durata,$trama,$id_film);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_film non trovato");
        }
    }

    function getLastInsertedFilm(){
        $query = "SELECT * FROM Film ORDER BY ID DESC LIMIT 1";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array(MYSQLI_ASSOC);
    }

}

?>