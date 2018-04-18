<?php
include("../php.fonctions.php");
require_once('../connexion.php');
  if(!isset($_SESSION))
{
session_start();
}
$idDepot=	$_SESSION['IdDepot'];
include("lang.php");
if (isset($_GET['goAddVidange'])){ 
//print_r($_POST);echo $_SESSION['IdVendeur'];

/* --------------------Begin transaction---------------------- */
$error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
		
	if(isset($_FILES['file']))
	{
		$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
		$file_extension = end($ext); // Store extensions in the variable.
		$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
		if (!file_exists("imgVendeur/")) {
			mkdir("imgVendeur/", 0777, true);
		}
		$target_path = "imgVendeur/" . $nameFile;     // Set the target path with a new name of image.
		
			$error="";
			
			if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
				{
				?>
							<script type="text/javascript"> 
								alert("<?php echo $trad['msg']['echecDeplacementImage'] ; ?>");
							</script>
				<?php
				return;
				}
	}
	else
	{
		$target_path = "";     // Set the target path with a new name of image.
	}
	//---------------------------IdVehicule--------------------------------//
/*$idVehicule="";	
$sql = "SELECT a.idVehicule FROM affectations a WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;*/
$idVehicule =$_GET['idVehicule'];// sqlsrv_get_field( $stmt2, 0);
//echo $idVehicule ."----". $target_path;//return;
	
	  $dateD=date("m/d/Y");
		$reqInser1 = "INSERT INTO Frais ([IdVendeur] ,[idVehicule] ,[DateOperation] ,[Operation],[Montant],Km,latitude,longitude,
		IdDepot ,[TypeFiltre],[TypeVidange]) values 	(?,?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		$_SESSION['IdVendeur'],
		$idVehicule,
		$dateD,
		"Vidange",
		str_replace(' ', '',$_POST['mt']),
		str_replace(' ', '',$_POST['km']),
		$_POST['lat1'],
		$_POST['lng1'],
		$idDepot,
		$_POST['filtre'],
		$_POST['type']
		) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					jAlert('<?php echo $trad['msg']['messageAjoutSucces']; ?>',"<?php echo $trad['titre']['Confirm']; ?>"); 
					
					$("#boxVidange").dialog('close');
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
			// echo "<font style='color:red'>".$error."</font>";
			?>
				<script type="text/javascript"> 
					jAlert('<?php echo $trad['msg']['Erreur']; ?>',"<?php echo $trad['titre']['Alert']; ?>"); 
				</script>
			<?php
		}	

exit;
}
if (isset($_GET['goAddOther'])){ //print_r($_POST);return;
//print_r($_POST);echo $_SESSION['IdVendeur'];

/* --------------------Begin transaction---------------------- */
$error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
		
//---------------------------IdVehicule--------------------------------//
/*$idVehicule="";	
$sql = "SELECT a.idVehicule FROM affectations a WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;*/
$idVehicule = $_GET['idVehicule'];//sqlsrv_get_field( $stmt2, 0);
//echo $idVehicule ."----". $target_path;//return;
	
	  $dateD=date("m/d/Y");
		$reqInser1 = "INSERT INTO Frais ([IdVendeur] ,[idVehicule] ,[DateOperation] ,[Operation],[Montant],latitude,longitude,
		IdDepot,Details) values 	(?,?,?,?,?,?,?,?,?)";

		$params1= array(
		$_SESSION['IdVendeur'],
		$idVehicule,
		$dateD,
		"Divers",
		str_replace(' ', '',$_POST['mt']),
		$_POST['lat'],
		$_POST['lng'],$idDepot,
		$_POST['details']
		) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					
					jAlert('<?php echo $trad['msg']['messageAjoutSucces']; ?>',"<?php echo $trad['titre']['Confirm']; ?>"); 
					$("#box").dialog('close');
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
			 //echo "<font style='color:red'>".$error."</font>";
			?>
				<script type="text/javascript"> 
					jAlert('<?php echo $trad['msg']['Erreur']; ?>',"<?php echo $trad['titre']['Alert']; ?>"); 
				</script>
			<?php
		}	

exit;
}
if (isset($_GET['goAddGasoil'])){ 
//print_r($_POST);echo $_SESSION['IdVendeur'];

/* --------------------Begin transaction---------------------- */
$error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
		
	if(isset($_FILES['file']))
	{
		$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
		$file_extension = end($ext); // Store extensions in the variable.
		$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
		if (!file_exists("imgVendeur/")) {
			mkdir("imgVendeur/", 0777, true);
		}
		$target_path = "imgVendeur/" . $nameFile;     // Set the target path with a new name of image.
		
			$error="";
			
			if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
				{
				?>
							<script type="text/javascript"> 
								alert("<?php echo $trad['msg']['echecDeplacementImage']; ?>");
							</script>
				<?php
				return;
				}
	}
	else
	{
		$target_path = "";     // Set the target path with a new name of image.
	}
	//---------------------------IdVehicule--------------------------------//
$idVehicule="";	
$sql = "SELECT a.idVehicule FROM affectations a WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$idVehicule = sqlsrv_get_field( $stmt2, 0);
//echo $idVehicule ."----". $target_path;//return;

	  $dateD=date("m/d/Y");
		$reqInser1 = "INSERT INTO Frais ([IdVendeur] ,[idVehicule] ,[DateOperation] ,[Operation],[Montant],Km,latitude,longitude,IdDepot,Bon) values 	(?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		$_SESSION['IdVendeur'],
		$idVehicule,
		$dateD,
		"Gasoil",
		str_replace(' ', '',$_POST['mt']),
		str_replace(' ', '',$_POST['km']),
		$_POST['lat'],
		$_POST['lng'],$idDepot,
		$target_path
		) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}
	
		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
				jAlert('<?php echo $trad['msg']['messageAjoutSucces']; ?>',"<?php echo $trad['titre']['Confirm']; ?>"); 
					
					$("#box").dialog('close');
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );//echo " 444 here ".$error;return;
			// echo "<font style='color:red'>".$error."</font>";
			?>
				<script type="text/javascript"> 
					jAlert('<?php echo $trad['msg']['Erreur']; ?>',"<?php echo $trad['titre']['Alert']; ?>"); 
				</script>
			<?php
		}	

exit;
}
if (isset($_GET['goAddAutoroute'])){ 
//print_r($_POST);echo $_SESSION['IdVendeur'];

/* --------------------Begin transaction---------------------- */
$error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
		
	if(isset($_FILES['file']))
	{
		$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
		$file_extension = end($ext); // Store extensions in the variable.
		$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
		if (!file_exists("imgVendeur/")) {
			mkdir("imgVendeur/", 0777, true);
		}
		$target_path = "imgVendeur/" . $nameFile;     // Set the target path with a new name of image.
		
			$error="";
			
			if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
				{
				?>
							<script type="text/javascript"> 
								alert("<?php echo $trad['msg']['echecDeplacementImage']; ?>");
							</script>
				<?php
				return;
				}
	}
	else
	{
		$target_path = "";     // Set the target path with a new name of image.
	}
	//---------------------------IdVehicule--------------------------------//
/*$idVehicule="";	
$sql = "SELECT a.idVehicule FROM affectations a WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;*/
$idVehicule =$_GET["IdVehicule"]; //sqlsrv_get_field( $stmt2, 0);
//echo $idVehicule ."----". $target_path;//return;
	
	  $dateD=date("m/d/Y");
		$reqInser1 = "INSERT INTO Frais ([IdVendeur] ,[idVehicule] ,[DateOperation] ,[Operation],[Montant],latitude,longitude,IdDepot,Bon,EntreAutoroute,SortieAutoroute) values (?,?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		$_SESSION['IdVendeur'],
		$idVehicule,
		$dateD,
		"Autoroute",
		str_replace(' ', '',$_POST['mt']),
		$_POST['lat'],
		$_POST['lng'],$idDepot,
		$target_path,
		$_POST['Ville1'],	
		$_POST['Ville2']		
		) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
						jAlert('<?php echo $trad['msg']['messageAjoutSucces']; ?>',"<?php echo $trad['titre']['Confirm']; ?>");
					
					$("#box").dialog('close');
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
			 //echo "<font style='color:red'>".$error."</font>";
			?>
				<script type="text/javascript"> 
					jAlert('<?php echo $trad['msg']['Erreur']; ?>',"<?php echo $trad['titre']['Alert']; ?>"); 
				</script>
			<?php
		}	

