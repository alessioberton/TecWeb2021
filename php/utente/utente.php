<?php
require_once('../connectable/connectable.php');
require_once('../immagine/immagine.php');

class Utente extends Connectable{

    function find($username){
        $query = "SELECT * FROM Utente WHERE Username = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;

    }

    function inserisci($username,$email,$password,$data_nascita,$permessi=NULL){
        $query = "INSERT INTO Utente(Username,Email,Password,Data_nascita,Permessi)
                  VALUES(?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssss",$username,$email,md5($password),$data_nascita,$permessi);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

    function associa_immagine($username, $id_immagine){
        $immagine = new Immagine();
        if($this->find($username) && $immagine->find($id_immagine))
        {
            $query = "UPDATE Utente SET foto_profilo = ?
                      WHERE Username = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("is",$id_immagine,$username);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("username o id_immagine non trovati");
        }
    }

}

?>