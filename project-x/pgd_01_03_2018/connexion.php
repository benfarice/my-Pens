<?php
$serverName ="YOUSSEF-PC\SQLEXPRESS";//"192.168.1.50";  //
// pgd_ma_t3 tester 
$connectionInfo = array( "Database"=>"pgd_ma_10" , "CharacterSet" => "UTF-8");
/*$serverName = "AMINA-PC\SQLEXPRESS"; 
$connectionInfo = array( "Database"=>"pgd_ma_v2_4" , "UID"=>"sa", "PWD"=>"azerty","CharacterSet" => "UTF-8");*/
//, "UID"=>"sa", "PWD"=>"sa","CharacterSet" => "UTF-8");
global $conn;
 $conn = sqlsrv_connect( $serverName, $connectionInfo);//, $connectionInfo
if( $conn ) {
   // echo "Connexion établie.<br />";
}else{
   // echo "La connexion n'a pu être établie.<br/>";
   header('Location: erreur.php');      
   // die( print_r( sqlsrv_errors(), true));
}
?>