exit;
}
/*if (isset($_GET['goAddGasoil'])){ 
//print_r($_POST);echo $_SESSION['IdVendeur'];


$error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
		
	if(isset($_FILES['file']))
	{
		$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
		$file_extension = end($ext); // Store extensions in the variable.
		$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
		if (!file_exists("imgVendeur/")) {
			mkdir("imgVendeur/", 0777, true);
		}
		$target_path = "imgVendeur/" . $nameFile;     // Set the target path with a new name of image.
		
			$error="";
			
			if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
				{
				?>
							<script type="text/javascript"> 
								alert("<?php echo $trad['msg']['echecDeplacementImage']; ?>");
							</script>
				<?php
				return;
				}
	}
	else
	{
		$target_path = "";     // Set the target path with a new name of image.
	}
	//---------------------------IdVehicule--------------------------------//
$idVehicule="";	
$sql = "SELECT a.idVehicule FROM affectations a WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
sqlsrv_fetch($stmt2) ;
$idVehicule = $_GET['IdVnd']; // sqlsrv_get_field( $stmt2, 0);
//echo $idVehicule ."----". $target_path;//return;
	
	    $dateD=date("m/d/Y");
		$reqInser1 = "INSERT INTO Frais ([IdVendeur] ,[idVehicule] ,[DateOperation] ,[Operation],[Montant],Km,latitude,longitude,IdDepot,Bon) values 	(?,?,?,?,?,?,?,?,?,?)";

		$params1= array(
		$_SESSION['IdVendeur'],
		$idVehicule,
		$dateD,
		"Gasoil",
		str_replace(' ', '',$_POST['mt']),
		str_replace(' ', '',$_POST['km']),
		$_POST['lat'],
		$_POST['lng'],1,
		$target_path
		) ;

		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}

		if( $error=="" ) {
			 sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
					jAlert('<?php echo $trad['msg']['messageAjoutSucces']; ?>',"<?php echo $trad['titre']['Confirm']; ?>");
					
					$("#box").dialog('close');
				
				</script>
		<?php
		} else {
			 sqlsrv_rollback( $conn );
			 echo "<font style='color:red'>".$error."</font>";
		}	

exit;
}*/
if (isset($_GET['addVidange'])){ 

$sql = "SELECT Km FROM Frais f WHERE idVendeur=".$_SESSION["IdVendeur"]." and Operation='Vidange' ORDER BY IdFrais desc";
$stmt = sqlsrv_query( $conn, $sql, array(),array( "Scrollable" => 'static' ) );
	if( $stmt === false ) 
	{
			$errors = sqlsrv_errors();
			echo "Erreur : ".$errors[0]['message'] . " <br/> ";
			return;
	}
	//echo $sql ;
$nRes = sqlsrv_num_rows($stmt);
$Km=0;
if($nRes != 0 )
{
$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ;
$Km=$row["Km"];
}

$idVehicule = $_GET['IdVnd'];
$immatriculation = $_GET['Imm'];
$Vehicule = $_GET['Vehicule'];
$Type = $_GET['Type'];
?>

<!--link rel="stylesheet" href="css/stylesheet-image-based.css"-->
<style>
input[type=radio]{
		display:none;
	}

input[type=radio] + label{
		display:inline-block;
		font-weight:bold;
		padding: 14px 12px;
		margin-bottom: 0;
		line-height: 20px;
		color: #333;
		text-align: center;
		text-shadow: 0 1px 1px rgba(255,255,255,0.75);
		vertical-align: middle;
		cursor: pointer;
		background-color: #f5f5f5;
		background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
		background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
		background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
		background-image: -o-linear-gradient(top,#fff,#e6e6e6);
		background-image: linear-gradient(to bottom,#fff,#e6e6e6);
		background-repeat: repeat-x;
		border: 1px solid #ccc;
		border-color: #e6e6e6 #e6e6e6 #bfbfbf;
		border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
		border-bottom-color: #b3b3b3;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
		filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
		-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
		-moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
		box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
		-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
	}

	 input[type=radio]:checked + label{
		   background-image: none;
		outline: 0;
		-webkit-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
		box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
			background-color:#e0e0e0;
	}
.info{

font-size:14px;
}
</style>
<div style="padding:10px;margin:0px;width:100%;background-color:#1a4ba8;line-height:30px;color:white;font-weight:bold;">
<span><strong><?php echo $trad['frais']['immatriculation'] ; ?></strong> : </span><?php echo $immatriculation; ?>&nbsp;&nbsp;&nbsp;<span><strong><?php echo $trad['frais']['vehicule'] ; ?></strong> : </span><?php echo $Vehicule; ?><br/>
	<!--input type="button" value=""  class="close2" onclick="Fermer()" Style="display:clear;float:right;"/-->
</div>
<form id="formAddVidange" action="NULL" method="post"  name="formAdd1"  enctype="multipart/form-data" > 	
<table border="0" cellspacing="10" cellpadding="5" style="margin:auto;margin-top:10px;">
<tr><td ><strong><u><?php echo $trad['label']['type'] ; ?></u></strong></td>
<td>
<select multiple="multiple" id="type" name="type" Class="Select type" style="width:280px;">
					 <option value="3000" style="direction:ltr;">3,000 <?php echo $trad['frais']['km'] ; ?></option>
					 <option value="5000" style="direction:ltr;">5,000 <?php echo $trad['frais']['km'] ; ?></option>
					 <option value="7000" style="direction:ltr;">7,000 <?php echo $trad['frais']['km'] ; ?></option>
					 <option value="10000" style="direction:ltr;">10,000 <?php echo $trad['frais']['km'] ; ?></option>					 
</select>
</td>
<td ><strong><u><?php echo $trad['frais']['kilometrage'] ; ?> </u>:</strong></td>
<td>
<input class="numberOnly" id="km" name="km" onblur="calcule(<?php echo $Km; ?>)" style="direction:ltr;" onkeypress="return isEntier(event) " /> 
<!--span style="font-size:14px">Prochain Vidange :</span><input id="Prkm" name="Prkm" style="border:none;padding:0px;" readonly /--> 
</td>
</tr>

<tr>
<td><strong><u><?php echo $trad['frais']['montant'] ; ?></u>:</strong></td><td><input class="numberOnly"  id="mt" name="mt" style="direction:ltr;" onkeypress="return isEntier(event) " /></td>
<td><strong><u><?php echo $trad['frais']['bon'] ; ?> </u>:</strong></td>
<td>
  <input  class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="10" tabindex="1" value="" />
</td></tr>

<tr><td colspan="3">
    <div class="example">
      <div>
        <input id="radio1" type="radio" name="filtre" value="huile" ><label for="radio1"><?php echo $trad['frais']['filtreHuile'] ; ?></label>
			<?php
			
				$sql1 = "SELECT convert(varchar(20),DateOperation,103) as DateOperation,km FROM Frais f WHERE idVendeur=".$_SESSION["IdVendeur"]." and Operation='Vidange' and TypeFiltre='huile' ORDER BY IdFrais desc";
				//echo $sql1;
				$stmt1 = sqlsrv_query( $conn, $sql1 , array(),array( "Scrollable" => 'static' ));
					if( $stmt1 === false ) 
					{
							$errors = sqlsrv_errors();
							echo "Erreur : ".$errors[0]['message'] . " <br/> ";
							return;
					}
					//echo $sql ;
				$nRes1 = sqlsrv_num_rows($stmt1);
				$Km1=0;$Date="";
				if($nRes1 != 0 )
				{ //echo "here------";
				$row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC ) ;
				
				?>
				<span class="info"><strong> <?php echo $trad['frais']['DernierDate'] ; ?>: <?php echo $row1["DateOperation"];?> , <?php echo $trad['frais']['km'] ; ?> :  </strong>
				<input size="7" id="kmhuile" name="kmhuile" value="<?php echo $row1["km"];?>" valeur="<?php echo $row1["km"];?>" style="border:0;padding:0px; background:none;font-size:14px;font-weight:bold;" readonly /> 
				
				</span>
				
				<?php
				}
				?>
      </div><br/>
      <div>
        <input id="radio2" type="radio" name="filtre" value="carburant"><label for="radio2"><?php echo $trad['frais']['filtreCarburant']; ?></label>
			<?php
			
				$sql1 = "SELECT convert(varchar(20),DateOperation,103) as DateOperation,km FROM Frais f WHERE idVendeur=".$_SESSION["IdVendeur"]." and Operation='Vidange' and TypeFiltre='carburant' ORDER BY IdFrais desc";
				//echo $sql1;
				$stmt1 = sqlsrv_query( $conn, $sql1 , array(),array( "Scrollable" => 'static' ));
					if( $stmt1 === false ) 
					{
							$errors = sqlsrv_errors();
							echo "Erreur : ".$errors[0]['message'] . " <br/> ";
							return;
					}
					//echo $sql ;
				$nRes1 = sqlsrv_num_rows($stmt1);
				$Km1=0;$Date="";
				if($nRes1 != 0 )
				{ //echo "here------";
				$row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC ) ;
				
				?>
				<span class="info"><strong> <?php echo $trad['frais']['DernierDate'] ; ?>: <?php echo $row1["DateOperation"];?> , <?php echo $trad['frais']['km'] ; ?> :  </strong>
				<input id="kmcarburant" name="kmcarburant" value="<?php echo $row1["km"];?> " valeur="<?php echo $row1["km"];?>" style="border:0;padding:0px; background:none;font-size:14px;font-weight:bold;" readonly /> </span>
				
				<?php
				}
				?>
      </div><br/>
      <div>
        <input id="radio3" type="radio" name="filtre" value="air"><label for="radio3"><?php echo $trad['frais']['filtreAir']; ?></label>
			<?php
			
				$sql1 = "SELECT convert(varchar(20),DateOperation,103) as DateOperation,km FROM Frais f WHERE idVendeur=".$_SESSION["IdVendeur"]." and Operation='Vidange' and TypeFiltre='air' ORDER BY IdFrais desc";
				//echo $sql1;
				$stmt1 = sqlsrv_query( $conn, $sql1 , array(),array( "Scrollable" => 'static' ));
					if( $stmt1 === false ) 
					{
							$errors = sqlsrv_errors();
							echo "Erreur : ".$errors[0]['message'] . " <br/> ";
							return;
					}
					//echo $sql ;
				$nRes1 = sqlsrv_num_rows($stmt1);
				$Km1=0;$Date="";
				if($nRes1 != 0 )
				{ //echo "here------";
				$row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC ) ;
				
				?>
				<span class="info"><strong> <?php echo $trad['frais']['DernierDate'] ; ?>: <?php echo $row1["DateOperation"];?> , <?php echo $trad['frais']['km'] ; ?> :  </strong>
				<input id="kmair" name="kmair" value="<?php echo $row1["km"];?> " valeur="<?php echo $row1["km"];?>" style="border:0;padding:0px; background:none;font-size:14px;font-weight:bold;" readonly /> </span>
				
				<?php
				}
				?>
      </div>
    </div>

