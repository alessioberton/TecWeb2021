<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';

require_once($abs_path.'connectable/connectable.php');

class Categorizzazione extends Connectable{

    function find($id_film){
        $query = "SELECT * FROM Categorizzazione WHERE Film = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_film);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;

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