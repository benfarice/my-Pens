SELECT a.Reference,dit.idArticle,dit.NBR_colisage,dit.NBR_piece FROM Detail_inventaire_table dit
INNER JOIN inventaire_table it ON it.numero = dit.Numero
INNER JOIN articles a ON a.IdArticle = dit.idArticle
WHERE it.Numero LIKE 'IN1800008'  
and dit.idArticle not in(
		 SELECT  dm.idArticle FROM detailMouvements dm 
		 INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
		 WHERE dm.idDepot=3 AND m.type='entree' 
)ORDER BY idArticle


INSERT INTO detailMouvements
(
    --iddetailMouvement - this column value is auto-generated
    idMouvement,
    idArticle,
    type,
    qte,
    pa,
    idDepot,
    IdFacture,
    EtatSotie,
    UniteVente
)
VALUES
(
    -- iddetailMouvement - int
    6544, -- idMouvement - int
    0, -- idArticle - int
    N'', -- type - nvarchar
    0, -- qte - int
    0, -- pa - decimal
    0, -- idDepot - int
    0, -- IdFacture - int
    0, -- EtatSotie - int
    '' -- UniteVente - varchar
)