</td>
<td>
<div id="map2" style="width:500px; height:180px;float:right;"></div>
</td>
</tr>
</table>
<div style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>; margin-<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:20px;">
<input type="button" value="<?php echo $trad['button']['enregistrer'] ; ?>" class="btn" onclick='valider(<?php echo $idVehicule ?>)' />
<input type="button" value="<?php echo $trad['button']['Fermer'] ; ?>" class="btn" onclick='Fermer()' />
<input type="hidden" value="" id="lat1" name="lat1" /><input type="hidden" value="" id="lng1" name="lng1" />
<div>
</form>
<div id="resAdd"></div>

<script language="javascript" type="text/javascript">
$(document).ready(function() {
$("#boxVidange").dialog("open");
initMap("map2");

if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     //pos = {lat: position.coords.latitude, lng: position.coords.longitude};
					 $("#lat1").val(position.coords.latitude);
					 $("#lng1").val(position.coords.longitude);
					//alert("Geo : "+position.coords.latitude);
                });
				}
				
$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});
});

$('#type').multipleSelect({
filter: true,placeholder:'<?php echo $trad['map']['selectType'] ; ?>',single:true,maxHeight: 200,
  onClick: function(view) {
				
				var Secteur2 =$('#type').val();
				if(Secteur2!="") {
					$('div.type').removeClass('erroer');
					$('div.type button').css("border","1px solid #ccc").css("background","#fff");
				}
            }
});
$("input[name='filtre']").click(function(){

var kmNew=$('#km').val().replace(/\s/g,'');

if($('input:radio[name=filtre]:checked').val() == "huile"){
	
		if(parseInt(kmNew) <= parseInt($('#kmhuile').attr('valeur')))
		{
		alert("<?php echo $trad['msg']['KmSup'] ; ?>"+$('#kmhuile').attr('valeur'));
		$('#km').val("");
		$('#kmhuile').val($('#kmhuile').attr('valeur'));
		return;
		}

 }
 else if($('input:radio[name=filtre]:checked').val() == "carburant")
 {
		if(parseInt(kmNew) <= parseInt($('#kmcarburant').attr('valeur')))
		{
		alert("<?php echo $trad['msg']['KmSup'] ; ?>"+$('#kmcarburant').attr('valeur'));
		$('#km').val("");
		$('#kmcarburant').val($('#kmcarburant').attr('valeur'));
		return;
		}
 } else if($('input:radio[name=filtre]:checked').val() == "air")
 {
		if(parseInt(kmNew) <= parseInt($('#kmair').attr('valeur')))
		{
		alert("<?php echo $trad['msg']['KmSup'] ; ?>"+$('#kmair').attr('valeur'));
		$('#km').val("");
		$('#kmair').val($('#kmair').attr('valeur'));
		return;
		}
 }

});
function calcule(kmOld)
{
var kmNew=$('#km').val().replace(/\s/g,'');

if($('#km').val() ==="")
{
$('#kmhuile').val($('#kmhuile').attr('valeur'));
$('#kmcarburant').val($('#kmcarburant').attr('valeur'));
$('#kmair').val($('#kmair').attr('valeur'));
return;
}	
	if($('#kmhuile').val() !="")
	{
		var kmH=$('#kmhuile').attr('valeur');
		var km=parseInt(kmNew)-parseInt(kmH);
		$('#kmhuile').val(km);
	}
	if($('#kmcarburant').val() !="")
	{
		var kmC=$('#kmcarburant').attr('valeur');
		var km=parseInt(kmNew)-parseInt(kmC);
		$('#kmcarburant').val(km);
	}
	if($('#kmair').val() !="")
	{
		var kmA=$('#kmair').attr('valeur');
		var km=parseInt(kmNew)-parseInt(kmA);
		$('#kmair').val(km);
	}	

	/*var km=$('#km').val().replace(/\s/g,'');
	if(km==='')
	km=0;
	var kmPr=parseInt(km)+parseInt(kmOld);
	$('#Prkm').val(kmPr);*/
}


function Fermer(){
	
	$("#boxVidange").dialog('close');

}
function valider(IdVehicule){
 $("#formAddVidange").validate({
                     rules: { 
											type:"required",
											km:"required",
                                            mt: "required"
                            } 
		});
	var test=$("#formAddVidange").valid();//alert(test);
	verifSelect2('type');
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['confimationDajout'] ; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						$('#formAddVidange').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'frais.php?goAddVidange&idVehicule='+IdVehicule,
														method			:	'post'
													}); 

		
					}
				})
		}

}


$('#Prkm').keydown(function() {
  //code to not allow any changes to be made to input field
  return false;
});


