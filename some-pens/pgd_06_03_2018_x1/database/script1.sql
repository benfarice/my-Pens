hALTER TABLE Avance
add  Etat int;

ALTER TABLE depots ADD IdVille int;

ALTER TABLE factures ADD EtatCmd int;

UPDATE vendeurs
SET
	idDepot=2 WHERE idVendeur=1019 OR  idVendeur=1018

ALTER table detailMouvements ADD IdFacture int;
ALTER table detailMouvements ADD EtatSotie int;
ALTER table mouvements ADD IdFacture int;
ALTER table detailMouvements ADD UniteVente varchar(50);
DELETE  FROM marques WHERE idMarque NOT in( 17,18,1017);
select * from articles where Reference like 'TL050'
update detailMouvements 
				set pa=10 where idArticle=(select idArticle FROM  articles where Reference LIKE 'a000112')


				SELECT df.*,co.colisagee,f.totalTTC  FROM detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture
inner join colisages co on co.idArticle=df.idArticle	
   ORDER BY f.IdFacture desc




