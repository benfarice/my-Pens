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
/*************************************
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
**************************************/
/*************************************
function dataReturn():string{
	return "This is what the function will give back.";
}
echo var_export(dataReturn());
**************************************/
/*************************************
function dataReturn():int{
	return 1993;
}
echo var_export(dataReturn());
**************************************/
/*************************************
function dataReturn():int{
	return "2015";
}
echo var_export(dataReturn()+5);
**************************************/
/*************************************
function dataReturn():int{
	return "29.36";
}
echo var_export(dataReturn()+5);
**************************************/
/*************************************
function dataReturn():int{
	return 56.39;
}
echo var_export(dataReturn()+5);
**************************************/
/*************************************
function dataReturn():int{
	return true;
}
echo var_export(dataReturn()+5);
**************************************/
/*************************************
function dataReturn():float{
	return true;
}
echo var_export(dataReturn()+5);
**************************************/
/*************************************
function dataReturn():float{
	return 21.36;
}
echo var_export(dataReturn()+5);
**************************************/
/*************************************
function dataReturn():bool{
	return 21.36;
}
echo var_export(dataReturn());
**************************************/
/*************************************
function dataReturn():array{
	return array('hello',20,31);
}
echo var_export(dataReturn());
**************************************/
/*************************************
function dataReturn():array{
	return array('hello'=>'k',20=>5,31=>'y');
}
echo var_export(dataReturn());
**************************************/
/*************************************
class myOBJ {}
function dataReturn():myOBJ{
	return new myOBJ;
}
echo var_export(dataReturn());
**************************************/
/*************************************
interface register {}

class myOBJ implements register{}

class cake implements register{}
function dataReturn():register{
	return new Cake;
}
echo var_export(dataReturn());
**************************************/
/*************************************
function dataReturn():callable{
	return  function(){};
}
echo var_export(dataReturn());
**************************************/
/*************************************
class myOBJ{
	function dataReturn():self{
	echo "do something and then callback a function <br/>";
	return  function(){
		echo "hello";
	};
}
}

echo var_export(dataReturn());
**************************************/
/*************************************
class myOBJ{
	function dataReturn($object):self{
	
	return  $object;
	}
}

$a = new myOBJ;
echo var_export($a->dataReturn(new myOBJ));
**************************************/
/*************************************
echo '<pre>'.json_encode($_GET,JSON_PRETTY_PRINT).'<pre>';

echo $_GET['name'] ?? 'loz';

echo "<hr>";

echo $a ?? 5;

echo "<hr>";

$myVar = $_GET['name'] ?? 'foe';

echo $myVar;
**************************************/
/*************************************
// -1 less than
// 0 equal to
// 1 greater than

$spaceshipOperator = 2 <=> 1.2 ;

echo $spaceshipOperator;
**************************************/
/*************************************
// -1 less than
// 0 equal to
// 1 greater than

$spaceshipOperator = 2 <=> "2.6" ;

echo $spaceshipOperator;
**************************************/
/*************************************
// -1 less than
// 0 equal to
// 1 greater than

$spaceshipOperator = "0" <=> true ;

echo $spaceshipOperator;
**************************************/
/*************************************
// -1 less than
// 0 equal to
// 1 greater than

$spaceshipOperator = array(20,20,20) <=> array(20,20,20) ;

echo $spaceshipOperator;
**************************************/
/*************************************
// -1 less than
// 0 equal to
// 1 greater than

$spaceshipOperator = array(200,20,20) <=> array(20,20,20) ;

echo $spaceshipOperator;
**************************************/
/*************************************
// -1 less than
// 0 equal to
// 1 greater than
if(array(20,20,20) <=> array(200,20,20)){
	echo "something happened";
}
**************************************/
// -1 less than
// 0 equal to
// 1 greater than
if((array(200,20,20) <=> array(20,20,20))===1){
	echo "greater than";
}
