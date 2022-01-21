<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php'].'database/connectable.php');

class Categorizzazione extends Connectable{

    function find($id_film){
        $query = "SELECT * FROM Categorizzazione WHERE Film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_film);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

    function find_all(){
        $query = "SELECT * FROM Categorizzazione";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }


    function dynamic_find($sql){
        $query = "SELECT * FROM Categorizzazione";
        $query .= ' WHERE ' . implode(' OR ', $sql);
        $stmt = $this->connection->prepare($query);
//        $stmt->bind_param("s",$query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }


    function inserisci($id_film,$tema,$eta_pubblico,$livello,$mood,$riconoscimenti){
        if($this->find($id_film)){
            throw new Exception("id_film gia' presente");
        }

        $query = "INSERT INTO Categorizzazione(Film,Tema,Eta_pubblico,Livello,Mood,Riconoscimenti)
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