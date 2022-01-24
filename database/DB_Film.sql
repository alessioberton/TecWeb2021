CREATE TABLE lista_generi
(
    Nome VARCHAR(20) NOT NULL,
    PRIMARY KEY (Nome)
);

CREATE TABLE immagini
(
    ID          INT  NOT NULL AUTO_INCREMENT,
    Descrizione text NOT NULL,
    Percorso    text NOT NULL,
    PRIMARY KEY (ID)
);

CREATE TABLE piattaforma
(
    Nome             VARCHAR(20)  NOT NULL,
    Info_abbonamento VARCHAR(150) NOT NULL,
    PRIMARY KEY (Nome)
);


CREATE TABLE film
(
    ID            INT          NOT NULL AUTO_INCREMENT,
    Titolo        VARCHAR(40)  NOT NULL,
    Lingua_titolo VARCHAR(2)   NOT NULL,
    Anno          VARCHAR(4)   NOT NULL,
    Paese         VARCHAR(35)  NOT NULL,
    Durata        INT          NOT NULL,
    Trama         VARCHAR(500) NOT NULL,
    Locandina     int,
    PRIMARY KEY (ID),
    FOREIGN KEY (Locandina) REFERENCES immagini (ID) ON DELETE SET NULL
);

CREATE TABLE disponibilità
(
    Film            INT         NOT NULL,
    Piattaforma     VARCHAR(20) NOT NULL,
    CC              bool        NOT NULL,
    SDH             bool        NOT NULL,
    AD              bool        NOT NULL,
    CostoAggiuntivo bool DEFAULT FALSE,
    Giorno_entrata  date        NOT NULL,
    Giorno_uscita   date,
    PRIMARY KEY (Piattaforma, Film),
    FOREIGN KEY (Piattaforma) REFERENCES piattaforma (Nome) ON DELETE CASCADE,
    FOREIGN KEY (Film) REFERENCES film (ID) ON DELETE CASCADE
);

CREATE TABLE categorizzazione
(
    Film           INT          NOT NULL,
    Tema           VARCHAR(200) NOT NULL,
    Eta_pubblico   ENUM ('T', 'VM14', 'VM18') NOT NULL,
    Livello        ENUM ('demenziale','basso','medio', 'alto') NOT NULL,
    Mood           ENUM ('suspence', 'protesta', 'commovente', 'trash','comico','sentimentale', 'sorprendente') NOT NULL,
    Riconoscimenti BOOL         NOT NULL DEFAULT false,
    PRIMARY KEY (Film),
    FOREIGN KEY (Film) REFERENCES film (ID) ON DELETE CASCADE
);

CREATE TABLE attore
(
    ID            INT         NOT NULL AUTO_INCREMENT,
    Nome          VARCHAR(40) NOT NULL,
    Cognome       VARCHAR(40) NOT NULL,
    Data_nascita  DATE        NOT NULL,
    Data_morte    DATE         DEFAULT NULL,
    ID_foto       INT          DEFAULT NULL,
    Note_carriera VARCHAR(500) DEFAULT NULL,
    PRIMARY KEY (ID),
    FOREIGN KEY (ID_foto) REFERENCES immagini (ID) ON DELETE SET NULL
);


CREATE TABLE genere_film
(
    ID_film     INT         NOT NULL,
    Nome_genere VARCHAR(20) NOT NULL,
    PRIMARY KEY (ID_film, Nome_genere),
    FOREIGN KEY (ID_film) REFERENCES film (ID) ON DELETE CASCADE,
    FOREIGN KEY (Nome_genere) REFERENCES lista_generi (Nome) ON DELETE CASCADE
);


CREATE TABLE cast_film
(
    Film   INT NOT NULL,
    Attore INT NOT NULL,
    PRIMARY KEY (Film, attore),
    FOREIGN KEY (Film) REFERENCES film (ID) ON DELETE CASCADE,
    FOREIGN KEY (Attore) REFERENCES attore (ID) ON DELETE CASCADE
);

CREATE TABLE regia
(
    Film    INT NOT NULL,
    Regista INT NOT NULL,
    PRIMARY KEY (Film, Regista),
    FOREIGN KEY (Film) REFERENCES film (ID) ON DELETE CASCADE,
    FOREIGN KEY (Regista) REFERENCES attore (ID) ON DELETE CASCADE
);


CREATE TABLE utente
(
    Username     INT         NOT NULL AUTO_INCREMENT,
    Email        VARCHAR(40) NOT NULL,
    Password     VARCHAR(32) NOT NULL,
    Data_nascita DATE        NOT NULL,
    foto_profilo INT DEFAULT NULL,
    Permessi     ENUM ('Admin', 'Moderatore', 'Utente') DEFAULT NULL,
    PRIMARY KEY (Username),
    CONSTRAINT Email_unique UNIQUE (Email),
    FOREIGN KEY (foto_profilo) REFERENCES immagini (ID) ON DELETE SET NULL
);

