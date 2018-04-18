<?php
//$to = 'amina@electroprotect.ma';
/*
$to = 'ouboukrimsanaa01@gmail.com';
$headers = 'From: contact@pgd.ma'."\r\n\r\n";
if (mail($to, "teste d'envoi", "message pour le teste", $headers))
echo "votre mail a été bien envoyé";
else echo "echoué";  */

require('fpdf.php');

class PDF extends FPDF
{
// En-tête
function Header()
{
    // Logo
    $this->Image('../images/logo.png',10,6,30);
    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(80);
    // Titre
    $this->Cell(30,10,'Bon de commande',0,0,'C');
    // Saut de ligne
    $this->Ln(20);
}





// Tableau coloré
function FancyTable($header, $data)
{
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(176,176,176);
    $this->SetTextColor(0,0,0);
    $this->SetDrawColor(176,176,176);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // En-tête

    $w = array(100, 35, 30, 30);
    for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(233,233,233);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Données
    $fill = false;
	//print_r($data);//return;
    //foreach($data as $row)
   for ( $i=0; $i<count($data);$i++) {
		
	//	echo $data[$i][0]."<br>";
	echo $data[$i];
      $this->Cell($w[0],6,utf8_decode($data[$i][0]),1,0,'L',$fill);
        $this->Cell($w[1],6,$data[$i][1],1,0,'L',$fill);
        $this->Cell($w[2],6,($data[$i][2]),1,0,'R',$fill);
        $this->Cell($w[3],6,($data[$i][3]),1,0,'R',$fill);
        $this->Ln();
		  $this->SetFillColor(233,233,233);
        $fill = !$fill;
    }
//	return;
    // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
}
// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function SetCol($col)
{
    // Positionnement sur une colonne
    $this->col = $col;
    $x = 10+$col*65;
    $this->SetLeftMargin(5);
    $this->SetX(120);
}
function CorpsChapitre($txt)
{
    // Police
    $this->SetFont('Times','',12);
    // Sortie du texte sur 6 cm de largeur
   // 
 //  $this->MultiCell(60,5,utf8_decode($txt));
   $this->MultiCell(110,3,utf8_decode($txt) ,0,'L',false);
	$x = $this->GetX();
	$y = $this->GetY();
 /*  $this->SetXY($x + 60, $y);
    $this->Ln();*/
    // Mention
    $this->SetFont('','I');
   // $this->Cell(0,5,"(fin de l'extrait)");
    // Retour en première colonne
    $this->SetCol(0);
}

function TitreChapitre($num, $libelle)
{
    // Titre
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,220,255);
    $this->Cell(0,6,"Chapitre $num : $libelle",0,1,'L',true);
    $this->Ln(4);
    // Sauvegarde de l'ordonnée
    $this->y0 = $this->GetY();
}
function AjouterChapitre($txt,$numCol,$titre)
{
  
  // Lecture du fichier texte
  // $this->TitreChapitre($numCol,$titre);
    $this->CorpsChapitre($txt);
}

}
/*
// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Impression de la ligne numéro '.$i,0,1);
$pdf->Output();

return;*/

function envoiCmd($IdCmd,$conn){
/*require_once('../connexion.php');
session_start();*/
//$mail = 'amina@electroprotect.ma'; 
$mail = 'amina.wahmane@gmail.com';
$mail2 = 'oudani.ayoub@gmail.com'; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}


