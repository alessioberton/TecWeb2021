<?php
require_once(__DIR__.'/../../php/config.php');
require_once(__DIR__.'/../../php/logic/functions.php');

require_once(__DIR__.'/../../php/database/connectable.php');
require_once(__DIR__.'/../../php/database/immagine.php');

class Attore extends Connectable{

    function find($id_attore){
        $query = "SELECT * FROM attore WHERE ID = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_attore);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;

    }


    function find_nome_cognome_nascita($nome, $cognome, $data_nascita){
        $query = "SELECT * FROM attore WHERE Nome = ? AND Cognome = ? AND Data_nascita = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sss" ,$nome, $cognome, $data_nascita);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function inserisci($nome,$cognome,$data_nascita,$data_morte=NULL,$note_carriera=NULL){
        $query = "INSERT INTO attore(Nome,Cognome,Data_nascita,Data_morte,Note_carriera) VALUES(?,?,?,?,?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssss",$nome,$cognome,$data_nascita,$data_morte,$note_carriera);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result < 0) {
            throw new Exception($this->connection->error);
        }
    }

    function associa_immagine($id_attore, $id_immagine){
        $immagine = new Immagine();
        if ($this->find($id_attore) && $immagine->find($id_immagine)) {
            $query = "UPDATE attore SET ID_foto = ? WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ii",$id_immagine,$id_attore);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if ($result < 0) {
                throw new Exception($this->connection->error);
            }
        } else{
            throw new Exception("id_attore o id_immagine non trovati");
        }
    }

    function elimina($id_attore){
        if ($this->find($id_attore)) {
            $query = "DELETE FROM attore WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i",$id_attore);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if ($result < 0) {
                throw new Exception($this->connection->error);
            }
        } else{
            throw new Exception("id_attore non trovato");
        }
    }

    function modifica($id_attore,$nome,$cognome,$data_nascita,$data_morte=NULL,$note_carriera=NULL){
        if ($this->find($id_attore)) {
            $query = "UPDATE attore SET Nome = ?, Cognome = ?, Data_nascita = ?, Data_morte = ?, Note_carriera = ? WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("sssssi",$nome,$cognome,$data_nascita,$data_morte,$note_carriera,$id_attore);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if ($result < 0) {
                throw new Exception($this->connection->error);
            }
        } else{
            throw new Exception("id_attore non trovato");
        }
    }

    function getLastInsertedAttore(){
        $query = "SELECT * FROM attore ORDER BY ID DESC LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array(MYSQLI_ASSOC);
    }

    function getById($id_attore){
        if ($this->find($id_attore)) {
            $query = "SELECT * FROM attore WHERE Id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i" ,$id_attore);
            $stmt->execute();
            $result = convertQuery($stmt->get_result());
            return $result[0] ?? null;
        } else{
            throw new Exception("id_attore non trovato");
        }
    }

    function numeroFilm($id_attore){
        if ($this->find($id_attore)){
            $query = "SELECT COUNT(*) AS num_film 
                      FROM cast_film 
                      WHERE Attore = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i" ,$id_attore);
            $stmt->execute();
            $result = convertQuery($stmt->get_result());
            return $result[0]["num_film"] ?? null;
        } else{
            throw new Exception("id_attore non trovato");
        }
    }

    function guessByName($nome_attore){
        $nome_attore = $nome_attore."%";
        $query = "SELECT * FROM attore 
                  WHERE CONCAT(nome, ' ', cognome) LIKE ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $nome_attore);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }
}