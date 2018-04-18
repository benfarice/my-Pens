<?php
include("../php.fonctions.php");
require_once('../connexion.php');
  if(!isset($_SESSION))
{
session_start();
}

//print_r($_POST);echo $_SESSION['IdVendeur'];
if(isset($_GET['trace']))
{
?>

<script language="javascript" type="text/javascript">
	//alert("hereeeeeeeeeeee");
</script>
<?php 
/* --------------------Begin transaction---------------------- */
$error="";
		if ( sqlsrv_begin_transaction( $conn ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}
?>

<script language="javascript" type="text/javascript">
//alert("hereeeeeeeeeeee2");

</script>
<?php 	
    $lat="";$lng="";
	if(isset($_GET['lat']))
	{
		$lat=$_GET['lat'];
	}
	if(isset($_GET['lng']))
	{
		$lng=$_GET['lng'];
	}
	    $date=date("d/m/Y");
		$Heure=date('H:i:s');
		?>

<script language="javascript" type="text/javascript">
	//alert("hereeeeeeeeeeee3 : Lat : "+<?php echo $lat ?>+" - Lng : "+<?php echo $lng ?>);
</script>
<?php 
		$reqInser1 = "INSERT INTO TraceVendeur ([IdVendeur] ,[Latitude] ,[Longitude] ,[Date],[Heure]) values 	(?,?,?,?,?)";
		/*$params1= array(
		$_SESSION['IdVendeur'],
		$lat,
		$lng,
		$date,
		$Heure
		) ;*/
		$params1= array(
		$_SESSION['IdVendeur'],
		$lat,
		$lng,
		$date,
		$Heure
		) ;
?>

<script language="javascript" type="text/javascript">
//alert("hereeeeeeeeeeee4");

</script>
<?php 
		$stmt1 = sqlsrv_query( $conn, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}
/**/?>

<script language="javascript" type="text/javascript">
//alert("hereeeeeeeeeeee5");

</script>
<?php 
		if( $error=="" ) {
			sqlsrv_commit( $conn );
		?>
				<script type="text/javascript"> 
			//	var lat=<?php echo $lat; ?>;
				var lng=<?php echo $lng; ?>;				
					//alert('L\'ajout a été effectué.',"Message");
					//alert('lat : ' + lat + ' lng : ' + lng,"Message");
				</script>
		<?php
		} else {
			sqlsrv_rollback( $conn );
			// echo "<font style='color:red'>".$error."</font>";
				?>
				<script type="text/javascript"> 
					//alert('Error : '.$error,"Message");
					//alert('lat : ' + lat + ' lng : ' + lng,"Message");
				</script>
		<?php
		}	

}

?>