$IdDepot=$_SESSION['IdDepot'];
$sql = "
		select  
		
		v.adresse,v.idVendeur,a.idarticle,d.adresse AdresseDepot,d.Designation DsgDepot,
		a.designation article,v.nom+' '+v.prenom Vendeur ,
		dc.qte Qte,dc.colisage Colisage,
		c.date DateCmd,
		c.numCommande NumCmd
		from 
		commandeVendeurs c 
		inner join detailCommandeVendeurs dc on dc.idCommandeVendeur=c.idCommandeVendeur
		inner join vendeurs v on v.idvendeur=c.idvendeur
		inner join articles a on a.idarticle=dc.idarticle
		inner join depots d on d.idDepot=a.idDepot
		where c.idCommandeVendeur=? and a.idDepot=?
			 ";
		
		 $params = array($IdCmd,$IdDepot);	
	
		$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
		if( $stmt === false ) {
									$errors = sqlsrv_errors();
									echo "Erreur : ".$errors[0]['message'] . " <br/> ";
									RETURN;
								}

		$ntRes = sqlsrv_num_rows($stmt);
		//echo $sql;
			$nRes = sqlsrv_num_rows($stmt);	

				$groups = array();
								$i=0;
								$TotalHT=0;$TotalTTC=0;$TotalTVA=0;
			 while($row=sqlsrv_fetch_array($stmt)){							 
		
												 
												 
										$key = $row['idVendeur'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['idVendeur']=$row['idVendeur'];
											$groups[$key]['Vendeur']=$row['Vendeur'];
											$groups[$key]['Adresse']=$row['adresse'];
											$groups[$key]['NumCmd']=$row['NumCmd'];
											$date=date_create($row['DateCmd']);
											$groups[$key]['DateCmd']=date_format($date, 'd/m/Y');
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['DsgDepot']=$row['DsgDepot'];
												
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['IdArticle'] = $row['idarticle'];
											$groups[$key][$i]['DsgArticle'] = $row['article'];		
											$groups[$key][$i]['Qte'] =$row['Qte'];
											$groups[$key][$i]['Colisage'] =$row['Colisage'];
											$groups[$key][$i]['TotalQteUni'] =$row['Qte']*$row['Colisage'];
										
								
			 }
				
				
//=====Déclaration des messages au format texte et au format HTML.
$tabArticle=array();$i=0;
$message_html = '<html><head></head><body><div style="background-color:#ebebeb;    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;border:1px solid #ebebeb">';
 	foreach($groups as $u=>$v){	
$message_html .= '
<table style="width:80%" ><tr><td align="center" style="border-bottom:4px solid #333333;padding:7px 0">
<img src="http://pgd.ma/images/logo_print.png"></td></td></tr><tr><td padding:7px 0>
<font size="2" face="Open-sans, sans-serif" color="#555454">
			<br><span>Une nouvelle commande a été passée sur votre platforme  <span class="il">PGD</span> <span class="il"></span> 
			par le vendeur : '.$v['Vendeur'].' </span>
		</font>
</td></tr></table>
<table class= style="width:100%"  cellpadding="10">
			<tbody><tr>
				<td width="10" style="padding:7px 0">&nbsp;</td>
				<td style="padding:7px 0">
					<font size="2" face="Open-sans, sans-serif" color="#555454">
						<p style="border-bottom:1px solid #d6d4d4;
						margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
							Détails de la commande					</p>
						<span style="color:#777">
							<span style="color:#333"><strong>Commande :</strong></span> '.$v['NumCmd'].'
							passée le '. $v['DateCmd'].'<br><br>
						
					</font>
				</td>
				<td width="10" style="padding:7px 0">&nbsp;</td>
			</tr>
		</tbody></table>
		<table class="" bgcolor="#ffffff" cellpadding="10" style="width:100%;border-collapse:collapse">
				<thead>
					<tr>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Produit</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Quantité</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Colisage</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Total Qte </th>

					</tr>
				</thead>
				<tbody>
				';
				
			foreach($v as $r){
				if(is_array($r)){
					 // array_push($tabArticle, $r);
						$tabArticle[$i][0]=$r['DsgArticle'];
						$tabArticle[$i][1]=$r['Qte'];
						$tabArticle[$i][2]=$r['Colisage'];
						$tabArticle[$i][3]=$r['TotalQteUni'];
						$i++;
					//  $tabArticle=$r;
						$message_html .='<tr>
						  <td class="prestation " style="border:1px solid #d6d4d4;">'.$r['DsgArticle'].'	</td>
							<td  align="right" style="border:1px solid #d6d4d4;" >'.$r['Qte'].'	</td>
							<td  align="right" style="border:1px solid #d6d4d4;">'.$r['Colisage'].'	</td>
							<td  align="right" style="border:1px solid #d6d4d4;">'. $r['TotalQteUni'].'	</td>
						</tr>';
			 } 
			}
		$message_html .='
		</tbody></table>';
	}		
	//print_r($tabArticle);return;
	$message_html .='<br><b>Nb: Merci de trouver ci-joint votre commande.<br>';
	$message_html .='</div></body></html>';
//==========
 
//creation de fichier pdf

// Instanciation de la classe dérivée
$pdf = new PDF();
  $pdf->AddPage();
$pdf->AjouterChapitre('Dépôt Electroprotect

Electro Protect 38, rue Imam El Boukhari .Quartier: Maarif casablanca

Tél:0522 992 970

Mail:contact@pgd.ma',1,'');
$pdf->AjouterChapitre('Vendeur : Nabil Lahlou

Bon de commande n° NC1700016

Date de commande : 21/02/2017 ',2,'');
$headerTab= array('Produit	', 'Quantité', 'Colisage', 'Total Qte');

$pdf->SetFont('Arial','',14);
//$pdf->AddPage();
  $pdf->Ln(10);
$pdf->FancyTable($headerTab,$tabArticle);

//$pdf->Output();


$filename = "commande.pdf";
//=====Lecture et mise en forme de la pièce jointe.
// encode data (puts attachment in proper format)
//$pdfdoc = $pdf->Output('test.pdf', "S");
$path = "img_magasins/commande.pdf";
$pdf->Output($path,'F');
//header('location:'.$path);

$file = fopen($path, 'rb');
$data = fread($file, filesize($path));
fclose($file);

$attachment = chunk_split(base64_encode($data));


//=====Création de la boundary
$boundary = "-----=".md5(rand());
$boundary_alt = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.

$sujet = "nouvelle commande!";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"PGD\"<contact@pgd.ma>".$passage_ligne;
$header.= "Reply-to: \"contact\" <contact@pgd.ma>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
//==========
$message = $passage_ligne."--".$boundary.$passage_ligne;
$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
//=====Ajout du message au format HTML
//$message.= "Content-Type: text/html; charset=UTF-8\r\n".$passage_ligne;
$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
//$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========
 
$message.= $passage_ligne."--".$boundary.$passage_ligne;

//=====Ajout de la pièce jointe.
$message.= "Content-Type: application/octet-stream; name=\"".$filename."\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
$message.= "Content-Disposition: attachment; filename=\"".$filename."\"".$passage_ligne;
$message.= $passage_ligne.$attachment.$passage_ligne;
//$message.= $passage_ligne."--".$boundary."--".$passage_ligne; 


//=====Envoi de l'e-mail.

if ( (mail($mail, $sujet, $message, $header)) && (mail($mail2, $sujet, $message, $header)))
return true;
else return false;
}

/*
if( envoiCmd('2046')==true) $error="bien envoyé";
	else $error="Erreur";
	echo $error;*/	
//==========
?>
 
     
