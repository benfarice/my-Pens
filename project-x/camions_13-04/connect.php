<?php
	$serverName ="YOUSSEF-PC\SQLEXPRESS";
	$connectionInfo = array( "Database"=>"camions_data" , "CharacterSet" => "UTF-8");
	global $con;
	 $con = sqlsrv_connect( $serverName, $connectionInfo);
	if( $con ) {

	}
	else{
		//header('Location: erreur.php'); 
		echo "cannot open connection with Database";
	}
?>