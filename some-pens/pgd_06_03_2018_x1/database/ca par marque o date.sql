SELECT a.Reference, a.Designation  AS article,c.colisagee Colisage,
 isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0) AS Qte_Pcs,					
					sum(df.ttc) AS TTC 
FROM factures f 
	INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
	INNER JOIN articles a ON a.IdArticle=df.idArticle 
	INNER JOIN gammes g ON g.IdGamme=a.IdFamille
	INNER JOIN Sousfamilles s ON s.idSousFamille=g.IdSousFamille
	INNER JOIN familles fa ON fa.idFamille=s.idFamille
	INNER JOIN colisages c  ON c.idArticle = a.idArticle

where cast(f.date AS date)  between  convert(date, '01/02/2018', 105) and convert(date, '28/02/2018',105) 
AND g.IdMarque=18  AND f.idDepot=4   AND f.EtatCmd=2
GROUP BY fa.Designation,a.Reference, a.Designation ,c.colisagee 

