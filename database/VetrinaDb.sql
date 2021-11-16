DROP SCHEMA public CASCADE;
CREATE SCHEMA public;
GRANT
ALL
ON SCHEMA public TO postgres;
GRANT ALL
ON SCHEMA public TO public;
COMMENT
ON SCHEMA public IS 'standard public schema';

CREATE TABLE Categoria
(
    Nome VARCHAR(30) NOT NULL,
    PRIMARY KEY (Nome)
);

CREATE TABLE Media
(
    ID          INT         NOT NULL,
    Titolo      VARCHAR(40) NOT NULL,
    Descrizione VARCHAR(40) NOT NULL,
    PRIMARY KEY (ID)
);

CREATE
    TYPE PERMESSI AS ENUM ('Admin', 'Moderatore', 'Utente');
CREATE
    TYPE Sesso AS ENUM ('Uomo','Donna','Altro');


CREATE TABLE LISTA_CATEGORIA
(
    ID_media       INT         NOT NULL,
    Nome_categoria VARCHAR(30) NOT NULL,
    PRIMARY KEY (ID_media, Nome_categoria),
    FOREIGN KEY (ID_media) REFERENCES Media (ID) ON DELETE NO ACTION,
    FOREIGN KEY (Nome_categoria) REFERENCES Categoria (Nome) ON DELETE NO ACTION
);

CREATE TABLE Foto
(
    ID          INT  NOT NULL,
    Descrizione text NOT NULL,
    Percorso    text NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE LISTA_FOTO
(
    ID_foto  INT NOT NULL,
    ID_media INT DEFAULT NULL,
    PRIMARY KEY (ID_foto, ID_media),
    FOREIGN KEY (ID_foto) REFERENCES Foto (ID) ON DELETE NO ACTION,
    FOREIGN KEY (ID_media) REFERENCES Media (ID) ON DELETE NO ACTION
);

CREATE TABLE Utente
(
    ID           INT                                         NOT NULL,
    Email        VARCHAR(40) CHECK (Email LIKE '%_@__%.__%') NOT NULL,
    Password     VARCHAR(20)                                 NOT NULL,
    Nome         VARCHAR(40)                                 NOT NULL,
    Cognome      VARCHAR(40)                                 NOT NULL,
    Data_nascita DATE                                        NOT NULL,
    ID_foto      INT      DEFAULT NULL,
    Permessi     PERMESSI DEFAULT NULL,
    Sesso        SESSO    DEFAULT NULL,
    PRIMARY KEY (ID),
    FOREIGN KEY (ID_foto) REFERENCES Foto (ID) ON DELETE NO ACTION
);

CREATE TABLE Attore
(
    ID           INT         NOT NULL,
    Nome         VARCHAR(40) NOT NULL,
    Cognome      VARCHAR(40) NOT NULL,
    Data_nascita DATE        NOT NULL,
    ID_foto      INT   DEFAULT NULL,
    Sesso        SESSO DEFAULT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE Valutazione
(
    ID                 INT          NOT NULL,
    ID_utente          INT          NOT NULL,
    ID_media           INT          NOT NULL,
    Testo              VARCHAR(512) NOT NULL,
    In_moderazione     BOOLEAN      NOT NULL,
    Data_inserimento   DATE         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Data_aggiornamento DATE         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stelle             INT          NOT NULL,
    PRIMARY KEY (ID),
    FOREIGN KEY (ID_utente) REFERENCES Utente (ID) ON DELETE NO ACTION,
    FOREIGN KEY (ID_media) REFERENCES Media (ID) ON DELETE NO ACTION
);

ALTER TABLE Valutazione
    ADD CHECK (Stelle >= 1 AND Stelle <= 5);


ALTER TABLE LISTA_CATEGORIA DISABLE TRIGGER ALL;
ALTER TABLE LISTA_FOTO DISABLE TRIGGER ALL;

INSERT INTO Categoria (Nome)
VALUES ('Animazione'),
       ('Avventura'),
       ('Azione'),
       ('Biografico'),
       ('Commedia'),
       ('Documentario'),
       ('Drammatico');

INSERT INTO Media(ID, Titolo, Descrizione)
VALUES (1, 'Film 1', 'Descrizione 1'),
       (2, 'Film 2', 'Descrizione 2'),
       (3, 'Film 3', 'Descrizione 3'),
       (4, 'Film 4', 'Descrizione 4'),
       (5, 'Film 5', 'Descrizione 5');

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

INSERT INTO Utente(ID, Email, Password, Nome, Cognome, Data_nascita, ID_foto, Permessi, Sesso)
VALUES (1, 'aleber@gmail.com', 'Password 1', 'Nome 1', 'Cognome 1', '10-01-1990', 1, 'Utente', 'Uomo'),
       (2, 'alesantin@gmail.com', 'Password 2', 'Nome 2', 'Cognome 2', '19-11-1980', 2, 'Admin', 'Donna'),
       (3, 'batman.batman@gmail.com', 'Password 3', 'Nome 3', 'Cognome 3', '22-07-2000', 3, 'Moderatore', 'Donna'),
       (4, 'superman@gmail.com', 'Password 4', 'Nome 4', 'Cognome 4', '08-04-1999', 4, 'Utente', 'Uomo');

INSERT INTO Attore(ID, Nome, Cognome, Data_nascita, ID_foto, Sesso)
VALUES (1, 'Nome 1', 'Cognome 1', '21-03-1993', 5, 'Donna'),
       (2, 'Nome 2', 'Cognome 2', '23-05-1987', 6, 'Uomo'),
       (3, 'Nome 3', 'Cognome 3', '07-09-2000', 7, 'Donna');

INSERT INTO LISTA_CATEGORIA(ID_media, Nome_categoria)
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

INSERT INTO LISTA_FOTO(ID_foto, ID_media)
VALUES (8, 1),
       (9, 2),
       (10, 3),
       (11, 3),
       (12, 4);

INSERT INTO Valutazione(ID, ID_utente, ID_media, Testo, In_moderazione, Data_inserimento, Data_aggiornamento, Stelle)
VALUES (1, 1, 1, 'Commento 1', true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3),
       (2, 1, 2, 'Commento 2', true, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2),
       (3, 3, 4, 'Commento 1', false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 5),
       (4, 3, 2, 'Commento 4', false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

ALTER TABLE LISTA_CATEGORIA ENABLE TRIGGER ALL;
ALTER TABLE LISTA_FOTO ENABLE TRIGGER ALL;