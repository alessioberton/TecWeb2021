<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/TecWeb2021/php/config.php');

require_once($_SESSION['$abs_path_php'].'database/connectable.php');
require_once($_SESSION['$abs_path_php'].'database/immagine.php');

class Film_crud extends Connectable{

    function findById($id_film){
        $query = "SELECT * FROM Film WHERE ID = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_film);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result = $stmt->affected_rows > 0;
    }

    function find($titolo){
		$query = "SELECT * FROM film WHERE Titolo = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $titolo);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function find_all(){
        $query = "SELECT * FROM film";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

    function inserisciFilm($titolo,$lingua_titolo,$anno,$paese,$durata,$trama){
        $query = "INSERT INTO Film(Titolo,lingua_titolo,Anno,Paese,Durata,Trama)
                  VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssssss",$titolo,$lingua_titolo,$anno,$paese,$durata,$trama);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

	function inserisci($film_id, $tema, $eta_pubblico, $livello, $riconoscimenti){
        $query = "INSERT INTO Categorizzazione(Film, Tema, Eta_pubblico, Livello, Mood, Riconoscimenti) VALUES(?,?,?,?,?,?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isssi", $film_id, $tema, $eta_pubblico, $livello, $riconoscimenti);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if($result < 0){
            throw new Exception($this->connection->error);
        }
    }

    function associa_immagine($id_film, $id_immagine){
        $immagine = new Immagine();
        if($this->findById($id_film) && $immagine->find($id_immagine))
        {
            $query = "UPDATE Film SET Locandina = ? WHERE ID = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ii",$id_immagine,$id_film);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_film o id_immagine non trovati");
        }
    }

    function elimina($id_film){
        if($this->findById($id_film)){
            $query = "DELETE FROM Film
                      WHERE ID = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i",$id_film);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_film non trovato");
        }
    }

    function modifica($id_film,$titolo,$lingua_titolo,$anno,$paese,$durata,$trama){
        if($this->findById($id_film)){
            $query = "UPDATE Film SET
                      Titolo = ?,
                      lingua_titolo = ?,
                      Anno = ?,
                      Paese = ?,
                      Durata = ?,
                      Trama = ?
                      WHERE ID = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssssssi",$titolo,$lingua_titolo,$anno,$paese,$durata,$trama,$id_film);
            $stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("id_film non trovato");
        }
    }

	function delete($film_id){
		if($this->findById($film_id)){
			$query = "DELETE FROM Categorizzazione WHERE ID_film = ?";

            $stmt = $this->connection->prepare($query);
			$stmt->bind_param("i", $film_id);           
			$stmt->execute();
            $result = $stmt->affected_rows;
            if($result < 0){
                throw new Exception($this->connection->error);
            }
        }
        else{
            throw new Exception("Categorizzazione non trovata");
        }
    }

    function getLastInsertedFilm(){
        $query = "SELECT * FROM Film ORDER BY ID DESC LIMIT 1";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array(MYSQLI_ASSOC);
    }
}