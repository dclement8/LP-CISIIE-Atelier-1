#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: evenement
#------------------------------------------------------------

CREATE TABLE evenement(
        id              int (11) Auto_increment  NOT NULL ,
        nom             Varchar (150) ,
        description     Varchar (1000) ,
        etat            Int ,
		dateheureLimiteInscription Datetime ,
        tarif                      Decimal (25,2) ,
        id_discipline   Int ,
        id_organisateur Int ,
        PRIMARY KEY (id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: discipline
#------------------------------------------------------------

CREATE TABLE discipline(
        id  int (11) Auto_increment  NOT NULL ,
        nom Varchar (200) ,
        PRIMARY KEY (id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: epreuve
#------------------------------------------------------------

CREATE TABLE epreuve(
        id           int (11) Auto_increment  NOT NULL ,
        nom          Varchar (200) ,
        distance     Int ,
        dateheure    Datetime ,
        id_evenement Int ,
        PRIMARY KEY (id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: participant
#------------------------------------------------------------

CREATE TABLE participant(
        id     int (11) Auto_increment  NOT NULL ,
        nom    Varchar (200) ,
        prenom Varchar (200) ,
        rue    Varchar (500) ,
        cp     Varchar (5) ,
        ville  Varchar (200) ,
        tel    Varchar (10) ,
        PRIMARY KEY (id )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: organisateur
#------------------------------------------------------------

CREATE TABLE organisateur(
        id      int (11) Auto_increment  NOT NULL ,
        login   Varchar (50) ,
        mdp     Varchar (500) ,
        nom     Varchar (50) ,
        prenom  Varchar (50) ,
        adresse Varchar (500) ,
        cp      Varchar (5) ,
        ville   Varchar (200) ,
        tel     Varchar (10) ,
        PRIMARY KEY (id ) ,
        UNIQUE (login )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: inscrit
#------------------------------------------------------------

CREATE TABLE inscrit(
        dossard    Int ,
        id_participant         Int NOT NULL ,
        id_epreuve Int NOT NULL ,
        PRIMARY KEY (id_participant ,id_epreuve )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: classer
#------------------------------------------------------------

CREATE TABLE classer(
        position       Int ,
        temps          Int ,
        id_epreuve             Int NOT NULL ,
        id_participant Int NOT NULL ,
        PRIMARY KEY (id_epreuve ,id_participant )
)ENGINE=InnoDB;

ALTER TABLE evenement ADD CONSTRAINT FK_evenement_id_discipline FOREIGN KEY (id_discipline) REFERENCES discipline(id);
ALTER TABLE evenement ADD CONSTRAINT FK_evenement_id_organisateur FOREIGN KEY (id_organisateur) REFERENCES organisateur(id);
ALTER TABLE epreuve ADD CONSTRAINT FK_epreuve_id_evenement FOREIGN KEY (id_evenement) REFERENCES evenement(id);
ALTER TABLE inscrit ADD CONSTRAINT FK_inscrit_id FOREIGN KEY (id_participant) REFERENCES participant(id);
ALTER TABLE inscrit ADD CONSTRAINT FK_inscrit_id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve(id);
ALTER TABLE classer ADD CONSTRAINT FK_classer_id FOREIGN KEY (id_epreuve) REFERENCES epreuve(id);
ALTER TABLE classer ADD CONSTRAINT FK_classer_id_participant FOREIGN KEY (id_participant) REFERENCES participant(id);
