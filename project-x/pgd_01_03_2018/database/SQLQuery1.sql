SELECT df.idArticle,df.IddetailFacture,df.tarif as 'prix vente', tt.pvHT,df.idFacture,df.idFiche,f.[date] 
FROM detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture  
INNER JOIN tarifs tt ON  tt.idArticle = df.idArticle
WHERE 
df.idFiche=4037 AND   tt.idFiche=4037 and
df.tarif <>(SELECT t.pvHT FROM tarifs t  WHERE t.idFiche=4037 AND df.idArticle= t.idArticle)

SELECT * FROM detailFactures WHERE detailFactures.idFacture= 7333

SELECT df.idArticle,df.IddetailFacture,df.tarif as 'prix vente', tt.pvHT,df.idFacture,df.idFiche,f.[date] 
FROM detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture  
INNER JOIN tarifs tt ON  tt.idArticle = df.idArticle
WHERE 
df.idFiche=4036 AND   tt.idFiche=4036 and
df.tarif <>(SELECT t.pvHT FROM tarifs t  WHERE t.idFiche=4036 AND df.idArticle= t.idArticle)

UPDATE detailFactures SET tarif=21.50 WHERE IddetailFacture=


SELECT f.idVendeur, df.tarif FROM detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture   

  WHERE f.idVendeur =1030 idArticle=3301 AND idFiche=4037 or idFiche=4036
SELECT * FROM TARIFS  WHERE TARIFS.idArticle=3194
SELECT * FROM articles a WHERE idArticle=3301
DELETE FROM detailFactures WHERE IdArticle<2103
SELECT * FROM vendeurs v

SELECT df.idFacture,df.tarif as 'prix vente',df.qte,co.colisagee,df.UniteVente,df.ttc,f.[date]
FROM detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture  
inner join colisages co on co.idArticle=df.idArticle		
WHERE 
df.idFiche=4036 AND df.ttc <> (df.tarif*df.qte)  AND df.UniteVente='Pièce'
AND f.idDepot=2

SELECT * FROM detailFactures dµ


SELECT df.*,co.colisagee,f.totalTTC  FROM detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture
inner join colisages co on co.idArticle=df.idArticle	
   ORDER BY f.IdFacture DESC
   SELECT  * FROM articles WHERE articles.Reference LIKE '5932'
    
   SELECT * FROM detailFactures df WHERE df.idArticle=3166 AND df.idDepot=3

   SELECT SUM(df.tarif*df.qte*co.colisagee) FROM detailFactures df
   	inner join colisages co on co.idArticle=df.idArticle	
	 WHERE df.idArticle=3166 AND df.idDepot=3

   select SUM(
					CASE 
					   WHEN  df.UniteVente='Pièce' THEN df.tarif*df.qte
					   else  df.tarif*df.qte*co.colisagee
					END)
					FROM factures f INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
					inner join colisages co on co.idArticle=df.idArticle					
					WHERE df.idArticle=3166  and EtatCmd=2 and f.idDepot=3
					go

SELECT TOP 20 * FROM ( select sum(f.totalTTC) AS Total 
FROM factures f INNER JOIN vendeurs v ON f.idVendeur=v.idVendeur  GROUP BY v.idDepot) as tab ORDER BY Total DESC 

SELECT * FROM  detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture where cast(f.date AS date)= convert(date,'05/12/2017',105)
and EtatCmd=2 and f.idDepot=5

SELECT df.*, (
					CASE 
					   WHEN  df.UniteVente='Pièce' THEN df.tarif*df.qte
					   else  df.tarif*df.qte*co.colisagee
					END)
					FROM factures f INNER JOIN detailFactures df ON f.IdFacture=df.idFacture 
					inner join colisages co on co.idArticle=df.idArticle	
					WHERE cast(f.date AS date)= convert(date,'05/12/2017',105)				
				 and EtatCmd=2 and f.idDepot=5
				 ORDER BY f.IdFacture

SELECT df.*,(df.ttc) AS total FROM  detailFactures df
INNER JOIN factures  f ON f.IdFacture=df.idFacture where cast(f.date AS date)= convert(date,'05/12/2017',105)
and EtatCmd=2 and f.idDepot=5 	 ORDER BY f.IdFacture

SELECT * FROM colisages c WHERE c.idArticle=3155

SELECT * FROM factures f WHERE f.IdFacture=7423