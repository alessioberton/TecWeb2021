<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/connectable.php');

class Categorizzazione extends Connectable{

    function find($id_film){
        $query = "SELECT * FROM categorizzazione WHERE Film = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_film);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

    function find_all(){
        $query = "SELECT * FROM categorizzazione";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

    function dynamic_find($sql){
        $query = "SELECT * FROM categorizzazione";
        $query .= ' WHERE ' . implode(' OR ', $sql);
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }


    function inserisci($id_film,$eta_pubblico,$livello,$mood){
        if($this->find($id_film)){
            throw new Exception("id_film gia' presente");
        }

        $query = "INSERT INTO categorizzazione(Film,Eta_pubblico,Livello,Mood)
                  VALUES(?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isss",$id_film,$eta_pubblico,$livello,$mood);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

}

?>