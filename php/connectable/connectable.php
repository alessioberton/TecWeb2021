<?php
require_once("../dbconnection/dbconnection.php");

class Connectable{
    private $connection;

    function __construct(){
        $this->$connection = new DBConnection();
        try{
            $this->$connection = $this->$connection->openDBConnection();
        }
        catch(Exception $e){
            echo $e;
            exit();
        }
        
    }
}
?>