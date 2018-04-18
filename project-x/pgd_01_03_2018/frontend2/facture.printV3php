<?php 
require_once('../connexion.php');
session_start();
include("lang.php");
include("../php.fonctions.php");
$IdDepot=$_SESSION['IdDepot'];
$sql = "select  

			c.CodeClient CodeClt,c.nom+' '+c.prenom Client,c.adresse AdresseClt,c.IdClient,
			c.intitule CompanyClt,vi.Designation VilleClt,c.cp CodePostaleClt,
			c.tel TelClt,			
			v.nom+' '+v.prenom Vendeur,v.adresse AdresseVdr,v.idvendeur IdVdr,v.telephone TelVdr,		
		   f.[date] AS 'DateFac',
		   f.numfacture  NumFac,
		   dr.mode ModePaiement,
		   r.DateEcheance,
		   f.DateLivraison,
		   f.Observation,
		   	a.Reference,      
			a.idarticle IdArticle,
			a.Designation DsgArticle,
			df.UniteVente,
			df.qte Qte,
			df.tarif PrixUnite,
			df.ttc TotalUnite,

			dr.numCheque NumCheque,
			dr.numVersement NumVersement,			
			d.adresse AdresseDepot,d.Designation DsgDepot,
			d.Email EmailDepot,d.Tel TelDepot,d.SiteWeb SiteDepot,d.Fax FaxDepot,
			d.CodePostal CodePostaleDepot,co.colisagee Colisage
		from 
				factures f 
				inner join detailfactures df on df.idfacture=f.idfacture
				inner join reglementFactures rf ON  rf.idFacture=f.IdFacture
				inner join reglements  r on r.idReglement=rf.idReglement
				inner join detailReglements dr  on dr.IdReglement=rf.idReglement			
				inner join clients c on c.idclient=f.idclient
				INNER JOIN villes vi ON vi.idville=c.ville			
				inner join vendeurs v on v.idvendeur=f.idvendeur
				
				inner join articles a on a.IdArticle=df.idarticle
					inner join colisages co on co.idarticle=a.idarticle
				inner join depots d on d.idDepot=a.idDepot
				
		
		where 
		f.idfacture=? and a.idDepot=?
			 ";
	//echo $sql ;
		 $params = array($_GET['IdFacture'],$IdDepot);	
	
		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									RETURN;
								}

		$ntRes = sqlsrv_num_rows($stmt);
		//echo $sql;
			$nRes = sqlsrv_num_rows($stmt);	

				if($nRes==0)
				{ ?>
							<div class="resAff" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
							
								<?php echo $trad['msg']['AucunResultat'] ;?>
							</div>
							<?php
							return;
				}
		else
		{	
						$groups = array();
								$i=0;
								$TotalFac=0;
			 while($row=sqlsrv_fetch_array($stmt)){							 
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$key = $row['IdClient'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdClient']=$row['IdClient'];
											$groups[$key]['Client']=$row['Client'];
											$groups[$key]['CodeClt']=$row['CodeClt'];
											$groups[$key]['CompanyClt']=$row['CompanyClt'];
											$groups[$key]['AdresseClt']=$row['AdresseClt'];
											$groups[$key]['VilleClt']=$row['VilleClt'];
											$groups[$key]['CodePostaleClt']=$row['CodePostaleClt'];
											$groups[$key]['TelClt']=$row['TelClt'];
											$groups[$key]['Vendeur']=$row['Vendeur'];
											$groups[$key]['AdresseVdr']=$row['AdresseVdr'];
											$groups[$key]['IdVdr']=$row['IdVdr'];
											$date=date_create($row['DateFac']);
											$groups[$key]['DateFac']=date_format($date, 'd/m/Y');
											
											$groups[$key]['DateFac']=$row['DateFac'];
											$groups[$key]['NumFac']=$row['NumFac'];
											$groups[$key]['ModePaiement']=$row['ModePaiement'];
											$groups[$key]['DateEcheance']=$row['DateEcheance'];
											$groups[$key]['DateLivraison']=$row['DateLivraison'];
											$groups[$key]['Observation']=$row['Observation'];
											$groups[$key]['NumCheque']=$row['NumCheque'];
											$groups[$key]['numVersement']=$row['NumVersement'];
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['DsgDepot']=$row['DsgDepot'];
											
											$groups[$key]['EmailDepot']=$row['EmailDepot'];
											$groups[$key]['TelDepot']=$row['TelDepot'];
											$groups[$key]['SiteDepot']=$row['SiteDepot'];
											$groups[$key]['FaxDepot']=$row['FaxDepot'];
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['CodePostaleDepot']=$row['CodePostaleDepot'];
												
									
										} 
									//	echo "<br>---".$i."---<br>";
												$groups[$key][$i]['Colisage'] = $row['Colisage'];
											$groups[$key][$i]['IdArticle'] = $row['IdArticle'];
											$groups[$key][$i]['DsgArticle'] = $row['DsgArticle'];	$groups[$key][$i]['Reference'] =$row['Reference'];								
											$groups[$key][$i]['UniteVente'] =$row['UniteVente'];
											$groups[$key][$i]['Qte'] =$row['Qte'];
											$groups[$key][$i]['PrixUnite'] =$row['PrixUnite'];
											$groups[$key][$i]['TotalUnite'] =
											$row['Qte']*$row['PrixUnite']* $row['Colisage'];
									
									
										$TotalFac+=$row['TotalUnite']* $row['Colisage'];
			 }
		}
	//	parcourir($groups);return;		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/catalogue.css" rel="stylesheet" />


