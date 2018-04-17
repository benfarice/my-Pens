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
------------------------------------------------------------------ 
class First{
	public $id = 23;
	//public $name = 'John Doe';
	//private $name = 'John Doe';
	protected $name = 'John Doe';

	public function saySomething(){
		echo 'Something';
	}
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
------------------------------------------------------------------ 
class User{
	public $username;
	public static $minPassLength = 5 ;
	public static function validatePassword($password){
		if(strlen($password) >= self::$minPassLength){
			return true;
		}else{
			return false;
		}

	}
}

$password = 'monkey';

if(User::validatePassword($password)){
	echo "password is valid";
}else{
	echo "password is NOT valid";
}
echo "<hr>";
echo User::$minPassLength;
------------------------------------------------------------------ 
abstract class Animal{
	public $name;
	public $color;
	public function describe(){
		return $this->name.' is '.$this->color;

	}
	abstract public function makeSound();
}

class Duck extends Animal{
	public function describe(){
		return parent::describe();
	}
	public function makeSound(){
		return 'Quack';
	}
}

class Dog extends Animal{
	public function describe(){
		return parent::describe();
	}
	public function makeSound(){
		return 'Bark';
	}
}

$animal = new Duck();
$animal->name='Ben';
$animal->color='Yellow';
echo $animal->describe();
echo "<hr>";
$animal = new Duck();
$animal->name='William';
$animal->color='red';
echo $animal->describe();
echo "<br />";
echo $animal->makeSound();
------------------------------------------------------------------ 
//include 'foo.php';
//include 'bar.php';
spl_autoload_register(function($class_name){
	include $class_name.'.php';
});

$foo = new Foo;
$bar = new Bar;

$foo->sayHello();
echo '<hr>';
$bar->sayHello();
------------------------------------------------------------------ 
class People{
	public $person1 = 'Mike';
	public $person2 = 'Shelly';
	public $person3 = 'Jeff';

	protected $person4 = 'john';
	private $person5 = 'Jen';

	function iterateObject(){
		foreach ($this as $key => $value) {
			print "$key => $value \n";
		}
	}
}
$p = new People;
$p->iterateObject();
------------------------------------------------------------------ */
class People{
	public $person1 = 'Mike';
	public $person2 = 'Shelly';
	public $person3 = 'Jeff';

	protected $person4 = 'john';
	private $person5 = 'Jen';


}
$p = new People;
foreach ($p as $key => $value) {
	print "$key => $value \n";
}
