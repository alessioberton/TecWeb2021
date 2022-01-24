<?php

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

    function dynamic_find($sql){
        $query = "SELECT * FROM categorizzazione";
        if (!empty($query)) {
            $query .= ' WHERE ' . implode(' OR ', $sql);
        }

        echo $query;

        $stmt = $this->connection->prepare($query);
//        $stmt->bind_param("s",$query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }


    function inserisci($id_film,$tema,$eta_pubblico,$livello,$mood,$riconoscimenti){
        if($this->find($id_film)){
            throw new Exception("id_film gia' presente");
        }

        $query = "INSERT INTO categorizzazione(Film,Tema,Eta_pubblico,Livello,Mood,Riconoscimenti)
                  VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("issssi",$id_film,$tema,$eta_pubblico,$livello,$mood,$riconoscimenti);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

}

?>