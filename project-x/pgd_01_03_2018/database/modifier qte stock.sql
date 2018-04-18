SELECT * FROM detailMouvements dm 
INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
WHERE dm.idArticle=(SELECT a.idArticle FROM 
articles a WHERE a.Reference LIKE '4828')
AND dm.idDepot=3 AND m.type='entree'


SELECT * FROM detailMouvements dm 
INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
WHERE m.reference ='NE1800072'

SELECT * FROM mouvements m
UPDATE  detailMouvements
SET
    qte=11 WHERE iddetailMouvement=4944


UPDATE  detailMouvements
SET
   UniteVente='Colisage' WHERE iddetailMouvement=5997

   SELECT * 