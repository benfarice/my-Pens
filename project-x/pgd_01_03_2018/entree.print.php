<?php 
	include("php.fonctions.php");
	require_once('connexion.php');
	session_start();
	include("lang.php");
	$IdDepot=$_SESSION["IdDepot"];

$sql = "
		SELECT 
			idMouvement as idFiche,reference,livreur,f.designation as fournisseur,convert(varchar(50),date,105)  as dateFiche,heure

		FROM 
			mouvements m
			inner join fournisseurs f on f.idFournisseur=m.fournisseur
			where m.idDepot=? and type like ? and idMouvement = ?";
	 $params = array($IdDepot ,'Entree',$_GET['IdFiche']);
//	parcourir($params);
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
	<div id="page" >
    <div id="">
    		<div id="logo" >&nbsp;</div>
		<div id="logoTexte"> 
		<?php echo $trad['label']['DocEntree'];?>
		<?php echo ucfirst($row["reference"]);?></div>
      </div>

<p>
<div id="tete" >
<table border="0" cellspacing="0" cellpadding="4" width="70%" align="center" class=""  > 
	<tr>	
			<td  valign="top"  align="right">
				<div class="etiqForm" id="SYMBT" ><strong> <?php echo $trad['label']['Fournisseur'];?>: </strong> </div>	</td>
			<td><?php 	echo stripslashes($row['fournisseur']);?></td>
			<td  valign="top"  align="right">
				<div class="etiqForm" id="SYMBT" > <strong> <?php echo $trad['label']['Livreur'];?>: </strong></div>	</td>
			<td><?php 	echo stripslashes($row['livreur']);?></td>
	</tr>			
				
</table>
</div><br>
<div class="corps">
	<?php
	$sqlA = "
		SELECT a.Reference as CodeaBarre,d.qte as Qte,d.pa as PA,a.designation as NomArt,date as dateFiche,heure
		 FROM detailMouvements d 
		 inner join mouvements m on m.idMouvement=d.idMouvement 
		 inner join articles a on a.idArticle=d.idArticle 
		 where m.idDepot=$IdDepot and m.type like 'Entree' and d.idMouvement = ?";

	 $params = array($_GET['IdFiche']);
	 $resAffA = sqlsrv_query($conn,$sqlA,$params) or die( print_r( sqlsrv_errors(), true));

	?>
	<table width="100%" class="table">
			     <tr class="entete">
					<td><?php echo $trad['label']['CodeArticle'];?> </td>
					<td><?php echo $trad['label']['Article'];?> </td>
					<td > <?php echo $trad['label']['Qte'];?> </td>
					 <td> <?php echo $trad['label']['PrixAchat'];?> </td>
      
  </tr>
			<?php
			$k=0;
			while($rowA = sqlsrv_fetch_array($resAffA, SQLSRV_FETCH_ASSOC)){
			
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			
				<tr  class=" ligne <?php echo $c; ?>">
				<td width="20%"><?php echo $rowA['CodeaBarre']; ?></td>
					<td width="40%"><?php echo stripslashes($rowA['NomArt']); ?></td>
					<td width="10%" align="right"><?php echo $rowA['Qte']; ?></td>
					<td width="15%" align="right" style=""><?php echo number_format($rowA['PA'], 2, '.', ' '); ?>	</td>
				</tr>
		
			<?php
		}	
	?>
	</table>
	</div>
	<br>
	<div style="float:<?php if ($_SESSION['align']=="left") echo "right"; else echo "left";;?>; width:30%">
	<?php echo $trad['label']['Le'];?>: <?php  echo $row['dateFiche'];//date_format($row['dateFiche'], 'd/m/Y'); ?>
</p>
</DIV>
	<script language="javascript">
			//		window.print();
	</script>
</body>
</html>
