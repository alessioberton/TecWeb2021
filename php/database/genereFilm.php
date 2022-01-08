<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php'].'database/connectable.php');

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