<style media="screen" type="text/css">
#page,#piedPage{	border-left:1px solid #778;border-right:1px solid #778; -moz-box-shadow:0px 0px 20px #666;}
.style1 {font-size: 12pt}

.HeadLeft{
	float:left;
	width:50%;
}
.HeadRight{
	float:right;
	width:45%;
}
#page{
	
	font-size:12px;
}
.TrSuper{
	background:#376091;color:#fff;font-weight:bold;font-size:14px;
}
table.Table1 {
    border-collapse: collapse;
}
table.Table1 th,table.TableFac th{
background:#376091;
color:#fff;font-size:14px;
}
.TitleNote{
background:#376091;
color:#fff;
padding:5px 15px;
font-weight:bold;
font-size:16px;
}
table.Table1, table.Table1 th,table.Table1 td ,table.tableHead td,table.TableTotal td {
    border: 1px solid #ccc;
}
table.tableHead td{
	padding:5px 10px;
}
.boite{
	text-align:center;
	width:90%;
	margin:0 auto;
	padding:10px;
	line-height:20px;
}
.nbr{
	unicode-bidi: bidi-override;direction: ltr;
}
</style>
</head>

<body>
  <input type="button" onclick=" fermer() " value="" class="Fermer" style="display:none;"/>
<div id="page"> 
	<?php foreach($groups as $v){ ?>
<div class="head">

	<div class="HeadLeft">
	<h1>GLOBAL MANUFACTURERS ,Technolab </h1><BR><BR>
	<img src="img/logo.png" /><br><BR><BR>
	</div>
	<div class="HeadRight">
			<h1>Facture</h1><BR>
			<table border="0" width="100%" cellpadding="2" class="tableHead">
				<tr><th align="left">Date:	</th><td>	<?php 
				$newDate = date("M d, Y", strtotime($v["DateFac"]));
				echo $newDate; ?>	</td></tr>
				<tr><th align="left">Facture #:		</th><td><?php echo $v["NumFac"]; ?>	</td></tr>
				<tr><th align="left">ID client:	</th><td>	<?php echo $v["CodeClt"]; ?>	</td></tr>
				<tr><th align="left">Commande #:	</th><td>		</td></tr>
				<tr><th align="left">Paiement dû par:	</th><td>	<?php
				$newDate = date("M d, Y", strtotime($v["DateEcheance"]));
				echo $newDate;
				?>		</td></tr>
			</table>			
	</div>
	<div class="clear"></div>
		<div class="HeadLeft">
				<table border="0" cellpadding="3" width="100%">
				<tr class="TrSuper"><td colspan="3">Facturer / العميل : 	</td></tr>
				<tr><td align="left" valign="top">[Nom]		</td><td>	</td></tr>
				<tr><td valign="top">[Nom dépôt]	</td>
				<td valign="top">	<?php echo $v["DsgDepot"]; ?></td></tr>
				<tr><td valign="top">[ Address]	</td><td>	<?php echo $v["AdresseDepot"]; ?>	</td></tr>
				<tr><td valign="top">[Ville, Code postal]</td>
				<td valign="top">	<?php echo $v["VilleClt"]." ".$v["CodePostaleDepot"]; ?>	</td></tr>	
				<tr><td valign="top">[Tél]			</td><td valign="top">	<?php echo $v["TelDepot"]; ?></td></tr>

			</table>
		</div>
		<div class="HeadRight">
				<table width="100%">
				<tr class="TrSuper"><td colspan="2" width="50%" valign="top">Expédier à  / العميل :	</td></tr>
				<tr><td align="left " valign="top">[Nom du client]	</td><td valign="top"><?php echo $v["Client"]; ?>	</td></tr>
				<tr><td valign="top">[Entreprise]	</td><td valign="top"><?php echo $v["CompanyClt"]; ?>	</td></tr>
				<tr><td valign="top">[Adresse]	</td><td valign="top">	<?php echo $v["AdresseClt"]; ?>	</td></tr>
				<tr><td valign="top">[Ville, Code postal]	</td><td valign="top">	<?php echo $v["VilleClt"]." ".$v["CodePostaleClt"];  ?>	</td></tr>	
				<tr><td valign="top">[Tél]</td><td valign="top"><?php echo $v["TelClt"]; ?>	</td></tr>
		
			</table>
		</div>
		</div>
		<div class="clear"></div>
		<p>
		<table width="100%" class="Table1" cellpadding="4">
		<tr style="font-weight:bold;">
		  <th>Vendeur / المندوب	</th>
		  <th></th>
		  <th></th>
		  <th> COND Paiement / شروط الدفع 	</th>
		  <th>Date d'échéance / تاريخ الاستحقاق	</th>
		  <th>Livraison / التسليم	</th>
		</tr>
			<tr>
		  <td><?php echo $v["Vendeur"]; ?>	</td>
		    <td></td>
		  <td></td>
		  <td align="center"><?php echo $v["ModePaiement"]; ?>	</td>
		  <td align="center"><?php
				$newDate = date("M d, Y", strtotime($v["DateEcheance"]));
				echo $newDate;
				?>	</td>
		  <td align="center"><?php 
		  $newDate = date("M d, Y", strtotime($v["DateLivraison"]));
				echo $newDate;
		  ?>	</td>
		
		</tr>
		</table>
		</p>
			<table width="100%" class="Table1" cellpadding="4">
		<tr style="font-weight:bold;">
		  <th>Réf/كود	</th>
		  <th>Description/الوصف</th>
		  <th>Unité/الوحدة</th>
		  <th>Colisage	</th>
		  <th>Qty/كمية</th>
		  <th>Prix unitair/سعر البيع	</th>
		   
		  <th>Total / إجمالي	</th>  
		</tr>
		<?php foreach($v as $r){
			if(is_array($r)){ ?>
			<tr>
		  <td><?php echo $r["Reference"]; ?></td>
		    <td><?php echo $r["DsgArticle"]; ?></td>
		  <td><?php
		if($r["UniteVente"]=="Palette") echo "Palete / طبلية";
		if($r["UniteVente"]=="Box") echo "Boîte / علبة";
		if($r["UniteVente"]=="Colisage") echo "Colisage / حزمة";?>
	</td>
		<td align="right">
				  <span class="nbr">
					<?php echo $r["Colisage"]; ?></span>
					</td>
		  <td align="right">
		  <span class="nbr">
					<?php echo number_format($r["Qte"], 0, '.', ' '); ?></span>
					</td>
		  <td align="right">
		  <span class="nbr">
					<?php echo number_format($r["PrixUnite"], 2, '.', ' '); ?></span>
				</td>
			
		  <td align="right">
		  <span class="nbr">
					<?php echo number_format($r["TotalUnite"], 2, '.', ' '); ?></span>
				</td>
		
		</tr>
	<?php } }?>
		</table>
		<br>
			<div class="HeadLeft" style="border:1px solid #ccc;width:450px; min-height:100px;">
				<div class="TitleNote">
				Termes et notes / شروط وملاحظات 
				</div><div class="clear"></div>
				<div style="line-height:20px; padding:8px 5px"
				><?php echo $v["Observation"]; ?>
				</div>
			</div>
	
		<div class=" HeadRight"  style="width:300px;">
		
			<table width="100%" class="TableTotal" cellpadding="4">
				
				<tr ><th align="left">Sous-total/إجمالي</th><td>DH</td><td align="right" width="50%">
				
					  <span class="nbr">
					<?php echo number_format($TotalFac, 2, '.', ' '); ?></span>
					
				</td></tr>
				<tr ><th align="left">VAT Rate :ض ق م </th><td>%</td><td align="right">0.00</td></tr>
				<tr ><th align="left">قيمة ض ق م </th><td>DH</td><td></td></tr>
				<tr ><th align="left"></th><td></td><td></td></tr>
				<tr ><th align="left">Remise / خصم</th><td>DH</td><td></td></tr>
				<tr ><th align="left">Total/صافي الفاتورة  </th><td>DH</td><td align="right">	 <span class="nbr">
					<?php echo number_format($TotalFac, 2, '.', ' '); ?></span></td></tr>
				</table>
		</div>
		<div class="clear"></div>
		<div class="boite" >
											
<br>
		<strong>je vous remercie de faire affaire avec vous!</strong><br>
		Si vous avez des questions concernant cette facture, veuillez contacter			<br>
		<?php echo $v["AdresseDepot"]; ?>
		<br>
		Tél: <?php echo $v["TelDepot"]; ?> Fax: <?php echo $v["FaxDepot"]; ?> E-mail: <?php echo $v["EmailDepot"]; ?> Web: <?php echo $v["SiteDepot"]; ?>			<BR>
<SPAN style="font-size:11px; color:red;">Veuillez retourner ce bordereau avec votre paiement
											
</span>		
		</div>
		<div class="HeadLeft" style="width:550px; ">
	<h1>GLOBAL MANUFACTURERS , Technolab </h1><BR>
	<table border="0"  cellpadding="2" class="">
				<tr><th align="left">Ne pas cocher.	</th><td>	<INPUT type="text" size="15">	</td></tr>
				<tr><th align="left">ID Client:		</th><td><?php echo $v["CodeClt"]; ?>	</td></tr>
				<tr><th align="left">Facture #:	</th><td>	<?php echo $v["NumFac"]; ?>	</td></tr>
			</table>

	</div>
	<div class="HeadRight"  style="width:250px; ">
			<h1>Bordereau de paiement
</h1><BR>
			<table border="0"  cellpadding="2" class="">
				<tr><th align="left">Date:	</th><td>	<INPUT type="text" size="15">	</td></tr>
				<tr><th align="left">Montant inclus:		</th><td><INPUT type="text" size="15">	</td></tr>
				<tr><th align="left">Solde à payer:	</th><td>	<INPUT type="text" size="15">	</td></tr>
			</table>
			
	</div>
	
				<div class="clear"></div>
		<div class="boite" ><?php echo $v["AdresseDepot"]; ?>
		<br>
		Tél: <?php echo $v["TelDepot"]; ?> Fax: <?php echo $v["FaxDepot"]; ?> E-mail: <?php echo $v["EmailDepot"]; ?> Web: <?php echo $v["SiteDepot"]; ?>													
									
	
		</div>
	<?php } // end foreach groups ?>


</div> <!-- fin div page -->
</body>
</html>