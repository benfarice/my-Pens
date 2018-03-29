select a.idarticle,c.colisagee,a.designation DsgArticle,a.Reference RefArticle, g.IdGamme from articles a INNER JOIN colisages c ON a.IdArticle=c.idArticle INNER JOIN gammes g ON g.IdGamme=a.IdFamille INNER JOIN marques m ON m.IdMarque=g.IdMarque INNER JOIN sousfamilles sf on 
sf.idSousFamille=g.IdSousFamille INNER JOIN Familles fa ON sf.idFamille=fa.idFamille 
INNER JOIN detailMouvements dmo ON dmo.idArticle = a.idArticle inner join mouvements mo 
on dmo.idMouvement = mo.idMouvement where mo.idDepot= 5 group by a.idarticle ,c.colisagee,a.designation ,
a.Reference , fa.idFamille ,fa.Designation, fa.codeFamille ,sf.codeSousFamille , g.Reference , sf.idSousFamille , 
sf.Designation , g.IdGamme,g.Designation order by DsgArticle ASC


----------------------------------------------------------
select a.IdArticle,a.Designation,m.url as media, g.Designation as gamme,q.Designation as marque from 
articles a inner join media m on m.idArticle = a.IdArticle inner join gammes g on a.IdFamille = g.IdGamme 
inner join marques q on g.IdMarque = q.idMarque where a.Reference = '5321'
---------------------------------------------
SELECT * from articles WHERE reference LIKE '5900'

$qteEntreeGlobal=SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
					as QteEntree FROM detailMouvements dm
			INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
			INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=3296 AND m.type='entree' AND m.idDepot=1
---------------------------------------------

$$qteChargementGlobal=
SELECT isnull(sum(
				CASE 
					  WHEN  UniteVente='Colisage' THEN  (qte*c.colisagee)
					  WHEN  UniteVente='Pièce' THEN  (qte)
				END 
					),0)
				 as QteSortie FROM detailMouvements dm
				INNER JOIN mouvements m ON m.idMouvement = dm.idMouvement
				INNER JOIN colisages c  ON c.idArticle = dm.idArticle
			WHERE dm.idArticle=3296 AND m.type='sortie' and EtatSotie!=3 and EtatSotie=1 AND m.idDepot=1


			$qteDispo=$qteEntreeGlobal-$qteChargementGlobal;	
			$qteDispoEnBoite=$qteDispo/ $row['colisagee'];
			$qteDispoEnBPcs=$qteDispo % $row['colisagee']);