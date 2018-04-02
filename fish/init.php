<?php
	//session_start();
	//Routes
	$tpl = 'includes/templates/'; // template directory
	$lang = 'includes/languages/';//Language Directory
	$func ='includes/functions/';//Functions Directory
	$css = 'layout/css/'; // Css directory
	$js = 'layout/js/'; // Js directory
	
	//include 'includes/languages/arabic.php';
	include 'connect.php';


	// Include The Important Files

	//include $lang.'arabic.php';
	include $func."func1.php";
	include $tpl."header.php";

	//Include Navbar On all pages expect the one with $nonavbar variable

	if(!isset($noNavbar)){
		include $tpl."Navbar.php";
	}
	
	
	//print_r($_SESSION);
	
	
	//include 'includes/languages/arabic.php';
	
?>