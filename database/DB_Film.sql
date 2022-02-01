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
    Attori         VARCHAR(500) NOT NULL DEFAULT '',
    Registi         VARCHAR(500) NOT NULL DEFAULT '',
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
    PRIMARY KEY (Piattaforma, Film),
    FOREIGN KEY (Piattaforma) REFERENCES piattaforma (Nome) ON DELETE CASCADE,
    FOREIGN KEY (Film) REFERENCES film (ID) ON DELETE CASCADE
);

CREATE TABLE categorizzazione
(
    Film           INT          NOT NULL,
    Eta_pubblico   ENUM ('T', 'VM14', 'VM18') NOT NULL,
    Livello        ENUM ('demenziale','basso','medio', 'alto') NOT NULL,
    Mood           ENUM ('suspense', 'protesta', 'commovente', 'trash','divertente','ottimista', 'sorprendente') NOT NULL,
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
    Permessi     ENUM ('Admin', 'Utente') DEFAULT NULL,
    PRIMARY KEY (Username),
    CONSTRAINT Email_unique UNIQUE (Email)
);

CREATE TABLE valutazione
(
    Utente           INT          NOT NULL,
    ID_film          INT          NOT NULL,
    Commento         VARCHAR(512) NOT NULL,
    Data_inserimento TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Stelle           INT          NOT NULL,
    PRIMARY KEY (Utente, ID_Film),
    FOREIGN KEY (Utente) REFERENCES utente (Username),
    FOREIGN KEY (ID_film) REFERENCES film (ID) ON DELETE CASCADE
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
    FOREIGN KEY (ID_film) REFERENCES film (ID) ON DELETE CASCADE
);

INSERT INTO `piattaforma` (`Nome`)
VALUES ('Netflix');
INSERT INTO `piattaforma` (`Nome`)
VALUES ('Prime Video');
INSERT INTO `piattaforma` (`Nome`)
VALUES ('Disney+');
INSERT INTO `piattaforma` (`Nome`)
VALUES ('Discovery+');

INSERT INTO `lista_generi` (Nome)
VALUES ('Animazione');
INSERT INTO `lista_generi` (Nome)
VALUES ('Avventura');
INSERT INTO `lista_generi` (Nome)
VALUES ('Azione');
INSERT INTO `lista_generi` (Nome)
VALUES ('Commedia');
INSERT INTO `lista_generi` (Nome)
VALUES ('Drammatico');
INSERT INTO `lista_generi` (Nome)
VALUES ('Fantascienza');
INSERT INTO `lista_generi` (Nome)
VALUES ('Horror');

