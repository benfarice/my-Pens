<?php 
	include("php.fonctions.php");
	require_once('connexion.php');
	session_start();
	include("lang.php");
//	$IdDepot=1;
$sql = "
		SELECT 
			idFiche as idFiche,numFiche,f.TypeVente, date as dateFiche,Dsg_".$_SESSION['lang']." Dsg
		FROM 
			fichetarifs f
			left join TypeVente  tc on tc.IdType= f.TypeVente
			where idFiche = ?";
	 $params = array($_GET['IdFiche']);
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
		<div id="logoTexte"><?php echo $trad['label']['FicheTarif']."  "; 
		echo ucfirst($row["numFiche"]);?></div>
      </div>

<p>
<div id="tete" >
<table border="0" cellspacing="0" cellpadding="4" width="40%" align="center" class=""  > 
	<tr>	
			<td  valign="top" >
				<div class="etiqForm" id="SYMBT" ><strong> <?php echo $trad['label']['TypeVente'];?>: </strong> </div>	</td>
			<td><?php 
				 echo $row['Dsg'];
				 ?> </td>

	</tr>			
				
</table>
</div><br>
<div class="corps">
	<?php
	$sqlA = "
		SELECT Reference as CodeaBarre,t.qteMin as Qte,t.pvHt,a.designation as NomArt
		 FROM tarifs t 
		 inner join fichetarifs f on f.idFiche=t.idFiche 
		 inner join articles a on a.idArticle=t.idArticle 
		 where t.idDepot=1 and   t.idFiche = ?";

	 $params = array($_GET['IdFiche']);
	 $resAffA = sqlsrv_query($conn,$sqlA,$params) or die( print_r( sqlsrv_errors(), true));

	?>
	<table width="100%" class="table">
			     <tr class="entete">
					<td><?php echo $trad['label']['CodeArticle'];?> </td>
					<td><?php echo $trad['label']['Article'];?> </td>
					<td style="display:none"> <?php echo $trad['label']['Qte'];?></td>
					 <td> <?php echo $trad['Menu']['tarif']." (".$trad['label']['riyal'].")";?></td>
      
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
					<td width="20%" style="display:none" align="right"><?php
				echo number_format($rowA['Qte'], 2, '.', ' '); 
				?></td>
					<td width="15%" align="right" style="">	<span class="nbr"><?php
					echo number_format($rowA['pvHt'], 2, '.', ' '); 
				 ?>	</span></td>
				</tr>
		
			<?php
		}	
	?>
	</table>
	</div>
	<br>
	<div style="float:<?php if ($_SESSION['align']=="left") echo "right"; else echo "left";;?>; width:30%">
	<?php echo $trad['label']['Le'];?>: <?php echo date_format($row['dateFiche'], 'd/m/Y');?>
</p>
</DIV>
	<script language="javascript">
			//		window.print();
	</script>
</body>
</html>
