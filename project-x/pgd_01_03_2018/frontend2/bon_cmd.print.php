
<?php
require_once('headerPrint.php');
$IdDepot=$_SESSION['IdDepot'];
$sql = "
		select  
		
		v.adresse,v.idVendeur,a.idarticle,d.adresse AdresseDepot,d.Designation DsgDepot,
		a.designation article,v.nom+' '+v.prenom Vendeur ,
		dc.qte Qte,dc.colisage Colisage,
		c.date DateCmd,
		c.numCommande NumCmd
		from 
		commandeVendeurs c 
		inner join detailCommandeVendeurs dc on dc.idCommandeVendeur=c.idCommandeVendeur
		inner join vendeurs v on v.idvendeur=c.idvendeur
		inner join articles a on a.idarticle=dc.idarticle
		inner join depots d on d.idDepot=v.idDepot
		where c.idCommandeVendeur=? and v.idDepot=?
			 ";
	//	echo $sql;
		 $params = array($_GET['IdCmd'],$IdDepot);	
	
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
							<?php  echo $trad['msg']['AucunResultat'];?>.
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
		
												 
												 
										$key = $row['idVendeur'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['idVendeur']=$row['idVendeur'];
											$groups[$key]['Vendeur']=$row['Vendeur'];
											$groups[$key]['Adresse']=$row['adresse'];
											$groups[$key]['NumCmd']=$row['NumCmd'];
											$date=date_create($row['DateCmd']);
											$groups[$key]['DateCmd']=date_format($date, 'd/m/Y');
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['DsgDepot']=$row['DsgDepot'];
												
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['IdArticle'] = $row['idarticle'];
											$groups[$key][$i]['DsgArticle'] = $row['article'];		
											$groups[$key][$i]['Qte'] =$row['Qte'];
											$groups[$key][$i]['Colisage'] =$row['Colisage'];
											$groups[$key][$i]['TotalQteUni'] =$row['Qte']*$row['Colisage'];
										
								
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
<!--
<div class="infoClt" style="margin-top:160px">
  <div id="boxClient">Bon de commande du 
	<div id="nomClient">
			<p><?php  echo $v['Nom']." ".$v['Prenom'];?> </p></div>
		<p><?php  echo $v['Adresse'];?> <br />
 <span id="dtx-highlighting-item"><?php  echo $v['Ville'];?>  </span></p>		
		<div id="adrClient"> </div>
	</div>
	</div>-->
	
	<p class="clear"></p>
	<div id="boxInfos" class="infoClt" >
		<div id="numFacture"> 
		  	 <?php echo $trad['label']['Vendeur'] ;?> <strong> : <?php  echo $v['Vendeur'];?> </strong></div>
		<div id="numFacture"> 
		 <?php echo $trad['label']['NumBonCmd'] ;?> <strong> :<?php  echo $v['NumCmd'];?> </strong></div>
		  	
		<div id="dateFacture"><?php echo $trad['label']['DateCmd'] ;?> : <strong><?php  echo $v['DateCmd'];?> </strong></div>
	<!--	<div id="dateFacture" class="">Opérateur : <strong>Ahmed Karami</strong></div>-->
	</div>
	
	<div id="boxContenu">
		<table width="100%" style="">
			<tr>
				<td width="51%" class="headerTab "><?php echo $trad['label']['Article'];?></td>
				<td  class="headerTab "><?php echo $trad['label']['Qte'].' ('.$trad['label']['Box'].')';?></td>
				<!--td  class="headerTab chpinvisible"><?php echo $trad['label']['Colisage'];?></td>
				<td  class="headerTab chpinvisible "><?php echo $trad['label']['QteTotal'];?></td-->
			</tr>
		<?php 
			foreach($v as $r){
				if(is_array($r)){?>
						<tr>
						  <td class="prestation "><?php  echo stripslashes($r['DsgArticle']);?>	</td>
							<td  align="right" ><?php  echo $r['Qte'];?>	</td>
							<!--td  align="right"   class=" chpinvisible "><?php  echo $r['Colisage'];?>	</td>
							<td  align="right"  class=" chpinvisible "><?php  echo $r['TotalQteUni'];?>	</td-->
						</tr>
			<?php } 
			}?>
		
		
		</table>
	</div>
	<?php } // fin first WHILE
	 } // fin else table sql has data
	?>
  <div id="boxPied">
  <TABLE style="display:none;">
	<tr><th colspan="4" height="25" ></th></tr>
				<tr><th colspan="2" height="35" style="padding-left:50px;" >
				Signature employee </th>
				<th colspan="2" style="align:center;" >Visa du responsable </th>
				</tr>
				</table>
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
//window.print();
document.body.innerHTML = oldstr;

return false;
});
	</script>

</div>

</body>
</html>