INSERT INTO immagini(ID, Descrizione, Percorso)
VALUES (1, 'Foto 1', 'film/Avengers_endgame.jpg'),
       (2, 'Foto 2', 'film/Freaks_out.jpg'),
       (3, 'Foto 3', 'film/Il_re_leone.jpg'),
       (4, 'Foto 4', 'film/Matrix_reloaded.jpg'),
       (5, 'Foto 5', 'film/Nomadland.jpg'),
       (6, 'Foto 6', 'film/Spider-Man_No_Way_Home.jpg'),
       (7, 'Foto 7', 'film/Alien.jpg'),
       (8, 'Foto 8', 'film/Bastardi_senza_gloria.jpg'),
       (9, 'Foto 9', 'film/Encanto.jpg'),
       (10, 'Foto 10', 'film/Fight_club.jpg'),
       (11, 'Foto 11', 'film/Il_gladiatore.jpg'),
       (12, 'Foto 12', 'film/Il_padrino.jpg'),
       (13, 'Foto 13', 'film/Il_pianista.jpg'),
       (14, 'Foto 14', 'film/Interstellar.jpg'),
       (15, 'Foto 15', 'film/Joker.jpg'),
       (16, 'Foto 16', 'film/The_departed.jpg'),
       (17, 'Foto 17', 'film/Dune.jpg'),
       (18, 'Adrien Brody', 'attori/Adrien_Brody.jpg'),
       (19, 'Al Pacino', 'attori/Al_Pacino.jpg'),
       (20, 'Anne Hathaway', 'attori/Anne_Hathaway.jpg'),
       (21, 'Annika Wedderkopp', 'attori/Annika_Wedderkopp.jpg'),
       (22, 'Aurora Giovinazzo', 'attori/Aurora_Giovinazzo.jpg'),
       (23, 'Benedict Cumberbatch', 'attori/Benedict_Cumberbatch.jpg'),
       (24, 'Beyoncé', 'attori/Beyoncé.jpg'),
       (25, 'Brad Pitt', 'attori/Brad_Pitt.jpg'),
       (26, 'Brie Larson', 'attori/Brie_Larson.jpg'),
       (27, 'Carrie-Anne Moss', 'attori/Carrie-Anne_Moss.jpg'),
       (28, 'Chiwetel Ejiofor', 'attori/Chiwetel_Ejiofor.jpg'),
       (29, 'Chris Evans', 'attori/Chris_Evans.jpg'),
       (30, 'Chris Hemsworth', 'attori/Chris_Hemsworth.jpg'),
       (31, 'Christoph Waltz', 'attori/Christoph_Waltz.jpg'),
       (32, 'Claudio Santamaria', 'attori/Claudio_Santamaria.jpg'),
       (33, 'Connie Nielsen', 'attori/Connie_Nielsen.jpg'),
       (34, 'Diane Keaton', 'attori/Diane_Keaton.jpg'),
       (35, 'Donald Glover', 'attori/Donald_Glover.jpg'),
       (36, 'Edward Norton', 'attori/Edward_Norton.jpg'),
       (37, 'Eli Roth', 'attori/Eli_Roth.jpg'),
       (38, 'Emilia Fox', 'attori/Emilia_Fox.jpg'),
       (39, 'Frances Conroy', 'attori/Frances_Conroy.jpg'),
       (40, 'Frank Finlay', 'attori/Frank_Finlay.jpg'),
       (41, '', 'attori/No_image.jpg'),
       (42, 'Giorgio Cantarini', 'attori/Giorgio_Cantarini.jpg'),
       (43, 'Hugo Weaving', 'attori/Hugo_Weaving.jpg'),
       (44, 'Jack Nicholson', 'attori/Jack_Nicholson.jpg'),
       (45, 'Jacob Batalon', 'attori/Jacob_Batalon.jpg'),
       (46, 'James Caan', 'attori/James_Caan.jpg'),
       (47, 'Jason Momoa', 'attori/Jason_Momoa.jpg'),
       (48, 'Jeremy Renner', 'attori/Jeremy_Renner.jpg'),
       (49, 'Jessica Chastain', 'attori/Jessica_Chastain.jpg'),
       (50, 'Jessica Darrow', 'attori/Jessica_Darrow.jpg'),
       (51, 'Joaquin Phoenix', 'attori/Joaquin_Phoenix.jpg'),
       (52, 'John Hurt', 'attori/John_Hurt.jpg'),
       (53, 'John Leguizamo', 'attori/John_Leguizamo.jpg'),
       (54, 'John Oliver', 'attori/John_Oliver.jpg'),
       (55, 'Keanu Reeves', 'attori/Keanu_Reeves.jpg'),
       (56, 'Laurence Fishburne', 'attori/Laurence_Fishburne.jpg'),
       (57, 'Leonardo DiCaprio', 'attori/Leonardo_DiCaprio.jpg'),
       (58, 'Mackenzie Foy', 'attori/Mackenzie_Foy.jpg'),
       (59, 'Mads Mikkelsen', 'attori/Mads_Mikkelsen.jpg'),
       (60, 'María Cecilia Botero', 'attori/María_Cecilia_Botero.jpg'),
       (61, 'Mark Ruffalo', 'attori/Mark_Ruffalo.jpg'),
       (62, 'Mark Wahlberg', 'attori/Mark_Wahlberg.jpg'),
       (63, 'Marlon Brando', 'attori/Marlon_Brando.jpg'),
       (64, 'Martin Sheen', 'attori/Martin_Sheen.jpg'),
       (65, 'Matt Damon', 'attori/Matt_Damon.jpg'),
       (66, 'Matthew McConaughey', 'attori/Matthew_McConaughey.jpg'),
       (67, 'Mauro Castillo', 'attori/Mauro_Castillo.jpg'),
       (68, 'Meat Loaf', 'attori/Meat_Loaf.jpg'),
       (69, 'Mélanie Laurent', 'attori/Mélanie_Laurent.jpg'),
       (70, 'Nicoletta Braschi', 'attori/Nicoletta_Braschi.jpg'),
       (71, 'Oliver Reed', 'attori/Oliver_Reed.jpg'),
       (72, 'Oscar Isaac', 'attori/Oscar_Isaac.jpg'),
       (73, 'Pietro Castellitto', 'attori/Pietro_Castellitto.jpg'),
       (74, 'Rebecca Ferguson', 'attori/Rebecca_Ferguson.jpg'),
       (75, 'Robert De Niro', 'attori/Robert_De_Niro.jpg'),
       (76, 'Robert Downey Jr.', 'attori/Robert_Downey_Jr..jpg'),
       (77, 'Robert Duvall', 'attori/Robert_Duvall.jpg'),
       (78, 'Roberto Benigni', 'attori/Roberto_Benigni.jpg'),
       (79, 'Russell Crowe', 'attori/Russell_Crowe.jpg'),
       (80, 'Scarlett Johansson', 'attori/Scarlett_Johansson.jpg'),
       (81, 'Seth Rogen', 'attori/Seth_Rogen.jpg'),
       (82, 'Sigourney Weaver', 'attori/Sigourney_Weaver.jpg'),
       (83, 'Stephanie Beatriz', 'attori/Stephanie_Beatriz.jpg'),
       (84, 'Susse Wold', 'attori/Susse_Wold.jpg'),
       (85, 'Thomas Bo Larsen', 'attori/Thomas_Bo_Larsen.jpg'),
       (86, 'Thomas Kretschmann', 'attori/Thomas_Kretschmann.jpg'),
       (87, 'Timothée Chalamet', 'attori/Timothée_Chalamet.jpg'),
       (88, 'Tom Holland', 'attori/Tom_Holland.jpg'),
       (89, 'Tom Skerritt', 'attori/Tom_Skerritt.jpg'),
       (90, 'Veronica Cartwright', 'attori/Veronica_Cartwright.jpg'),
       (91, 'Zach Grenier', 'attori/Zach_Grenier.jpg'),
       (92, 'Zazie Beetz', 'attori/Zazie_Beetz.jpg'),
       (93, 'Zendaya', 'attori/Zendaya.jpg'),
       (94, 'Anthony Russo', 'attori/Anthony_Russo.jpg'),
       (95, 'Joe Russo', 'attori/Joe_Russo.jpg'),
       (96, 'Gabriele Mainetti', 'attori/Gabriele_Mainetti.jpg'),
       (97, 'Jon Favreau', 'attori/Jon_Favreau.jpg'),
       (98, 'Lilly Wachowski', 'attori/Lilly_Wachowski.jpg'),
       (99, 'Lana Wachowski', 'attori/Lana_Wachowski.jpg'),
       (100, 'Chloé Zhao', 'attori/Chloé_Zhao.jpg'),
       (101, 'Jon Watts', 'attori/Jon_Watts.jpg'),
       (102, 'Ridley Scott', 'attori/Ridley_Scott.jpg'),
       (103, 'Quentin Tarantino', 'attori/Quentin_Tarantino.jpg'),
       (104, 'Jared Bush', 'attori/Jared_Bush.jpg'),
       (105, 'David Fincher', 'attori/David_Fincher.jpg'),
       (106, 'Francis Ford Coppola', 'attori/Francis_Ford_Coppola.jpg'),
       (107, 'Roman Polanski', 'attori/Roman_Polanski.jpg'),
       (108, 'Christopher Nolan', 'attori/Christopher_Nolan.jpg'),
       (109, 'Todd Phillips', 'attori/Todd_Phillips.jpg'),
       (110, 'Martin Scorsese', 'attori/Martin_Scorsese.jpg'),
       (111, 'Denis Villeneuve', 'attori/Denis_Villeneuve.jpg'),
       (112, '', 'film/no_image.png');


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
       (13, 'Azione'),
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

