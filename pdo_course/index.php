<?php
//print_r(PDO::getAvailableDrivers());
//$handler = new PDO('mysql:host=localhost;dbname=shareboard','youssef','123');

try{
	$handler = new PDO('sqlsrv:Server=192.168.1.51;Database=SOR_OMAN','sa','sa');
	$handler->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	//echo 'Caught';
	//die("Sorry, database problem");
	echo $e->getMessage();
}

echo "The rest of our page";

 