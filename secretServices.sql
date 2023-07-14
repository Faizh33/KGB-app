/* Création de la Base de données */
CREATE DATABASE SecretService;

/* Selection de la Base de données */
USE SecretService;

/* Création des tables */
CREATE TABLE CountriesNationalities 
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    country VARCHAR(50),
    nationality VARCHAR(60)
);

CREATE TABLE Persons 
(
    id CHAR(36) PRIMARY KEY NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    firstName VARCHAR(100) NOT NULL,
    birthDate DATE NOT NULL,
    countrynationality_id INT NOT NULL,
    FOREIGN KEY (countrynationality_id) REFERENCES CountriesNationalities(id) ON DELETE CASCADE
);

CREATE TABLE Specialities
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    speciality VARCHAR(150) NOT NULL
);

CREATE TABLE Agents 
(
    id CHAR(36) PRIMARY KEY NOT NULL,
    identification_code VARCHAR(20) NOT NULL UNIQUE,
    FOREIGN KEY (id) REFERENCES Persons(id) ON DELETE CASCADE
);

CREATE TABLE Agents_Specialities
(
    agent_id CHAR(36) NOT NULL,
    speciality_id INT NOT NULL,
    PRIMARY KEY(agent_id, speciality_id),
    FOREIGN KEY(agent_id) REFERENCES Agents(id) ON DELETE CASCADE,
    FOREIGN KEY(speciality_id) REFERENCES Specialities(id) ON DELETE CASCADE
);

CREATE TABLE Contacts 
(
    id CHAR(36) PRIMARY KEY NOT NULL,
    code_name VARCHAR(50) NOT NULL UNIQUE,
    FOREIGN KEY (id) REFERENCES Persons(id) ON DELETE CASCADE
);

CREATE TABLE Targets 
(
    id CHAR(36) PRIMARY KEY NOT NULL,
    code_name VARCHAR(50) NOT NULL UNIQUE,
    FOREIGN KEY (id) REFERENCES Persons(id) ON DELETE CASCADE
);


CREATE TABLE MissionStatuses
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    status VARCHAR(150) NOT NULL
);

CREATE TABLE MissionTypes
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    type VARCHAR(150) NOT NULL
);

CREATE TABLE SafeHouses
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    code VARCHAR(10) NOT NULL UNIQUE,
    address VARCHAR(350) NOT NULL,
    type VARCHAR(100) NOT NULL,
    countrynationality_id INT NOT NULL,
    FOREIGN KEY (countrynationality_id) REFERENCES CountriesNationalities(id) ON DELETE CASCADE
);

CREATE TABLE Missions
(
    id CHAR(36) PRIMARY KEY NOT NULL,
    title VARCHAR(250) NOT NULL,
    description TEXT(500) NOT NULL,
    codeName VARCHAR(250) NOT NULL UNIQUE,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    countrynationality_id INT NOT NULL,
    speciality_id INT NOT NULL,
    missionstatuses_id INT NOT NULL,
    missiontype_id INT NOT NULL,
    FOREIGN KEY (countrynationality_id) REFERENCES CountriesNationalities(id) ON DELETE CASCADE,
    FOREIGN KEY (speciality_id) REFERENCES Specialities(id) ON DELETE CASCADE,
    FOREIGN KEY (missionstatuses_id) REFERENCES MissionStatuses(id) ON DELETE CASCADE,
    FOREIGN KEY (missiontype_id) REFERENCES MissionTypes(id) ON DELETE CASCADE
);

