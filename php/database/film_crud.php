<?php

require_once(__DIR__.'/../../php/config.php');

require_once(__DIR__.'/../../php/database/connectable.php');
require_once(__DIR__.'/../../php/logic/functions.php');
require_once(__DIR__.'/../../php/database/immagine.php');

class Film_crud extends Connectable{

    function findById($id_film){
        $query = "SELECT * FROM film WHERE ID = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i",$id_film);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function find($titolo){
		$query = "SELECT * FROM film WHERE Titolo = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $titolo);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result[0] ?? null;
    }

    function guessByTitle($titolo){
        $titolo = $titolo."%";
        $query = "SELECT * FROM film WHERE Titolo LIKE ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $titolo);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }

    function find_all(){
        $query = "SELECT film.*, immagini.Percorso AS img_path, immagini.descrizione AS img_desc
                  FROM film
                  LEFT JOIN immagini ON immagini.id = film.locandina";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());

        foreach($result as &$film){
            $film["Piattaforme"] = $this->getPiattaforme($film["ID"]);
        }

        return $result;
    }

    function inserisciFilm($titolo,$lingua_titolo,$anno,$paese,$durata,$trama){
        $query = "INSERT INTO film(Titolo,lingua_titolo,Anno,Paese,Durata,Trama)
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
        $query = "INSERT INTO categorizzazione(Film, Tema, Eta_pubblico, Livello, Mood, Riconoscimenti) VALUES(?,?,?,?,?,?)";

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
            $query = "UPDATE film SET Locandina = ? WHERE ID = ?";

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
            $query = "DELETE FROM film
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
            $query = "UPDATE film SET
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
			$query = "DELETE FROM categorizzazione WHERE ID_film = ?";

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
        $query = "SELECT * FROM film ORDER BY ID DESC LIMIT 1";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array(MYSQLI_ASSOC);
    }

    function getPiattaforme($film_id){
        $query = "SELECT *
                  FROM piattaforma
                  JOIN disponibilità ON piattaforma.nome = disponibilità.piattaforma
                  WHERE disponibilità.film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);           
        $stmt->execute();
        return convertQuery($stmt->get_result());
    }

    function getAttori($film_id){
        $query = "SELECT *
                  FROM cast_film 
                  JOIN attore ON attore.id = cast_film.attore
                  WHERE cast_film.film = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $film_id);
        $stmt->execute();
        $result = convertQuery($stmt->get_result());
        return $result ?? null;
    }
}