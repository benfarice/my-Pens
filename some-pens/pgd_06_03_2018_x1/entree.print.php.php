<?php 
	include("php.fonctions.php");
	require_once('connexion.php');
	session_start();
	$IdDepot=1;
$sql = "
		SELECT 
			idMouvement as idFiche,reference,livreur,f.designation as fournisseur,date as dateFiche,heure

		FROM 
			$tableInser t
			inner join fournisseurs f on f.idFournisseur=t.fournisseur
			where t.idDepot=$IdDepot and type like 'Entree' and idMouvement = ?";
	 $params = array($_GET['IdFiche']);
	 $resAff = sqlsrv_query($conn,$sql,$params) or die( print_r( sqlsrv_errors(), true));
	$row = sqlsrv_fetch_array( $resAff, SQLSRV_FETCH_ASSOC);
?>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="css/printOptions.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="page" >
    <div id="entete">
    		<div id="logo" >&nbsp;</div>
		<div id="logoTexte"> Bon d'entrée N° <?php echo ucfirst($row["reference"]);?></div>
      </div>
<div class="clear"></div>


<p>
<table border="1" cellspacing="0" cellpadding="10" width="100%" align="center" class="table"  > 
	<tr>	
			<td  valign="top" >
				<div class="etiqForm" id="SYMBT" > Fournisseur: </div>	</td>
			<td><?php 	echo stripslashes($row['fournisseur']);?></td>
			<td  valign="top" >
				<div class="etiqForm" id="SYMBT" > Livreur: </div>	</td>
			<td><?php 	echo stripslashes($row['livreur']);?></td>
	</tr>			
				
</table>
	<?php
	$sqlA = "
		SELECT 
			qte as Qte,pa as PA,livreur,f.designation as fournisseur,date as dateFiche,heure

		FROM 
			detailMouvements d
			inner join mouvements  m on m.idMouvement=d.idMouvement
			inner join fournisseurs f on f.idFournisseur=m.fournisseur 
			where t.idDepot=$IdDepot and m.type like 'Entree' and idMouvement = ?";
	 $params = array($_GET['IdFiche']);
	 $$resAffA, SQLSRV_FETCH_ASSOC = sqlsrv_query($conn,$sqlA,$params) or die( print_r( sqlsrv_errors(), true));

	?>
	<table width="100%">
			     <tr class="entete">
					<td>Code article </td>
					<td>Article </td>
					<td > Quantité </td>
					 <td> Prix d'achat </td>
      
  </tr>
			<?php
			$k=0;
			while($rowA = sqlsrv_fetch_array($resAffA, SQLSRV_FETCH_ASSOC)){
				if($i==0){
					$ligne1=' style="border-top:1px solid #778;"';
				}else{
					$ligne1='';
				}
				
					if($k%2 == 0) $c = "pair";
					else $c="impair";
			
				?>
			<div onDblClick="modLigne('<?php echo $ligne; ?>');" class="ligneEdit" style="margin-bottom:-10px;">
	
			<form id="formLigne" action="" method="post" name="<?php echo $ligne; ?>" >
			
				<tr  class="<?php echo $c; ?>">
				<td width="110"><?php echo $rowA['CodeaBarre']; ?></td>
					<td width="110"><?php echo $rowA['NomArt']; ?></td>
					<td width="230" align="right"><?php echo $rowA['Qte']; ?></td>
					<td width="50" align="right" style=""><?php $rowA['PA']; ?>	</td>
				</tr>
		
			</form>
			</div>
			<?php
		}	
	?>
	</table>
	
	<div style="float:right; width:20%">
	Le: <?php echo date_format($row['dateFiche'], 'm/d/Y');?>
</p>
</DIV>
	<script language="javascript">
					window.print();
	</script>
</body>
</html>
