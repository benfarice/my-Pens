<?php
	//Routes

	//session_start();
	$_SESSION['Lang']="ar";
/*	$_SESSION['username']="amina";
	if(!isset($_SESSION['username'])){
		header('Location: index.php');
		exit();
	}*/
		
	$tpl = 'includes/templates/'; // template directory
	$lang = '../includes/languages/';//Language Directory
	$func ='../includes/functions/';//Functions Directory
	$css = 'layout/css/'; // Css directory
	$js = 'layout/js/'; // Js directory
	
	//include 'includes/languages/arabic.php';
	include '../connect.php';
	include $func.'php.fonctions.php';


	// Include The Important Files

	include $lang.$_SESSION['Lang'].'.php';
	include $func."func1.php";
	
	//include $tpl."header.php";

	//Include Navbar On all pages expect the one with $nonavbar variable

	if(!isset($noNavbar)){
	//	include $tpl."Navbar.php";
	}
	//Include Navbar On all pages expect the one with $nonavbar variable

	
	
	//include 'includes/languages/arabic.php';
	
?>