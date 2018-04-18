<?php 
require ('class/class.phpmailer.php');
require_once('TCPDF-master/tcpdf.php');

$les_emails=array("direction@electroprotect.ma","lahloureda69@gmail.com","oudani.ayoub@gmail.com","amzianx@gmail.com","imzoughene@outlook.com","imzou01@gmail.com","benfarice@gmail.com"
);
//
$les_pdfs =  array();
$message_div = "";
function sendMail_daily(array $les_emails,array $les_pdfs){
	foreach ($les_emails as $key ) {



	$mail = new PHPMailer;
	

	//$mail->IsSMTP();								//Sets Mailer to send message using SMTP
	//$mail->Host = 'smtp.gmail.com';



	$mail->Host = 'tls://smtp.gmail.com:587';






		//Sets the SMTP hosts of your Email hosting, this for Godaddy
	$mail->Port = 587;//465;//587							//Sets the default SMTP server port
	$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
	$mail->Username = 
	'amzianx@gmail.com';					//Sets SMTP username
	$mail->Password = 'benfqrice01';					//Sets SMTP password
	//$mail->SMTPSecure = 'ssl';	
	$mail->SMTPSecure = 'tls';						//Sets connection prefix. Options are "", "ssl" or "tls"
	$mail->From = 'amzianx@gmail.com';					//Sets the From email address for the message
	$mail->FromName = 'pgd';				//Sets the From name of the message
	$mail->AddAddress($key, 'Electroprotect');		//Adds a "To" address
	$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
	$mail->IsHTML(true);	
	$mail->AddEmbeddedImage("images\header.jpg", "my-attach");
	$message = '
		<h3 align="center">RAPPORT D\'ACTIVITE COMMERCIALE DISHOP</h3>
		<table border="1" width="100%" cellpadding="5" cellspacing="5">
			<tr>
				<td colspan="2">
				<img src="cid:my-attach" height="80px" width="800px">
				</td>
				
			</tr>
			<tr>
				<td width="30%">JOURNNEE : </td>
				<td width="70%">'.date('d/m/Y').'</td>
			</tr>
		
		</table>
	';
	$message_div = "";						//Sets message type to HTML
	//$mail->AddAttachment('upload\Plateforme de gestion de distribution.pdf');	
	foreach ($les_pdfs as $keypdf ) {
		# code...
		$mail->AddAttachment($keypdf);
	}
						//Adds an attachment from a path on the filesystem
	$mail->Subject = 'Electroprotect Daily News Mail';				//Sets the Subject of the message
	$mail->Body = $message;	
						//An HTML or plain text message body
	//echo $mail->Send();	
	if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }						//Send an Email. Return true on 
	}
}
//sendMail_daily();


