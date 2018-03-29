<?php
  $full_path = 'inventaire_pdf/inventaire.pdf';
/*  header("Content-type: application/pdf");  
	header('Content-disposition: attachment; filename="bon_commande.pdf"');  
	readfile('bon_commande.pdf');  */
  
	header('Content-Description: File Transfer'); 
	header('Content-Type: application/octet-stream'); 
	header('Content-Disposition: attachment; filename="inventaire.pdf"'); 
	header('Content-Transfer-Encoding: binary'); 
	header('Expires: 0'); 
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
	header('Pragma: public'); 
	header('Content-Length: ' . filesize($full_path)); 
	ob_clean(); 
	flush(); 
	readfile($full_path);      
	exit(); 
echo "mmm";
return;
include("php.fonctions.php");
	require_once('connexion.php');
	session_start();
	include("lang.php");
	$IdDepot=$_SESSION["IdDepot"];
	
	$v2 = array();
	$new_numero= "IN".Increment_Chaine_F("Numero","inventaire_table","id",$conn,
	"",$v2);
	
	echo $new_numero;return;

echo crypt("admin@123", 'aminawahmane');
return;
echo   5%3;return;

//date("Y-m-d");return;
$myDateTime = DateTime::createFromFormat('d/m/Y H:i', '15/09/2015 12:00');
$newDateString = $myDateTime->format('Y-m-d H:i');	
echo $newDateString;
?>