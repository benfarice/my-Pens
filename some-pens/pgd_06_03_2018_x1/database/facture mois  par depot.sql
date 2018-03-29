SELECT count(numFacture)
FROM factures f 

where cast(f.date AS date)  between  convert(date, '01/01/2018', 105) and convert(date, '31/01/2018',105) 
  AND f.idDepot=2  AND f.EtatCmd=2


SELECT * FROM detailFactures df WHERE df.idArticle=3108 AND df.idDepot=4

SELECT * FROM articles a WHERE a.Reference LIKE 'tl017'

SELECT count(c.IdClient) FROM clients c WHERE c.idDepot=2 AND 
 cast(c.date_create AS date)  between  convert(date, '01/01/2018', 105) and convert(date, '31/01/2018',105) 