INSERT INTO disponibilità(Piattaforma, Film, CC, SDH, AD)
VALUES ('Netflix', 1, true, true, true),
       ('Prime Video', 2, false, true, false),
       ('Discovery+', 2, false, true, false),
       ('Prime Video', 3, false, true, false),
       ('Netflix', 3, false, true, false),
       ('Disney+', 3, false, true, false),
       ('Disney+', 4, true, true, false),
       ('Discovery+', 4, true, true, false),
       ('Discovery+', 5, true, true, false),
       ('Netflix', 5, true, true, false),
       ('Netflix', 6, true, true, false),
       ('Disney+', 6, true, true, false),
       ('Prime Video', 6, true, true, false),
       ('Netflix', 7, true, true, false),
       ('Netflix', 8, true, true, false),
       ('Discovery+', 9, true, true, false),
       ('Disney+', 10, true, true, false),
       ('Disney+', 11, true, true, false),
       ('Netflix', 11, true, true, false),
       ('Prime Video', 12, true, true, false),
       ('Disney+', 12, true, true, false),
       ('Discovery+', 13, true, true, false),
       ('Disney+', 13, true, true, false),
       ('Discovery+', 14, true, true, false),
       ('Netflix', 15, true, true, false),
       ('Disney+', 16, true, true, false),
       ('Prime Video', 17, true, true, false),
       ('Netflix', 17, true, true, false),
       ('Discovery+', 17, true, true, false);

