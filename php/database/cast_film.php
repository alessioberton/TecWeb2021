<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/connectable.php');

class Cast_film extends Connectable{

    function inserisci($film_id,$attore_id){
        $query = "INSERT INTO cast_film(Film,Attore)
                  VALUES(?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ii",$film_id,$attore_id);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

}

?>