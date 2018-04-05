<?php 
//Connect to Database
$db_host = 'localhost';
$db_name = 'store';
$db_user ='youssef';
$db_pass = '123';

//Create mysqli Object
$mysqli = new mysqli($db_host,$db_user,$db_pass,$db_name);

if(mysqli_connect_error()){
	echo 'This connection Failed '.mysqli_connect_error();
	die();
}

?>