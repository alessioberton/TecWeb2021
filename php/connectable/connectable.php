<?php
require_once("../dbconnection/dbconnection.php");

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