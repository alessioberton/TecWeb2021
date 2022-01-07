<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/';

require_once($abs_path.'connectable/connectable.php');

class Disponibilita extends Connectable{
    
    function inserisci($piattaforma,$id_film,$cc,$sdh,$ad,$costo_aggiuntivo,$tempo_limite=NULL){

        $query = "INSERT INTO Disponibilità(Piattaforma,Film,CC,SDH,AD,CostoAggiuntivo,TempoLimite)
                  VALUES(?,?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("siiiiis",$piattaforma,$id_film,$cc,$sdh,$ad,$costo_aggiuntivo,$tempo_limite);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

}

?>