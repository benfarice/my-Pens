<?php
//$to = 'amina@electroprotect.ma';
/*
$to = 'ouboukrimsanaa01@gmail.com';
$headers = 'From: contact@pgd.ma'."\r\n\r\n";
if (mail($to, "teste d'envoi", "message pour le teste", $headers))
echo "votre mail a été bien envoyé";
else echo "echoué";  */
/*
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
    $this->Cell(30,10,'Titre',1,0,'C');
    // Saut de ligne
    $this->Ln(20);
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
}

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Impression de la ligne numéro '.$i,0,1);
$pdf->Output();

return;*/
/*
$nom_user='amina';$prenom_user='wahmane';
$mail='contact@pgd.ma';

        $headers = "MIME-Version: 1.0\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\n";        
        $headers .= 'From: '.strtoupper($nom_user).' '.$prenom_user.' <'.$mail.'>' . "\n";
        $headers .= 'Reply-to: '.strtoupper($nom_user).' '.$prenom_user.' <'.$mail.'>' . "\n";
        $headers .= 'Return-path: '.strtoupper($nom_user).' '.$prenom_user.' <'.$mail.'>' . "\n";
        $headers .= "X-Mailer: PHP ".phpversion()."\n";
          
       $to ='amina@electroprotect.ma';
        $subject = 'test  d\'envoi ';

$body =    '<html>
      <head>
       <title>Calendrier des anniversaires pour Août</title>
      </head>
      <body>
       <p>Voici les anniversaires à venir au mois d\'Août !</p>
       <table>
        <tr>
         <th>Personne</th><th>Jour</th><th>Mois</th><th>Année</th>
        </tr>
        <tr>
         <td>Josiane</td><td>3</td><td>Août</td><td>1970</td>
        </tr>
        <tr>
         <td>Emma</td><td>26</td><td>Août</td><td>1973</td>
        </tr>
       </table>
      </body>
     </html>';
       if (mail($to, $subject, $body, $headers))
echo "votre mail a été bien envoyé";
else echo "echoué"; */
function envoiCmd($IdFacture){
$mail = 'amina.wahmane@gmail.com'; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}


require_once('../connexion.php');
session_start();
$IdDepot=$_SESSION['IdDepot'];
$sql = "
		select  
		 f.[date] AS 'date_facture',c.nom,c.prenom,c.adresse,c.IdClient,a.idarticle,vi.Designation ville,d.adresse AdresseDepot,d.Designation DsgDepot,
		a.designation article,df.tarif,df.qte,c.nom+' '+c.prenom client,c.intitule,v.nom+' '+v.prenom vendeur 
		,df.tauxtva,df.tva,df.ht,df.ttc,df.[type] colisage,f.numfacture,f.totalTTC-f.reste avance,f.reste 
		,case df.[type] when '' then convert(varchar,df.qte) 
		else  convert(varchar,df.qte*convert(int,df.[type])) end TotalUnitaire
		from 
		factures f inner join detailfactures df on df.idfacture=f.idfacture
		inner join clients c on c.idclient=f.idclient
		INNER JOIN villes vi ON vi.idville=c.ville
		inner join vendeurs v on v.idvendeur=f.idvendeur
		inner join articles a on a.idarticle=df.idarticle
		inner join depots d on d.idDepot=a.idDepot
		where f.idfacture=? and a.idDepot=?
			 ";
		/*echo $sql;
		echo "<br>".$IdFacture;return;*/
		 $params = array($IdFacture,$IdDepot);	
	
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
								/*	  $Options.="<option value=".$row['IdType'].">". stripslashes (htmlentities($row['IdType']."  ".
												 $row['Prenom']			."  ".$row['Designation']))."</option>";*/
												 
												 
										$key = $row['IdClient'];
										$i=$i+1;
										if (!isset($groups[$key])) {
											
											$groups[$key] = array();
											$groups[$key]['IdClient']=$row['IdClient'];
											$groups[$key]['Nom']=$row['nom'];
											$groups[$key]['Prenom']=$row['prenom'];
											$groups[$key]['Intitule']=$row['intitule'];
											$groups[$key]['Adresse']=$row['adresse'];
											$groups[$key]['Ville']=$row['ville'];
											$groups[$key]['NumFacture']=$row['numfacture'];
											$date=date_create($row['date_facture']);
											$groups[$key]['DateFac']=date_format($date, 'd/m/Y');
											$groups[$key]['AdresseDepot']=$row['AdresseDepot'];
											$groups[$key]['DsgDepot']=$row['DsgDepot'];
												
									
										} 
									//	echo "<br>---".$i."---<br>";
										
											$groups[$key][$i]['IdArticle'] = $row['idarticle'];
											$groups[$key][$i]['DsgArticle'] = $row['article'];									
											$groups[$key][$i]['PV'] =$row['tarif'];
											$groups[$key][$i]['Qte'] =$row['qte'];
											$groups[$key][$i]['Colisage'] =$row['colisage'];
											$groups[$key][$i]['TotalQteUni'] =$row['qte']*$row['colisage'];
											$groups[$key][$i]['TVA'] =$row['tauxtva'];
											$groups[$key][$i]['HT'] =$row['ht'];
											$groups[$key][$i]['TTC'] =$row['ttc'];
										
										$TotalHT+=$row['ht'];
										$TotalTTC+=$row['ttc'];
										$TotalTVA+=$row['tva'];
			 }
				
				
				