$('.numberOnly').on('keydown', function(e){
	
  if(this.selectionStart || this.selectionStart == 0){
	// selectionStart won't work in IE < 9
	
	var key = e.which;
	var prevDefault = true;
	
	var thouSep = " ";  // your seperator for thousands
	var deciSep = ".";  // your seperator for decimals
	var deciNumber = 2; // how many numbers after the comma
	
	var thouReg = new RegExp(thouSep,"g");
	var deciReg = new RegExp(deciSep,"g");
	
	function spaceCaretPos(val, cPos){
		/// get the right caret position without the spaces
		
		if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
		cPos = cPos-1;
		
		if(val.substring(0,cPos).indexOf(thouSep) >= 0){
		cPos = cPos - val.substring(0,cPos).match(thouReg).length;
		}
		
		return cPos;
	}
	
	function spaceFormat(val, pos){
		/// add spaces for thousands
		
		if(val.indexOf(deciSep) >= 0){
			var comPos = val.indexOf(deciSep);
			var int = val.substring(0,comPos);
			var dec = val.substring(comPos);
		} else{
			var int = val;
			var dec = "";
		}
		var ret = [val, pos];
		
		if(int.length > 3){
			
			var newInt = "";
			var spaceIndex = int.length;
			
			while(spaceIndex > 3){
				spaceIndex = spaceIndex - 3;
				newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
				if(pos > spaceIndex) pos++;
			}
			ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
		}
		return ret;
	}
	
	$(this).on('keyup', function(ev){
		
		if(ev.which == 8){
			// reformat the thousands after backspace keyup
			
			var value = this.value;
			var caretPos = this.selectionStart;
			
			caretPos = spaceCaretPos(value, caretPos);
			value = value.replace(thouReg, '');
			
			var newValues = spaceFormat(value, caretPos);
			this.value = newValues[0];
			this.selectionStart = newValues[1];
			this.selectionEnd   = newValues[1];
		}
	});
	
	if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
	   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
		prevDefault = false;
	
	if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
		e.preventDefault();
		
		if(!e.altKey && !e.shiftKey && !e.ctrlKey){
		
			var value = this.value;
			if((key > 95 && key < 106)||(key > 47 && key < 58) ||
			  (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
				
				var keys = { // reformat the keyCode
				48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
				96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
				110: deciSep, 188: deciSep, 190: deciSep
				};
				
				var caretPos = this.selectionStart;
				var caretEnd = this.selectionEnd;
				
				if(caretPos != caretEnd) // remove selected text
				value = value.substring(0,caretPos) + value.substring(caretEnd);
				
				caretPos = spaceCaretPos(value, caretPos);
				
				value = value.replace(thouReg, '');
				
				var before = value.substring(0,caretPos);
				var after = value.substring(caretPos);
				var newPos = caretPos+1;
				
				if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
					if(before.indexOf(deciSep) >= 0){ newPos--; }
					before = before.replace(deciReg, '');
					after = after.replace(deciReg, '');
				}
				var newValue = before + keys[key] + after;
				
				if(newValue.substring(0,1) == deciSep){
					newValue = "0"+newValue;
					newPos++;
				}
				
				while(newValue.length > 1 && 
				  newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
					newValue = newValue.substring(1);
					newPos--;
				}
				
				if(newValue.indexOf(deciSep) >= 0){
					var newLength = newValue.indexOf(deciSep)+deciNumber+1;
					if(newValue.length > newLength){
					newValue = newValue.substring(0,newLength);
					}
				}
				
				newValues = spaceFormat(newValue, newPos);
				
				this.value = newValues[0];
				this.selectionStart = newValues[1];
				this.selectionEnd   = newValues[1];
			}
		}
	}
	
	$("#mt").on('blur', function(e){
		
		if(deciNumber > 0){
			var value = this.value;
			
			var noDec = "";
			for(var i = 0; i < deciNumber; i++)
			noDec += "0";
			
			if(value == "0"+deciSep+noDec)
			this.value = ""; //<-- put your default value here
			else
			if(value.length > 0){
				if(value.indexOf(deciSep) >= 0){
					var newLength = value.indexOf(deciSep)+deciNumber+1;
					if(value.length < newLength){
					while(value.length < newLength){ value = value+"0"; }
					this.value = value.substring(0,newLength);
					}
				}
				else this.value = value + deciSep + noDec;
			}
		}
	});
  }
});

</script>
<?php
exit;
}
if (isset($_GET['addOther'])){ 

$idVehicule = $_GET['IdVnd'];
$immatriculation = $_GET['Imm'];
$Vehicule = $_GET['Vehicule'];
$Type = $_GET['Type'];

?>

<div style="padding:10px;margin:0px;width:100%;background-color:#1a4ba8;line-height:30px;color:white;font-weight:bold;">
<span><strong><?php echo $trad['frais']['immatriculation'] ; ?></strong> : </span><?php echo $immatriculation; ?>&nbsp;&nbsp;&nbsp;<span><strong><?php echo $trad['frais']['vehicule'] ; ?></strong> : </span><?php echo $Vehicule; ?><br/>
	<!--input type="button" value=""  class="close2" onclick="Fermer()" Style="display:clear;float:right;"/-->
</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"  enctype="multipart/form-data" > 	
<table border="0" cellspacing="10" cellpadding="5" style="margin-top:10px;">
<tr>
<td><strong><u><?php echo $trad['frais']['montant'] ; ?></u>:</strong></td><td><input class="numberOnly"  id="mt" name="mt" onkeypress="return isEntier(event) " style="direction:ltr;" /></td>
<td rowspan="2" valign="top"><div id="map" style="width:280px; height:295px;"></div></td>
</tr>
<tr><td><strong><u><?php echo $trad['frais']['details'] ; ?> </u>:</strong></td>
<td>
   <textarea id="details" name="details" style="padding:10px;;font-size:26px;" rows="7" cols="30" value=""/>
</td></tr>
</table>
<div style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>; margin-<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:20px;margin-top:15px;">
<input type="button" value="<?php echo $trad['button']['enregistrer'] ; ?>" class="btn" onclick='valider(<?php echo $idVehicule ?>)' />
<input type="button" value="<?php echo $trad['button']['Fermer'] ; ?>" class="btn" onclick='Fermer()' />
<input type="hidden" value="" id="lat" name="lat" /><input type="hidden" value="" id="lng" name="lng" />
<div>
</form>
<div id="resAdd"></div>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
initMap("map");
 /*if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     //pos = {lat: position.coords.latitude, lng: position.coords.longitude};
					 $("#lat").val(position.coords.latitude);
					 $("#lng").val(position.coords.longitude);
					// alert(position.coords.latitude);
                });
				}*/
});
function Fermer(){
	
	$("#box").dialog('close');

}
function valider(IdVehicule){

 $("#formAdd").validate({
                     rules: { 
                                                mt: "required",
												details: "required"
                                        } 
		});
	var test=$("#formAdd").valid();
	
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['confimationDajout'] ; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'frais.php?goAddOther&idVehicule='+IdVehicule,
														method			:	'post'
													}); 

		
					}
				})
		}

}
$('.numberOnly').on('keydown', function(e){
	
  if(this.selectionStart || this.selectionStart == 0){
	// selectionStart won't work in IE < 9
	
	var key = e.which;
	var prevDefault = true;
	
	var thouSep = " ";  // your seperator for thousands
	var deciSep = ".";  // your seperator for decimals
	var deciNumber = 2; // how many numbers after the comma
	
	var thouReg = new RegExp(thouSep,"g");
	var deciReg = new RegExp(deciSep,"g");
	
	function spaceCaretPos(val, cPos){
		/// get the right caret position without the spaces
		
		if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
		cPos = cPos-1;
		
		if(val.substring(0,cPos).indexOf(thouSep) >= 0){
		cPos = cPos - val.substring(0,cPos).match(thouReg).length;
		}
		
		return cPos;
	}
	
	function spaceFormat(val, pos){
		/// add spaces for thousands
		
		if(val.indexOf(deciSep) >= 0){
			var comPos = val.indexOf(deciSep);
			var int = val.substring(0,comPos);
			var dec = val.substring(comPos);
		} else{
			var int = val;
			var dec = "";
		}
		var ret = [val, pos];
		
		if(int.length > 3){
			
			var newInt = "";
			var spaceIndex = int.length;
			
			while(spaceIndex > 3){
				spaceIndex = spaceIndex - 3;
				newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
				if(pos > spaceIndex) pos++;
			}
			ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
		}
		return ret;
	}
	
	$(this).on('keyup', function(ev){
		
		if(ev.which == 8){
			// reformat the thousands after backspace keyup
			
			var value = this.value;
			var caretPos = this.selectionStart;
			
			caretPos = spaceCaretPos(value, caretPos);
			value = value.replace(thouReg, '');
			
			var newValues = spaceFormat(value, caretPos);
			this.value = newValues[0];
			this.selectionStart = newValues[1];
			this.selectionEnd   = newValues[1];
		}
	});
	
	if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
	   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
		prevDefault = false;
	
	if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
		e.preventDefault();
		
		if(!e.altKey && !e.shiftKey && !e.ctrlKey){
		
			var value = this.value;
			if((key > 95 && key < 106)||(key > 47 && key < 58) ||
			  (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
				
				var keys = { // reformat the keyCode
				48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
				96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
				110: deciSep, 188: deciSep, 190: deciSep
				};
				
				var caretPos = this.selectionStart;
				var caretEnd = this.selectionEnd;
				
				if(caretPos != caretEnd) // remove selected text
				value = value.substring(0,caretPos) + value.substring(caretEnd);
				
				caretPos = spaceCaretPos(value, caretPos);
				
				value = value.replace(thouReg, '');
				
				var before = value.substring(0,caretPos);
				var after = value.substring(caretPos);
				var newPos = caretPos+1;
				
				if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
					if(before.indexOf(deciSep) >= 0){ newPos--; }
					before = before.replace(deciReg, '');
					after = after.replace(deciReg, '');
				}
				var newValue = before + keys[key] + after;
				
				if(newValue.substring(0,1) == deciSep){
					newValue = "0"+newValue;
					newPos++;
				}
				
				while(newValue.length > 1 && 
				  newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
					newValue = newValue.substring(1);
					newPos--;
				}
				
				if(newValue.indexOf(deciSep) >= 0){
					var newLength = newValue.indexOf(deciSep)+deciNumber+1;
					if(newValue.length > newLength){
					newValue = newValue.substring(0,newLength);
					}
				}
				
				newValues = spaceFormat(newValue, newPos);
				
				this.value = newValues[0];
				this.selectionStart = newValues[1];
				this.selectionEnd   = newValues[1];
			}
		}
	}
	
	$("#mt").on('blur', function(e){
		
		if(deciNumber > 0){
			var value = this.value;
			
			var noDec = "";
			for(var i = 0; i < deciNumber; i++)
			noDec += "0";
			
			if(value == "0"+deciSep+noDec)
			this.value = ""; //<-- put your default value here
			else
			if(value.length > 0){
				if(value.indexOf(deciSep) >= 0){
					var newLength = value.indexOf(deciSep)+deciNumber+1;
					if(value.length < newLength){
					while(value.length < newLength){ value = value+"0"; }
					this.value = value.substring(0,newLength);
					}
				}
				else this.value = value + deciSep + noDec;
			}
		}
	});
  }
});

