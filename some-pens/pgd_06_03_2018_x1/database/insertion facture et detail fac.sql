
INSERT INTO [dbo].[factures]
           ([numFacture]
           ,[date]
           ,[heure]
           ,[idClient]
           ,[idVendeur]
           ,[etat]
           ,[visite]
           ,[idDepot]
           ,[totalHT]
           ,[totalTVA]
           ,[totalTTC]
           ,[reste]
           ,[TypeVente]
           ,[DateLivraison]
           ,[Observation]
           ,[Espece]
           ,[Cheque]
           ,[Credit]
           ,[PhotoCheque]
           ,[EtatCmd])
     VALUES
           ('NF1700262'	,'2017-11-29','11:35:48'	,5234,	1030	,'NR'	,8655,	5,	NULL	,NULL	,
 608	,0,	2,'2017-11-29',NULL	,NULL	,NULL,	NULL	,NULL	,2	)

GO



INSERT INTO [dbo].[detailFactures]
           ([idFacture]
           ,[idArticle]
           ,[type]
           ,[qte]
           ,[tarif]
           ,[idDepot]
           ,[idFiche]
           ,[tauxTVA]
           ,[ht]
           ,[tva]
           ,[ttc]
           ,[UniteVente])
     VALUES
           (7504
           ,3238
           ,NULL
           ,2
           ,	12.00
           ,5
           ,4037
           ,NULL
           ,NULL
           ,NULL
           ,24.00
           ,'Pièce')	
GO

INSERT INTO [dbo].[detailFactures]
           ([idFacture]
           ,[idArticle]
           ,[type]
           ,[qte]
           ,[tarif]
           ,[idDepot]
           ,[idFiche]
           ,[tauxTVA]
           ,[ht]
           ,[tva]
           ,[ttc]
           ,[UniteVente])
     VALUES
           (7504
           ,3175
           ,NULL
           ,2
           ,	160.00
           ,5
           ,4037
           ,NULL
           ,NULL
           ,NULL
           ,320.00
           ,'Colisage')	
GO
	
	
INSERT INTO [dbo].[detailFactures]
           ([idFacture]
           ,[idArticle]
           ,[type]
           ,[qte]
           ,[tarif]
           ,[idDepot]
           ,[idFiche]
           ,[tauxTVA]
           ,[ht]
           ,[tva]
           ,[ttc]
           ,[UniteVente])
     VALUES
           (7504
           ,3262
           ,NULL
           ,1
           ,	264.00
           ,5
           ,4037
           ,NULL
           ,NULL
           ,NULL
           ,264.00
           ,'Colisage')	
GO
	