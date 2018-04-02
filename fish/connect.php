<?php
	/*$serverName ="AMINA-PC\SQLEXPRESS";
	$connectionInfo = array( "Database"=>"base_port_oman2" , "CharacterSet" => "UTF-8");*/
	$serverName ="192.168.1.51";
	$connectionInfo = array( "Database"=>"SOR_OMAN" , "UID"=>"sa", "PWD"=>"sa","CharacterSet" => "UTF-8");
	global $con;
	 $con = sqlsrv_connect( $serverName, $connectionInfo);
	if( $con ) {

	}
	else{
		//header('Location: erreur.php'); 
		echo "cannot open connection with Database";
	}
?>