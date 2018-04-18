create table camions
(
id int primary key identity(1,1) not null,
marque varchar(200),
matricule  varchar(200),
designation  varchar(200),
tare float,
etat int 
)


--drop table camions

create table chauffeurs
(
id int primary key identity(1,1) not null,
nom varchar(200),
matricule  varchar(200),
cin varchar(200),
date_emb date,
etat int 
)
--drop table chauffeurs

create table activite
(
id int primary key identity(1,1) not null,
journee date,
id_chauffeur int foreign key references chauffeurs(id),
id_camion int foreign key references camions(id),
heure_depart datetime,
heurre_fin datetime,
km_depart float,
kimometrage_fin float
)
--drop table activite

create table voyages
(
id int primary key identity(1,1) not null,
id_activite int foreign key references activite(id),
pesee float,
heure datetime
)

--drop table voyages

create table carburant
(
id int primary key identity(1,1) not null,
id_activite int foreign key references activite(id),
litres float,
prix_u float,
total float,
kilometrage float
)

--drop table carburant
