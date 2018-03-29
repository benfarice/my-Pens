<?php
//$serverName = "AMINA-PC\SQLEXPRESS"; 
echo "tester la connexion";
$serverName =".\MSSQLSERVER2012";  //
$connectionInfo = array( "Database"=>"pgd_ma_distri_v2" , "UID"=>"pgd_ma_v2", "PWD"=>"p@rfi@Inf@123","CharacterSet" => "UTF-8");
//, "UID"=>"sa", "PWD"=>"sa","CharacterSet" => "UTF-8");
global $conn;
 $conn = sqlsrv_connect( $serverName, $connectionInfo);//, $connectionInfo
if( $conn ) {
   echo "Connexion établie.<br />";
}else{
   // echo "La connexion n'a pu être établie.<br />";
   header('Location: erreur.php');      
   // die( print_r( sqlsrv_errors(), true));
}
?>