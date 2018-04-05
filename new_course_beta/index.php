<?php 
/*$numbers = array(10,12,45,78);
print_r($numbers);
*/
class User{
	public function register(){
		echo 'User registered';
	}
}
$user = new User;

$user->register();
