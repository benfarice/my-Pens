<?php 
require_once('../connexion.php');
session_start();
include("lang.php");
$IdDepot=$_SESSION['IdDepot'];
$sql = "
		select  
			c.CodeClient CodeClt,c.nom+' '+c.prenom Client,c.adresse AdresseClt,c.IdClient,
			,c.intitule CompanyClt,vi.Designation VilleClt,c.cp CodePostaleClt,
			c.tel TelClt,
			
			v.nom+' '+v.prenom Vendeur,v.adresse AdresseVdr,v.idvendeur IdVdr,
			v3.Designation VilldeVdr,v.telephone TelVdr
			
			
		   f.[da te] AS 'DateFac',
		   f.numfacture  NumFac,
		   dr.mode ModePaiement,
		   rf.DateEcheance,
		   f.DateLivraison,
		   f.Observation
		   
			a.idarticle IdArticle,
			a.Designation DsgArticle,
			df.UniteVente,
			df.qte Qte,
			dv.tarif PrixUnite,
			dv.ttc TotalUnite,
			dr.numCheque NumCheque,
			dr.numVersement NumVersement,
			
			d.adresse AdresseDepot,d.Designation DsgDepot,
			d.Email,d.Tel,d.SiteWeb,d.Fax
			
		from 
			factures f
			inner join detailfactures df on df.idfacture=f.idfacture
			inner join reglementFactures rf.idFacture=f.IdFacture
			inner join reglements  rf on rf.idReglement=rf.idReglement
			inner join detailReglements dr  on dr.IdReglement=rf.idReglement
			
			inner join clients c on c.idclient=f.idclient
			INNER JOIN villes vi ON vi.idville=c.ville
			
			inner join vendeurs v on v.idvendeur=f.idvendeur
			 INNER JOIN affectations a ON v.idVendeur = a.idVendeur 
			 INNER JOIN vehicules v2 ON v2.idVehicule = a.idVehicule 
			 INNER JOIN detailAffectations da ON da.idaffectation = a.idaffectation 
			  INNER JOIN departements d ON d.iddepartment =da.idDepartement 
			 INNER JOIN villes v3 ON v3.idville=d.idVille
 
			inner join articles a on a.idarticle=df.idarticle
			inner join depots d on d.idDepot=a.idDepot
			
			
			
		where 
		f.idfacture=? and a.idDepot=?
			 ";
	echo $sql ;
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
											$groups[$key]['CodeClient']=$row['CodeClient'];
											$groups[$key]['Intitule']=$row['CompanyClt'];
											$groups[$key]['Adresse']=$row['AdresseClt'];
											$groups[$key]['Ville']=$row['VilleClt'];
											$groups[$key]['CodePostaleClt']=$row['CodePostaleClt'];
											$groups[$key]['TelClt']=$row['TelClt'];
											$groups[$key]['Vendeur']=$row['Vendeur'];
											$groups[$key]['AdresseVdr']=$row['AdresseVdr'];
											$groups[$key]['IdVdr']=$row['IdVdr'];
											$groups[$key]['VilldeVdr']=$row['VilldeVdr'];
											$date=date_create($row['DateFac']);
											$groups[$key]['DateFac']=date_format($date, 'd/m/Y');
											
											$groups[$key]['DateFac']=$row['DateFac'];
											$groups[$key]['NumFac']=$row['NumFac'];
											$groups[$key]['ModePaiement']=$row['ModePaiement'];
											$groups[$key]['DateEcheance']=$row['DateEcheance'];
											$groups[$key]['DateLivraison']=$row['DateLivraison'];
											$groups[$key]['Observation']=$row['Observation'];
											$groups[$key]['NumCheque']=$row['NumCheque'];
											$groups[$key]['numVersement']=$row['numVersement'];
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['DsgDepot']=$row['DsgDepot'];
											
											$groups[$key]['EmailDepot']=$row['EmailDepot'];
											$groups[$key]['TelDepot']=$row['TelDepot'];
											$groups[$key]['SiteDepot']=$row['SiteDepot'];
											$groups[$key]['FaxDepot']=$row['FaxDepot'];
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
												
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['IdArticle'] = $row['IdArticle'];
											$groups[$key][$i]['DsgArticle'] = $row['DsgArticle'];									
											$groups[$key][$i]['UniteVente'] =$row['UniteVente'];
											$groups[$key][$i]['Qte'] =$row['Qte'];
											$groups[$key][$i]['PrixUnite'] =$row['PrixUnite'];
											$groups[$key][$i]['TotalUnite'] =$row['TotalUnite'];
									
									
										$TotalFac+=$row['TotalUnite'];
			 }
		}
				
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
	width:550px;
}
.HeadRight{
	float:right;
	width:250px;
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
background:#333;
color:#fff;font-size:14px;
}
.TitleNote{
background:#333;
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
</style>
</head>

<body>
  <input type="button" onclick=" fermer() " value="" class="Fermer" style="display:none;"/>
<div id="page"> 

<div class="head">

	<div class="HeadLeft">
	<h1>GLOBAL MANUFACTURERS CO,Mosaneon </h1><BR><BR>
	<img src="img/logo.png" /><br><BR><BR>
	
			

	</div>
	<div class="HeadRight">
			<h1>Invoice</h1><BR>
			<table border="0"  cellpadding="2" class="tableHead">
				<tr><th align="left">Date:	</th><td>	May 18, 2017	</td></tr>
				<tr><th align="left">Invoice #:		</th><td>INV-00000	</td></tr>
				<tr><th align="left">Customer ID:	</th><td>	[ABC12345]	</td></tr>
				<tr><th align="left">Purchase Order #	</th><td>	12345678	</td></tr>
				<tr><th align="left">Payment Due by:	</th><td>	June 17, 2017	</td></tr>
			</table>
			
	</div>
	<div class="clear"></div>
		<div class="HeadLeft">
				<table border=0 cellpadding="3" width="100%">
				<tr class="TrSuper"><td colspan="3">Bill To / العميل : 	</td></tr>
				<tr><td align="left">[Name]		</td><td>	</td></tr>
				<tr><td>[Company Name]	</td><td>	</td></tr>
				<tr><td>[Street Address]		</td><td>	</td></tr>
				<tr><td>[City, ST  ZIP Code]		</td><td>		</td></tr>	
				<tr><td>[Phone]			</td><td>	</td></tr>

			</table>
		</div>
		<div class="HeadRight">
				<table width="100%">
				<tr class="TrSuper"><td colspan="2"width="50%">Ship To / العميل :	</td></tr>
				<tr><td align="left">[Name]		</td><td>	</td></tr>
				<tr><td>[Company Name]	</td><td>	</td></tr>
				<tr><td>[Street Address]		</td><td>		</td></tr>
				<tr><td>[City, ST  ZIP Code]		</td><td>		</td></tr>	
				<tr><td>[Phone]			</td><td>		</td></tr>
		
			</table>
		</div>
		<div class="clear"></div>
		<p>
		<table width="100%" class="Table1" cellpadding="4">
		<tr style="font-weight:bold;">
		  <th>Salesperson / المندوب	</th>
		  <th></th>
		  <th></th>
		  <th>PMT COND / شروط الدفع 	</th>
		  <th>Due Date / تاريخ الاستحقاق	</th>
		  <th>Delivery / التسليم	</th>
		</tr>
			<tr>
		  <td>amina whamane</td>
		    <td></td>
		  <td></td>
		  <td>Espece</td>
		  <td>12/02/2019</td>
		  <td>12/02/2019</td>
		
		</tr>
		</table>
		</p>
			<table width="100%" class="Table1" cellpadding="4">
		<tr style="font-weight:bold;">
		  <th>Item/كود	</th>
		  <th>Description/الوصف</th>
		  <th>Unit/الوحدة</th>
		  <th>Qty/كمية</th>
		  <th>Unit Price/سعر البيع	</th>
		  <th>Line Total / إجمالي	</th>  
		</tr>
			<tr>
		  <td>amina whamane</td>
		    <td></td>
		  <td></td>
		  <td>Espece</td>
		  <td>12/02/2019</td>
		  <td>12/02/2019</td>
		
		</tr>
		</table>
		<br>
			<div class="HeadLeft" style="border:1px solid #ccc;width:500px; min-height:100px">
				<div class="TitleNote">
				Special Notes and Instructions / شروط وملاحظات 
				</div><div class="clear"></div>
			
			</div>
	
		<div class=" HeadRight" style="">
		
			<table width="100%" class="TableTotal" cellpadding="4">
				
				<tr ><th align="left">Subtotal/إجمالي</th><td>SR</td><td align="right" width="50%">104</td></tr>
				<tr ><th align="left">VAT Rate :ض ق م </th><td>%</td><td align="right">0.00</td></tr>
				<tr ><th align="left">قيمة ض ق م </th><td>SR</td><td></td></tr>
				<tr ><th align="left"></th><td></td><td></td></tr>
				<tr ><th align="left">Discount / خصم</th><td>SR</td><td></td></tr>
				<tr ><th align="left">Total/صافي الفاتورة  </th><td>SR</td><td align="right">103.00</td></tr>
				</table>
		</div>
		<div class="clear"></div>
		<div class="boite" >
		Make all checks payable to GLOBAL MANUFACTURERS CO,Mosaneon 											
<br>
		<strong>Thank you for your business!</strong><br>
		Should you have any enquiries concerning this invoice, please contact  on 			<br>
		111 Dahaiah Dist, Khomrah, Jeddah, Saudia Arabia, ST, 00000		<br>
		Tel: 966122756666 Fax: 966126064071 E-mail: info@mosaneon.com Web: mosaneon.com.sa			<BR>
<SPAN style="font-size:11px; color:red;">Please return this slip along with your payment											
</span>		
		</div>
		<div class="HeadLeft">
	<h1>GLOBAL MANUFACTURERS CO,Mosaneon </h1><BR>
	<table border="0"  cellpadding="2" class="">
				<tr><th align="left">Check No.	</th><td>	<INPUT type="text" size="15">	</td></tr>
				<tr><th align="left">Customer ID:		</th><td>FSSDF324234	</td></tr>
				<tr><th align="left">Invoice #:	</th><td>	DSF3R4R4	</td></tr>
			</table>

	</div>
	<div class="HeadRight">
			<h1>Remittance Slip</h1><BR>
			<table border="0"  cellpadding="2" class="">
				<tr><th align="left">Date:	</th><td>	<INPUT type="text" size="15">	</td></tr>
				<tr><th align="left">Amount Enclosed:		</th><td><INPUT type="text" size="15">	</td></tr>
				<tr><th align="left">Balance to Pay:	</th><td>	<INPUT type="text" size="15">	</td></tr>
			</table>
			
	</div>
	
				<div class="clear"></div>
		<div class="boite" >111 Dahaiah Dist, Khomrah, Jeddah, Saudia Arabia, ST, 00000		<BR>Tel: 966122756666 Fax: 966126064071 E-mail: info@mosaneon.com Web: mosaneon.com.sa											
									
	
		</div>
</div>

</div> <!-- fin div page -->
</body>
</html>