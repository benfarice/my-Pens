create table inventaire_table(
id int identity(1,1),
Numero varchar(50) primary key not null,
Date_ date not null,
Heure time ,
Superviseur int not null foreign key references vendeurs(idVendeur),
Depot int not null foreign key references depots(idDepot))
--drop table inventaire_table
GO
create table Detail_inventaire_table(
id_detail int not null primary key identity(1,1),
Numero varchar(50) not null 
foreign key references 
inventaire_table(Numero),
idArticle int not null 
foreign key references articles(IdArticle),
NBR_colisage float,
NBR_piece float,
stock_pda float
)
--drop table Detail_inventaire_table

GO


			_


			 ALTER TABLE inventaire_table
			 ADD  Etat int default 0

			ALTER TABLE inventaire_table
ALTER COLUMN date_ varchar(50);
GO
		ALTER TABLE inventaire_table
ALTER COLUMN Heure varchar(50);

ALTER TABLE inventaire_table
add  DateValid varchar(50);

	