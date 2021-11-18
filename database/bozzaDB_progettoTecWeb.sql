CREATE
    TYPE CLASSIFICAZIONE AS ENUM ('T', 'VM14', 'VM18');
CREATE
    TYPE MOOD AS ENUM ('allegro', 'triste', 'suspence', 'impegnato/riflessivo','spensierato','protesta');
CREATE
    TYPE LIVELLO AS ENUM ('demenziale','leggero','medio', 'alto');
CREATE
    TYPE PERMESSI AS ENUM ('Admin', 'Moderatore', 'Utente');
CREATE
    TYPE Sesso AS ENUM ('Uomo','Donna','Altro');


CREATE TABLE Genere 
(
    Nome VARCHAR(20) NOT NULL,
    PRIMARY KEY (Nome)
);

CREATE TABLE Foto
(
    ID          INT      NOT NULL,
    Descrizione text     NOT NULL,
    Percorso    text     NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE Film
(
    ID                INT               NOT NULL,
    Titolo            VARCHAR(40)       NOT NULL,
    Regia             VARCHAR(40)       NOT NULL,
	Anno              VARCHAR(4)        NOT NULL,
	Paese             VARCHAR(35)       NOT NULL,
	Durata            time              NOT NULL,
	Trama             VARCHAR(500)      NOT NULL,
	Locandina         int               NOT NULL,
	Premiato          bool              DEFAULT FALSE,
	Classificazione   CLASSIFICAZIONE   NOT NULL,
	ImpegnoRichiesto  LIVELLO           NOT NULL,
	Tema              VARCHAR(20)       NOT NULL,
	Mood              MOOD              NOT NULL,
    PRIMARY KEY (ID),
	FOREIGN KEY (Locandina) REFERENCES Foto (ID) ON DELETE NO ACTION
);

CREATE TABLE Genere_Film
(
    ID_film       INT            NOT NULL,
    Nome_genere   VARCHAR(20)    NOT NULL,
    PRIMARY KEY (ID_film, Nome_genere),
    FOREIGN KEY (ID_film) REFERENCES Film (ID) ON DELETE NO ACTION,
    FOREIGN KEY (Nome_genere) REFERENCES Genere (Nome) ON DELETE NO ACTION
);

ALTER TABLE Genere_Film DISABLE TRIGGER ALL;


CREATE TABLE Piattaforma
(
    Nome     VARCHAR(20)   NOT NULL,
	Dettagli VARCHAR(150)  NOT NULL,
	PRIMARY KEY(Nome)
);

CREATE TABLE Disponibilità
(
    Piattaforma      VARCHAR(20)   NOT NULL,
	Film             int           NOT NULL,
	CC               bool          NOT NULL,
	SDH              bool          NOT NULL,
	AD               bool          NOT NULL,
	CostoAggiuntivo  bool          DEFAULT FALSE,
	TempoLimite      date  ,
	PRIMARY KEY (Piattaforma, Film),
    FOREIGN KEY (Piattaforma) REFERENCES Piattaforma (Nome) ON DELETE NO ACTION,
	FOREIGN KEY (Film) REFERENCES Film (ID) ON DELETE NO ACTION
	
);

CREATE TABLE Attore
(
    ID           INT         NOT NULL,
    Nome         VARCHAR(40) NOT NULL,
    Cognome      VARCHAR(40) NOT NULL,
    Data_nascita DATE        NOT NULL,
    ID_foto      INT        DEFAULT NULL,
	Sesso        SESSO      DEFAULT NULL,
    PRIMARY KEY (ID),
	FOREIGN KEY (ID_foto) REFERENCES Foto (ID) ON DELETE NO ACTION
);

CREATE TABLE Cast_Film 
(
    Film     INT   NOT NULL,
	Attore   INT   NOT NULL,
	PRIMARY KEY (Film, attore),
	FOREIGN KEY (Film) REFERENCES Film (ID) ON DELETE NO ACTION,
    FOREIGN KEY (Attore) REFERENCES Attore (ID) ON DELETE NO ACTION
);


CREATE TABLE Utente
(
    Username     VARCHAR(10)                                 NOT NULL,
    Email        VARCHAR(40) CHECK (Email LIKE '%_@__%.__%') NOT NULL,
    Password     VARCHAR(20)                                 NOT NULL,
    Nome         VARCHAR(40)                                 NOT NULL,
    Cognome      VARCHAR(40)                                 NOT NULL,
    Data_nascita DATE                                        NOT NULL,
    ID_foto      INT      DEFAULT NULL,
    Permessi     PERMESSI DEFAULT NULL,
    Sesso        SESSO    DEFAULT NULL,
    PRIMARY KEY (Username),
    FOREIGN KEY (ID_foto) REFERENCES Foto (ID) ON DELETE NO ACTION
);

CREATE TABLE Valutazione
(
    Utente             VARCHAR(10)  NOT NULL,
    ID_film            INT          NOT NULL,
    Testo              VARCHAR(512) NOT NULL,
    In_moderazione     bool      NOT NULL,
    Data_inserimento   DATE         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Data_aggiornamento DATE         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stelle             INT          NOT NULL,
    PRIMARY KEY (Utente, ID_Film),
    FOREIGN KEY (Utente) REFERENCES Utente (Username) ON DELETE NO ACTION,
    FOREIGN KEY (ID_film )  REFERENCES Film (ID) ON DELETE NO ACTION
);

ALTER TABLE Valutazione
    ADD CHECK (Stelle >= 1 AND Stelle <= 5);

CREATE TABLE Scheda_Utente
(
    Utente      VARCHAR(10)     NOT NULL,
	ID_Film     INT     NOT NULL,
	Visto       bool    NOT NULL    DEFAULT FALSE,
	Salvato     bool    NOT NULL    DEFAULT FALSE,
	Suggerito   bool    NOT NULL    DEFAULT FALSE,
	PRIMARY KEY (Utente, ID_Film),
    FOREIGN KEY (Utente) REFERENCES Utente (Username) ON DELETE NO ACTION,
    FOREIGN KEY (ID_film )  REFERENCES Film (ID) ON DELETE NO ACTION
);
ALTER TABLE Scheda_Utente
    ADD CHECK (Visto or Salvato or Suggerito = true);

INSERT INTO Genere (Nome)
VALUES ('Animazione'),
       ('Avventura'),
       ('Azione'),
       ('Biografico'),
       ('Commedia'),
       ('Documentario'),
       ('Drammatico');
	   
INSERT INTO Foto(ID, Descrizione, Percorso)
VALUES (1, 'Foto 1', 'Percorso 1'),
       (2, 'Foto 2', 'Percorso 2'),
       (3, 'Foto 3', 'Percorso 3'),
       (4, 'Foto 4', 'Percorso 4'),
       (5, 'Foto 5', 'Percorso 5'),
       (6, 'Foto 6', 'Percorso 6'),
       (7, 'Foto 7', 'Percorso 7'),
       (8, 'Foto 8', 'Percorso 8'),
       (9, 'Foto 9', 'Percorso 9'),
       (10, 'Foto 10', 'Percorso 10'),
       (11, 'Foto 11', 'Percorso 11'),
       (12, 'Foto 12', 'Percorso 12'),
       (13, 'Foto 13', 'Percorso 13');

INSERT INTO Film (ID, Titolo, Regia, Anno, Paese, Durata, Trama, Locandina, Premiato, Classificazione, ImpegnoRichiesto, Tema, Mood)
VALUES (1, 'Film 1', 'RegistaX', 2021, 'Inghilterra', '1:30:00','Trama del film', 1, false, 'T','demenziale', 'tema1', 'allegro'),
       (2, 'Film 2', 'RegistaX', 2001, 'Inghilterra','2:05:00', 'Trama del film', 5, true, 'T','demenziale', 'tema2', 'allegro'),
       (3, 'Film 3', 'RegistaY', 2011, 'Spagna','3:00:00', 'Trama del film', 1, false, 'VM14','medio', 'tema1', 'protesta'),
       (4, 'Film 4', 'RegistaZ', 2010, 'Italia', '1:45:00', 'Trama del film', 8, true, 'VM18','alto', 'tema3', 'triste'),
       (5, 'Film 5', 'RegistaX', 2021, 'Inghilterra','2:15:00', 'Trama del film', 1, false,'VM18','alto', 'tema4', 'allegro');

INSERT INTO Genere_Film(ID_film, Nome_genere)
VALUES (1, 'Azione'),
       (1, 'Avventura'),
       (1, 'Documentario'),
       (2, 'Biografico'),
       (3, 'Drammatico'),
       (3, 'Avventura'),
       (4, 'Biografico'),
       (4, 'Animazione'),
       (4, 'Drammatico'),
       (5, 'Commedia');
	   
INSERT INTO Piattaforma(Nome, Dettagli)
VALUES ('Netflix', 'costo mensile di x/y/z euro a seconda di utenti collegati'),
       ('AmazonPrime', 'abbonamento annuale di w euro, ridotto a w/2 se studente'),
       ('Disney+', 'abbonamento annuale di 89.90 euro o abbonamento mensile di 8.99 euro');

INSERT INTO Disponibilità(Piattaforma, Film, CC, SDH, AD, CostoAggiuntivo, TempoLimite)
VALUES ('Netflix', 1, true, true, true,false,'10-02-2022'),
       ('AmazonPrime', 2, false, true, false, true,null),
       ('Disney+', 5, true, true, false, false, '25-12-2025'),
       ('Netflix', 2, false, true, false, false, null);

INSERT INTO Attore(ID, Nome, Cognome, Data_nascita, ID_foto, Sesso)
VALUES (1, 'Nome 1', 'Cognome 1', '21-03-1993', 5, 'Donna'),
       (2, 'Nome 2', 'Cognome 2', '23-05-1987', 6, 'Uomo'),
       (3, 'Nome 3', 'Cognome 3', '07-09-2000', 7, 'Donna');
	   
INSERT INTO Cast_Film(Film, Attore)
VALUES (1, 1),
       (1, 2),
       (1, 3);

INSERT INTO Utente(Username, Email, Password, Nome, Cognome, Data_nascita, ID_foto, Permessi, Sesso)
VALUES ('user1', 'aleber@gmail.com', 'Password 1', 'Nome 1', 'Cognome 1', '10-01-1990', 1, 'Utente', 'Uomo'),
       ('user2', 'alesantin@gmail.com', 'Password 2', 'Nome 2', 'Cognome 2', '19-11-1980', 2, 'Admin', 'Donna'),
       ('user3', 'batman.batman@gmail.com', 'Password 3', 'Nome 3', 'Cognome 3', '22-07-2000', 3, 'Moderatore', 'Donna'),
       ('user4', 'superman@gmail.com', 'Password 4', 'Nome 4', 'Cognome 4', '08-04-1999', 4, 'Utente', 'Uomo');

INSERT INTO Valutazione(Utente, ID_film, Testo, In_moderazione, Data_inserimento, Data_aggiornamento, Stelle)
VALUES ('user1', 1, 'Commento 1', true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3),
       ('user1', 2, 'Commento 2', true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
       ('user2', 4, 'Commento 1', false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 5),
       ('user3', 2, 'Commento 4', false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO Scheda_Utente(Utente, ID_film, Visto, Salvato, Suggerito)
VALUES ('user1', 2, true, false, false),
       ('user1', 1, true, false, true),
       ('user2', 2, true, false, true),
       ('user4', 1, false, true, false);