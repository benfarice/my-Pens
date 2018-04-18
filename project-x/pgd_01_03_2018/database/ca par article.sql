SELECT a.IdArticle,a.Reference,a.Designation,sum(df.ttc) AS Total FROM factures f 
				INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
				INNER JOIN articles a ON df.idArticle=a.IdArticle
				where cast(f.date AS date) between  convert(date,'01/02/2018',105) and convert(date,'28/02/2018',105)
				AND  f.iddepot=3 AND F.EtatCmd=2
				GROUP by a.IdArticle,a.Reference,a.Designation ORDER BY  Total DESC


SELECT		a.IdArticle,a.Reference,a.Designation,sum(df.ttc) AS Total FROM factures f 
				INNER JOIN detailFactures df ON f.IdFacture=df.idFacture
				INNER JOIN articles a ON df.idArticle=a.IdArticle
				where cast(f.date AS date) between  convert(date,'01/02/2018',105) and convert(date,'28/02/2018',105)
				AND  f.iddepot=3 AND F.EtatCmd=2
				GROUP by a.IdArticle,a.Reference,a.Designation ORDER BY  Total DESC