INSERT INTO categorizzazione(Film, Eta_pubblico, Livello, Mood)
VALUES (1, 'T', 'demenziale', 'suspense'),
       (2, 'T', 'medio', 'suspense'),
       (3, 'VM14', 'medio', 'divertente'),
       (4, 'VM18', 'basso', 'sorprendente'),
       (5, 'T', 'basso', 'ottimista'),
       (6, 'VM18', 'basso', 'divertente'),
       (7, 'T', 'alto', 'ottimista'),
       (8, 'VM14', 'medio', 'commovente'),
       (9, 'T', 'demenziale', 'ottimista'),
       (10, 'T', 'medio', 'protesta'),
       (11, 'VM18', 'demenziale', 'commovente'),
       (12, 'T', 'alto', 'ottimista'),
       (13, 'VM18', 'basso', 'trash'),
       (14, 'T', 'demenziale', 'suspense'),
       (15, 'VM18', 'medio', 'sorprendente'),
       (16, 'T', 'medio', 'divertente'),
       (17, 'VM14', 'alto', 'commovente');


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
       (3, 23),
       (3, 17),
       (3, 19),
       (3, 40),
       (3, 54),
       (4, 76),
       (4, 13),
       (4, 12),
       (4, 5),
       (5, 7),
       (5, 81),
       (5, 9),
       (5, 19),
       (5, 23),
       (5, 25),
       (6, 27),
       (6, 29),
       (6, 34),
       (6, 36),
       (6, 37),
       (7, 39),
       (7, 10),
       (7, 43),
       (8, 46),
       (8, 49),
       (8, 41),
       (8, 50),
       (8, 52),
       (8, 54),
       (9, 59),
       (9, 57),
       (9, 60),
       (9, 61),
       (10, 54),
       (10, 66),
       (10, 69),
       (10, 80),
       (10, 77),
       (10, 75),
       (11, 53),
       (11, 5),
       (11, 60),
       (11, 61),
       (12, 78),
       (12, 25),
       (12, 64),
       (12, 67),
       (12, 74),
       (12, 60),
       (12, 57),
       (13, 83),
       (13, 85),
       (13, 88),
       (13, 16),
       (14, 54),
       (14, 57),
       (14, 72),
       (14, 67),
       (14, 44),
       (14, 49),
       (14, 52),
       (14, 36),
       (15, 60),
       (15, 67),
       (15, 54),
       (15, 33),
       (15, 35),
       (15, 39),
       (16, 71),
       (16, 30),
       (16, 27),
       (16, 18),
       (16, 75),
       (16, 83),
       (16, 57),
       (17, 37),
       (17, 40),
       (17, 43),
       (17, 63),
       (17, 70),
       (17, 9);

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

INSERT INTO `utente` (`Username`, `Email`, `Password`, `Data_nascita`, `Permessi`)
VALUES (1, 'admin','admin', '1995/05/19', 'Admin'),
       (2, 'user', 'user', '1998/05/05', 'Utente');

INSERT INTO `scheda_utente` (`Utente`, `ID_Film`, `Visto`, `Salvato`, `Suggerito`)
VALUES (1, 1, true, true, true),
       (1, 3, true, true, true),
       (1, 14, true, true, true),
       (1, 17, true, true, true),
       (2, 4, true, true, true),
       (2, 7, true, true, true);

INSERT INTO `valutazione` (`Utente`, `ID_film`, `Commento`, `Data_inserimento`, `Stelle`)
VALUES (1, 1, 'Film incredibile, gli avengers sono i miei supereroi preferiti :)', current_timestamp(), 5),
       (1, 3, 'Cartone della mia infanzia, iper consigliato', current_timestamp(), 3),
       (1, 14, 'Spettacolare film di fantascienza, regista come sempre superlativo', current_timestamp(), 2),
       (1, 17, 'Cast stellare, film che vi lascera a bocca aperta, dal primo all ultimo minuto', current_timestamp(), 4),
       (2, 4, 'Secondo episodio della trilogia. Che dire, non vi lascerà indifferenti', current_timestamp(), 1),
       (2, 7, 'Horror non adatto ai deboli di cuore, consiglaito vederlo al buio :)))', current_timestamp(), 4);