//=====Déclaration des messages au format texte et au format HTML.

$message_html = '<html><head></head><body>.';
 	foreach($groups as $u=>$v){	
$message_html .= '
<table style="width:100%" ><tr><td align="center" style="border-bottom:4px solid #333333;padding:7px 0">
<img src="../image/logo.png"></td></td></tr><tr><td padding:7px 0>
<font size="2" face="Open-sans, sans-serif" color="#555454">
			<span>Une nouvelle facture a été crée sur votre platforme  <span class="il">PGD</span> <span class="il"></span> par ce client : '.$v['Intitule'].' </span>
		</font>
</td></tr></table>
<table class= style="width:100%"  cellpadding="10">
			<tbody><tr>
				<td width="10" style="padding:7px 0">&nbsp;</td>
				<td style="padding:7px 0">
					<font size="2" face="Open-sans, sans-serif" color="#555454">
						<p style="border-bottom:1px solid #d6d4d4;
						margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
							Détails de la facture					</p>
						<span style="color:#777">
							<span style="color:#333"><strong>Facture :</strong></span> '.$v['NumFacture'].'
							passée le '. $v['DateFac'].'<br><br>
						
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
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">Total Qte Unitaire</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">PV HT</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">TVA %</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">HT</th>
						<th style="border:1px solid #d6d4d4;background-color:#fbfbfb;font-family:Arial;color:#333;font-size:13px;padding:10px">TTC</th>
					</tr>
				</thead>
				<tbody>
				';
				
			foreach($v as $r){
				if(is_array($r)){
						$message_html .='<tr>
						  <td class="prestation ">'.$r['DsgArticle'].'	</td>
							<td  align="right" ><'.$r['Qte'].'	</td>
							<td  align="right">'.$r['Colisage'].'	</td>
							<td  align="right">'. $r['TotalQteUni'].'	</td>
							<td  align="right">'. $r['PV'].'	</td>
							<td align="right" >'. $r['TVA'].'	</td>
							<td   align="right" >'. $r['HT'].'	</td>
							<td align="right"  >'. $r['TTC'].'	</td>
						</tr>';
			 } 
			}
	$message_html .='
						
					
					<tr class="">
						<td bgcolor="#f8f8f8" colspan="6" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
							<table class="" style="width:100%;border-collapse:collapse">
								<tbody><tr>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
									<td align="right" style="color:#333;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											<strong>Total H.T.</strong>
										</font>
									</td>
									<td width="10" style="color:#333;padding:0"></td>
								</tr>
							</tbody></table>
						</td>
						<td bgcolor="#f8f8f8" align="right" colspan="4" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
							<table class="" style="width:100%;border-collapse:collapse">
								<tbody><tr>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
									<td align="right" style="color:#333;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											'.number_format($TotalHT, 2, '.', ' ').' Dh
										</font>
									</td>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
								</tr>
							</tbody></table>
						</td>
					</tr>	


				<tr class="">
						<td bgcolor="#f8f8f8" colspan="6" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
							<table class="" style="width:100%;border-collapse:collapse">
								<tbody><tr>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
									<td align="right" style="color:#333;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											<strong>Total T.V.A.</strong>
										</font>
									</td>
									<td width="10" style="color:#333;padding:0"></td>
								</tr>
							</tbody></table>
						</td>
						<td bgcolor="#f8f8f8" align="right" colspan="4" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
							<table class="" style="width:100%;border-collapse:collapse">
								<tbody><tr>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
									<td align="right" style="color:#333;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											'.number_format($TotalTVA, 2, '.', ' ').' Dh
										</font>
									</td>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
								</tr>
							</tbody></table>
						</td>
					</tr>					
					<tr class="">
						<td bgcolor="#f8f8f8" colspan="6" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
							<table class="" style="width:100%;border-collapse:collapse">
								<tbody><tr>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
									<td align="right" style="color:#333;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											<strong>Total T.T.C.</strong>
										</font>
									</td>
									<td width="10" style="color:#333;padding:0"></td>
								</tr>
							</tbody></table>
						</td>
						<td bgcolor="#f8f8f8" align="right" colspan="4" style="border:1px solid #d6d4d4;color:#333;padding:7px 0">
							<table class="" style="width:100%;border-collapse:collapse">
								<tbody><tr>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
									<td align="right" style="color:#333;padding:0">
										<font size="2" face="Open-sans, sans-serif" color="#555454">
											'.number_format($TotalTTC, 2, '.', ' ').' Dh
										</font>
									</td>
									<td width="10" style="color:#333;padding:0">&nbsp;</td>
								</tr>
							</tbody></table>
						</td>
					</tr>	
				</tbody>
			</table>
			';
	}		
	$message_html .='
</body></html>';
//==========
 
//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = "Nouvelle facture!";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"test\"<contact@pgd.ma>".$passage_ligne;
$header.= "Reply-to: \"test\" <contact@pgd.ma>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
//==========
$message= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========
 
//=====Envoi de l'e-mail.

if (mail($mail, $sujet, $message, $header))
return true;
else return false;
}
if( envoiCmd('2145')==true) $error="bien envoyé";
	else $error="Erreur";
	echo $error;	
//==========
?>
 
     
