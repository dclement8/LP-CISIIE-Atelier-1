/* Table organisateur */

INSERT INTO organisateur (login, mdp, nom, prenom, adresse, cp, ville, tel) 
VALUES ('ADB', '$2y$10$fwYKrAIJuJx8QNVCNWB7NuEZLgwKZfT9ZziFUhrbjUdybBqRgIddW', 'Morris', 'Bob', '15 rue de l\alimentation Advance', '54000', 'Advance City', '03040506');

INSERT INTO organisateur (login, mdp, nom, prenom, adresse, cp, ville, tel) 
VALUES ('Bobybob', '$2y$10$fwYKrAIJuJx8QNVCNWB7NuEZLgwKZfT9ZziFUhrbjUdybBqRgIddW', 'Morris', 'John', '17 avenu du bracelet antistatique', '54000', 'banc de test City', '03040506');

INSERT INTO organisateur (login, mdp, nom, prenom, adresse, cp, ville, tel) 
VALUES ('ElectronikEart', '$2y$10$fwYKrAIJuJx8QNVCNWB7NuEZLgwKZfT9ZziFUhrbjUdybBqRgIddW', 'Fabien', 'Fabien', '12 impasse du téléphone chinois', '54000', 'AMD City', '03040506');

/* Table evenement */

INSERT INTO evenement (nom, description, etat, dateheureLimiteInscription, tarif, id_discipline, id_organisateur)
VALUES ('Course Pastile Vichy', 'Course avec des heuuuu', 1, '2016-02-02 12:12:12', 78, 1, 2);

INSERT INTO evenement (nom, description, etat, dateheureLimiteInscription, tarif, id_discipline, id_organisateur)
VALUES ('Randoné dans la forêt HTML', '<bien></bien>', 1, '2016-02-02 12:12:12', 999, 2, 1);

INSERT INTO evenement (nom, description, etat, dateheureLimiteInscription, tarif, id_discipline, id_organisateur)
VALUES ('truc', 'heuuuu', 1, '2016-02-02 12:12:12', 78, 1, 2);

/* Table discipline */

INSERT INTO discipline (nom)
VALUES ('Tire de Yahoo');

INSERT INTO discipline (nom)
VALUES ('Course de PHP');

INSERT INTO discipline (nom)
VALUES ('Sky Lapin');

/* Table participant*/

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Phillippe', 'Michel', '18 rue Robert Ier', '54000', 'Nancy', '03040506');


INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('George', 'Denis', '18 rue Robert IIer', '54000', 'Nancy', '03040506'); 


INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Weasley', 'Bob', '18 rue Robert IIIer', '54000', 'Nancy', '03040506');

/* Table épreuve */

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Epreuve truc', 150, '2016-02-02 12:12:12', 1);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Epreuve machin', 200, '2016-04-02 12:12:12', 1);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Epreuve chose', 250, '2016-08-02 12:12:12', 1);

/* Table inscrit */

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (1, 1, 1);


INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (2, 2, 1);


INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (3, 3, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (1, 1, 2);


INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (2, 2, 2);


INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (3, 3, 2);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (1, 1, 3);


INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (2, 2, 3);


INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (3, 3, 3);

/* Table classer */

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (2, 233, 1, 2);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (3, 512, 1, 1);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (1, 54, 1, 3);