CREATE TABLE valutazione
(
    Utente           INT          NOT NULL,
    ID_film          INT          NOT NULL,
    Commento         VARCHAR(512) NOT NULL,
    In_moderazione   BOOL         NOT NULL,
    Data_inserimento TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stelle           INT          NOT NULL,
    PRIMARY KEY (Utente, ID_Film),
    FOREIGN KEY (Utente) REFERENCES utente (Username),
    FOREIGN KEY (ID_film) REFERENCES film (ID)
);

ALTER TABLE valutazione
    ADD CHECK (Stelle >= 1 AND Stelle <= 5);

CREATE TABLE scheda_utente
(
    Utente    INT  NOT NULL,
    ID_Film   INT  NOT NULL,
    Visto     BOOL NOT NULL DEFAULT FALSE,
    Salvato   BOOL NOT NULL DEFAULT FALSE,
    Suggerito BOOL NOT NULL DEFAULT FALSE,
    PRIMARY KEY (Utente, ID_Film),
    FOREIGN KEY (Utente) REFERENCES utente (Username),
    FOREIGN KEY (ID_film) REFERENCES film (ID)
);

ALTER TABLE scheda_utente
    ADD CHECK (Visto or salvato or suggerito = true);

INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Netflix', '11 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('AmazonPrime', '7 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Crunchyroll', '4 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Rakuten', '14 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Disney+', '9 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('NowTV', '6 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('TimVision', '7.99 euro al mese');

INSERT INTO `lista_generi` (Nome)
VALUES ('Anime');
INSERT INTO `lista_generi` (Nome)
VALUES ('Animazione');
INSERT INTO `lista_generi` (Nome)
VALUES ('Avventura');
INSERT INTO `lista_generi` (Nome)
VALUES ('Azione');
INSERT INTO `lista_generi` (Nome)
VALUES ('Biografico');
INSERT INTO `lista_generi` (Nome)
VALUES ('Commedia');
INSERT INTO `lista_generi` (Nome)
VALUES ('Documentario');
INSERT INTO `lista_generi` (Nome)
VALUES ('Drammatico');

INSERT INTO immagini(ID, Descrizione, Percorso)
VALUES (1, 'Foto 1', 'film/dune.jpg'),
       (2, 'Foto 2', 'film/freaks out.jpg'),
       (3, 'Foto 3', 'film/il re leone.jpg'),
       (4, 'Foto 4', 'film/matrix.jpg'),
       (5, 'Foto 5', 'film/nomadland.jpg'),
       (6, 'Foto 6', 'film/spiderman.jpg'),
       (7, 'adam sendler', 'attori/adam sendler.jpg'),
       (8, 'brad pitt', 'attori/brad pitt.jpg'),
       (9, 'bruce willis', 'attori/bruce willis.jpg'),
       (10, 'catherine zeta jones', 'attori/catherine zeta jones.jpg'),
       (11, 'chris evans', 'attori/chris evans.jpg'),
       (12, 'jennifer aniston', 'attori/jennifer aniston.jpg'),
       (13, 'jim carrey', 'attori/jim carrey.jpg'),
       (14, 'leonardo dicaprio', 'attori/leonardo dicaprio.jpg'),
       (15, 'rydley scott', 'attori/rydley scott.jpg'),
       (16, 'sandra bullock', 'attori/sandra bullock.jpg'),
       (17, 'utente', 'utenti/imgnotfound.jpg'),
       (18, 'utente', 'utenti/imgnotfound.jpg');

INSERT INTO film (ID, Titolo, Lingua_titolo, Anno, Paese, Durata, Trama, Locandina)
VALUES (1, 'dune', 'EN', '2019', 'Stati uniti', 113,
        'La trama è bella lunga e claslòcmsalòcmaslc l òamsvlòcm lla ld dlò a.\"\"\"\"!!!!!!!!!!scmal mm\r\n\r\n\r\nfsdcs<',
        1),
       (2, 'freaks out', 'EN', '2021', 'Inghilterra', 112, 'Trama freaks out', 2),
       (3, 'il re leone', 'IT', '2001', 'Inghilterra', 110, 'Trama il re leone', 3),
       (4, 'matrix', 'EN', '2011', 'Spagna', 76, 'Trama matrix', 4),
       (5, 'nomadland', 'EN', '2010', 'Italia', 88, 'Trama nomadland', 5),
       (6, 'spiderman', 'EN', '2023', 'Inghilterra', 92, 'Trama spiderman', 6);

INSERT INTO disponibilità(Piattaforma, Film, CC, SDH, AD, CostoAggiuntivo, Giorno_entrata, Giorno_uscita)
VALUES ('Netflix', 1, true, true, true, false, '2022-10-02', null),
       ('AmazonPrime', 2, false, true, false, true, '2022-04-20', null),
       ('Disney+', 5, true, true, false, false, '2022-06-02', null),
       ('Netflix', 2, false, true, false, false, '2022-12-20', null);


INSERT INTO attore(`ID`, `Nome`, `Cognome`, `Data_nascita`, `Data_morte`, `ID_foto`, `Note_carriera`)
VALUES (1, 'Brad', 'Pitt', '1963-12-18', NULL, 8,
        'Pitt ha guadagnato dapprima una certa notorietà interpretando un cowboy autostoppista nel film Thelma & Louise (1991) di Ridley Scott. Questo gli ha permesso, negli anni successivi, di avere i suoi primi ruoli da protagonista nei drammi In mezzo scorre il fiume (1992) di Robert Redford e Vento di passioni (1994) di Edward Zwick e nell\'horror Intervista col vampiro (1994) di Neil Jordan. Le sue prove attoriali nel thriller Seven di David Fincher e nel fantascientifico L\'esercito delle 12 scimmie '),
       (2, 'Ridley', 'Scott', '1973-12-18', NULL, 15,
        'Pitt ha guadagnato dapprima una certa notorietà interpretando un cowboy autostoppista nel film Thelma & Louise (1991) di Ridley Scott. Questo gli ha permesso, negli anni successivi, di avere i suoi primi ruoli da protagonista nei drammi In mezzo scorre il fiume (1992) di Robert Redford e Vento di passioni (1994) di Edward Zwick e nell\'horror Intervista col vampiro (1994) di Neil Jordan. Le sue prove attoriali nel thriller Seven di David Fincher e nel fantascientifico L\'esercito delle 12 scimmie '),
       (3, 'Adam', 'Sendler', '1954-12-18', NULL, 7,
        'Pitt ha guadagnato dapprima una certa notorietà interpretando un cowboy autostoppista nel film Thelma & Louise (1991) di Ridley Scott. Questo gli ha permesso, negli anni successivi, di avere i suoi primi ruoli da protagonista nei drammi In mezzo scorre il fiume (1992) di Robert Redford e Vento di passioni (1994) di Edward Zwick e nell\'horror Intervista col vampiro (1994) di Neil Jordan. Le sue prove attoriali nel thriller Seven di David Fincher e nel fantascientifico L\'esercito delle 12 scimmie ');



INSERT INTO categorizzazione(Film, Tema, Eta_pubblico, Livello, Mood, Riconoscimenti)
VALUES (1, 'Tema Strano e triste', 'T', 'basso', 'commovente', true),
       (2, 'Tema cattivo', 'VM18', 'medio', 'trash', true),
       (3, 'Tema triste e strano', 'VM14', 'medio', 'comico', false),
       (4, 'Tema lellato', 'VM14', 'basso', 'sorprendente', false),
       (5, 'Tema barabbo e babbeo', 'T', 'medio', 'sentimentale', true);

INSERT INTO `cast_film` (`Film`, `Attore`)
VALUES (1, 1),
       (1, 2),
       (2, 2),
       (3, 3);

INSERT INTO `regia` (`Film`, `Regista`)
VALUES (1, 3),
       (2, 2),
       (3, 2),
       (4, 3),
       (5, 1);

INSERT INTO `utente` (`Username`, `Email`, `Password`, `Data_nascita`, `foto_profilo`, `Permessi`)
VALUES (1, "admin@admin.com", "admin", "19/05/1995", 17, "Admin"),
       (2, "user@user.com", "userrr", "19/05/2005", 18, "Utente");

INSERT INTO `scheda_utente` (`Utente`, `ID_Film`, `Visto`, `Salvato`, `Suggerito`)
VALUES (1, 1, true, false, true),
       (1, 3, false, true, true),
       (1, 4, true, false, false),
       (1, 5, false, true, true);


INSERT INTO `valutazione` (`Utente`, `ID_film`, `Commento`, `In_moderazione`, `Data_inserimento`, `Stelle`)
VALUES (1, 1, 'Bello i guesss', false, current_timestamp(), 5),
       (1, 3, 'Brutto i guesss', false, current_timestamp(), 3),
       (1, 4, 'Ciao i guesss', true, current_timestamp(), 2),
       (1, 5, 'cocoa i guesss', false, current_timestamp(), 4),
       (2, 1, 'sacmsnamcsa i guesss', false, current_timestamp(), 1),
       (2, 3, 'dalamsldaò i guesss', false, current_timestamp(), 4);
