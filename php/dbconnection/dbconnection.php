<?php

   class DBConnection{
       private const HOST_DB = "localhost";
       private const DATABASE_NAME = "film";
       private const USERNAME = "root";
       private const PASSWORD = "root";
       private $connection;
   
       public function openDBConnection(){
           $this->$connection = new mysqli(DBConnection::HOST_DB,DBConnection::USERNAME,DBConnection::PASSWORD,DBConnection::DATABASE_NAME);
 
           if(!$this->$connection->connect_errno){
               return true;
           }
           else{
                throw new Exception($this->$connection->connect_error);
           }
       }
   
       public function closeConnection(){
           $this->$connection->close();
       }

       public function getConnection(){
           return $this->$connection;
       }
   }
?>