<?php

	
	$serveur_mysqli="localhost";
	$bdd_mysqli="distribution";
	//$bdd_mysqli="firstaci_configfa";
	/*$login_mysqli="root";
	$mdp_mysqli="root";*/
	
	
	$login_mysqli="electro@distrib";
	$mdp_mysqli="d@str@b@123";
	
	
	$conn= mysqli_connect($serveur_mysqli, $login_mysqli, $mdp_mysqli,$bdd_mysqli)
    or die("Impossible de se connecter : " . mysqli_error());
	
	//$mysqli_bdd_config = mysqli_select_db($conn,$bdd_mysqli);

	if (mysqli_connect_errno()) 
	{
		echo " IMPOSSIBLE DE CHOISIR LA BASE";
	//erreur.php
	//  header('Location: ../erreur.php');   
	}
	

	
?>
	
	