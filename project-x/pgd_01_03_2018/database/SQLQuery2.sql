		UPDATE detailFactures SET tarif=	11.00	 WHERE IddetailFacture=	7775
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8353
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8463
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8748
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8753
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8784
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8797
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8799
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8820
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8822
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8823
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8833
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8840
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8854
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8863
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8864
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8891
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8893
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8915
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8916
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8928
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8934
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8935
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8938
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8974
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	8976
		UPDATE detailFactures SET tarif=	21.50	 WHERE IddetailFacture=	8989
		UPDATE detailFactures SET tarif=	30.00	 WHERE IddetailFacture=	8990
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	9017
		UPDATE detailFactures SET tarif=	10.50	 WHERE IddetailFacture=	9023
		UPDATE detailFactures SET tarif=	9.00	 WHERE IddetailFacture=	7513
		UPDATE detailFactures SET tarif=	12.50	 WHERE IddetailFacture=	8275
		
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


DELETE FROM detailFactures WHERE idFacture=(
SELECT idFacture FROM factures f WHERE cast(f.date AS date)
 < convert(date,'01/12/2017',105)  AND f.IdFacture=detailFactures.idFacture) 

 delete  FROM factures WHERE cast(date AS date)
 < convert(date,'01/12/2017',105)  