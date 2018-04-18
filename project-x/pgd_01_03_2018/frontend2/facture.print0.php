<?php 
require_once('headerPrint.php');
$IdDepot=$_SESSION['IdDepot'];
$sql = "
		select  
		 f.[date] AS 'date_facture',c.nom,c.prenom,c.adresse,c.IdClient,a.idarticle,vi.Designation ville,d.adresse 
		 AdresseDepot,d.Designation DsgDepot,
		a.designation article,df.tarif,df.qte,c.nom+' '+c.prenom client,c.intitule,v.nom+' '+v.prenom vendeur 
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
							
								<?php echo $trad['msg']['AucunResultat'] ;?>
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
											$groups[$key]['Intitule']=$row['intitule'];
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
	  <img src="../images/logo_print.png" alt="LOGO" style=""/>
	  
	</div>
	<div class="clear"></div>
  <div id=""  style="float:left">
	  
	  	<div id="nomClient">
			<p><?php  echo $v['DsgDepot'];?> </p></div>
		<p><?php  echo $v['AdresseDepot'];?> <br />
		<?php echo $trad['label']['Tel'] ;?>:0522 992 970<br />
		<?php echo $trad['label']['Mail'] ;?>:contact@electroprotect.ma<br />
 <span id="dtx-highlighting-item"> </span></p>
	  </div>
</div>
<div class="infoClt" style="margin-top:160px">
  <div id="boxClient"> 	<?php echo $trad['label']['Facture'] ;?><?php echo $trad['label']['A'] ;?>
	<div id="nomClient">
			<p><?php  echo $v['Intitule'];?> </p></div>
		<p><?php  echo $v['Adresse'];?> <br />
 <span id="dtx-highlighting-item"><?php  echo $v['Ville'];?>  </span></p>
		
		<div id="adrClient"> </div>
	</div>
	</div>
	
	<p class="clear"></p>
	<div id="boxInfos">
		<div id="numFacture"> 
		  <?php echo $trad['label']['Facture'] ;?> : <strong><?php  echo $v['NumFacture'];?> </strong></div>
		<div id="dateFacture"> <?php echo $trad['label']['DateFacture'] ;?> : <strong><?php  echo $v['DateFac'];?> </strong></div>
	<!--	<div id="dateFacture" class="">Opérateur : <strong>Ahmed Karami</strong></div>-->
	</div>
	
	<div id="boxContenu">
		<table width="100%" style="">
			<tr>
				<td width="51%" class="headerTab "><?php echo $trad['label']['Article'] ;?></td>
				<td  class="headerTab "><?php echo $trad['label']['Qte'] ;?></td>
				<td  class="headerTab "><?php echo $trad['label']['Colisage'] ;?></td>
				<td  class="headerTab "><?php echo $trad['label']['TotalQte'] ;?></td>
				<td  class="headerTab " width="19%"><?php echo $trad['label']['PVHT'];?></td>
				<td width="10%" class="headerTab " style="display:none"><?php echo $trad['label']['TVA'];?> </td>
				<td width="19%" class="headerTab " style="display:none"><?php echo $trad['label']['ValHT'];?></td>
				<td width="19%" class="headerTab "><?php echo $trad['label']['ValTTC'];?></td>
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
							<td align="right" style="display:none"><?php  echo $r['TVA'];?>	</td>
							<td   align="right" style="display:none" ><?php  echo $r['HT'];?>	</td>
							<td align="right" ><span class="nbr"><?php echo  number_format($r['TTC'], 2, '.', ' ');?>
								</span>	
								</td>
						</tr>
			<?php } 
			}?>
			<tr style="display:none">
			  <td class="headerTab" id="total" colspan="6" ><?php echo $trad['label']['TotalHT'] ;?>
			  <?php echo $trad['label']['riyal'] ;?></td>
			  <td class="montant" id="total" colspan="2"><?php echo number_format($TotalHT, 2, '.', ' '); ?></td>
		  </tr>
			
		  <tr style="display:none">
			  <td class="headerTab" id="total" colspan="6"><?php echo $trad['label']['TotalTVA'] ;?> </td>
			  <td class="montant" id="total" colspan="2"><?php echo number_format($TotalTVA, 2, '.', ' '); ?>
			    <?php echo $trad['label']['riyal'] ;?>
				</td>
		  </tr>
		   <tr>
			  <td class="headerTab" id="total" colspan="5"><?php echo $trad['label']['TotalTTC'] ;?></td>
			  <td class="montant" id="total" colspan="2">
			  <span class="nbr"><?php echo number_format($TotalTTC, 2, '.', ' '); ?></span>
			    <?php echo $trad['label']['riyal'] ;?>
				</td>
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
			window.print();
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