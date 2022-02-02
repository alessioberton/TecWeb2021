<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/dbconnection.php');

class Connectable{
    protected $connection;

    function __construct(){
        $this->connection = new DBConnection();
        try{
            $this->connection->openDBConnection();
        }
        catch(Exception $e){
            if(!isStageWebsite()) header("Location: ../../html/pagine_altre/error.php");
            echo $e;
            exit();
        }

        $this->connection = $this->connection->getConnection();

    }
}