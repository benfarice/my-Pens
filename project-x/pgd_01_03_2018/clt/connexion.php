<?php

	
	$serveur_mysql="localhost";
	$bdd_mysql="distribution";
	//$bdd_mysql="firstaci_configfa";
	/*$login_mysql="root";
	$mdp_mysql="root";*/
	
	
	$login_mysql="electro@distrib";
	$mdp_mysql="d@str@b@123";
	
	
	$conn= mysql_connect($serveur_mysql, $login_mysql, $mdp_mysql)
    or die("Impossible de se connecter : " . mysql_error());
	
	$mysql_bdd_config =   mysql_select_db('database_name', $bdd_mysql) or die('IMPOSSIBLE DE CHOISIR LA BASE.');

	if (conn()) 
	{
		echo " IMPOSSIBLE DE CHOISIR LA BASE";
	//erreur.php
	//  header('Location: ../erreur.php');   
	}
	

	
?>
	
	