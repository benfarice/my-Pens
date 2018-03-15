<?php 
//echo "youssef you will be rich insha allah ! ammine";
//echo "<br>hello my friend ".$_GET['name'];
$con = mysqli_connect("localhost","root","","ajax_jquery");
$name = $_POST['name'];
$adress =$_POST['adress'];
$email = $_POST['email'];
$sel = "select * from my_table where email ='$email'";

$run = mysqli_query($con,$sel);

$check_email = mysqli_num_rows($run);

if($check_email >0){
	echo "<p>this email is already registered ,please try another !</p>";
	exit();
 }else{
 	$insert = "insert into my_table(name,adresse,email) 
 	values('$name','$adress','$email')";
 	$run_insert = mysqli_query($con,$insert);
 	if($run_insert){
 		echo "<p>registration successful ,thanks !</p>";
 	}

 }
