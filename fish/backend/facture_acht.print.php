<?php
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
	include_once "init.php";
?>
<link href="layout/css/print.css" rel="stylesheet" type="text/css" />
<style media="screen" type="text/css">
#page,#piedPage{	border-left:1px solid #778;border-right:1px solid #778; -moz-box-shadow:0px 0px 20px #666;}
.style1 {font-size: 12pt}
</style>

<div id="page"> 
<?php


$sql = "
	  SELECT df.codeAdj NumAdj,fac.IdFac, fac.date DateFac,CodeFac,Date,Heure,Montant,codeAdj,a.Code_Acheteur CodeBye ,bye.name_ar NameAcht ,e.Nom_espece NameEsp
   ,a.Prix_unitaire PrixUnite,a.Poids_net TotalPoids, a.Prix_net TotalPrix ,sel.name_ar NameSel,a.num_lot NumLot,fac.montant TotalFac,a.Date_adjudication DateAdj
   FROM factures_acht fac 
   INNER JOIN detailfactures df ON df.idfac=fac.idfac
   INNER JOIN ADJUDICATION a ON a.Num_adjudication=df.Codeadj
   INNER JOIN LOT l ON l.Num_lot = a.num_lot
    INNER JOIN ESPECE e ON e.Code_espece=l.Code_espece 
    INNER JOIN BUYER_SELLER bye ON bye.ID_B_S=a.Code_Acheteur AND bye.TYPE_B_S='BUYER' 
		 INNER JOIN BUYER_SELLER sel ON sel.ID_B_S=l.Code_vendeur AND sel.TYPE_B_S='SELLER' 
		where fac.IdFac=? 
			 ";
		
		 $params = array($_GET['IdFac']);	
	
		$stmt=sqlsrv_query($con,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
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
												 
												 
										$key = $row['IdFac'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['CodeFac']=$row['CodeFac'];											
											$groups[$key]['NameAcht']=$row['NameAcht'];
											$groups[$key]['TotalFac']=$row['TotalFac'];
											$groups[$key]['DateFac']=$row['DateFac'];
													$groups[$key]['CodeBye']=$row['CodeBye'];
											$groups[$key]['NameBye']=$row['NameAcht'];
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['NumAdj'] = $row['NumAdj'];
											$groups[$key][$i]['CodeBye']=$row['CodeBye'];
											$groups[$key][$i]['NameBye']=$row['NameAcht'];
											$groups[$key][$i]['NumLot'] = $row['NumLot'];									
											$groups[$key][$i]['NameSeller'] =$row['NameSel'];
											$groups[$key][$i]['NameEsp'] =$row['NameEsp'];
											$groups[$key][$i]['PrixUnite'] =$row['PrixUnite'];
											$groups[$key][$i]['TotalPoids'] =$row['TotalPoids'];
											$groups[$key][$i]['TotalPrix'] =$row['TotalPrix'];
											$groups[$key][$i]['DateAdj'] = $row['DateAdj'];
										
										
									
			 }
		//	 parcourir($groups);
				
?>
<table>
<tr>
<td width="25%"></td>
<td width="25%"></td>
<td width="25%"></td>
</tr>
</table>


<div id="boxLogo">
	<?php  	foreach($groups as $u=>$v){	?>
	  <!--img src="images/logo_print.png" alt="LOGO" style=""/-->
	   <H3><?php echo lang('Titleproject');?></h3>
	</div>

<div class="">
  <div id="boxClient">Facture à 
	<div id="nomClient">
			<p><?php  echo $v['CodeBye']." ".$v['NameBye'];?> </p></div>
		</p>
		
		
	</div>
<div id="boxInfos">
		<div id="numFacture"> 
		  Facture n° <strong><?php  echo $v['CodeFac'];?> </strong></div>
		<div id="dateFacture">Date de facture : <strong><?php  echo $v['DateFac'];?> </strong></div>
	<!--	<div id="dateFacture" class="">Opérateur : <strong>Ahmed Karami</strong></div>-->

	</div>
	</div>
	


	
	<div id="boxContenu">
		<table width="100%" style="" border=1>
			<tr>
						<th width="10%"><?php echo lang('NumAdj');?></th>
						<th width="10%"><?php echo lang('NameSeller');?></th>
						<!--th width="10%"><?php echo lang('DateAdj');?></th-->
						<th width="10%"><?php echo lang('NameEspece');?></th>
					
						<th width="12%"><?php echo lang('PrixUnite')."(".lang('reyal_homany').")";?></th>
						<th width="14%"><?php echo lang('TotalPoids')." ".lang('Kg');?></th>
						<th width="14%" ><?php echo lang('TotalPrix')."(".lang('reyal_homany').')';?></th>
			</tr>
		<?php 
			foreach($v as $row){
				if(is_array($row)){?>
								<tr>				
						<td ><?php echo ucfirst(stripslashes($row['NumAdj']));?></td>
						<td ><?php  echo ucfirst(stripslashes($row['NameSeller']));?>	</td>
						<!--td ><?php $Date = $row['DateAdj']->format('d/m/Y');echo $Date;?>	</td-->
						<td ><?php echo ucfirst(stripslashes($row['NameEsp']));?></td>
						
						<td ><span class="nbr">&#x200E;<?php  echo  number_format($row['PrixUnite'], 3, '.', ' ');?></span></td>
						<td span class="nbr">&#x200E;<?php  echo  number_format($row['TotalPoids'], 3, '.', ' ');?></span></td>
						<td><span class="nbr">&#x200E;<?php  echo number_format($row['TotalPrix'], 3, '.', ' ');?></span></td>
						
					</tr>
			<?php } 
			}?>
		
		</table>
	</div>
	<?php } // fin first WHILE
	 } // fin else table sql has data
	?>
  <div id="boxPied">

  </div>
  </div>
<script type="text/javascript" src="<?php echo $js ?>jquery-3.3.1.min.js"></script>
<script language="javascript">
		//	window.print();
		function fermer(){
			alert("test");
			window.close();
		}
$(document).ready(function(){	
//	window.print();
});
	</script>