</script>
<?php
exit;
}
if (isset($_GET['addAutoroute'])){ 
$idVehicule = $_GET['IdVnd'];
$immatriculation = $_GET['Imm'];
$Vehicule = $_GET['Vehicule'];
$Type = $_GET['Type'];
?>
<style>
FIELDSET {

    border: 1px solid silver;
    padding: 8px;    
    border-radius: 4px;

}
LEGEND{
    padding: 2px;    	
	text-decoration:underline;
	font-weight:bold;
}
</style>
<div style="padding:10px;margin:0px;width:100%;background-color:#1a4ba8;line-height:30px;color:white;font-weight:bold;">
<span><strong><?php echo $trad['frais']['immatriculation'] ; ?></strong> : </span><?php echo $immatriculation; ?>&nbsp;&nbsp;&nbsp;<span><strong><?php echo $trad['frais']['vehicule'] ; ?></strong> : </span><?php echo $Vehicule; ?><br/>
	<!--input type="button" value=""  class="close2" onclick="Fermer()" Style="display:clear;float:right;"/-->
</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"  enctype="multipart/form-data" > 	
<table border="0" cellspacing="10" cellpadding="5" style="margin-top:10px;">
<tr>
<td><strong><u><?php echo $trad['frais']['montant'] ; ?></u>:</strong></td><td><input class="numberOnly"  id="mt" name="mt" onkeypress="return isEntier(event) " style="direction:ltr;" /></td>
<td rowspan="2">
<div id="map" style="width:320px; height:180px;"></div>
</td>
</tr>

<tr><td><strong><u><?php echo $trad['frais']['bon'] ; ?> </u>:</strong></td>
<td>
  <input  class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="10" tabindex="1" value="" />
</td>
</tr>
<tr>
<td><strong><u><?php echo $trad['frais']['trajet'] ; ?>  </u>:</strong></td>
<td colspan="2">
<fieldset>
	
<div>
 
<?php 
 
$sql = "select idville, Designation from villes ";
$reponse=sqlsrv_query( $conn, $sql, array(), array( "Scrollable" => 'static' ) );  
$options=""; 
while ($donnees =  sqlsrv_fetch_array($reponse))
{
	$options.="	<option value='". $donnees['idville'] ."'>".$donnees['Designation']."</option>";
}

?>

<select id="Ville1" name="Ville1" multiple="multiple"  Class="Select Ville1" style="width:300px">
	<?php echo $options; ?>
</select>
&nbsp;&nbsp;
<select id="Ville2" name="Ville2" multiple="multiple"  Class="Select Ville2" style="width:300px">
	<?php echo $options; ?>
</select>
</div>
</fieldset>
</td>
</tr>
</table>

<div style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>; margin-<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:20px;margin-top:5px;">
<input type="button" value="<?php echo $trad['button']['enregistrer'] ; ?>" class="btn" onclick='valider(<?php echo $idVehicule; ?>)' />
<input type="button" value="<?php echo $trad['button']['Fermer'] ; ?>" class="btn" onclick='Fermer()' />
<input type="hidden" value="" id="lat" name="lat" /><input type="hidden" value="" id="lng" name="lng" />
<div>
</form>
<div id="resAdd"></div>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
initMap("map");

$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});	
$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});
$('#Ville1').multipleSelect({filter: true,placeholder:'<?php echo $trad['frais']['entree'] ; ?>',maxHeight: 200,single:true,position: 'top'});
$('#Ville2').multipleSelect({filter: true,placeholder:'<?php echo $trad['frais']['sortie'] ; ?>',maxHeight: 200,single:true, position: 'top'});
 /*if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     //pos = {lat: position.coords.latitude, lng: position.coords.longitude};
					 $("#lat").val(position.coords.latitude);
					 $("#lng").val(position.coords.longitude);
					// alert(position.coords.latitude);
                });
				}*/
});
function Fermer(){
	
	$("#box").dialog('close');

}
function valider(IdVehicule){

 $("#formAdd").validate({
                     rules: { 
                                                mt: "required"
                                        } 
		});
	var test=$("#formAdd").valid();
	
		if(test==true){		
			 jConfirm('<?php echo $trad['msg']['confimationDajout'] ; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'frais.php?goAddAutoroute&IdVehicule='+IdVehicule,
														method			:	'post'
													}); 

		
					}
				})
		}

}
$('.numberOnly').on('keydown', function(e){
	
  if(this.selectionStart || this.selectionStart == 0){
	// selectionStart won't work in IE < 9
	
	var key = e.which;
	var prevDefault = true;
	
	var thouSep = " ";  // your seperator for thousands
	var deciSep = ".";  // your seperator for decimals
	var deciNumber = 2; // how many numbers after the comma
	
	var thouReg = new RegExp(thouSep,"g");
	var deciReg = new RegExp(deciSep,"g");
	
	function spaceCaretPos(val, cPos){
		/// get the right caret position without the spaces
		
		if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
		cPos = cPos-1;
		
		if(val.substring(0,cPos).indexOf(thouSep) >= 0){
		cPos = cPos - val.substring(0,cPos).match(thouReg).length;
		}
		
		return cPos;
	}
	
	function spaceFormat(val, pos){
		/// add spaces for thousands
		
		if(val.indexOf(deciSep) >= 0){
			var comPos = val.indexOf(deciSep);
			var int = val.substring(0,comPos);
			var dec = val.substring(comPos);
		} else{
			var int = val;
			var dec = "";
		}
		var ret = [val, pos];
		
		if(int.length > 3){
			
			var newInt = "";
			var spaceIndex = int.length;
			
			while(spaceIndex > 3){
				spaceIndex = spaceIndex - 3;
				newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
				if(pos > spaceIndex) pos++;
			}
			ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
		}
		return ret;
	}
	
	$(this).on('keyup', function(ev){
		
		if(ev.which == 8){
			// reformat the thousands after backspace keyup
			
			var value = this.value;
			var caretPos = this.selectionStart;
			
			caretPos = spaceCaretPos(value, caretPos);
			value = value.replace(thouReg, '');
			
			var newValues = spaceFormat(value, caretPos);
			this.value = newValues[0];
			this.selectionStart = newValues[1];
			this.selectionEnd   = newValues[1];
		}
	});
	
	if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
	   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
		prevDefault = false;
	
	if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
		e.preventDefault();
		
		if(!e.altKey && !e.shiftKey && !e.ctrlKey){
		
			var value = this.value;
			if((key > 95 && key < 106)||(key > 47 && key < 58) ||
			  (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
				
				var keys = { // reformat the keyCode
				48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
				96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
				110: deciSep, 188: deciSep, 190: deciSep
				};
				
				var caretPos = this.selectionStart;
				var caretEnd = this.selectionEnd;
				
				if(caretPos != caretEnd) // remove selected text
				value = value.substring(0,caretPos) + value.substring(caretEnd);
				
				caretPos = spaceCaretPos(value, caretPos);
				
				value = value.replace(thouReg, '');
				
				var before = value.substring(0,caretPos);
				var after = value.substring(caretPos);
				var newPos = caretPos+1;
				
				if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
					if(before.indexOf(deciSep) >= 0){ newPos--; }
					before = before.replace(deciReg, '');
					after = after.replace(deciReg, '');
				}
				var newValue = before + keys[key] + after;
				
				if(newValue.substring(0,1) == deciSep){
					newValue = "0"+newValue;
					newPos++;
				}
				
				while(newValue.length > 1 && 
				  newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
					newValue = newValue.substring(1);
					newPos--;
				}
				
				if(newValue.indexOf(deciSep) >= 0){
					var newLength = newValue.indexOf(deciSep)+deciNumber+1;
					if(newValue.length > newLength){
					newValue = newValue.substring(0,newLength);
					}
				}
				
				newValues = spaceFormat(newValue, newPos);
				
				this.value = newValues[0];
				this.selectionStart = newValues[1];
				this.selectionEnd   = newValues[1];
			}
		}
	}
	
	$("#mt").on('blur', function(e){
		
		if(deciNumber > 0){
			var value = this.value;
			
			var noDec = "";
			for(var i = 0; i < deciNumber; i++)
			noDec += "0";
			
			if(value == "0"+deciSep+noDec)
			this.value = ""; //<-- put your default value here
			else
			if(value.length > 0){
				if(value.indexOf(deciSep) >= 0){
					var newLength = value.indexOf(deciSep)+deciNumber+1;
					if(value.length < newLength){
					while(value.length < newLength){ value = value+"0"; }
					this.value = value.substring(0,newLength);
					}
				}
				else this.value = value + deciSep + noDec;
			}
		}
	});
  }
});