CREATE TABLE Missions_agents 
(
    mission_id CHAR(36) NOT NULL,
    agent_id CHAR(36) NOT NULL,
    PRIMARY KEY (mission_id, agent_id),
    FOREIGN KEY (mission_id) REFERENCES Missions(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES Agents(id) ON DELETE CASCADE
);

CREATE TABLE Missions_contacts 
(
    mission_id CHAR(36) NOT NULL,
    contact_id CHAR(36) NOT NULL,
    PRIMARY KEY (mission_id, contact_id),
    FOREIGN KEY (mission_id) REFERENCES Missions(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES Contacts(id) ON DELETE CASCADE
);

CREATE TABLE Missions_targets 
(
    mission_id CHAR(36) NOT NULL,
    target_id CHAR(36) NOT NULL,
    PRIMARY KEY (mission_id, target_id),
    FOREIGN KEY (mission_id) REFERENCES Missions(id) ON DELETE CASCADE,
    FOREIGN KEY (target_id) REFERENCES Targets(id) ON DELETE CASCADE
);

CREATE TABLE Missions_safehouses
(
    mission_id CHAR(36) NOT NULL,
    safehouse_id INT NOT NULL,
    PRIMARY KEY (mission_id, safehouse_id),
    FOREIGN KEY (mission_id) REFERENCES Missions(id) ON DELETE CASCADE,
    FOREIGN KEY (safehouse_id) REFERENCES SafeHouses(id) ON DELETE CASCADE
);

CREATE TABLE Admins 
(
    id CHAR(36) PRIMARY KEY NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    firstName VARCHAR(100) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(128) NOT NULL,
    creationDate DATE NOT NULL
);

/* Insertion des données */
INSERT INTO CountriesNationalities (country, nationality)
VALUES
    ('France', 'Français(e)'),
    ('Angleterre', 'Britannique'),
    ('Espagne', 'Espagnol(e)'),
    ('Allemagne', 'Allemand(e)'),
    ('Pologne', 'Polonais(e)'),
    ('Etats-Unis', 'Américain(e)');

INSERT INTO Persons
VALUES
    ('9473ba58-10c9-11ee-8afc-0a0027000006', 'Bouchard', 'Gabriel', '1989-10-13', 1),
    ('9473c24b-10c9-11ee-8afc-0a0027000006', 'Porter', 'Ryan', '2001-07-28', 2),
    ('9473c3ae-10c9-11ee-8afc-0a0027000006', 'Arce', 'Adelfo', '1995-10-23', 3),
    ('9473c485-10c9-11ee-8afc-0a0027000006', 'Fruehauf', 'Laura', '1985-11-15', 4),
    ('9473c549-10c9-11ee-8afc-0a0027000006', 'Sawicka', 'Wiktoria', '1982-03-26', 5),
    ('9473c611-10c9-11ee-8afc-0a0027000006', 'Boone', 'Vivian', '1977-05-23', 6),
    ('9473c6d0-10c9-11ee-8afc-0a0027000006', 'Bourgeau', 'Arthur', '1993-06-02', 1),
    ('9473c78d-10c9-11ee-8afc-0a0027000006', 'Holland', 'Kyle', '1970-01-30', 2),
    ('9473c84a-10c9-11ee-8afc-0a0027000006', 'Osorio', 'Marino', '1978-11-06', 3),
    ('9473c90b-10c9-11ee-8afc-0a0027000006', 'Weissmuller', 'Barbara', '1987-04-17', 4),
    ('9473c9da-10c9-11ee-8afc-0a0027000006', 'Pawlowska', 'Sylwia', '1994-10-12', 5),
    ('9474843d-10c9-11ee-8afc-0a0027000006', 'Moore', 'Sarah', '1990-09-04', 6),
    ('947485ec-10c9-11ee-8afc-0a0027000006', 'Lachapelle', 'Anna', '1986-08-17', 1),
    ('9474866c-10c9-11ee-8afc-0a0027000006', 'Lough', 'Amelie', '1987-09-19', 2),
    ('947486dd-10c9-11ee-8afc-0a0027000006', 'Lujan', 'Catrin', '1976-06-05', 3),
    ('94748747-10c9-11ee-8afc-0a0027000006', 'Werfel', 'Thomas', '1983-05-24', 4),
    ('947487b4-10c9-11ee-8afc-0a0027000006', 'Maciejewski', 'Jedrzej', '1987-02-12', 5),
    ('94748821-10c9-11ee-8afc-0a0027000006', 'Bates', 'Nathan', '1968-12-06', 6);

INSERT INTO Specialities (speciality)
VALUES 
    ('Cybersécurité'),
    ('Espionnage industriel'),
    ('Lutte antiterroriste'),
    ('Traduction en langues rares'),
    ('Déminage'),
    ('Arts martiaux'),
    ('Cryptographie'),
    ("Technique d'infiltration");

INSERT INTO Agents 
VALUES
    ('9473ba58-10c9-11ee-8afc-0a0027000006', '8740551947665429'),
    ('9473c24b-10c9-11ee-8afc-0a0027000006', '0246649519785547'),
    ('9473c3ae-10c9-11ee-8afc-0a0027000006', '4596780449165275'),
    ('9473c485-10c9-11ee-8afc-0a0027000006', '4852941746069557'),
    ('9473c549-10c9-11ee-8afc-0a0027000006', '5254769718464059'),
    ('9473c611-10c9-11ee-8afc-0a0027000006', '1527546749089456');

INSERT INTO Agents_Specialities
VALUES 
    ('9473ba58-10c9-11ee-8afc-0a0027000006', 1),
    ('9473ba58-10c9-11ee-8afc-0a0027000006', 7),
    ('9473c24b-10c9-11ee-8afc-0a0027000006', 2),
    ('9473c3ae-10c9-11ee-8afc-0a0027000006', 8),
    ('9473c3ae-10c9-11ee-8afc-0a0027000006', 3),
    ('9473c485-10c9-11ee-8afc-0a0027000006', 5),
    ('9473c485-10c9-11ee-8afc-0a0027000006', 1),
    ('9473c485-10c9-11ee-8afc-0a0027000006', 3),
    ('9473c549-10c9-11ee-8afc-0a0027000006', 4),
    ('9473c611-10c9-11ee-8afc-0a0027000006', 6),
    ('9473c611-10c9-11ee-8afc-0a0027000006', 2);

INSERT INTO Contacts
VALUES 
    ('9473c6d0-10c9-11ee-8afc-0a0027000006', 'Wavy Citadel'),
    ('9473c78d-10c9-11ee-8afc-0a0027000006', 'Muted Alpha'),
    ('9473c84a-10c9-11ee-8afc-0a0027000006', 'Corrupt Willow'),
    ('9473c90b-10c9-11ee-8afc-0a0027000006', 'Plastic Dragonfly'),
    ('9473c9da-10c9-11ee-8afc-0a0027000006', 'Classic Shadow'),
    ('9474843d-10c9-11ee-8afc-0a0027000006', 'General Widow');

INSERT INTO Targets
VALUES 
    ('947485ec-10c9-11ee-8afc-0a0027000006', 'Glass Author'),
    ('9474866c-10c9-11ee-8afc-0a0027000006', 'Yellow Nighthawk'),
    ('947486dd-10c9-11ee-8afc-0a0027000006', 'Last Wolf'),
    ('94748747-10c9-11ee-8afc-0a0027000006', 'Numb Dynamo'),
    ('947487b4-10c9-11ee-8afc-0a0027000006', 'General Prodigy'),
    ('94748821-10c9-11ee-8afc-0a0027000006', 'Candid Bear');

INSERT INTO MissionStatuses (status)
VALUES
    ('En préparation'),
    ('En cours'),
    ('Terminé'),
    ('Echec');

INSERT INTO MissionTypes (type)
VALUES
    ('Surveillance'),
    ('Assassinat'),
    ('Infiltration'),
    ('Contre-espionnage'),
    ('Piratage'),
    ('Sécurisation');

INSERT INTO SafeHouses (code, address, type, countrynationality_id)
VALUES
    ('21jo2rx0c9', '88 Place de la Madeleine 75009 PARIS', 'appartement', 1),
    ('2ojx1rc029', '86 Victoria Road LITTLE BADDOW CM3 8PT', 'maison', 2),
    ('rj0x2o129c', 'Avda. Explanada Barnuevo, 82 35430 Firgas', 'villa', 3),
    ('22x9cj0or1', 'Ziegelstr. 95 94143 Grainet', 'cabane', 4),
    ('r9x20o1j2c', 'ul. Grzybowska 117 00-132 Warszawa', 'hotel', 5),
    ('1jro902cx2', '1079 Deer Haven Drive Greenville SC 29607', 'maison', 6);

INSERT INTO Missions
VALUES
    (
        '61d25a65-10cc-11ee-8afc-0a0027000006', 
        'Opération Œil de Lynx', 
        "L'agence déploie deux agents pour infiltrer un réseau criminel international basé en Allemagne. Leur mission consiste à surveiller discrètement les mouvements des chefs de cartel pendant une réunion secrète. Avec une oreillette équipée d'une technologie de pointe, les agents doivent recueillir des preuves cruciales pour démanteler le réseau et garantir la sécurité mondiale.",
        'Project Shadowwatch',
        '2023-07-07',
        '2023-07-22',
        4,
        8,
        1,
        3
    ),
    (
        '61d26910-10cc-11ee-8afc-0a0027000006',
        'Opération Éclipse Mortelle',
        "Un dictateur brutal menace la stabilité de la Pologne en proie à la terreur. L'agence envoie son assassin le plus redoutable pour éliminer la menace. L'agent doit se faufiler à travers un dédale de gardes et de pièges dans le palais fortifié du dictateur, pour atteindre la salle du trône où il se tient pendant un discours crucial. Le temps est compté et chaque mouvement doit être calculé avec précision pour assurer le succès de la mission.",
        'Operation Phantom Strike',
        '2023-05-28',
        '2023-06-01',
        5,
        6,
        3,
        2
    ),
    (
        '61d27115-10cc-11ee-8afc-0a0027000006',
        'Opération CyberNexus',
        " Une organisation criminelle britannique utilise une plateforme de cryptomonnaie décentralisée pour financer ses opérations illégales. L'agence assigne son expert en piratage informatique pour infiltrer le système et neutraliser leurs opérations. L'agent doit contourner les pare-feu sophistiqués et les défenses de sécurité pour accéder aux comptes des criminels. Avec chaque seconde qui s'écoule, l'agence se rapproche de l'identité des cerveaux derrière cette organisation.",
        'Project CyberVortex',
        '2023-06-30',
        '2023-07-30',
        2,
        1,
        2,
        5
    );

INSERT INTO Missions_agents
VALUES
    ('61d25a65-10cc-11ee-8afc-0a0027000006', '9473c3ae-10c9-11ee-8afc-0a0027000006'),
    ('61d25a65-10cc-11ee-8afc-0a0027000006', '9473c485-10c9-11ee-8afc-0a0027000006'),
    ('61d26910-10cc-11ee-8afc-0a0027000006', '9473c611-10c9-11ee-8afc-0a0027000006'),
    ('61d27115-10cc-11ee-8afc-0a0027000006', '9473c485-10c9-11ee-8afc-0a0027000006');

INSERT INTO Missions_contacts
VALUES
    ('61d25a65-10cc-11ee-8afc-0a0027000006', '9473c90b-10c9-11ee-8afc-0a0027000006'),
    ('61d26910-10cc-11ee-8afc-0a0027000006', '9473c9da-10c9-11ee-8afc-0a0027000006'),
    ('61d27115-10cc-11ee-8afc-0a0027000006', '9473c78d-10c9-11ee-8afc-0a0027000006');

INSERT INTO Missions_targets
VALUES 
    ('61d25a65-10cc-11ee-8afc-0a0027000006', '947485ec-10c9-11ee-8afc-0a0027000006'),
    ('61d26910-10cc-11ee-8afc-0a0027000006', '947487b4-10c9-11ee-8afc-0a0027000006'),
    ('61d27115-10cc-11ee-8afc-0a0027000006', '9474866c-10c9-11ee-8afc-0a0027000006');

INSERT INTO Missions_safehouses (mission_id, safehouse_id)
VALUES
    ('61d25a65-10cc-11ee-8afc-0a0027000006', 4),
    ('61d26910-10cc-11ee-8afc-0a0027000006', 5);

INSERT INTO Admins
VALUES 
    ('7203ffdd-10dd-11ee-8afc-0a0027000006', 'Clavet', 'Christian', 'christian.clavet@kgb.fr', 'G8km7s4]-UTR7[{k', '2023-06-22'),
    ('7204086f-10dd-11ee-8afc-0a0027000006', 'Gosselin', 'Arlette', 'arlette.gosselin@kgb.fr', '3Zn}w#6ya37GQ(', '2023-06-23');