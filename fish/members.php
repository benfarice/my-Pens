<?php 
	/*
	*********************************************
	** manage members page
	** you can add | edit | delete members from here
	*********************************************
	*/


	/***********************************************
	session_start();
	//print_r($_SESSION);
	if(isset($_SESSION['username'])){
		
		include 'init.php';

		
		include $tpl ."footer.php";
	}else{
		//echo "You Are not Authorized To view this page";
		header('Location: index.php');
		exit();
	}
	***************************************************/
?>