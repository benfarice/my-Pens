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
/* ------------------------------------------------------------------
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
------------------------------------------------------------------ 
class Post{
	private $name;
	public function __set($name,$value){
		echo "Setting ".$name." to <strong>".$value."</strong><br />";
		$this->name = $value;
	}
	public function __get($name){
		echo "Getting ".$name."  <strong>".$this->name."</strong><br />";
		
	}
	public function __isset($name){
		echo 'Is '.$name.' set?<br />';
		return isset($this->name);
	}
}

$post = new Post;
$post->name = "Testing pgd";
echo $post->name;
var_dump(isset($post->name));
------------------------------------------------------------------ */
class First{
	public $id = 23;
	//public $name = 'John Doe';
	//private $name = 'John Doe';
	protected $name = 'John Doe';

	/*public function saySomething(){
		echo 'Something';
	}*/
	public function saySomething($word){
		echo $word;
	}
}
class Second extends First{
	public function getName(){
		echo $this->name;
	}
}
$second = new Second;
//echo $second->name;
echo $second->getName();
//echo $second->saySomething();
//echo $second->saySomething('imzoughene youssef');