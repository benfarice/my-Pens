<?php

if(isset($_GET["DownloadPdf"])){
	
	downloadPdf($_GET["File"]);
		exit(); 
}

function downloadPdf($FileName){
	  $full_path = 'inventaire_pdf/'.$FileName;
/*  header("Content-type: application/pdf");  
	header('Content-disposition: attachment; filename="bon_commande.pdf"');  
	readfile('bon_commande.pdf');  */
  
	header('Content-Description: File Transfer'); 
	header('Content-Type: application/octet-stream'); 
	header('Content-Disposition: attachment; filename="'.$FileName.'"'); 
	header('Content-Transfer-Encoding: binary'); 
	header('Expires: 0'); 
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
	header('Pragma: public'); 
	header('Content-Length: ' . filesize($full_path)); 
	ob_clean(); 
	flush(); 
	readfile($full_path);      


}
?>