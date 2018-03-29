<?php 
	include("php.fonctions.php");
	require_once('connexion.php');
	session_start();
	include("lang.php");
	$IdDepot=$_SESSION['IdDepot'];
$sql = "
		SELECT 
			IdChargement as idFiche,numChargement,v.nom,v.prenom,
			convert(varchar(50),date,105) as dateFiche,u.nom as NomUtilisateur,u.prenom as PrenomUtilisateur

		FROM 
			chargements c
			inner join vendeurs v on v.idVendeur=c.idVendeur
			inner join utilisateurs u on u.IdUtilisateur=operateur
			where c.idDepot=$IdDepot and IdChargement = ?";
	 $params = array($_GET['IdFiche']);
	// echo $sql; print_r($params);
	 $resAff = sqlsrv_query($conn,$sql,$params) or die( print_r( sqlsrv_errors(), true));
	$row = sqlsrv_fetch_array( $resAff, SQLSRV_FETCH_ASSOC);
?>
<html>
<head>
<?php if($_SESSION['lang']=="ar") { ?>
	<link type="text/css" rel="stylesheet" href="css/printOptionsAr.css" />
<?php } else { ?>
	<link type="text/css" rel="stylesheet" href="css/printOptions.css" />
<?php } ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="page"  >
    <div id="">
    		<div id="logo" >&nbsp;</div>
		<div id="logoTexte">  <?php echo $trad['label']['FicheChargement'];?> <?php echo ucfirst($row["numChargement"]);?></div>
      </div>

<p>
<div id="tete" >
<table border="0" cellspacing="0" cellpadding="4" width="80%" align="center" class="" > 
	<tr>	
			<td  valign="top"  align="right">
				<div class="etiqForm" id="SYMBT" ><strong> <?php echo $trad['label']['date'];?>: </strong> </div>	</td>
			<td><?php 
				
				$date=$row['dateFiche'];//01/11/2016
				echo $date// date_format($date,"d/m/Y");
			
			//echo $row['dateFiche'];?></td>
			
			<td  valign="top"  align="right">
				<div class="etiqForm" id="SYMBT" ><strong><?php echo $trad['label']['Superviseur'];?> : </strong> </div>	</td>
			<td><?php 	echo stripslashes($row['NomUtilisateur'])." ".stripslashes($row['PrenomUtilisateur']);?></td>
			
		<td  valign="top"  align="right">
				<div class="etiqForm" id="SYMBT" ><strong> <?php echo $trad['label']['Vendeur'];?>: </strong> </div>	</td>
			<td><?php 	echo stripslashes($row['nom'])." ".stripslashes($row['prenom']);?></td>
	</tr>			
				
</table>
</div><br>
<div class="corps">
	<?php
	$sqlA = "
		SELECT Reference as CodeaBarre,d.qte as Qte,a.designation as NomArt,date as dateFiche,
		c.colisagee idColisage
		 FROM detailchargements d 
		 inner join chargements m on m.IdChargement=d.IdChargement 
		 inner join articles a on a.idArticle=d.idArticle 
		 INNER JOIN colisages c ON c.idArticle=a.IdArticle
		 where m.idDepot=$IdDepot  and d.IdChargement = ?";

	 $params = array($_GET['IdFiche']);
	 $resAffA = sqlsrv_query($conn,$sqlA,$params) or die( print_r( sqlsrv_errors(), true));

	?>
	<table width="100%" class="table" >
			     <tr class="entete">
					<td><?php echo $trad['label']['CodeArticle'];?></td>
					<td><?php echo $trad['label']['Article'];?> </td>
					<td > <?php echo $trad['label']['Qte'];?> </td>
					 <td> <?php echo $trad['label']['Colisage'];?> </td>
      
  </tr>
			<?php
			$k=0;
			while($rowA = sqlsrv_fetch_array($resAffA, SQLSRV_FETCH_ASSOC)){
			
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			
				<tr  class=" ligne <?php echo $c; ?>">
				<td width="20%"><?php echo $rowA['CodeaBarre']; ?></td>
					<td width="40%"><?php echo $rowA['NomArt']; ?></td>
					<td width="10%" align="right"><?php echo $rowA['Qte']; ?></td>
					<td width="15%" align="right" style=""><?php if (($rowA['idColisage']!="" ) &&($rowA['idColisage']!="0" )) echo $rowA['idColisage']; ?></td>
				</tr>
		
			<?php
		}	
	?>
	</table>
	</div>
	<br>
	<div class="Visa">
	Visa: 

	</DIV>
	<script language="javascript">
			//		window.print();
	</script>
</body>
</html>