</script>
<?php
exit;
}
if (isset($_GET['addGazoil'])){ 
//---------------------------IdVehicule--------------------------------//
/*$idVehicule="";	
$sql = "SELECT  v.idVehicule,v.immatriculation,v.Designation,tv.Designation as typeC  FROM vehicules v INNER JOIN affectations a ON v.idVehicule=a.idVehicule INNER JOIN typeVehicules tv ON v.idTypeVehicule=tv.idTypeVehicule WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
$row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC ) ;*/
$idVehicule = $_GET['IdVnd'];
$immatriculation = $_GET['Imm'];
$Vehicule = $_GET['Vehicule'];
$Type = $_GET['Type'];
?>
<div style="padding:10px;margin:0px;width:100%;background-color:#1a4ba8;line-height:30px;color:white;font-weight:bold;">
<span><strong><?php echo $trad['frais']['immatriculation'] ; ?></strong> : </span><?php echo $immatriculation; ?>&nbsp;&nbsp;&nbsp;<span><strong><?php echo $trad['frais']['vehicule'] ; ?></strong> : </span><?php echo $Vehicule; ?><br/>
	<!--input type="button" value=""  class="close2" onclick="Fermer()" Style="display:clear;float:right;"/-->
</div>
<form id="formAdd" action="NULL" method="post"  name="formAdd1"  enctype="multipart/form-data" > 	
<table border="0" width="100%" cellspacing="13" cellpadding="10" style="margin-top:10px;">
<tr><td><strong><u><?php echo $trad['frais']['montant'] ; ?></u>:</strong></td><td><input class="numberOnly"  id="mt" name="mt" onkeypress="return isEntier(event) " style="direction:ltr;" /></td>
<td rowspan="3">

<div id="map" style="width:300px; height:257px;float:left; "><!-- width:330px-->
</div>
</td>
</tr>
<tr><td><strong><u><?php echo $trad['frais']['kilometrage'] ; ?> </u>:</strong></td><td><input class="numberOnly" id="km" name="km" onkeypress="return isEntier(event) "  style="direction:ltr;" /></td></tr>
<tr><td><strong><u><?php echo $trad['frais']['bon'] ; ?> </u>:</strong></td>
<td>
  <input class="jfilestyle" data-input="false" type="file" name="file" control="1" id="file" size="10" tabindex="1" value="" />
</td></tr><!--class="nicefileinput nice"-->
</table>

<div style="float:<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>; margin-<?php echo ($_SESSION['lang'] == 'ar' ) ? 'left' : 'right'; ?>:20px;margin-top:5px;">
<input type="button" value="<?php echo $trad['button']['enregistrer'] ; ?>" class="btn" onclick='valider(<?php echo $idVehicule; ?>)' />
<input type="button" value="<?php echo $trad['button']['Fermer'] ; ?>" class="btn" onclick='Fermer()' />
<input type="hidden" value="" id="lat" name="lat" /><input type="hidden" value="" id="lng" name="lng" />
<div>
</form>
<div id="resAdd"></div>

<script language="javascript" type="text/javascript">


$(document).ready(function() {

initMap("map");
$(":file").jfilestyle({input: false,buttonText: "<img src='img/folder.png' /><?php echo $trad['button']['parcourir'] ; ?>"});			
/*$("input[type=file]").nicefileinput({
	label : 'Choisir...'
});*/

});


function Fermer(){
	
	$("#box").dialog('close');

}
function valider(IdVehicule){

 $("#formAdd").validate({
                     rules: { 
                                                mt: "required",
												km:"required"
                                        } 
		});
	var test=$("#formAdd").valid();
	if(test==true){		
			 jConfirm('<?php echo $trad['msg']['confimationDajout'] ; ?>', '<?php echo $trad['titre']['Confirm']; ?>', function(r) {
					if(r)	{
						$('#formAdd').ajaxSubmit({
														target			:	'#resAdd',
														url				:	'frais.php?goAddGasoil&idVehicule='+IdVehicule,
														method			:	'post'
													}); 

		
					}
				})
		}

}
$('.numberOnly').on('keydown', function(e){
	
  if(this.selectionStart || this.selectionStart == 0){
	// selectionStart won't work in IE < 9
	
	var key = e.which;
	var prevDefault = true;
	
	var thouSep = " ";  // your seperator for thousands
	var deciSep = ".";  // your seperator for decimals
	var deciNumber = 2; // how many numbers after the comma
	
	var thouReg = new RegExp(thouSep,"g");
	var deciReg = new RegExp(deciSep,"g");
	
	function spaceCaretPos(val, cPos){
		/// get the right caret position without the spaces
		
		if(cPos > 0 && val.substring((cPos-1),cPos) == thouSep)
		cPos = cPos-1;
		
		if(val.substring(0,cPos).indexOf(thouSep) >= 0){
		cPos = cPos - val.substring(0,cPos).match(thouReg).length;
		}
		
		return cPos;
	}
	
	function spaceFormat(val, pos){
		/// add spaces for thousands
		
		if(val.indexOf(deciSep) >= 0){
			var comPos = val.indexOf(deciSep);
			var int = val.substring(0,comPos);
			var dec = val.substring(comPos);
		} else{
			var int = val;
			var dec = "";
		}
		var ret = [val, pos];
		
		if(int.length > 3){
			
			var newInt = "";
			var spaceIndex = int.length;
			
			while(spaceIndex > 3){
				spaceIndex = spaceIndex - 3;
				newInt = thouSep+int.substring(spaceIndex,spaceIndex+3)+newInt;
				if(pos > spaceIndex) pos++;
			}
			ret = [int.substring(0,spaceIndex) + newInt + dec, pos];
		}
		return ret;
	}
	
	$(this).on('keyup', function(ev){
		
		if(ev.which == 8){
			// reformat the thousands after backspace keyup
			
			var value = this.value;
			var caretPos = this.selectionStart;
			
			caretPos = spaceCaretPos(value, caretPos);
			value = value.replace(thouReg, '');
			
			var newValues = spaceFormat(value, caretPos);
			this.value = newValues[0];
			this.selectionStart = newValues[1];
			this.selectionEnd   = newValues[1];
		}
	});
	
	if((e.ctrlKey && (key == 65 || key == 67 || key == 86 || key == 88 || key == 89 || key == 90)) ||
	   (e.shiftKey && key == 9)) // You don't want to disable your shortcuts!
		prevDefault = false;
	
	if((key < 37 || key > 40) && key != 8 && key != 9 && prevDefault){
		e.preventDefault();
		
		if(!e.altKey && !e.shiftKey && !e.ctrlKey){
		
			var value = this.value;
			if((key > 95 && key < 106)||(key > 47 && key < 58) ||
			  (deciNumber > 0 && (key == 110 || key == 188 || key == 190))){
				
				var keys = { // reformat the keyCode
				48: 0, 49: 1, 50: 2, 51: 3,  52: 4,  53: 5,  54: 6,  55: 7,  56: 8,  57: 9,
				96: 0, 97: 1, 98: 2, 99: 3, 100: 4, 101: 5, 102: 6, 103: 7, 104: 8, 105: 9,
				110: deciSep, 188: deciSep, 190: deciSep
				};
				
				var caretPos = this.selectionStart;
				var caretEnd = this.selectionEnd;
				
				if(caretPos != caretEnd) // remove selected text
				value = value.substring(0,caretPos) + value.substring(caretEnd);
				
				caretPos = spaceCaretPos(value, caretPos);
				
				value = value.replace(thouReg, '');
				
				var before = value.substring(0,caretPos);
				var after = value.substring(caretPos);
				var newPos = caretPos+1;
				
				if(keys[key] == deciSep && value.indexOf(deciSep) >= 0){
					if(before.indexOf(deciSep) >= 0){ newPos--; }
					before = before.replace(deciReg, '');
					after = after.replace(deciReg, '');
				}
				var newValue = before + keys[key] + after;
				
				if(newValue.substring(0,1) == deciSep){
					newValue = "0"+newValue;
					newPos++;
				}
				
				while(newValue.length > 1 && 
				  newValue.substring(0,1) == "0" && newValue.substring(1,2) != deciSep){
					newValue = newValue.substring(1);
					newPos--;
				}
				
				if(newValue.indexOf(deciSep) >= 0){
					var newLength = newValue.indexOf(deciSep)+deciNumber+1;
					if(newValue.length > newLength){
					newValue = newValue.substring(0,newLength);
					}
				}
				
				newValues = spaceFormat(newValue, newPos);
				
				this.value = newValues[0];
				this.selectionStart = newValues[1];
				this.selectionEnd   = newValues[1];
			}
		}
	}
	
	$("#mt").on('blur', function(e){
		
		if(deciNumber > 0){
			var value = this.value;
			
			var noDec = "";
			for(var i = 0; i < deciNumber; i++)
			noDec += "0";
			
			if(value == "0"+deciSep+noDec)
			this.value = ""; //<-- put your default value here
			else
			if(value.length > 0){
				if(value.indexOf(deciSep) >= 0){
					var newLength = value.indexOf(deciSep)+deciNumber+1;
					if(value.length < newLength){
					while(value.length < newLength){ value = value+"0"; }
					this.value = value.substring(0,newLength);
					}
				}
				else this.value = value + deciSep + noDec;
			}
		}
	});
  }
});

</script>
<?php
exit;
}


