  DELETE FROM mouvements WHERE idDepot=6 AND type LIKE 'Sortie'
 DELETE FROM detailMouvements WHERE idDepot=6 AND idMouvement NOT IN
		(SELECT idMouvement FROM mouvements )

		DELETE  FROM factures  WHERE idDepot=6
		DELETE FROM detailfactures WHERE idDepot=6 AND idFacture NOT IN
		(SELECT IdFacture FROM factures )
		DELETE FROM Avance WHERE IdDepot=6
		DELETE FROM avoir_client WHERE IdDepot=6

		SELECT * FROM avoir_client ac WHERE ac.IdDepot=5
		SELECT * FROM vendeurs v WHERE v.idVendeur=1030



		  DELETE FROM mouvements WHERE idDepot=5 AND type LIKE 'Sortie'
 DELETE FROM detailMouvements WHERE idDepot=5 AND idMouvement NOT IN
		(SELECT idMouvement FROM mouvements )

		DELETE  FROM factures  WHERE idDepot=5
		DELETE FROM detailfactures WHERE idDepot=5 AND idFacture NOT IN
		(SELECT IdFacture FROM factures )
		DELETE FROM Avance WHERE IdDepot=5
		DELETE FROM avoir_client WHERE IdDepot=5


		SELECT * FROM mouvements m WHERE m.idDepot=6