function produce_pdfs() {
require('connexion.php');
require("lang.php");
$les_pdfs_produced =  array();
$query_get_ca_id_v_nbr_clients = "";
//".date('d.m.y')."




$query_get_ca_id_v_nbr_clients = "select v.idVendeur as id ,sum(dtf.ttc) 
as CA,count(distinct f.idClient) as NBRclients 
 from detailFactures dtf inner join factures f 
on dtf.idFacture = f.IdFacture  and EtatCmd = 2
inner join vendeurs v on v.idVendeur = f.idVendeur and v.idDepot <> 1 where 
cast(f.date as date) = '".date('Y-m-d')."' group by v.idVendeur";

/*
$query_get_ca_id_v_nbr_clients = "select v.idVendeur as id ,sum(dtf.ttc) 
as CA,count(distinct f.idClient) as NBRclients 
 from detailFactures dtf inner join factures f 
on dtf.idFacture = f.IdFacture  and EtatCmd = 2
inner join vendeurs v on v.idVendeur = f.idVendeur and v.idDepot <> 1 where 
cast(f.date as date) = '2018-02-08' group by v.idVendeur";
*/
echo $query_get_ca_id_v_nbr_clients."<br>";

$params0 = array();
//echo $query_get_ca_id_v_nbr_clients;
//echo $query_get_cities;
$options0 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt0=sqlsrv_query($conn,$query_get_ca_id_v_nbr_clients,$params0,$options0);
$ntRes0 = sqlsrv_num_rows($stmt0);
if($ntRes0==0)
		{ 
					$output = ' <div class="resAff">
					<br><br>
					'.$trad['msg']['AucunResultat'].'
					 ';
					  $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
				      $obj_pdf->SetCreator(PDF_CREATOR);  
				      $obj_pdf->SetTitle("Plateforme de gestion de distribution");  
				      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
				      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
				      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
				      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
				      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
				      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
				      $obj_pdf->setPrintHeader(false);  
				      $obj_pdf->setPrintFooter(false);  
				      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
				      //$obj_pdf->SetFont('helvetica', '', 12); 
				      $obj_pdf->SetFont('aealarabiya', '', 18); 
				      $obj_pdf->AddPage();  
					  $output .= '</div>';  
			          $obj_pdf->writeHTML($output); 
			          //unlink('C:\xampp\htdocs\pgd\upload\Plateforme de gestion de distribution.pdf'); 
			          $obj_pdf->Output(__DIR__ .'\ca_journalier\Plateforme de gestion de distribution.pdf', 'F');
			          //sendMail_daily($les_emails);
			          array_push($les_pdfs_produced,__DIR__ .'\ca_journalier\Plateforme de gestion de distribution.pdf');
		}
else{
while($row = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){	

  
      $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      $obj_pdf->SetCreator(PDF_CREATOR);  
      $obj_pdf->SetTitle("Plateforme de gestion de distribution");  
      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(false);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      //$obj_pdf->SetFont('helvetica', '', 12); 
      $obj_pdf->SetFont('aealarabiya', '', 18); 
      $obj_pdf->AddPage();  
      $output = '';
      $pdf_name = __DIR__ .'\ca_journalier\\';



//echo $query_get_ca_id_v_nbr_clients;

//echo $row['city']."<br>";
$query_vendeur = " select v.nom +' '+v.prenom as vendeur from vendeurs v
 where v.idVendeur = $row[id]";
//echo $query_marque ;
echo $query_vendeur."<br>";
$params_2 = array();
$options_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_vendeur=sqlsrv_query($conn,$query_vendeur,$params_2,$options_2);
$ntRes_query_vendeur = sqlsrv_num_rows($stmt_query_vendeur);
//echo $query_vendeur;




$query_site = "select distinct v.Designation as city 
 from detailFactures dtf inner join 
factures fa on dtf.idFacture = fa.IdFacture inner join 
depots dpt on dpt.idDepot = fa.idDepot inner join villes v 
on v.idville = dpt.IdVille where cast(fa.date as date) = '".date('Y-m-d')."'
 and fa.idVendeur = $row[id]";
echo $query_site."<br>";
/*

$query_site = "select distinct v.Designation as city 
 from detailFactures dtf inner join 
factures fa on dtf.idFacture = fa.IdFacture inner join 
depots dpt on dpt.idDepot = fa.idDepot inner join villes v 
on v.idville = dpt.IdVille where cast(fa.date as date) = '2018-02-08'
 and fa.idVendeur = $row[id]";

*/



 //echo $query_site;
 $params_site = array();
$options_site =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_site =sqlsrv_query($conn,$query_site,$params_site,$options_site);
$output .= ' 
<div id="my_print_div">
	<div style="border: 5px solid black;margin-top: 10px">
		
		<p style="font-size: 23px;margin:10px;">	
			Date & Heure '.date('d/m/Y').' | '.date('H:i').' 
		</p>';
while($reader_site = sqlsrv_fetch_array($stmt_site, SQLSRV_FETCH_ASSOC)){
	$output .= '
	
	<p style="font-size: 23px;margin:10px;">
	Site : '.
	ucwords($reader_site['city']).' </p>
	';
	$pdf_name.=$reader_site['city'].'-';
}
while($reader_vendeur = sqlsrv_fetch_array($stmt_query_vendeur, SQLSRV_FETCH_ASSOC)){	
//echo $query_vendeur;

	    $pdf_name.=$reader_vendeur['vendeur'].'-';
	    $pdf_name.=date('d-m-Y').'-.pdf';
	    $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
		$pdf_name = strtr( $pdf_name, $unwanted_array );
		$output .= '<table class="table table-striped" style="font-size: 20px;">
			<thead>
				<tr>
					<th>Vendeur : </th>
					<th colspan="2">'.$reader_vendeur['vendeur'].'</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td>CA : </td>
				<td colspan="3">
				 '.number_format($row['CA'], 2, ',', ' ').
				' DH TTC
				</td>
			</tr> 
			<tr> ';

}

  

$sql_espece ="select isnull(sum(f.Espece),0) as espece from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '".date('Y-m-d')."' and EtatCmd = 2";
echo $sql_espece."<br>";

/*
$sql_espece ="select isnull(sum(f.Espece),0) as espece from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '2018-02-08' and EtatCmd = 2";
*/


//echo $sql_espece;
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt=sqlsrv_query($conn,$sql_espece,$params,$options);
$ntRes = sqlsrv_num_rows($stmt);



$sql_Cheque ="select isnull(sum(f.Cheque),0) as cheque from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '".date('Y-m-d')."' and EtatCmd = 2";
echo $sql_Cheque."<br>";
/*

$sql_Cheque ="select isnull(sum(f.Cheque),0) as cheque from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '2018-02-08' and EtatCmd = 2";

*/

//echo $sql_Cheque;
$params_Cheque = array();
$options_Cheque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Cheque=sqlsrv_query($conn,$sql_Cheque,$params_Cheque,$options_Cheque);
$ntRes_Cheque = sqlsrv_num_rows($stmt_Cheque);




$sql_credit = "select isnull(sum(f.Credit),0) as credit from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '".date('Y-m-d')."' and EtatCmd = 2";
echo $sql_credit."<br>";
/*

$sql_credit = "select isnull(sum(f.Credit),0) as credit from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '2018-02-08' and EtatCmd = 2";

*/
 $params_Credit = array();
$options_Credit =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Credit=sqlsrv_query($conn,$sql_credit,$params_Credit,$options_Credit);
$ntRes_Credit = sqlsrv_num_rows($stmt_Credit);
//echo $sql_credit;



$sql_fact ="select count(f.IdFacture) as nbr_f from factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '".date('Y-m-d')."' and EtatCmd = 2";
echo $sql_fact."<br>";
/*

$sql_fact ="select count(f.IdFacture) as nbr_f from factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '2018-02-08' and EtatCmd = 2 ";
*/

$params_fact = array();
$options_fact =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_fact =sqlsrv_query($conn,$sql_fact,$params_fact,$options_fact);
$ntRes_fact  = sqlsrv_num_rows($stmt_fact);
//echo $sql_fact;





$sql_marque = "select m.Designation as mar,
sum(dtf.ttc) as total ,
COUNT(a.IdArticle) as nbr_ref from detailFactures dtf  inner join
  articles a on dtf.idArticle = a.IdArticle inner join 
  gammes g on a.IdFamille = g.IdGamme inner join
   marques m on g.IdMarque = m.idMarque inner join
   factures f on f.IdFacture = dtf.idFacture
 where f.idVendeur = $row[id] and EtatCmd = 2 and 
 cast(f.date as date)='".date('Y-m-d')."' group by m.Designation";
echo $sql_marque."<br>";
/*
 
$sql_marque = "select m.Designation as mar,
sum(dtf.ttc) as total ,
COUNT(a.IdArticle) as nbr_ref from detailFactures dtf  inner join
  articles a on dtf.idArticle = a.IdArticle inner join 
  gammes g on a.IdFamille = g.IdGamme inner join
   marques m on g.IdMarque = m.idMarque inner join
   factures f on f.IdFacture = dtf.idFacture
 where f.idVendeur = $row[id] and 
 cast(f.date as date)='2018-02-08' group by m.Designation";

*/

//echo $sql_marque;
$params_marq = array();
$options_marq =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marq =sqlsrv_query($conn,$sql_marque,$params_marq,$options_marq);
$ntRes_marq = sqlsrv_num_rows($stmt_marq);




$sql_frais = "select f.Operation,f.Montant from Frais f where f.DateOperation = '".date('Y-m-d')."'
and f.IdVendeur = $row[id]";

echo $sql_frais."<br>";
/*

$sql_frais = "select f.Operation,f.Montant from Frais f where f.DateOperation = '2018-02-08'
and f.IdVendeur = $row[id]";
*/

$params_frais = array();
$options_frais =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_frais =sqlsrv_query($conn,$sql_frais,$params_frais,$options_frais);
$ntRes_frais = sqlsrv_num_rows($stmt_frais);
 

         
while($reader = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){	
	
		$output .= '<td>Espece : '.$reader['espece'].'</td> '; 

} 


while($reader_c = sqlsrv_fetch_array($stmt_Cheque, SQLSRV_FETCH_ASSOC)){	
	
			$output .= '
         		<td>Cheque :  '.$reader_c['cheque'].'</td> '; 

} 


while($reader_d = sqlsrv_fetch_array($stmt_Credit, SQLSRV_FETCH_ASSOC)){	
	
			$output .= '
			<td>Credit : '.$reader_d['credit'].'</td>'; 

} 

			$output .= ' </tr>';
while($reader_f = sqlsrv_fetch_array($stmt_fact, SQLSRV_FETCH_ASSOC)){	
	
			$output .= '<tr><td colspan="3"><strong>Nombre de factures : </strong>'.
			$reader_f['nbr_f'].
			'</td> </tr>'; 

} 

		
			$output .= '<tr><td colspan="3">Nombre de clients : '
			.$row['NBRclients'].'</td></tr>
			</tbody>
      </table>';
if($ntRes_marq > 0){
	   $output .= ' <table class="table table-striped" style="font-size: 19px;
      border:0.5px solid gray">
      	<tr style="font-size: 19px;
      border:0.5px solid gray">
      		<th>Marque</th>
      		<th class="text-center">Nbr de Ref</th>
      		<th class="text-left">CA ( DH TTC )</th>
      	</tr> '; 
}
      
  

while($reader_marq = sqlsrv_fetch_array($stmt_marq, SQLSRV_FETCH_ASSOC)){	
	
			$x = number_format($reader_marq['total'], 2, ',', ' ');
			$output .= ' <tr>
				<td>'.$reader_marq['mar'].'</td>
				<td class="text-center">'.$reader_marq['nbr_ref'].'</td> 
				<td class="text-left">'. $x .' 
				</td>
				
			</tr>'; 

} 
if($ntRes_marq > 0){
$output .= '
      </table>';
}
 if($ntRes_frais > 0 ){
 	  $output .='<table  style="font-size: 19px;
      border:0.5px solid gray">
      	<tr style="font-size: 19px;
      border:0.5px solid gray">
      		<th>Operation(Frais)</th>
      		<th>Montant</th>
      		
      	</tr> '; 
 }     
    
while ($reader_frais = sqlsrv_fetch_array($stmt_frais,SQLSRV_FETCH_ASSOC)) {
	       $x = number_format($reader_frais['Montant'], 2, ',', ' ');
	       $output .= ' <tr>
				<td>'.$reader_frais['Operation'].'</td>
				
				<td>'. $x .' 
				</td>
				
			</tr>'; 
}
if($ntRes_frais > 0 ){
	 $output .= ' </table>  ';
	}
 $output .='</div> '; 
$output .= '</div>';



  
$obj_pdf->writeHTML($output); 
array_push($les_pdfs_produced,$pdf_name);


$obj_pdf->Output($pdf_name, 'F');

}
}
return $les_pdfs_produced;
}

  


$les_pdfs = produce_pdfs();
sendMail_daily($les_emails,$les_pdfs);

$files = glob( __DIR__ .'\ca_journalier\*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}
?>
<div><?php echo $message_div ; ?></div>