if (isset($_GET['add'])){ 
//------------------------------ IdVehicule ----------------------------//
$idVehicule="";	
$sql = "SELECT  v.idVehicule,v.immatriculation,v.Designation,tv.Designation as
 typeC  FROM vehicules v INNER JOIN affectations a ON 
 v.idVehicule=a.idVehicule
 INNER JOIN typeVehicules tv ON v.idTypeVehicule=tv.idTypeVehicule 
 WHERE idVendeur=".$_SESSION['IdVendeur']." ORDER BY a.idaffectation desc";
$stmt2 = sqlsrv_query( $conn, $sql );
if( $stmt2 === false ) {
    $error.="Erreur recupération idVehicule : ".sqlsrv_errors() . " <br/> ";
}
$row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC ) ;
$idVehicule = $row['idVehicule'];
$immatriculation = $row['immatriculation'];
$Vehicule = $row['Designation'];
$Type = $row['typeC'];
?>
<div>
<div style="width:70%; margin:auto;">
 <div class="cadreFrais hvr-grow" id="" onclick="AfficheGazoil(<?php echo $idVehicule;?> , '<?php echo $immatriculation;?>' , '<?php echo $Vehicule;?>' , '<?php echo $Type;?>' )">
			<div  class="childFrais"> 
			 <img src="img/gazoil.png"  width="120" height="100"/><br>
			<div class="titleCadre"><?php echo $trad['frais']['gasoil'] ; ?></div>
			</div>
</div>
 <div class="cadreFrais hvr-grow" id="" onclick="AfficheAutoroute(<?php echo $idVehicule;?> ,'<?php echo $immatriculation;?>' , '<?php echo $Vehicule;?>' , '<?php echo $Type;?>' )">
			<div  class="childFrais"> 
			 <img src="img/autoroute.png"  width="200" height="120"/><br>
			<div class="titleCadre"><?php echo $trad['frais']['autoroute'] ; ?></div>
			</div>
</div>
 <div class="cadreFrais hvr-grow" id="" onclick="AfficheVidange(<?php echo $idVehicule;?> , '<?php echo $immatriculation;?>' , '<?php echo $Vehicule;?>' , '<?php echo $Type;?>' )">
			<div  class="childFrais"> 
			 <img src="img/vidange.png"  width="200" height="170"/><br>
			<div class="titleCadre"><?php echo $trad['frais']['vidange'] ; ?></div>
			</div>
</div>
 <div class="cadreFrais hvr-grow" id="" onclick="AfficheDetails(<?php echo $idVehicule;?> , '<?php echo $immatriculation;?>' , '<?php echo $Vehicule;?>' , '<?php echo $Type;?>' )">
			<div  class="childFrais"> 
			 <img src="img/divers.jpg"  width="200" height="170"/><br>
			<div class="titleCadre"><?php echo $trad['frais']['divers'] ; ?></div>
			</div>
</div>
</div>
</div>
<?php exit;
}
include("header.php"); ?>
<!--script src="js/jquery.nicefileinput.min.js" type="text/javascript"></script-->

<script src="js/jquery-filestyle.min.js" type="text/javascript"></script>
<link href="css/jquery-filestyle.css"  rel="stylesheet" />

