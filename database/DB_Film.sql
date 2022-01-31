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
VALUES ('Netflix', '11,99 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Amazon Prime', '7 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Rakuten', '14,59 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('Disney+', '9 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('NowTV', '6 euro al mese');
INSERT INTO `piattaforma` (`Nome`, `Info_abbonamento`)
VALUES ('TimVision', '7.99 euro al mese');


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
VALUES ('Drammatico');
INSERT INTO `lista_generi` (Nome)
VALUES ('Fantascienza');
INSERT INTO `lista_generi` (Nome)
VALUES ('Horror');

INSERT INTO immagini(ID, Descrizione, Percorso)
VALUES (1, 'Foto 1', 'film/Avengers endgame.jpg'),
       (2, 'Foto 2', 'film/Freaks out.jpg'),
       (3, 'Foto 3', 'film/Il re leone.jpg'),
       (4, 'Foto 4', 'film/Matrix reloaded.jpg'),
       (5, 'Foto 5', 'film/Nomadland.jpg'),
       (6, 'Foto 6', 'film/Spider-Man No Way Home.jpg'),
       (7, 'Foto 7', 'film/Alien.jpg'),
       (8, 'Foto 8', 'film/Bastardi senza gloria.jpg'),
       (9, 'Foto 9', 'film/Encanto.jpg'),
       (10, 'Foto 10', 'film/Fight club.jpg'),
       (11, 'Foto 11', 'film/Il gladiatore.jpg'),
       (12, 'Foto 12', 'film/Il padrino.jpg'),
       (13, 'Foto 13', 'film/Il pianista.jpg'),
       (14, 'Foto 14', 'film/Interstellar.jpg'),
       (15, 'Foto 15', 'film/Joker.jpg'),
       (16, 'Foto 16', 'film/The departed.jpg'),
       (17, 'Foto 17', 'film/Dune.jpg'),
       (18, 'Adrien Brody', 'attori/Adrien Brody.jpg'),
       (19, 'Al Pacino', 'attori/Al Pacino.jpg'),
       (20, 'Anne Hathaway', 'attori/Anne Hathaway.jpg'),
       (21, 'Annika Wedderkopp', 'attori/Annika Wedderkopp.jpg'),
       (22, 'Aurora Giovinazzo', 'attori/Aurora Giovinazzo.jpg'),
       (23, 'Benedict Cumberbatch', 'attori/Benedict Cumberbatch.jpg'),
       (24, 'Beyoncé', 'attori/Beyoncé.jpg'),
       (25, 'Brad Pitt', 'attori/Brad Pitt.jpg'),
       (26, 'Brie Larson', 'attori/Brie Larson.jpg'),
       (27, 'Carrie-Anne Moss', 'attori/Carrie-Anne Moss.jpg'),
       (28, 'Chiwetel Ejiofor', 'attori/Chiwetel Ejiofor.jpg'),
       (29, 'Chris Evans', 'attori/Chris Evans.jpg'),
       (30, 'Chris Hemsworth', 'attori/Chris Hemsworth.jpg'),
       (31, 'Christoph Waltz', 'attori/Christoph Waltz.jpg'),
       (32, 'Claudio Santamaria', 'attori/Claudio Santamaria.jpg'),
       (33, 'Connie Nielsen', 'attori/Connie Nielsen.jpg'),
       (34, 'Diane Keaton', 'attori/Diane Keaton.jpg'),
       (35, 'Donald Glover', 'attori/Donald Glover.jpg'),
       (36, 'Edward Norton', 'attori/Edward Norton.jpg'),
       (37, 'Eli Roth', 'attori/Eli Roth.jpg'),
       (38, 'Emilia Fox', 'attori/Emilia Fox.jpg'),
       (39, 'Frances Conroy', 'attori/Frances Conroy.jpg'),
       (40, 'Frank Finlay', 'attori/Frank Finlay.jpg'),
       (41, 'Giancarlo Martini', 'attori/No image.jpg'),
       (42, 'Giorgio Cantarini', 'attori/Giorgio Cantarini.jpg'),
       (43, 'Hugo Weaving', 'attori/Hugo Weaving.jpg'),
       (44, 'Jack Nicholson', 'attori/Jack Nicholson.jpg'),
       (45, 'Jacob Batalon', 'attori/Jacob Batalon.jpg'),
       (46, 'James Caan', 'attori/James Caan.jpg'),
       (47, 'Jason Momoa', 'attori/Jason Momoa.jpg'),
       (48, 'Jeremy Renner', 'attori/Jeremy Renner.jpg'),
       (49, 'Jessica Chastain', 'attori/Jessica Chastain.jpg'),
       (50, 'Jessica Darrow', 'attori/Jessica Darrow.jpg'),
       (51, 'Joaquin Phoenix', 'attori/Joaquin Phoenix.jpg'),
       (52, 'John Hurt', 'attori/John Hurt.jpg'),
       (53, 'John Leguizamo', 'attori/John Leguizamo.jpg'),
       (54, 'John Oliver', 'attori/John Oliver.jpg'),
       (55, 'Keanu Reeves', 'attori/Keanu Reeves.jpg'),
       (56, 'Laurence Fishburne', 'attori/Laurence Fishburne.jpg'),
       (57, 'Leonardo DiCaprio', 'attori/Leonardo DiCaprio.jpg'),
       (58, 'Mackenzie Foy', 'attori/Mackenzie Foy.jpg'),
       (59, 'Mads Mikkelsen', 'attori/Mads Mikkelsen.jpg'),
       (60, 'María Cecilia Botero', 'attori/María Cecilia Botero.jpg'),
       (61, 'Mark Ruffalo', 'attori/Mark Ruffalo.jpg'),
       (62, 'Mark Wahlberg', 'attori/Mark Wahlberg.jpg'),
       (63, 'Marlon Brando', 'attori/Marlon Brando.jpg'),
       (64, 'Martin Sheen', 'attori/Martin Sheen.jpg'),
       (65, 'Matt Damon', 'attori/Matt Damon.jpg'),
       (66, 'Matthew McConaughey', 'attori/Matthew McConaughey.jpg'),
       (67, 'Mauro Castillo', 'attori/Mauro Castillo.jpg'),
       (68, 'Meat Loaf', 'attori/Meat Loaf.jpg'),
       (69, 'Mélanie Laurent', 'attori/Mélanie Laurent.jpg'),
       (70, 'Nicoletta Braschi', 'attori/Nicoletta Braschi.jpg'),
       (71, 'Oliver Reed', 'attori/Oliver Reed.jpg'),
       (72, 'Oscar Isaac', 'attori/Oscar Isaac.jpg'),
       (73, 'Pietro Castellitto', 'attori/Pietro Castellitto.jpg'),
       (74, 'Rebecca Ferguson', 'attori/Rebecca Ferguson.jpg'),
       (75, 'Robert De Niro', 'attori/Robert De Niro.jpg'),
       (76, 'Robert Downey Jr.', 'attori/Robert Downey Jr..jpg'),
       (77, 'Robert Duvall', 'attori/Robert Duvall.jpg'),
       (78, 'Roberto Benigni', 'attori/Roberto Benigni.jpg'),
       (79, 'Russell Crowe', 'attori/Russell Crowe.jpg'),
       (80, 'Scarlett Johansson', 'attori/Scarlett Johansson.jpg'),
       (81, 'Seth Rogen', 'attori/Seth Rogen.jpg'),
       (82, 'Sigourney Weaver', 'attori/Sigourney Weaver.jpg'),
       (83, 'Stephanie Beatriz', 'attori/Stephanie Beatriz.jpg'),
       (84, 'Susse Wold', 'attori/Susse Wold.jpg'),
       (85, 'Thomas Bo Larsen', 'attori/Thomas Bo Larsen.jpg'),
       (86, 'Thomas Kretschmann', 'attori/Thomas Kretschmann.jpg'),
       (87, 'Timothée Chalamet', 'attori/Timothée Chalamet.jpg'),
       (88, 'Tom Holland', 'attori/Tom Holland.jpg'),
       (89, 'Tom Skerritt', 'attori/Tom Skerritt.jpg'),
       (90, 'Veronica Cartwright', 'attori/Veronica Cartwright.jpg'),
       (91, 'Zach Grenier', 'attori/Zach Grenier.jpg'),
       (92, 'Zazie Beetz', 'attori/Zazie Beetz.jpg'),
       (93, 'Zendaya', 'attori/Zendaya.jpg'),
       (94, 'Anthony Russo', 'attori/Anthony Russo.jpg'),
       (95, 'Joe Russo', 'attori/Joe Russo.jpg'),
       (96, 'Gabriele Mainetti', 'attori/Gabriele Mainetti.jpg'),
       (97, 'Jon Favreau', 'attori/Jon Favreau.jpg'),
       (98, 'Lilly Wachowski', 'attori/Lilly Wachowski.jpg'),
       (99, 'Lana Wachowski', 'attori/Lana Wachowski.jpg'),
       (100, 'Chloé Zhao', 'attori/Chloé Zhao.jpg'),
       (101, 'Jon Watts', 'attori/Jon Watts.jpg'),
       (102, 'Ridley Scott', 'attori/Ridley Scott.jpg'),
       (103, 'Quentin Tarantino', 'attori/Quentin Tarantino.jpg'),
       (104, 'Jared Bush', 'attori/Jared Bush.jpg'),
       (105, 'David Fincher', 'attori/David Fincher.jpg'),
       (106, 'Francis Ford Coppola', 'attori/Francis Ford Coppola.jpg'),
       (107, 'Roman Polanski', 'attori/Roman Polanski.jpg'),
       (108, 'Christopher Nolan', 'attori/Christopher Nolan.jpg'),
       (109, 'Todd Phillips', 'attori/Todd Phillips.jpg'),
       (110, 'Martin Scorsese', 'attori/Martin Scorsese.jpg'),
       (111, 'Denis Villeneuve', 'attori/Denis Villeneuve.jpg'),
       (112, 'utente', 'utenti/imgnotfound.jpg'),
       (113, 'utente', 'utenti/imgnotfound.jpg');


INSERT INTO attore(`ID`, `Nome`, `Cognome`, `Data_nascita`, `Data_morte`, `ID_foto`, `Note_carriera`)
VALUES (1, 'Adrien', 'Brody', '1963-12-18', NULL, 18, ''),
       (2, 'Al', 'Pacino', '1973-12-18', NULL, 19, ''),
       (3, 'Anne', 'Hathaway', '1954-12-18', NULL, 20, ''),
       (4, 'Annika', 'Wedderkopp', '1954-12-18', NULL, 21, ''),
       (5, 'Aurora', 'Giovinazzo', '1954-12-18', NULL, 22, ''),
       (6, 'Benedict', 'Cumberbatch', '1954-12-18', NULL, 23, ''),
       (7, 'Beyoncé', '', '1954-12-18', NULL, 24, ''),
       (8, 'Brad', 'Pitt', '1954-12-18', NULL, 25, ''),
       (9, 'Brie', 'Larson', '1954-12-18', NULL, 26, ''),
       (10, 'Carrie-Anne', 'Moss', '1954-12-18', NULL, 27, ''),
       (11, 'Chiwetel', 'Ejiofor', '1954-12-18', NULL, 28, ''),
       (12, 'Chris', 'Evans', '1954-12-18', NULL, 29, ''),
       (13, 'Chris', 'Hemsworth', '1954-12-18', NULL, 30, ''),
       (14, 'Christoph', 'Waltz', '1954-12-18', NULL, 31, ''),
       (15, 'Claudio', 'Santamaria', '1954-12-18', NULL, 32, ''),
       (16, 'Connie', 'Nielsen', '1954-12-18', NULL, 33, ''),
       (17, 'Diane', 'Keaton', '1954-12-18', NULL, 34, ''),
       (18, 'Diane', 'Kruger', '1954-12-18', NULL, 35, ''),
       (19, 'Donald', 'Glover', '1954-12-18', NULL, 36, ''),
       (20, 'Edward', 'Norton', '1954-12-18', NULL, 37, ''),
       (21, 'Eli', 'Roth', '1954-12-18', NULL, 38, ''),
       (22, 'Emilia', 'Fox', '1954-12-18', NULL, 39, ''),
       (23, 'Frances', 'Conroy', '1954-12-18', NULL, 40, ''),
       (24, 'Frank', 'Finlay', '1954-12-18', NULL, 41, ''),
       (25, 'Giorgio', 'Cantarini', '1954-12-18', NULL, 42, ''),
       (26, 'Hugo', 'Weaving', '1954-12-18', NULL, 43, ''),
       (27, 'Jack', 'Nicholson', '1954-12-18', NULL, 44, ''),
       (28, 'Jacob', 'Batalon', '1954-12-18', NULL, 45, ''),
       (29, 'James', 'Caan', '1954-12-18', NULL, 46, ''),
       (30, 'Jason', 'Momoa', '1954-12-18', NULL, 47, ''),
       (31, 'Jeremy', 'Renner', '1954-12-18', NULL, 48, ''),
       (32, 'Jessica', 'Chastain', '1954-12-18', NULL, 49, ''),
       (33, 'Jessica', 'Darrow', '1954-12-18', NULL, 50, ''),
       (34, 'Joaquin', 'Phoenix', '1954-12-18', NULL, 51, ''),
       (35, 'John', 'Hurt', '1954-12-18', NULL, 52, ''),
       (36, 'John', 'Leguizamo', '1954-12-18', NULL, 53, ''),
       (37, 'John', 'Oliver', '1954-12-18', NULL, 54, ''),
       (38, 'Keanu', 'Reeves', '1954-12-18', NULL, 55, ''),
       (39, 'Laurence', 'Fishburne', '1954-12-18', NULL, 56, ''),
       (40, 'Leonardo', 'DiCaprio', '1954-12-18', NULL, 57, ''),
       (41, 'Mackenzie', 'Foy', '1954-12-18', NULL, 58, ''),
       (42, 'Mads', 'Mikkelsen', '1954-12-18', NULL, 59, ''),
       (43, 'María Cecilia', 'Botero', '1954-12-18', NULL, 60, ''),
       (44, 'Mark', 'Ruffalo', '1954-12-18', NULL, 61, ''),
       (45, 'Mark', 'Wahlberg', '1954-12-18', NULL, 62, ''),
       (46, 'Marlon', 'Brando', '1954-12-18', NULL, 63, ''),
       (47, 'Martin', 'Sheen', '1954-12-18', NULL, 64, ''),
       (48, 'Matt', 'Damon', '1954-12-18', NULL, 65, ''),
       (49, 'Matthew', 'McConaughey', '1954-12-18', NULL, 66, ''),
       (50, 'Mauro', 'Castillo', '1954-12-18', NULL, 67, ''),
       (51, 'Meat', 'Loaf', '1954-12-18', NULL, 68, ''),
       (52, 'Mélanie', 'Laurent', '1954-12-18', NULL, 69, ''),
       (53, 'Nicoletta', 'Braschi', '1954-12-18', NULL, 70, ''),
       (54, 'Oliver', 'Reed', '1954-12-18', NULL, 71, ''),
       (55, 'Oscar', 'Isaac', '1954-12-18', NULL, 72, ''),
       (56, 'Pietro', 'Castellitto', '1954-12-18', NULL, 73, ''),
       (57, 'Rebecca', 'Ferguson', '1954-12-18', NULL, 74, ''),
       (58, 'Robert', 'De Niro', '1954-12-18', NULL, 75, ''),
       (59, 'Robert', 'Downey Jr.', '1954-12-18', NULL, 76, ''),
       (60, 'Robert', 'Duvall', '1954-12-18', NULL, 77, ''),
       (61, 'Roberto', 'Benigni', '1954-12-18', NULL, 78, ''),
       (62, 'Russell', 'Crowe', '1954-12-18', NULL, 79, ''),
       (63, 'Scarlett', 'Johansson', '1954-12-18', NULL, 80, ''),
       (64, 'Seth', 'Rogen', '1954-12-18', NULL, 81, ''),
       (65, 'Sigourney', 'Weaver', '1954-12-18', NULL, 82, ''),
       (66, 'Stephanie', 'Beatriz', '1954-12-18', NULL, 83, ''),
       (67, 'Susse', 'Wold', '1954-12-18', NULL, 84, ''),
       (68, 'Thomas', 'Bo Larsen', '1954-12-18', NULL, 85, ''),
       (69, 'Thomas', 'Kretschmann', '1954-12-18', NULL, 86, ''),
       (70, 'Timothée', 'Chalamet', '1954-12-18', NULL, 87, ''),
       (71, 'Tom', 'Holland', '1954-12-18', NULL, 88, ''),
       (72, 'Tom', 'Skerritt', '1954-12-18', NULL, 89, ''),
       (73, 'Veronica', 'Cartwright', '1954-12-18', NULL, 90, ''),
       (74, 'Zach', 'Grenier', '1954-12-18', NULL, 91, ''),
       (75, 'Zazie', 'Beetz', '1954-12-18', NULL, 92, ''),
       (76, 'Zendaya', '', '1954-12-18', NULL, 93, ''),
       (77, 'Anthony', 'Russo', '1954-12-18', NULL, 94, ''),
       (78, 'Joe', 'Russo', '1954-12-18', NULL, 95, ''),
       (79, 'Gabriele', 'Mainetti', '1954-12-18', NULL, 96, ''),
       (80, 'Jon', 'Favreau', '1954-12-18', NULL, 97, ''),
       (81, 'Lilly', 'Wachowski', '1954-12-18', NULL, 98, ''),
       (82, 'Lana', 'Wachowski', '1954-12-18', NULL, 99, ''),
       (83, 'Chloé', 'Zhao', '1954-12-18', NULL, 100, ''),
       (84, 'Jon', 'Watts', '1954-12-18', NULL, 101, ''),
       (85, 'Ridley', 'Scott', '1954-12-18', NULL, 102, ''),
       (86, 'Quentin', 'Tarantino', '1954-12-18', NULL, 103, ''),
       (87, 'Jared', 'Bush', '1954-12-18', NULL, 104, ''),
       (88, 'David', 'Fincher', '1954-12-18', NULL, 105, ''),
       (89, 'Francis', 'Ford Coppola', '1954-12-18', NULL, 106, ''),
       (90, 'Roman', 'Polanski', '1954-12-18', NULL, 107, ''),
       (91, 'Christopher', 'Nolan', '1954-12-18', NULL, 108, ''),
       (92, 'Todd', 'Phillips', '1954-12-18', NULL, 109, ''),
       (93, 'Martin', 'Scorsese', '1954-12-18', NULL, 110, ''),
       (94, 'Denis', 'Villeneuve', '1954-12-18', NULL, 111, '');

INSERT INTO film (ID, Titolo, Lingua_titolo, Anno, Paese, Durata, Trama, Locandina)
VALUES (1, 'Avengers endgame', 'EN', '2019', 'us', 181,
        'Alla deriva nello spazio senza cibo o acqua, Tony Stark vede la propria scorta di ossigeno diminuire di minuto in minuto. Nel frattempo, i restanti Vendicatori affrontano un epico scontro con Thanos.',
        1),
       (2, 'Freaks out', 'EN', '2021', 'it', 141,
        'Nella Roma del 1943, quattro amici lavorano in un circo gestito da Israel, che sparisce nel nulla. Senza il loro capo a guidarli, Matilde, Cencio, Fulvio e Mario si sentono abbandonati e cercano una via di fuga dalla città occupata dai nazisti.',
        2),
       (3, 'Il re leone', 'IT', '2019', 'us', 118,
        'Tradito dallo zio che ha ordito un terribile complotto per prendere il potere, il piccolo Simba, leoncino figlio del re della foresta, affronta il proprio destino nel cuore della savana.',
        3),
       (4, 'Matrix reloaded', 'EN', '2003', 'us', 129,
        'Morpheus capisce che l''Uno è in realtà un sistema di controllo ideato dagli architetti di Matrix. Mentre l''esercito di Zion combatte le sentinelle e le macchine, Neo e Trinity decidono di sfidare il nemico nel loro cuore, la Città delle Macchine.',
        4),
       (5, 'Nomadland', 'EN', '2020', 'us', 108,
        'Dopo aver perso il marito e il lavoro durante la Grande recessione, la sessantenne Fern lascia la città Empire, Nevada. Fern vuole attraversare gli Stati Uniti occidentali a bordo del suo furgone.',
        5),
       (6, 'Spider-Man: No Way Home', 'EN', '2021', 'us', 148,
        'L''identità dell''Uomo Ragno viene rivelata a tutti, e non riesce più a separare la sua vita normale dalla vita da supereroe, e quando chiede aiuto al Dottor Strange, lo costringe a scoprire cosa significa veramente essere l''Uomo Ragno.',
        6),
       (7, 'Alien', 'EN', '1979', 'gb', 117,
        'Dopo che una nave mercantile spaziale percepisce una trasmissione sconosciuta che interpretano come una chiamata di soccorso, uno dei membri dell''equipaggio viene attaccato da una misteriosa forma di vita durante il suo sbarco sulla luna, e presto si rendono conto che il suo ciclo vitale è appena iniziato.',
        7),
       (8, 'Bastardi senza gloria', 'IT', '2009', 'Frncia', 153,
        'Nella Francia occupata dai nazisti durante la seconda guerra mondiale, un progetto per assassinare i leader nazisti da parte di un gruppo di soldati ebrei americani coincide con i stessi piani vendicativi della proprietaria di un cinema.',
        8),
       (9, 'Encanto', 'ES', '2021', 'co', 102,
        'In Colombia, una giovane donna affronta la frustrazione di essere l''unico membro della famiglia che non possiede poteri magici.',
        9),
       (10, 'Fight club', 'EN', '1999', 'us', 149,
        'Un impiegato che soffre di insonnia e un fabbricante di sapone menefreghista formano un club di combattimenti clandestino che si trasforma in qualcosa molto di più grande.',
        10),
       (11, 'Il gladiatore', 'IT', '2000', 'it', 155,
        'Un ex generale romano con l''intenzione di vendicarsi dell''imperatore corrotto che ha assassinato la sua famiglia e l''ha mandato in schiavitù.',
        11),
       (12, 'Il padrino', 'IT', '1972', 'us', 175,
        'Il patriarca invecchiando di un''organizzazione criminale trasferisce il controllo del suo impero clandestino al suo figlio riluttante.',
        12),
       (13, 'Il pianista', 'IT', '2002', 'pl', 150,
        'Un musicista ebreo in Polonia deve sopravvivere dopo la distruzione del ghetto di Varsavia durante la seconda guerra mondiale.',
        13),
       (14, 'Interstellar', 'EN', '2014', 'us', 169,
        'Una squadra di esploratori viaggia attraverso un tunnel spaziale nel tentativo di assicurare la sopravvivenza dell''umanità.',
        14),
       (15, 'Joker', 'EN', '2019', 'us', 122,
        'A Gotham City, il comico Arthur Fleck con problemi di malattia mentale viene disprezzato dalla società. Inizia una spirale verso il basso di rivolte e crimini sanguinanti. Questo lo mette faccia a faccia con il suo alter ego: "il Joker".',
        15),
       (16, 'The departed', 'EN', '2006', 'us', 151,
        'Un poliziotto in borghese e una talpa nella polizia tentano di identificarsi mentre si infiltrano in una banda irlandese di South Boston.',
        16),
       (17, 'Dune', 'EN', '2021', 'ca', 155,
        'Adattamento cinematografico del romanzo fantascientifico di Frank Herbert. Il figlio di una famiglia nobile cerca di vendicare la morte del padre mentre è alle prese con l''incarico di proteggere e salvare un pianeta che contiene un''importante spezia.',
        17);


INSERT INTO genere_film(ID_film, Nome_genere)
VALUES (1, 'Azione'),
       (1, 'Avventura'),
       (1, 'Fantascienza'),
       (2, 'Drammatico'),
       (3, 'Avventura'),
       (3, 'Drammatico'),
       (4, 'Fantascienza'),
       (4, 'Azione'),
       (5, 'Drammatico'),
       (6, 'Azione'),
       (6, 'Avventura'),
       (6, 'Fantascienza'),
       (7, 'Azione'),
       (7, 'Horror'),
       (7, 'Fantascienza'),
       (8, 'Avventura'),
       (8, 'Drammatico'),
       (9, 'Animazione'),
       (9, 'Avventura'),
       (9, 'Commedia'),
       (10, 'Drammatico'),
       (11, 'Azione'),
       (11, 'Avventura'),
       (11, 'Drammatico'),
       (12, 'Drammatico'),
       (13, 'Biografico'),
       (13, 'Drammatico'),
       (14, 'Avventura'),
       (14, 'Drammatico'),
       (14, 'Fantascienza'),
       (15, 'Drammatico'),
       (15, 'Azione'),
       (16, 'Drammatico'),
       (16, 'Azione'),
       (17, 'Avventura'),
       (17, 'Azione'),
       (17, 'Drammatico'),
       (17, 'Fantascienza');

INSERT INTO disponibilità(Piattaforma, Film, CC, SDH, AD, CostoAggiuntivo, Giorno_entrata, Giorno_uscita)
VALUES ('Netflix', 1, true, true, true, false, '2022-10-02', null),
       ('Amazon Prime', 2, false, true, false, true, '2022-04-20', null),
       ('TimVision', 2, false, true, false, false, '2022-12-20', null),
       ('Amazon Prime', 3, false, true, false, true, '2022-04-20', null),
       ('Rakuten', 3, false, true, false, true, '2022-04-20', null),
       ('NowTV', 3, false, true, false, true, '2022-04-20', null),
       ('Disney+', 4, true, true, false, false, '2022-06-02', null),
       ('TimVision', 4, true, true, false, false, '2022-06-02', null),
       ('Netflix', 5, true, true, false, false, '2022-06-02', null),
       ('TimVision', 5, true, true, false, false, '2022-06-02', null),
       ('Netflix', 6, true, true, false, false, '2022-06-02', null),
       ('Rakuten', 6, true, true, false, false, '2022-06-02', null),
       ('Amazon Prime', 6, true, true, false, false, '2022-06-02', null),
       ('Netflix', 7, true, true, false, false, '2022-06-02', null),
       ('Netflix', 8, true, true, false, false, '2022-06-02', null),
       ('NowTV', 9, true, true, false, false, '2022-06-02', null),
       ('Disney+', 10, true, true, false, false, '2022-06-02', null),
       ('Rakuten', 11, true, true, false, false, '2022-06-02', null),
       ('Netflix', 11, true, true, false, false, '2022-06-02', null),
       ('Amazon Prime', 12, true, true, false, false, '2022-06-02', null),
       ('Rakuten', 12, true, true, false, false, '2022-06-02', null),
       ('NowTV', 13, true, true, false, false, '2022-06-02', null),
       ('Disney+', 13, true, true, false, false, '2022-06-02', null),
       ('TimVision', 14, true, true, false, false, '2022-06-02', null),
       ('Netflix', 15, true, true, false, false, '2022-06-02', null),
       ('Disney+', 16, true, true, false, false, '2022-06-02', null),
       ('Amazon Prime', 17, true, true, false, false, '2022-06-02', null),
       ('Netflix', 17, true, true, false, false, '2022-06-02', null),
       ('TimVision', 17, true, true, false, false, '2022-06-02', null);

INSERT INTO categorizzazione(Film, Tema, Eta_pubblico, Livello, Mood, Riconoscimenti)
VALUES (1, 'Tema Strano e triste', 'T', 'demenziale', 'suspence', true),
       (2, 'Tema cattivo', 'T', 'medio', 'suspence', true),
       (3, 'Tema triste e strano', 'VM14', 'medio', 'comico', false),
       (4, 'Tema lellato', 'VM14', 'basso', 'sorprendente', false),
       (5, 'Tema barabbo e babbeo', 'T', 'basso', 'sentimentale', true),
       (6, 'Tema barabbo e babbeo', 'VM18', 'basso', 'comico', false),
       (7, 'Tema barabbo e babbeo', 'T', 'alto', 'sentimentale', true),
       (8, 'Tema barabbo e babbeo', 'VM14', 'medio', 'commovente', false),
       (9, 'Tema barabbo e babbeo', 'T', 'demenziale', 'sentimentale', false),
       (10, 'Tema barabbo e babbeo', 'T', 'medio', 'protesta', true),
       (11, 'Tema barabbo e babbeo', 'VM18', 'demenziale', 'commovente', true),
       (12, 'Tema barabbo e babbeo', 'T', 'alto', 'sentimentale', true),
       (13, 'Tema barabbo e babbeo', 'VM18', 'basso', 'trash', true),
       (14, 'Tema barabbo e babbeo', 'T', 'demenziale', 'suspence', false),
       (15, 'Tema barabbo e babbeo', 'VM18', 'medio', 'sorprendente', false),
       (16, 'Tema barabbo e babbeo', 'T', 'medio', 'comico', true),
       (17, 'Tema barabbo e babbeo', 'VM14', 'alto', 'commovente', true);


INSERT INTO `cast_film` (`Film`, `Attore`)
VALUES (1, 58),
       (1, 12),
       (1, 43),
       (1, 13),
       (1, 62),
       (1, 30),
       (1, 9),
       (1, 70),
       (2, 3),
       (2, 30),
       (2, 23),
       (2, 2),
       (2, 10),
       (2, 39),
       (3, 3),
       (3, 33),
       (3, 70);

INSERT INTO `regia` (`Film`, `Regista`)
VALUES (1, 77),
       (1, 78),
       (2, 79),
       (3, 80),
       (4, 81),
       (4, 82),
       (5, 83),
       (6, 84),
       (7, 85),
       (8, 86),
       (9, 87),
       (10, 88),
       (11, 85),
       (12, 89),
       (13, 90),
       (14, 91),
       (15, 92),
       (16, 93),
       (17, 94);


INSERT INTO `utente` (`Username`, `Email`, `Password`, `Data_nascita`, `foto_profilo`, `Permessi`)
VALUES (1, "admin", "admin", "19/05/1995", 112, "Admin"),
       (2, "user", "user", "19/05/2005", 113, "Utente");

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
