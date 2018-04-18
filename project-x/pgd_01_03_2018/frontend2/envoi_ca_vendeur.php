<?php

include("../php.fonctions.php");
require_once('../connexion.php');
include("fonctionCalcule.php");
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
   // $this->Image('../images/logo.png',10,6,30);
    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(80);
    // Titre
    $this->Cell(30,10,utf8_decode('CA réalisé par vendeur'),0,0,'C');
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

    $w = array(20, 60, 60, 25, 25);
	echo sizeof($header);
    for($i=0;$i<sizeof($header);$i++)
    $this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(233,233,233);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Données
    $fill = true;
	//print_r($data);//return;
    //foreach($data as $row)
  // for ( $i=0; $i<count($data);$i++) {
	foreach($data as $u=>$v){	
	//	echo $data[$i][0]."<br>";
		//Cell(width, height ,valeur, border, int ln [,align ,fond cellule,lien);
			if(is_array($v)){
			$fill = true;// pour application background  SetFillColor sur cellule
			$this->SetFillColor(24,105,243);//RGB  color
			$this->Cell($w[0],6,utf8_decode($v['Designation']),1,0,'L',$fill);
			$this->Cell($w[1],6,"",1,0,'L',$fill);
			$this->Cell($w[1],6,"",1,0,'L',$fill);
			$this->Cell($w[1],6,"",1,0,'L',$fill);
			$this->Cell($w[1],6,"",1,0,'L',$fill);
			 $this->Ln();
		
				foreach($v as $k)
				{	//vendeur
					if(is_array($k)){
						$this->SetFillColor(128,172,247);
						$fill = true;
						$this->Cell($w[0],6,"",1,0,'L',$fill);
						$this->Cell($w[1],6,utf8_decode($k['Vendeur']),1,0,'L',$fill);
						$this->Cell($w[1],6,"",1,0,'L',$fill);
						$this->Cell($w[1],6,"",1,0,'L',$fill);
						$this->Cell($w[1],6,"",1,0,'L',$fill);
						 $this->Ln();
						 foreach($k as $r){
							 if(is_array($r)){
									$this->SetFillColor(255,255,255);
									$fill = true;
									$this->Cell($w[0],6,"",1,0,'L',$fill);
									$this->Cell($w[1],6,"",1,0,'L',$fill);
									$this->Cell($w[1],6,utf8_decode($r['Intitule']),1,0,'L',$fill);
									$this->Cell($w[1],6,utf8_decode($r['CA']),1,0,'L',$fill);
									$this->Cell($w[1],6,utf8_decode($r['CreditClt']),1,0,'L',$fill);
									 $this->Ln();
							 }
						 }
					}
					
				}
				 
			
			}
       /* $this->Cell($w[1],6,$data[$i][1],1,0,'L',$fill);
        $this->Cell($w[2],6,($data[$i][2]),1,0,'R',$fill);
        $this->Cell($w[3],6,($data[$i][3]),1,0,'R',$fill);*/
      
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

function envoiAlert($conn){
/*require_once('../connexion.php');
session_start();*/
//$mail = 'amina@electroprotect.ma'; 
$mail = 'amina.wahmane@gmail.com';
$mail2 = 'amina.wahmane@gmail.com'; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}



$sql = "
		select 
			v.idville IdVille,v.Designation , vd.idVendeur IdVendeur, vd.cin,vd.nom+ ' ' + vd.prenom AS Vendeur,
			(SELECT count(*) 
			FROM visites WHERE year(cast(visites.dateFin AS date))=year(getdate()) and idClient=c.IdClient) AS nbrVisites,
			c.IdClient,c.intitule Intitule,d.idDepot IdDepot,
			sum(f.totalTTC) AS CA 
		FROM factures f 
			INNER JOIN vendeurs vd ON f.idVendeur=vd.idVendeur
			INNER JOIN depots d ON d.idDepot = vd.idDepot
			INNER JOIN villes v ON v.idville=d.IdVille
			INNER JOIN clients c ON c.IdClient=f.idClient
			where EtatCmd=2
		group by  v.idville ,v.Designation , vd.idVendeur, vd.cin,vd.nom,vd.prenom,
			c.IdClient,c.intitule ,d.idDepot
		ORDER BY v.idville ,vd.idVendeur,c.IdClient
	 ";
		
		 $params = array();	
	
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
								$TotalTTCVdr=0;
			 while($row=sqlsrv_fetch_array($stmt)){				 
										$keyVille = $row['IdVille'];		 
									
										$i=$i+1;
										if (!isset($groups[$keyVille])) {
											
											$groups[$keyVille] = array();
											$groups[$keyVille]['IdVille']=$row['IdVille'];
											$groups[$keyVille]['Designation']=$row['Designation'];
										} 
											$keyVdr= $row['IdVendeur'];
										if (!isset($groups[$keyVille][$keyVdr])) {
											
											$groups[$keyVille][$keyVdr] = array();
											$groups[$keyVille][$keyVdr]['IdVendeur']=$row['IdVendeur'];
											$groups[$keyVille][$keyVdr]['Vendeur']=$row['Vendeur'];
										} 
									//	echo "<br>---".$i."---<br>";
											$groups[$keyVille][$keyVdr][$i]['IdClient'] = $row['IdClient'];
											$groups[$keyVille][$keyVdr][$i]['Intitule'] = $row['Intitule'];
											$groups[$keyVille][$keyVdr][$i]['CA'] = $row['CA'];	
											$params3= array(				
													 $row['IdClient'],
													 $row['IdDepot']			
											) ;
											$CreditClt=creditClient($params3,$conn)[0];
											$Montant=creditClient($params3,$conn)[1];
										
											//ECHO $CreditClt."mmmm".$Montant;
											if((intval($CreditClt)==0) &&  (intval($Montant)!=0)){											
												$groups[$keyVille][$keyVdr][$i]['CreditClt']=$Montant;
											
											 }else $groups[$keyVille][$keyVdr][$i]['CreditClt']="";
			 }
			//	parcourir($groups);return;
//=====Déclaration des messages au format texte et au format HTML.
$tabArticle=array();$i=0;
$message_html = '<html><head></head><body><div style="background-color:#ebebeb;    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;border:1px solid #ebebeb">';
	$message_html .= '
		<table class="" bgcolor="#ffffff" cellpadding="10" style="width:100%;border-collapse:collapse">
				<thead>
					<tr>
					<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Ville</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Vendeur</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Client</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">CA</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Créditi</th>

					</tr>
				</thead>
				<tbody>
				';
 	foreach($groups as $u=>$v){	// ville

			if(is_array($v)){
					$message_html .='<tr>
							  <td class="prestation " colspan="5" style="border:1px solid #d6d4d4;">'.$v['Designation'].'	</td>
							</tr>';
							
			foreach($v as $k){	//vendeur
				if(is_array($k)){
					$message_html .='<tr>
								<td></td>
								  <td class="prestation " colspan="4" style="border:1px solid #d6d4d4;">'.$k['Vendeur'].'	</td>
								</tr>';
							foreach($k as $r){
								if(is_array($r)){
									 // array_push($tabArticle, $r);
									//  $tabArticle=$r;
										$message_html .='<tr>
										<td></td>	<td></td>
										  <td class="prestation " style="border:1px solid #d6d4d4;">'.$r['Intitule'].'	</td>
											<td  align="right" style="border:1px solid #d6d4d4;" >'.$r['CA'].'	</td>
											<td  align="right" style="border:1px solid #d6d4d4;">'.$r['CreditClt'].'	</td>
										</tr>';
							 } 
						}
				}
			}
			}
				}	
		$message_html .='
		</tbody></table>';
	
	//print_r($tabArticle);return;
	$message_html .='<br><b>Nb: Merci de trouver ci-joint votre commande.<br>';
	$message_html .='</div></body></html>';
//==========

//creation de fichier pdf

// Instanciation de la classe dérivée
$pdf = new PDF();
  $pdf->AddPage();
  /*
$pdf->AjouterChapitre('Dépôt Electroprotect

Electro Protect 38, rue Imam El Boukhari .Quartier: Maarif casablanca

Tél:0522 992 970

Mail:contact@pgd.ma',1,'');
$pdf->AjouterChapitre('Vendeur : Nabil Lahlou

Bon de commande n° NC1700016

Date de commande : 21/02/2017 ',2,'');*/
$headerTab= array('Ville	', 'Vendeur', 'Client', 'Chiffre d\'affaire', 'Crédit');

$pdf->SetFont('Arial','',14);
//$pdf->AddPage();
  $pdf->Ln(10);
  $tabArticle=$groups;
$pdf->FancyTable($headerTab,$tabArticle);

//$pdf->Output();


$filename = "ca_par_vendeur.pdf";
//=====Lecture et mise en forme de la pièce jointe.
// encode data (puts attachment in proper format)
//$pdfdoc = $pdf->Output('test.pdf', "S");
$path = "img_magasins/ca_par_vendeur.pdf";
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
return true;
}

/*
if( envoiCmd('2046')==true) $error="bien envoyé";
	else $error="Erreur";
	echo $error;*/	
//==========
echo envoiAlert($conn);
?>
 
     
