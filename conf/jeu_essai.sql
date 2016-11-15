/* Table organisateur */

INSERT INTO organisateur (login, mdp, nom, prenom, adresse, cp, ville, tel) 
VALUES ('AD-Bob', '$2y$10$fwYKrAIJuJx8QNVCNWB7NuEZLgwKZfT9ZziFUhrbjUdybBqRgIddW', 'Morris', 'Bob', '15 avenu de la Libération', '54000', 'Nancy', '03040506');

INSERT INTO organisateur (login, mdp, nom, prenom, adresse, cp, ville, tel) 
VALUES ('John', '$2y$10$fwYKrAIJuJx8QNVCNWB7NuEZLgwKZfT9ZziFUhrbjUdybBqRgIddW', 'Dupuis', 'John', '17 rue Paster', '54000', 'Nancy', '03040506');

INSERT INTO organisateur (login, mdp, nom, prenom, adresse, cp, ville, tel) 
VALUES ('M-Fab', '$2y$10$fwYKrAIJuJx8QNVCNWB7NuEZLgwKZfT9ZziFUhrbjUdybBqRgIddW', 'Maurice', 'Fabien', '12 avenu Foch', '54000', 'Maxéville', '03040506');

/* Table participant*/

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Phillippe', 'Michel', '18 rue Robert Ier', '54000', 'Nancy', '03040506');


INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('George', 'Denis', '19 rue Richert', '54000', 'Nancy', '03040506'); 


INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Weasley', 'Bob', '14 avenu Foch', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Pascal', 'Dylan', '2 impasse du théatre', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Weasley', 'Fred', '4 rue de la paix', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Fabien', 'Amine', '11 rue Roussaux', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('fleur', 'Lucie', '23 rue Jean Monnet', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Arthaud', 'Pierre', '78 impasse de la Brasserie', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('McCrey', 'Donald', '46 rue de la gare', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Weasley', 'Ron', '56 avenue de Paris', '54000', 'Nancy', '03040506');

INSERT INTO participant (nom, prenom, rue, cp, ville, tel)
VALUES ('Wayne', 'Bruce', '42 rue Jean Moulin', '54000', 'Nancy', '03040506');

/* Table discipline */

INSERT INTO discipline (nom)
VALUES ('Randonnée');

INSERT INTO discipline (nom)
VALUES ('Course');

INSERT INTO discipline (nom)
VALUES ('Tennis');

INSERT INTO discipline (nom)
VALUES ('VTT balisé');

INSERT INTO discipline (nom)
VALUES ('Marathon');

INSERT INTO discipline (nom)
VALUES ('Hockey');

/* Table evenement */

INSERT INTO evenement (nom, description, etat, dateheureLimiteInscription, tarif, id_discipline, id_organisateur)

INSERT INTO evenement (nom, description, etat, dateheureLimiteInscription, tarif, id_discipline, id_organisateur)

INSERT INTO evenement (nom, description, etat, dateheureLimiteInscription, tarif, id_discipline, id_organisateur)


/* Table épreuve */

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Marathon hivernal', 150, '2016-02-02 12:12:12', 1);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Tir à l\'arc hivernal', 30, '2016-02-02 12:12:12', 1);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Marathon hivernal', 150, '2016-02-02 12:12:12', 2);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Epreuve de Tennis', 200, '2016-04-02 12:12:12', 2);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Epreuve de course', 250, '2016-08-02 12:12:12', 3);

INSERT INTO epreuve (nom, distance, dateheure, id_evenement)
VALUES ('Epreuve de marche nordique', 250, '2016-08-02 12:12:12', 3);

/* Table inscrit */

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (1, 1, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (2, 2, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (3, 3, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (4, 4, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (5, 5, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (6, 6, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (7, 7, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (8, 8, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (9, 9, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (10, 10, 1);

INSERT INTO inscrit (dossard, id_participant, id_epreuve)
VALUES (11, 11, 1);



/* Table classer */

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (10, 6122800, 1, 1);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (11, 6482100, 1, 2);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (9, 2524500, 1, 3);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (1, 720700, 1, 4);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (2, 1261200, 1, 5);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (3, 1621500, 1, 6);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (7, 1624500, 1, 7);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (8, 1625600, 1, 8);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (6, 1624212, 1, 9);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (4, 1622115, 1, 10);

INSERT INTO classer (position, temps, id_epreuve, id_participant)
VALUES (5, 1622342, 1, 11);

