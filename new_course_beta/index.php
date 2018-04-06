<?php 
/*$numbers = array(10,12,45,78);
print_r($numbers);
*/
/*
class User{
	public $id;
	public $username;
	public $email;
	public $password;

	public function __construct(){
		//echo 'Constructor Called';
	} 
	public function register(){
		echo 'User registered';
	}
	public function login($username,$password){
		//echo $username. ' is now logged in';
		$this->auth_user($username,$password);
	}
	public function __destruct(){
		echo 'Destructor Called';
	}
	public function auth_user($username,$password){
		
		//echo $username. ' is authenticated';
	}
}
$user = new User;

//$user->register();

$user->login('youssef','5632');
*/
class User{
	public $id;
	public $username;
	public $email;
	public $password;

	public function __construct($username,$password){
		//echo 'Constructor Called';
		$this->username = $username;
		$this->password = $password;
	} 
	public function register(){
		echo 'User registered';
	}
	public function login(){
		//echo $username. ' is now logged in';
		$this->auth_user();
	}
	public function __destruct(){
		echo 'Destructor Called';
	}
	public function auth_user(){
		
		echo $this->username. ' is authenticated';
	}
}
$user = new User('youssef','5632');

//$user->register();

$user->login();