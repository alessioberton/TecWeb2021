<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"].'/progetto_tecweb/php/';

require_once($abs_path."dbconnection/dbconnection.php");

class Connectable{
    protected $connection;

    function __construct(){
        $this->connection = new DBConnection();
        try{
            $this->connection->openDBConnection();
        }
        catch(Exception $e){
            echo $e;
            exit();
        }

        $this->connection = $this->connection->getConnection();
        
    }
}
?>