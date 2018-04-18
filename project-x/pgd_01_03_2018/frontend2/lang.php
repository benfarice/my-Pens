<?php

//$_SESSION['lang']="ar";

	/*$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	echo $lang. "herrrrrrre";*/



if(isset($_SESSION['lang']) && ($_SESSION['lang']!= " ")) 
{

	$lang=$_SESSION['lang'];
	if(($lang != " ") && (($lang == 'en' || $lang == 'fr'|| $lang == 'ar'))){
		include($lang.'.php');
		$_SESSION['lang'] = $lang;
	}
	//echo "here 1 ".$lang.'.php';
}
else 
{
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);//echo ' Lan '.$lang;
	include($lang.'.php');
	$_SESSION['lang'] = $lang;
	
	//	echo "here 2 ".$lang.'.php';
}


 if($lang=='ar')	{
	  $_SESSION['dir']='rtl';
	  $_SESSION['align']='right';
	  }
  else {	
	$_SESSION['dir']='ltr';
	$_SESSION['align']='left';
  }

?>