<Style>
.ui-widget-content{
background:#fff;}
.Clt{
	border: 1px solid #CCC;
-webkit-border-radius: 5px;
-khtml-border-radius: 5px;
border-radius: 5px;
margin: 10px 20px; 
}

/****************************************************/


/*
.examples-list dt {
	font-size: 13px;
	border-bottom: dotted #ccc 1px;
	margin: 0 0 5px 0;
	padding: 0 0 5px 0;
	display: list-item;
	list-style: inside square;
}
.examples-list dd {
	padding: 10px 0;
}






#download-link {
	display: block;
	float: left;
	width: auto;
	margin: 0 0 20px 0;
	padding: 0 30px;
	font-size: 12px;
	text-align: center;
	text-shadow: 0px -1px 0px #0172bd;
	border: solid #0172bd 1px;
	border-bottom: solid #00428d 1px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px; 

	background-color: #0192DD;
	background-image: linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -o-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -moz-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -webkit-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -ms-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #1774A3),
		color-stop(0.56, #0194DD)
	);
	
	-webkit-box-shadow: inset 0px 1px 0px rgba(255,255,255,.2), 0px 1px 0px #fff, 0px -1px 0px rgba(0,0,0,.1);
	-moz-box-shadow: inset 0px 1px 0px rgba(255,255,255,.2), 0px 1px 0px #fff, 0px -1px 0px rgba(0,0,0,.1);
	box-shadow: inset 0px 1px 0px rgba(255,255,255,.2), 0px 1px 0px #fff, 0px -1px 0px rgba(0,0,0,.1); 	
	
	color: #fff;
	height: 30px;
	line-height: 30px;	
}
*/




/*
.NFI-wrapper {}
.NFI-button {}
.NFI-button:hover {}
.NFI-filename {}
.NFI-current {}
*/
/*
.nice {
	font-family: arial;
	font-size: 12px;
	-webkit-box-shadow: 0px 1px 0px #fff, 0px -1px 0px rgba(0,0,0,.1);
	-moz-box-shadow: 0px 1px 0px #fff, 0px -1px 0px rgba(0,0,0,.1);
	box-shadow: 0px 1px 0px #fff, 0px -1px 0px rgba(0,0,0,.1); 
	-moz-border-radius: 4px; 
	-webkit-border-radius: 4px;
	border-radius: 4px;
}
.nice .NFI-button {
	-moz-border-radius-topleft: 3px; 
	-moz-border-radius-bottomleft: 3px;
	-webkit-border-top-left-radius: 3px;
	-webkit-border-bottom-left-radius: 3px;
	border-top-left-radius: 3px; 
	border-bottom-left-radius: 3px;

	background-color: #0192DD;

	background-image: linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -o-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -moz-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -webkit-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -ms-linear-gradient(bottom, #1774A3 0%, #0194DD 56%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #1774A3),
		color-stop(0.56, #0194DD)
	);
	text-shadow: 0px -1px 0px #0172bd;
	border: solid #0172bd 1px;
	border-bottom: solid #00428d 1px;
	
	-webkit-box-shadow: inset 0px 1px 0px rgba(255,255,255,.2);
	-moz-box-shadow: inset 0px 1px 0px rgba(255,255,255,.2);
	box-shadow: inset 0px 1px 0px rgba(255,255,255,.2); 	
	
	color: #fff;
	width: 100px;
	height: 30px;
	line-height: 30px;
}
.nice .NFI-button:hover {
	background: #333;
	text-shadow: 0px -1px 0px #111;
	border: solid #000 1px;
	
}
.nice .NFI-filename {
	-moz-border-radius-topright: 3px; 
	-moz-border-radius-bottomright: 3px;
	-webkit-border-top-right-radius: 3px;
	-webkit-border-bottom-right-radius: 3px;
	border-top-right-radius: 3px; 
	border-bottom-right-radius: 3px;

	width: 200px;
	border: solid #777 1px;
	border-left: none;
	height: 30px;
	line-height: 30px;
	
	background: #fff;
	-webkit-box-shadow: inset 0px 2px 0px rgba(0,0,0,.05);
	-moz-box-shadow: inset 0px 2px 0px rgba(0,0,0,.05);
	box-shadow: inset 0px 2px 0px rgba(0,0,0,.05); 

	color: #777;
	text-shadow: 0px 1px 0px #fff;
}

.test .NFI-button{
	background-color: #DD9201; /* 1774A3, 0192DD : A37417, DD9201 */ 
	background-image: linear-gradient(bottom, #A37417 0%, #DD9201 56%);
	background-image: -o-linear-gradient(bottom, #A37417 0%, #DD9201 56%);
	background-image: -moz-linear-gradient(bottom, #A37417 0%, #DD9201 56%);
	background-image: -webkit-linear-gradient(bottom, #A37417 0%, #DD9201 56%);
	background-image: -ms-linear-gradient(bottom, #A37417 0%, #DD9201 56%);
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #A37417),
		color-stop(0.56, #DD9201)
	);
	text-shadow: none;
	border: solid #bb7200 1px;
	border-bottom: solid #995200 1px;
}
.test .NFI-button:hover {
	background: #bb7200;
	text-shadow: none;
	border: solid #995200 1px;	
}
.test .NFI-filename {
	border: solid #eee 1px;
	color: #777;
}
*/
</style>

<div style=" display:flex;align-items:center; padding:2px 0;" class="headVente">
							<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>
						<div >&nbsp;> <span  Class="TitleHead" onclick=""><?php echo $trad['frais']['gestionFrais'] ; ?></span></div> 
</div>
<div style="clear:both;"></div>

<div id="formRes" ></div><!--style="overflow-y:scroll;min-height:280px;"--> 
<div id="box" ></div>
<div id="boxVidange" ></div>
<?php
include("footer.php");
?>
 <script src="js/jquery.geolocation.js"></script>
<script language="javascript" type="text/javascript">

function AfficheGazoil(idVehicule,immatriculation,vehicule,type)
{
$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("frais.php?addGazoil&IdVnd="+idVehicule+"&Imm="+encodeURI(immatriculation)+"&Vehicule="+encodeURI(vehicule)+"&Type="+encodeURI(type)).dialog('open');
}
function AfficheAutoroute(idVehicule,immatriculation,vehicule,type)
{
$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("frais.php?addAutoroute&IdVnd="+idVehicule+"&Imm="+encodeURI(immatriculation)+"&Vehicule="+encodeURI(vehicule)+"&Type="+encodeURI(type)).dialog('open');
}
function AfficheDetails(idVehicule,immatriculation,vehicule,type)
{
$('#box').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("frais.php?addOther&IdVnd="+idVehicule+"&Imm="+encodeURI(immatriculation)+"&Vehicule="+encodeURI(vehicule)+"&Type="+encodeURI(type)).dialog('open');
}
function AfficheVidange(idVehicule,immatriculation,vehicule,type)
{
$('#boxVidange').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load("frais.php?addVidange&IdVnd="+idVehicule+"&Imm="+encodeURI(immatriculation)+"&Vehicule="+encodeURI(vehicule)+"&Type="+encodeURI(type));

}

$(document).ready(function() {

	$('#box').dialog({
					autoOpen		:	false,
					width			:	950,
					height			:	500,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							//terminer();
						
						}
					 }
			});
	$('#boxVidange').dialog({
					autoOpen		:	false,
					width			:	1200,
					height			:	600,
					bgiframe		:	true,
					modal			:	true,
					resizable		:	false,
					closeOnEscape	:	true,
					draggable		:	true,
					title			:	'',
					buttons			:	{
						"Annuler"		: function(){
							$(this).dialog('close');
						},
						"Terminer "	: function() {
							//terminer();
						
						}
					 }
			});
		 $('#formRes').load('frais.php?add');

});
var map;  
var geocoder;
var lat=null;var longi=null;


function initMap(DivId) {//alert('ffff');
  //$('#map').html('<center><br/><br/><br/><br/><img src="../images/loading2.gif" /></center>');
  //Center = new google.maps.LatLng(lat,longi); 

 //geocoder = new google.maps.Geocoder();
 map = new google.maps.Map(document.getElementById(DivId), {          
	 zoom: 15,	 
//	 center: Center, 	 
	 mapTypeId: 'roadmap'        });        
	    
	//Position actuel------------------------------------------------------------------
 var marker3 = new google.maps.Marker({   
			draggable: false, 
			animation: google.maps.Animation.DROP, 
			//label: "mmm",			
			map: map  
			
		}); 
	function watchMyPosition(position) 
	{
//	alert("Your position is: " + position.coords.latitude + ", " + position.coords.longitude + " (Timestamp: "  + position.timestamp + ")<br />");

	  var pos = {
			lat: position.coords.latitude,
			lng: position.coords.longitude
		  };
		 // alert(pos.lat);
	   map.setCenter(pos);    
	   marker3.setPosition(pos);  

		$("#lat").val(pos.lat);
		$("#lng").val(pos.lng);
		//alert("init"+$("#lat").val());
	}
	
	$.geolocation.get({success:watchMyPosition}); 
 


   function getAddress(latLng) {
    geocoder.geocode( {'latLng': latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                document.getElementById("Adresse").value = results[0].formatted_address;

				var s=String(latLng);
				s=s.substring(1, s.length-1);
				var res = s.split(",");
				$("#Lat").val(res[0]);
				$("#Lng").val( res[1]);
				
              }
              else {
                document.getElementById("Adresse").value = "pas de résultat";
              }
            }
            else {
              document.getElementById("Adresse").value = status;
            }
          });
		  
        }
 }  
</script>
 <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYVQe6p_mmOTlvM2A3vRRla64tqQIZRd4<?php echo ($_SESSION['lang'] == 'ar' ) ? '&language=ar' : '&language=en'; ?>"> </script>