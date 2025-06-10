
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS event_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE event_manager;

-- Table Venue
CREATE TABLE IF NOT EXISTS venue (
  id_venue INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  type VARCHAR(100),
  adresse VARCHAR(255),
  url VARCHAR(255),
  photo VARCHAR(255)
);

-- Table Artiste
CREATE TABLE IF NOT EXISTS artiste (
  id_artiste INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  url VARCHAR(255),
  photo VARCHAR(255)
);

-- Table Evenement
CREATE TABLE IF NOT EXISTS evenement (
  id_evenement INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  description TEXT,
  date_heure DATETIME NOT NULL,
  prix DECIMAL(10,2),
  id_venue INT NOT NULL,
  id_artiste INT NOT NULL,
  photo VARCHAR(255),
  FOREIGN KEY (id_venue) REFERENCES venue(id_venue) ON DELETE CASCADE,
  FOREIGN KEY (id_artiste) REFERENCES artiste(id_artiste) ON DELETE CASCADE
);

-- Table Ticket
CREATE TABLE IF NOT EXISTS ticket (
  id_ticket INT AUTO_INCREMENT PRIMARY KEY,
  code_unique VARCHAR(100) NOT NULL UNIQUE,
  nom_complet VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  quantite INT NOT NULL,
  prix_personne DECIMAL(10,2) NOT NULL,
  prix_total DECIMAL(10,2) NOT NULL,
  date_reservation DATETIME NOT NULL,
  utilise BOOLEAN DEFAULT FALSE,
  id_evenement INT NOT NULL,
  FOREIGN KEY (id_evenement) REFERENCES evenement(id_evenement) ON DELETE CASCADE
);

-- Données initiales pour Venue
INSERT INTO venue (nom, type, adresse, url, photo) VALUES
('Le Botanique', 'Salle de concert', 'Rue Royale 236, 1210 Bruxelles', 'https://botanique.be', 'botanique.jpg'),
('Tour & Taxis', 'Centre événementiel', 'Avenue du Port 86C, 1000 Bruxelles', 'https://tourtaxis.com', 'tourtaxis.jpg'),
('Bozar', 'Centre des Beaux-Arts', 'Rue Ravenstein 23, 1000 Bruxelles', 'https://bozar.be', 'bozar.jpg'),
('Théâtre Mercelis', 'Théâtre', 'Chaussée de Waterloo 510, 1050 Ixelles', 'https://mercelis.be', 'mercelis.jpg'),
('Ancienne Belgique', 'Salle de concert', 'Boulevard Anspach 110, 1000 Bruxelles', 'https://abconcerts.be', 'ab.jpg');

-- Données initiales pour Artiste
INSERT INTO artiste (nom, url, photo) VALUES
('The Rolling Stones', 'https://rollingstones.com', 'stones.jpg'),
('Daft Punk', 'https://daftpunk.com', 'daftpunk.jpg'),
('London Symphony Orchestra', 'https://lso.co.uk', 'lso.jpg'),
('Impro League', 'https://improleague.be', 'impro.jpg'),
('Coldplay', 'https://coldplay.com', 'coldplay.jpg'),
('David Guetta', 'https://davidguetta.com', 'guetta.jpg'),
('Beyoncé', 'https://www.beyonce.com', 'beyonce.jpg'),
('Adele', 'https://www.adele.com', 'adele.jpg'),
('Stromae', 'https://www.stromae.net', 'stromae.jpg'),
('Orelsan', 'https://orelsan.lnk.to', 'orelsan.jpg'),
('Arctic Monkeys', 'https://www.arcticmonkeys.com', 'arcticmonkeys.jpg'),
('Juliette Armanet', 'https://www.juliettearmanet.com', 'armanet.jpg'),
('Rosalía', 'https://rosalia.com', 'rosalia.jpg'),
('Billie Eilish', 'https://www.billieeilish.com', 'billie.jpg'),
('Nekfeu', 'https://nekfeu.fr', 'nekfeu.jpg'),
('Lous and The Yakuza', 'https://www.lousandtheyakuza.com', 'lous.jpg');

-- Données pour Evenement
INSERT INTO evenement (titre, description, date_heure, prix, id_venue, id_artiste, photo) VALUES
('Soirée Rock', 'Une soirée rock inoubliable avec les Rolling Stones', '2025-06-21 20:00:00', 15.00, 1, 1, 'stones_event.jpg'),
('Festival Électro', 'Festival de musique électronique avec Daft Punk', '2025-07-15 18:00:00', 25.00, 2, 2, 'daftpunk_event.jpg'),
('Concert Classique', 'Concert de musique classique par le LSO', '2025-08-03 19:30:00', 18.00, 3, 3, 'lso_event.jpg'),
('Impro Show', 'Spectacle d''improvisation théâtrale', '2025-06-28 21:00:00', 12.00, 4, 4, 'impro_event.jpg'),
('Coldplay Live', 'Concert exceptionnel de Coldplay', '2025-09-10 20:30:00', 35.00, 5, 5, 'coldplay_event.jpg'),
('David Guetta Set', 'Set DJ de David Guetta', '2025-07-22 22:00:00', 22.00, 2, 6, 'guetta_event.jpg'),
('Beyoncé World Tour', 'La reine de la pop au Botanique', '2025-09-25 20:00:00', 80.00, 1, 7, 'beyonce_concert.jpg'),
('Adele Intime', 'Concert acoustique unique d''Adele à Bozar', '2025-10-12 19:00:00', 75.00, 3, 8, 'adele_live.jpg'),
('Stromae en live', 'Retour sur scène très attendu de Stromae', '2025-08-30 21:00:00', 65.00, 2, 9, 'stromae_show.jpg'),
('Orelsan Flow Show', 'Rimes percutantes et show visuel', '2025-11-05 20:30:00', 55.00, 5, 10, 'orelsan_live.jpg'),
('Arctic Monkeys Tour', 'Rock britannique envoûtant', '2025-10-20 20:00:00', 60.00, 1, 11, 'arctic.jpg'),
('Juliette Armanet – Piano Club', 'Chansons d''amour et ambiance feutrée', '2025-12-01 19:30:00', 40.00, 4, 12, 'armanet_piano.jpg'),
('Rosalía – Motomami Tour', 'Fusion flamenco et pop', '2025-11-15 21:00:00', 65.00, 5, 13, 'rosalia_motomami.jpg'),
('Billie Eilish Experience', 'Ambiance intimiste et visuelle', '2025-10-25 20:00:00', 70.00, 3, 14, 'billie_concert.jpg'),
('Nekfeu – Feu Tour', 'Rap français engagé et musicalité', '2025-09-28 21:00:00', 50.00, 2, 15, 'nekfeu_feu.jpg'),
('Lous and The Yakuza Live', 'Pop urbaine et mise en scène artistique', '2025-08-20 20:00:00', 45.00, 1, 16, 'lous_show.jpg');

