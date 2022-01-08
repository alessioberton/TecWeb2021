<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';

require_once($abs_path.'database/connectable.php');

class GenereFilm extends Connectable{
    
    function inserisci($id_film,$nome_genere){

        $query = "INSERT INTO Genere_Film(ID_film,Nome_genere)
                  VALUES(?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("is",$id_film,$nome_genere);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

}

?>