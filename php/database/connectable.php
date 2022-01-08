<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php'].'database/dbconnection.php');

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