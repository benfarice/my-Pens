<?php 

//echo "hello youssef";
/*************************************
function parse( ...$ints){
	echo '<pre>'.json_encode($ints,JSON_PRETTY_PRINT).'</pre>';
}


parse(20,"ahmed",13.56,false);
**************************************/
/*************************************
function parse( int ...$ints){
	echo '<pre>'.json_encode($ints,JSON_PRETTY_PRINT).'</pre>';
}


parse(20,"200",13.56,false,true);
**************************************/
/*************************************
function parse( float ...$ints){
	echo '<pre>'.json_encode($ints,JSON_PRETTY_PRINT).'</pre>';
}


parse(20,"200.236",13.56,false,true);
**************************************/
/*************************************
function parse( bool ...$ints){
	echo '<pre>'.json_encode($ints,JSON_PRETTY_PRINT).'</pre>';
}


parse(20,"200.236",13.56,false,true,"0",0,"");
**************************************/
/*************************************
function parse( string ...$ints){
	echo '<pre>'.json_encode($ints,JSON_PRETTY_PRINT).'</pre>';
}


parse(20,"200.236",13.56,false,true,"0",0,"");
**************************************/
/*************************************
function parse(array $kit){
	echo '<pre>'.json_encode($kit,JSON_PRETTY_PRINT).'</pre>';

}

//$arr = array("hello","world","another","word");
$arr = array("test" => "hello","youssef"=>array("1",2,3));
parse($arr);
**************************************/
/*************************************
function parse(callable $callback){
	echo "This string came from the parse function <br/><br/>";
	$callback();
}

//parse(function(){echo "callable function - this string came from the callback function";});

$func = function(){echo "callable function - this string came from the callback function stored in variable";};
parse($func);
**************************************/
/*************************************
class cake {}
class salad{}

function restaurant(cake $food){
	echo var_dump($food);
}

//$box = new cake;
$box = new salad;
restaurant($box);
**************************************/
/*************************************
interface checker{}
class cake implements checker{}
class salad{}

function restaurant(checker $food){
	echo var_dump($food);
}

//$box = new cake;
$box = new cake;
restaurant($box);
**************************************/
/*************************************
class cake {
	function icing(self $thisCake){
		echo 'Cake to ice : <br/>';
		echo var_dump($thisCake);

	}
}

$Cake1 = new cake;
$Cake2 = new cake;

$Cake2->icing($Cake1);
**************************************/
class cake {
	function icing(self $thisCake){
		echo 'Cake to ice : <br/>';
		echo var_dump($thisCake);

	}
}

class pudding {}

$Cake1 = new pudding;
$Cake2 = new cake;

$Cake2->icing($Cake1);

?>