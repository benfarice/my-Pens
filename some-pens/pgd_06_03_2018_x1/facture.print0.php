<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/catalogue.css" rel="stylesheet" type="text/css" />
<style media="screen" type="text/css">
#page,#piedPage{	border-left:1px solid #778;border-right:1px solid #778; -moz-box-shadow:0px 0px 20px #666;}
.style1 {font-size: 12pt}
</style>
</head>

<body>
  <input type="button" onclick=" fermer() " value="" class="Fermer"/>
<div id="page"> 
<?php
require_once('connexion.php');
$IdDepot=1;
$sql = "
		select  
		 f.[date] AS 'date_facture',c.nom,c.prenom,c.adresse,c.IdClient,a.idarticle,vi.Designation ville,d.adresse AdresseDepot,d.Designation DsgDepot,
		a.designation article,df.tarif,df.qte,c.nom+' '+c.prenom+' '+c.intitule client,v.nom+' '+v.prenom vendeur 
		,df.tauxtva,df.tva,df.ht,df.ttc,df.[type] colisage,f.numfacture,f.totalTTC-f.reste avance,f.reste 
		,case df.[type] when '' then convert(varchar,df.qte) 
		else  convert(varchar,df.qte*convert(int,df.[type])) end TotalUnitaire
		from 
		factures f inner join detailfactures df on df.idfacture=f.idfacture
		inner join clients c on c.idclient=f.idclient
		INNER JOIN villes vi ON vi.idville=c.ville
		inner join vendeurs v on v.idvendeur=f.idvendeur
		inner join articles a on a.idarticle=df.idarticle
		inner join depots d on d.idDepot=a.idDepot
		where f.idfacture=? and a.idDepot=?
			 ";
		
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
								Aucun r&eacute;sultat &agrave; afficher.
							</div>
							<?php
							return;
				}
		else
		{	
						$groups = array();
								$i=0;
								$TotalHT=0;$TotalTTC=0;$TotalTVA=0;
			 while($row=sqlsrv_fetch_array($stmt)){							 
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$key = $row['IdClient'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdClient']=$row['IdClient'];
											$groups[$key]['Nom']=$row['nom'];
											$groups[$key]['Prenom']=$row['prenom'];
											$groups[$key]['Adresse']=$row['adresse'];
											$groups[$key]['Ville']=$row['ville'];
											$groups[$key]['NumFacture']=$row['numfacture'];
											$date=date_create($row['date_facture']);
											$groups[$key]['DateFac']=date_format($date, 'd/m/Y');
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['DsgDepot']=$row['DsgDepot'];
												
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['IdArticle'] = $row['idarticle'];
											$groups[$key][$i]['DsgArticle'] = $row['article'];									
											$groups[$key][$i]['PV'] =$row['tarif'];
											$groups[$key][$i]['Qte'] =$row['qte'];
											$groups[$key][$i]['Colisage'] =$row['colisage'];
											$groups[$key][$i]['TotalQteUni'] =$row['qte']*$row['colisage'];
											$groups[$key][$i]['TVA'] =$row['tauxtva'];
											$groups[$key][$i]['HT'] =$row['ht'];
											$groups[$key][$i]['TTC'] =$row['ttc'];
										
										$TotalHT+=$row['ht'];
										$TotalTTC+=$row['ttc'];
										$TotalTVA+=$row['tva'];
			 }
				
?>
<div class="infoSte">
<div id="boxLogo">
	<?php  	foreach($groups as $u=>$v){	?>
	  <img src="images/logo_print.png" alt="LOGO" style=""/>
	  
	</div>
	<div class="clear"></div>
  <div id=""  style="float:left">
	  
	  	<div id="nomClient">
			<p><?php  echo $v['DsgDepot'];?> </p></div>
		<p><?php  echo $v['AdresseDepot'];?> <br />
		Tél:0522 992 970<br />
		Mail:contact@electroprotect.ma<br />
 <span id="dtx-highlighting-item"> </span></p>
	  </div>
</div>
<div class="infoClt" style="margin-top:160px">
  <div id="boxClient">Facture à 
	<div id="nomClient">
			<p><?php  echo $v['Nom']." ".$v['Prenom'];?> </p></div>
		<p><?php  echo $v['Adresse'];?> <br />
 <span id="dtx-highlighting-item"><?php  echo $v['Ville'];?>  </span></p>
		
		<div id="adrClient"> </div>
	</div>
	</div>
	
	<p class="clear"></p>
	<div id="boxInfos">
		<div id="numFacture"> 
		  Facture n° <strong><?php  echo $v['NumFacture'];?> </strong></div>
		<div id="dateFacture">Date de facture : <strong><?php  echo $v['DateFac'];?> </strong></div>
	<!--	<div id="dateFacture" class="">Opérateur : <strong>Ahmed Karami</strong></div>-->
	</div>
	
	<div id="boxContenu">
		<table width="100%" style="">
			<tr>
				<td width="51%" class="headerTab ">Produit</td>
				<td  class="headerTab ">Quantité</td>
				<td  class="headerTab ">Colisage</td>
				<td  class="headerTab ">Total Qte Unitaire</td>
				<td  class="headerTab " width="19%">PV HT</td>
				<td width="10%" class="headerTab ">TVA %</td>
				<td width="19%" class="headerTab ">HT</td>
				<td width="19%" class="headerTab ">TTC</td>
			</tr>
		<?php 
			foreach($v as $r){
				if(is_array($r)){?>
						<tr>
						  <td class="prestation "><?php  echo $r['DsgArticle'];?>	</td>
							<td  align="right" ><?php  echo $r['Qte'];?>	</td>
							<td  align="right"><?php  echo $r['Colisage'];?>	</td>
							<td  align="right"><?php  echo $r['TotalQteUni'];?>	</td>
							<td  align="right"><?php  echo $r['PV'];?>	</td>
							<td align="right" ><?php  echo $r['TVA'];?>	</td>
							<td   align="right" ><?php  echo $r['HT'];?>	</td>
							<td align="right"  ><?php  echo $r['TTC'];?>	</td>
						</tr>
			<?php } 
			}?>
			<tr>
			  <td class="headerTab" id="total" colspan="6">Total H.T. </td>
			  <td class="montant" id="total" colspan="2"><?php echo number_format($TotalHT, 2, '.', ' '); ?></td>
		  </tr>
			
		  <tr>
			  <td class="headerTab" id="total" colspan="6">Total T.V.A. </td>
			  <td class="montant" id="total" colspan="2"><?php echo number_format($TotalTVA, 2, '.', ' '); ?></td>
		  </tr>
		   <tr>
			  <td class="headerTab" id="total" colspan="6">Total T.T.C. </td>
			  <td class="montant" id="total" colspan="2"><?php echo number_format($TotalTTC, 2, '.', ' '); ?></td>
	      </tr>
		</table>
	</div>
	<?php } // fin first WHILE
	 } // fin else table sql has data
	?>
  <div id="boxPied">

  </div>
     <script src="js/jquery.min.js" type="text/javascript" ></script>
<script language="javascript">
		//	window.print();
			function fermer(){
			window.close();
		}
		
$(document).ready(function(){	
		
var headstr = '<html><head><title></title><link href="css/catalogue.css" rel="stylesheet" type="text/css" /></head><body>';
var footstr = "</body>";
var newstr = document.getElementById("page").innerHTML;
var oldstr = document.body.innerHTML;
document.body.innerHTML = headstr+newstr+footstr;
window.print();
document.body.innerHTML = oldstr;

return false;
});
	</script>

</div>

</body>
</html>