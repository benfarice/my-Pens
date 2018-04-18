<?php
$_SESSION['lang']="ar";
if(isset($_SESSION['lang']) && ($_SESSION['lang']!= " ")) $lang=$_SESSION['lang'];

if(($lang!= " ") && (($lang=='eng' || $lang=='fr'|| $lang=='ar'))){
  include($lang.'.php');
  $_SESSION['lang'] = $lang;
  if($lang=='ar')	{
	  $_SESSION['dir']='rtl';
	  $_SESSION['align']='right';
	  }
  else {	
	$_SESSION['dir']='ltr';
	$_SESSION['align']='left';
  }
}else {
	include('ar.php');
	$_SESSION['dir']='rtl';
}
?>


<?php
//$_SESSION['lang']="ar";
if(isset($_SESSION['lang']) && ($_SESSION['lang']!= " ")) 
{

	$lang=$_SESSION['lang'];
	if(($lang != " ") && (($lang == 'eng' || $lang == 'fr'|| $lang == 'ar'))){
		include($lang.'.php');
		$_SESSION['lang'] = $lang;
		 if($lang=='ar')	{
		  $_SESSION['dir']='rtl';
		  $_SESSION['align']='right';
		  }
		  else {	
			$_SESSION['dir']='ltr';
			$_SESSION['align']='left';
		  }
  
	}
}
else {
	include('ar.php');
		$_SESSION['dir']='rtl';
		  $_SESSION['align']='right';
}
?>