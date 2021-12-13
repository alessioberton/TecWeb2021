<?php
require('../connectable/connectable.php');

class Utente extends Connectable{
    function inserisci($nome){
        $result = $this->connection -> query("SELECT * FROM Utente");
        var_dump($result->num_rows);
    }
}

?>