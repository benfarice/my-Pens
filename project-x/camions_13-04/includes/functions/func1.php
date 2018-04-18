<?php
	//echo "my function here";
	
	/* 
	** Title function that echo the page title in case the page
	** has the variable $pagetitle and echo default title for other pages
	*/
	function getTitle(){
		global $pagetitle;
		if(isset($pagetitle)){
			echo $pagetitle;
		}else{
			echo 'Default';
		}
	}
?>