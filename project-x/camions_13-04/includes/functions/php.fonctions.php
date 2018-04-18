<?php

function fctredimimage($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src) {
 $condition = 0;

 // Si certains paramètres ont pour valeur '' :
 if ($rep_Dst=='') { $rep_Dst = $rep_Src; } // (même répertoire)
 if ($img_Dst=='') { $img_Dst = $img_Src; } // (même nom)
 // ---------------------
echo "";
 // si le fichier existe dans le répertoire, on continue...
 if (file_exists($rep_Src.$img_Src) && ($W_max!=0 || $H_max!=0)) {  
   // ----------------------
   // extensions acceptées : 
	$extension_Allowed = 'jpg,jpeg,png';	// (sans espaces)
   // extension fichier Source
	$extension_Src = strtolower(pathinfo($img_Src,PATHINFO_EXTENSION));
   // ----------------------
   // extension OK ? on continue ...
   if(in_array($extension_Src, explode(',', $extension_Allowed))) {
     // ------------------------
      // récupération des dimensions de l'image Src
      $img_size = getimagesize($rep_Src.$img_Src);
      $W_Src = $img_size[0]; // largeur
      $H_Src = $img_size[1]; // hauteur
      // ------------------------
      // condition de redimensionnement et dimensions de l'image finale
      // ------------------------
      // A- LARGEUR ET HAUTEUR maxi fixes
      if ($W_max!=0 && $H_max!=0) {
         $ratiox = $W_Src / $W_max; // ratio en largeur
         $ratioy = $H_Src / $H_max; // ratio en hauteur
         $ratio = max($ratiox,$ratioy); // le plus grand
         $W = $W_Src/$ratio;
         $H = $H_Src/$ratio;   
		          $condition =1;
        // $condition = ($W_Src>$W) || ($W_Src>$H); // 1 si vrai (true)
      }
      // ------------------------
      // B- HAUTEUR maxi fixe
      if ($W_max==0 && $H_max!=0) {
         $H = $H_max;
         $W = $H * ($W_Src / $H_Src);
         $condition = ($H_Src > $H_max); // 1 si vrai (true)
      }
      // ------------------------
      // C- LARGEUR maxi fixe
      if ($W_max!=0 && $H_max==0) {
         $W = $W_max;
         $H = $W * ($H_Src / $W_Src);         
         $condition = ($W_Src > $W_max); // 1 si vrai (true)
      }
      // ---------------------------------------------
      // REDIMENSIONNEMENT si la condition est vraie
      // ---------------------------------------------
      // - Si l'image Source est plus petite que les dimensions indiquées :
      // Par defaut : PAS de redimensionnement.
      // - Mais on peut "forcer" le redimensionnement en ajoutant ici :
      // $condition = 1; (risque de perte de qualité)
      if ($condition==1) {
         // ---------------------
         // creation de la ressource-image "Src" en fonction de l extension
         switch($extension_Src) {
         case 'jpg':
         case 'jpeg':
           $Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src);
           break;
         case 'png':
           $Ress_Src = imagecreatefrompng($rep_Src.$img_Src);
           break;
         }
         // ---------------------
         // creation d une ressource-image "Dst" aux dimensions finales
         // fond noir (par defaut)
         switch($extension_Src) {
         case 'jpg':
         case 'jpeg':
           $Ress_Dst = imagecreatetruecolor($W,$H);
           break;
         case 'png':
           $Ress_Dst = imagecreatetruecolor($W,$H);
           // fond transparent (pour les png avec transparence)
           imagesavealpha($Ress_Dst, true);
           $trans_color = imagecolorallocatealpha($Ress_Dst, 0, 0, 0, 127);
           imagefill($Ress_Dst, 0, 0, $trans_color);
           break;
         }
         // ---------------------
         // REDIMENSIONNEMENT (copie, redimensionne, re-echantillonne)
         imagecopyresampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src); 
         // ---------------------
         // ENREGISTREMENT dans le repertoire (avec la fonction appropriee)
         switch ($extension_Src) { 
         case 'jpg':
         case 'jpeg':
           imagejpeg ($Ress_Dst, $rep_Dst.$img_Dst);
           break;
         case 'png':
           imagepng ($Ress_Dst, $rep_Dst.$img_Dst);
           break;
         }
         // ------------------------
         // liberation des ressources-image
         imagedestroy ($Ress_Src);
         imagedestroy ($Ress_Dst);
      }
      // ------------------------
   }
 }
 // ---------------------------------------------------
 // retourne : true si le redimensionnement et l'enregistrement ont bien eu lieu, sinon false
 if ($condition==1 && file_exists($rep_Dst.$img_Dst)) { return true; }
 else { return false; }
 // ---------------------------------------------------
};
//mb_ucfirst pour mettre les lettres accentuées en majiscule
 if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
      $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
      $str_end = "";
      if ($lower_str_end) {
	$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
      }
      else {
	$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
      }
      $str = $first_letter . $str_end;
      return $str;
    }
  }

function toDateSql(  $datee)
{
		$tab = explode("/",$datee);
		$result= $tab[1]."-".$tab[0]."-".$tab[2];
        return $result;           
}
  
function arrayUniqueByIdentifier(array $array, string $identifier)
{
    $ids = array_column($array, $identifier);
    $ids = array_unique($ids);
    $array = array_filter($array,
        function ($key, $value) use($ids) {
            return in_array($value, array_keys($ids));
        }, ARRAY_FILTER_USE_BOTH);
    return $array;
}
function Increment_Chaine($chp,$table,$orderBy){
	/*$resu= mysql_query("SELECT ".$chp." FROM ".$table." ORDER BY ".$orderBy." DESC LIMIT 1; ") or die(mysql_error()); //where Etat=1
		$r = mysql_fetch_row($resu);
		$LastNum=$r[0];
		$number=substr($LastNum,0,4);
		$number = intval($number)+1;
		if(strlen($number)==1) $number="000".$number."-".date("Y");
		if(strlen($number)==2) $number="00".$number."-".date("Y");
		if(strlen($number)==3) $number="0".$number."-".date("Y");
		if(strlen($number)==4) $number=$number."-".date("Y");
		return $number;
		*/
}
function Increment_Chaine_F($chp,$table,$orderBy,$conn,$req,$array){
	$errors="";$error="";
		if($req==""){
		$sql="SELECT TOP 1 (".$chp.") FROM ".$table." ORDER BY ".$orderBy." DESC";
		$params = array();
		}
		else {

			$sql="SELECT TOP 1 (".$chp.") FROM ".$table." where ".$req." ORDER BY ".$orderBy." DESC";
			$params =$array;
		}

		$resAff=sqlsrv_query( $conn, $sql,$params,array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));  

	if( $resAff== false ) {
		$errors = sqlsrv_errors();
		$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		
	}
	
	$nRes = sqlsrv_num_rows($resAff);
	 if($nRes !=0){	

		$row = sqlsrv_fetch_array( $resAff, SQLSRV_FETCH_ASSOC);
//		print_r($row);
		$LastNum = $row[$chp];		

		$number=substr($LastNum,4,5);
		$number = intval($number)+1;
		//echo $number."---<br>";
		if(strlen($number)==1) $number=date("y")."0000".$number;
		if(strlen($number)==2) $number=date("y")."000".$number;
		if(strlen($number)==3) $number=date("y")."00".$number;
		if(strlen($number)==4) $number=date("y")."0".$number;
		if(strlen($number)==5) $number=date("y").$number;

		}
		else {
		
		$number=date("y")."00001";
		
	/*	if(strlen($number)==1) $number="0000".$number."-".date("Y");
		if(strlen($number)==2) $number="000".$number."-".date("Y");
		if(strlen($number)==3) $number="00".$number."-".date("Y");
		if(strlen($number)==4) $number="0".$number."-".date("Y");
		if(strlen($number)==5) $number=$number."-".date("Y");*/
		}
		if( $error=="" ) {
			return ($number);
		}else return $error;
		
}
function securite_bdd($string)
	{
		// On regarde si le type de string est un nombre entier (int)
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		// Pour tous les autres types
		else
		{
			$string = ms_escape_string($string);
			$string = addcslashes($string, '%_');
		}
		
		return $string;
	}
function ms_escape_string($data) {
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
        return $data;
    }
	function dateDiff($date1, $date2) {
		 $s = strtotime($date2)-strtotime($date1);
		 $d = intval($s/86400)+1;  
		 return "$d";
	} 

	function br2nl($foo) {
		return preg_replace("/\<br\s*\/?\>/i", " \n ", $foo);
	}
	
	function jourPrec($dateSql){
		$tabDate = explode("-",$dateSql);
		$mois = $tabDate[1];
		$annee = $tabDate[0];
		$jour = $tabDate[2];
		
		if($jour == '01'){
			if($mois == '01'){
				return diffZero($annee-1,0)."-12-31";
			}else return $annee."-".diffZero($mois-1,0)."-".cal_days_in_month(CAL_GREGORIAN, ($mois-1), $annee);
		}else return $annee."-".$mois."-".diffZero($jour-1,0);
		
	}

	
	function connecter_MySQL(){
		@session_start();
		$serveur_mysql="127.0.0.1";
		if(isset($_SESSION['M'])) $bdd_mysql=$_SESSION['M']['base'];
		else {
			if(isset($_GET['bdd'])){
				$bdd_mysql = $_GET['bdd'];
				//echo $bdd_mysql;exit;
			}else 
				return;
		}
		$login_mysql="root";
		$mdp_mysql="root";
		
		$mysql_conn = mysql_connect($serveur_mysql, $login_mysql, $mdp_mysql)
		or die("Impossible de se connecter : " . mysql_error());
		
		$mysql_bdd = mysql_select_db($bdd_mysql,$mysql_conn)
		or die($bdd_mysql." <<< 	IMPOSSIBLE DE CHOISIR LA BASE");
		
		$nbResPage = 50 ;
	}
	
	function execSQL ($sql){
		$res = mysql_query($sql);
		if(!$res) {	echo "<br/>ERREUR mySQL = ".mysql_error()."<br/>"; return;}
		echo "exec SQL <br/>";
		while($row = mysql_fetch_assoc($res)){
		
			foreach($row as $a=>$b) {	echo "<br/><strong>$a</strong>:$b";	}
			echo "<br/>---<br/>";
		}
	}
	function moisPrec($moisCourant,$nbreMois,$anneeCourante){
		if($moisCourant == '01' && $nbreMois == 1) return ($anneeCourante - 1).'-12';
		elseif($moisCourant == '01' && $nbreMois == 2) return ($anneeCourante - 1).'-11'; 
		elseif($moisCourant == '01' && $nbreMois == 3) return ($anneeCourante - 1).'-10';
		elseif($moisCourant == '01' && $nbreMois == 4) return ($anneeCourante - 1).'-09';
		elseif($moisCourant == '02' && $nbreMois == 2) return ($anneeCourante - 1).'-12';
		elseif($moisCourant == '02' && $nbreMois == 3) return ($anneeCourante - 1).'-11'; 
		elseif($moisCourant == '02' && $nbreMois == 4) return ($anneeCourante - 1).'-10';
		elseif($moisCourant == '03' && $nbreMois == 3) return ($anneeCourante - 1).'-12';
		elseif($moisCourant == '03' && $nbreMois == 4) return ($anneeCourante - 1).'-11';
		elseif($moisCourant == '04' && $nbreMois == 4) return ($anneeCourante - 1).'-12';
		else {	$diff = ($moisCourant - $nbreMois);
			if($diff <=9) $diff = '0'.$diff;
			return $anneeCourante.'-'.$diff;
		}
	}
	function evalMem($debut){
		$mem = number_format( memory_get_usage() / 1000, 1);
		$sec = number_format((microtime(true) - $debut) * 1000 ,1);
		$afficher =  "<strong>$mem</strong> Ko en $sec ms";
		echo '<script language="javascript"> $("#infosMem").html("'.$afficher.'"); </script>';
	}
	function affC($n){
		if($n >= 0) return $n;
		else return 0;
	}
	function affD($n){
		if($n < 0) return $n;
		else return "";
	}
	function diffZero($a,$b){
		$c = $a-$b;
		if($c <= 9) return "0".$c;
		else return $c;
	}

	
	function virgule($valVentes){
		return substr($valVentes,0,strlen($valVentes)-1);
	}
	function nomMois($mois){
		$tab = array('Janvier','F&eacute;vrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','D&eacute;cembre');
		return $tab[$mois-1];
	}
	function nomMois2($mois){
		$tab = array('Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre');
		return $tab[$mois-1];
	}
	function nomMois3($mois){
		$tab = array('Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre');
		$x = explode("-",$mois);
		return $tab[$x[1]-1];
	}
	function nomMois4($mois){
		$tab = array('Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre');
		$x = explode("-",$mois);
		echo "&nbsp;<strong>".$tab[$x[1]-1]."&nbsp;".$x[0]."</strong>";
	}
	function parcourir($tab){
		//echo "<u> ";
		foreach($tab as $u=>$v){
			if(!is_array($v)) {
				echo "<li> <strong>$u</strong> : $v </li>";
			}else {
				echo "<ul style='border-left:1px solid red;'> <u>$u (tab)</u>";
				parcourir($v);
				echo "</ul>";
			}
			
		}
		//echo "</u>";
	
	}
	function regInBoite($dateSQL,$delai,$numBoite,$moisBoite){
		$dR = explode("-",dateReelle($dateSQL,$delai));
		if( ($dR[2] < $numBoite) && ($dR[2] >= ($numBoite-5)) && ($dR[1] == $moisBoite)) return true;
		else return false;
	}
	function determinerBoite($dateR){
		$tabDate = explode('-',$dateR);
		$jR = $tabDate[2];
		$mR = $tabDate[1];
		$aR = $tabDate[0];
		$boite = array();
		$boite['n'] = "";
		$boite['m'] = $mR;
		$boite['a'] = $aR;
		$tab[5] 	= array(1,2,3,4);
		$tab[10]	= array(5,6,7,8,9);	
		$tab[15]	= array(10,11,12,13,14);	
		$tab[20]	= array(15,16,17,18,19);	
		$tab[25]	= array(20,21,22,23,24);	
		$tab[30]	= array(25,26,27,28,29);	 
		
		if(in_array($jR,$tab[5])) $boite['n'] = '05';
		elseif(in_array($jR,$tab[10])) $boite['n'] = 10;
		elseif(in_array($jR,$tab[15])) $boite['n'] = 15;
		elseif(in_array($jR,$tab[20])) $boite['n'] = 20;
		elseif(in_array($jR,$tab[25])) $boite['n'] = 25;
		elseif(in_array($jR,$tab[30])) $boite['n'] = 30;
		elseif($jR == 30){
			$boite['n'] = '05';
			if($mR == 12){
				$boite['m'] = '01';
				$boite['a'] = diffZero($aR,-1);
			}else{
				$boite['m'] = diffZero($mR,-1);
			}
		
		}
		return $boite;
		
		
	
	}
	function dateReelle($dateSQL,$delai){
		$tabDate = explode('-',$dateSQL);
		$tabFinMois = explode('-',finMois($dateSQL));
		$jR = $tabDate[2];
		$mR = $tabDate[1];
		$aR = $tabDate[0];
		$nbreJoursMois = $tabFinMois[2];
		$jR += $delai;
		if($jR > $nbreJoursMois){
			$jR = $jR - $nbreJoursMois;
			$mR++;
			if($mR > 12){
				$mR = $mR - 12;
				$aR++;
			}	
		}
		if($jR<10) $jR = "0".$jR;
		return $aR."-".$mR."-".$jR;
		
		
	
	}
	function accents($str){
		$acc = array('é','è','à');
		$nAcc = array('&eacute;','&egrave;','&agrave;');
		$str = str_replace($acc, $nAcc,$str);
		return $str;	
	}
	function recupCh($table,$cleTable,$idTable,$mysql_conn_config=null){
		$req = "SELECT * FROM $table WHERE $cleTable='$idTable'";
		if(isset($mysql_conn_config))	$pp = mysql_fetch_array(mysql_query($req,$mysql_conn_config));
		else $pp =  mysql_fetch_array(mysql_query($req));
		//echo "<hr/> ERREUR = ".mysql_error();
		return $pp;
	}
	function DHS($val){
		if(!is_numeric($val)) return '';
		return number_format($val,2,"."," ");
	}
	function date_sql($d){
		if($d == "") return "";
		else{
			$tab = explode("/",$d);
			return $tab[2]."-".$tab[1]."-".$tab[0];
		}
		//return count($tab);
	}
	function sql_date($d){
		if($d == "") return "";
		else{
			$tab = explode("-",$d);
			if(count($tab) < 3) {
				$tab = explode("/",$d);
				if(count($tab) != 3) return "   ";
				else return $tab[2]."/".$tab[1]."/".$tab[0];
			}else return $tab[2]."/".$tab[1]."/".$tab[0];
		}
		//return count($tab);
	}
	function maxNumLignes($lignes){
		$max = 0;
		foreach($lignes as $numLigne=>$ligne){
			if($numLigne >= $max) $max = $numLigne ; 
		}
		return $max;
	}

	function mois($date){
		$t = explode('-',$date);
		return $t[1];
	}
	function dev($tab){
		echo "<div>";
		foreach($tab as $a=>$b){
			//if(is_int($a)) $a="";
			echo "<ul>";
			if(is_array($b)) {
				echo "<li> <u>$a</u> contient un tab : </li>";
				dev($b);
			}else{
				echo "<li> <u>$a</u> : $b </li>";
			}
			echo "</ul>";
		}
		echo "</div>";
	}

	function finMois($date){
		$tab = explode("-",$date);
		$mois = $tab[1];
		$annee = $tab[0];
		//1 3 5 7 8 10 12
		if($mois == "01" || $mois == "03" || $mois == "05" || $mois == "07" || $mois == "08" || $mois == "10" || $mois == "12") $jour = "31";
		//2
		if($mois == "02") $jour = "29";
		//4 6 9 11
		if($mois == "04" || $mois == "06" || $mois == "09" || $mois == "11") $jour = "30";
		return $annee."-".$mois."-".$jour;
	}

	

	$styleLog = '<style type="text/css">
#simulation{width:55%;font-family:arial;color:#333;}
#simulation div{	padding:10px;margin-top:-5px;margin-bottom:5px;border:1px solid #778;border-top:0px; }
#simulation li{	border:1px solid #778; margin:0px; list-style-type:none; padding:3px;	}
#simulation li.ste { background-color:#DAEBEB;	}
#simulation li.fa { background-color:#D2C2B2; color:#FFFFFF;	}
#simulation li.bl { background-color:#FBFEF3;	}
#simulation li.valider { display:none;	}
#simulation td,#simulation{font-size:10pt;}
</style>';
//


/*
function lettrer($reg,$fa,$tabRes){
	$tab = array();
	if($reg['mr'] == $fa['mf']) {	//on cree RG
	
		$n="";
		$n = trouverQ ($tabRes,$reg['idr']);
		if($n==""){ //on cree une nouvelle ligne
			$tab['MFL'] = $reg['mr'];
			$tab['MRL'] = $reg['mr'];
			$tab['ML'] = $reg['mr'];
			$tab['TYPEL'] = "RG";
			$tab['IDF'] = $fa['idf'];
			$tab['IDR'] = $reg['idr'];
			array_push($tabRes,$tab);
		}else{ //on utilise une ligne de rq deja existante
			
			$tabRes[$n]['IDF'] = $fa['idf'];
			$tabRes[$n]['MFL'] = $fa['mf'];
			$tabRes[$n]['ML'] = $fa['mf'];
			
		}
		
	}elseif($reg['mr'] > $fa['mf']) { //on cree RG+QR
		$diff = $reg['mr'] - $fa['mf'];
		
		$regPetit = array();
		$regPetit['mr'] = $fa['mf'];
		$regPetit['idr'] = $reg['idr'];
		
		$tabRes = lettrer($regPetit,$fa,$tabRes);
		
		$tab['MRL'] = $diff;;
		$tab['TYPEL'] = "QR";
		$tab['IDF'] = $tab['MFL'] = $tab['ML'] = "" ;
		$tab['IDR'] = $reg['idr'];
		
		array_push($tabRes,$tab);
	
	}elseif($reg['mr'] < $fa['mf']) { // on cree RG+QF
		$diff = $fa['mf'] - $reg['mr'];
		
		$faPetit = array();
		$faPetit['mf'] = $reg['mr'];
		$faPetit['idf'] = $fa['idf'];
		
		$tabRes = lettrer($reg,$faPetit,$tabRes);
		
		$tab['MFL'] = $diff;;
		$tab['TYPEL'] = "QF";
		$tab['IDR'] = $tab['MRL'] = $tab['ML'] = "" ;
		$tab['IDF'] = $fa['idf'];
		
		array_push($tabRes,$tab);
		$tabRes['stop'] = 1;
	}
		
	return $tabRes;
}
function lettrerReg($reg,$tabFa,$tabRes){
	foreach($tabFa as $a=>$fa){
		if($fa['mf']!= "" && $tabRes['stop'] == 0){
			$n="";
			$n=trouverQ($tabRes,$reg['idr']);
			//on a un rqR
			if($n != "")	$reg['mr'] = $tabRes[$n]['MRL'];
			
			
			$tabRes = lettrer($reg,$fa,$tabRes);
		}
	}
	return $tabRes;
}
function trouverQ ($tabRes,$idr){
	foreach($tabRes as $n=>$tab){
		
		
		if(isset($tab['IDR']) && $tab['IDR'] == $idr && $tab['TYPEL'] == "QR") {
			
			return $n;
			break;
		}
		
		//if(isset($tab['idr'])) echo $tab['TYPEL']." - ".$tab['idr'];
	}
}*/
function majTabRes($tabRes,$code,$lien){
		if(strlen($code) == 0){
			//echo "str terminé<br/>";
			$tabRes[$code{0}]= $lien;			
		}else{
		
			$c = $code{0};
			$nCode = substr($code,1,strlen($code));
			if(!isset($tabRes[$c])) $tabRes[$c] = array();
			$tabRes[$c] = majTabRes($tabRes[$c],$nCode,$lien);			
		}
		return $tabRes;
		//parcourir($tabRes);
		
	}
	
	
	
	
	function filtrerLiens($tab){
		
		$tabRes = array();
		foreach($tab as $i=>$t){
			$code = $t['code'];
			//echo "code=$code <br/>";
			$tabRes = majTabRes($tabRes,$code,$i);
			//echo "</hr><br/>";
			//echo "</hr>".parcourir($tabRes);
		}
		return $tabRes;
	}
	
	function lireLiens($tab,$tabLiens){
		$courant = "";
		foreach($tab as $idLien => $tabContenu){
			
			if(!is_array($tabContenu)) {
			
				echo "<li>";
				
				echo $tabLiens[$tabContenu]['code']." : ".$tabLiens[$tabContenu]['nom'];
				
				echo "</li>";
				$courant = "";
			}else{
			
				$long = count($tab) -1 ;
				$courant = $courant.$idLien;
				if($long) echo "<li>Tout cocher ".$courant."</li>";
				
				echo "<ul>";
				lireLiens($tabContenu,$tabLiens);
				echo "</ul>";
			}
		}
	}

/// 

function dateDiffZero($date1, $date2)  
{
 $s = strtotime($date2)-strtotime($date1);
 $d = intval($s/86400);  
 return "$d";
} 

function dateInf($dateDeb,$dateFin,$IOE){
	//if(dateDiff($dateDeb,$dateFin) > 0) return true;
	if($IOE == "1" && dateDiffZero($dateDeb,$dateFin) >= 0) return true; 
	elseif($IOE == "0" && dateDiffZero($dateDeb,$dateFin) > 0) return true;
	elseif($IOE == "0" && dateDiffZero($dateDeb,$dateFin) < 0) return false;
	elseif($IOE == "1" && dateDiffZero($dateDeb,$dateFin) <= 0) return false;
}

$styleLog = '<style type="text/css">
.titre{
		font-size:20px; 
		font-family:arial;
		}
	body{width:800px;margin:auto;}

.boxSte{
		border:0px solid #CCC;
		list-style:none;
		font-family:arial;
	}
	.contenuSte{
		border:0px solid #CCC;
		padding:0 5px ;
		font-family:arial;
	}
	
	.boxFa{
		
		list-style:none;
		padding:5px 0 5px 0;
		font-family:arial;
	}
	.titreSte{
		background:#CCCC00 url(../../images/arrBig1.png) repeat-x center;
		color:#333;
		font-size:23px;
		font-weight:bold;
		list-style:none;
		padding:5px;
		-moz-border-radius:7px;
		cursor:pointer;
		margin-bottom:2px;
		font-family:arial;
		
	}
	.titreFa{
		background:#91C5F9 url(../../images/arrBig1.png) repeat-x center;
		font-size:17px;
		list-style:none;
		padding:5px;
		color:#333;
		-moz-border-radius:5px;
		cursor:pointer;
		font-weight:bold;
		font-family:arial;
	}
	.titreBL,
	.recap{
		font-size:14px;
		list-style:none;
		padding:5px;
		margin:5px 0;
		-moz-border-radius:5px;
		background:#F4F4F4 url(../../images/arrBig1.png) repeat-x center;
		border:1px solid #CCC;
		font-weight:bold;
		font-family:arial;
			
	}
	.recap{
		background:#F4F4F4 url(../../images/arrBig3.png) repeat-x center;
		height:50px;
		font-family:arial;
	}

	.titreSte span.texte{
		font-size:17px;
		padding:5px;
		float:right;
		padding-right:100px;
		font-weight:bold;
		font-family:arial;
	}
	.titreSte span.montant{
		float:right;
		text-align:right;
		padding-right:5px;
		font-family:arial;
	}
	.titreBL span.montant{
		float:right;
		font-size:15px;
		color:#333;
		font-family:arial;
	}
	.titreFA span.montant{
		float:right;
		font-size:17px;
		color:#333;
		font-weight:bold;
		font-family:arial;
	}
		.resume{
		text-align:center;
		font-size:23px;
		padding:10px;
	}
		
</style>';



function rechTab($id,$tab){
	$r = "**";
	if(!is_array($tab)) return $r;
	foreach($tab as $nomTable => $t){	if($t['chp'] == $id) $r = $nomTable;	}
	return $r;
}
function nombreK($nbre){	return number_format($nbre,0,'',' ');	}

function req2ins($sql,$nomTable,$dbD,$dbF,$pkTable,$tabPK,$principale){

	$sqlRecup = " describe $dbD.$nomTable ";
	$resRecup = mysql_query($sqlRecup);
	$nomChamps = array();
	$colBesoin = "";
	while( $rowRecup = mysql_fetch_assoc($resRecup)){			
		array_push($nomChamps,$rowRecup['Field']);
		if($principale == 1) $colBesoin .= " $dbD.$nomTable.".$rowRecup['Field'].", ";
		else $colBesoin .= " besoin.".$rowRecup['Field'].", ";
	}
	
	
	
	//return;
	//echo "<hr/> $sql </hr>";
	$sql = str_replace("*",substr($colBesoin,0,-2),$sql);
	//echo "<hr/> $sql </hr>";
	//return;
	$res = mysql_query($sql);
	$resPK = array();
	if(!$res) {
		echo "<hr/>$sql<hr/>ERREUR MYSQL : <br/>".mysql_error();
		echo "<hr/>";
		
	}
	$nbre = mysql_num_rows($res);
	$ins = "";
	$pk = "";
	
		
	while($row = mysql_fetch_assoc($res)){
		$strNomFK = $strValFK  = "";
		
		foreach($row  as $nom => $val){	
			
			if(in_array($nom,$nomChamps)){
				$strNomFK  .= " $nom ,";
				if(($nom == 'GR' || substr($nom,0,2) == "ID" )&& ($val=='')) 	$strValFK  .= " NULL ,";
				else $strValFK  .= " '".addslashes($val)."' ,";		
			
			
			}
				
			if(substr($nom,0,2) == "ID"){
				$key = rechTab($nom,$tabPK);
				if($key != "**") {
					$pos = strpos($tabPK[$key]['pk'],$val);
					//echo "<br/> val= $val <br/>";
					if($pos == false && $val != "") $tabPK[$key]['pk'] .= " '$val' ,";
				}
				
			//echo $tabPK[$key]['pk']."$nomTable ";	
			//parcourir($tabPK);
			//echo "FIN<br>";
			
			}	
						
		}
		
		//echo "<hr/>";
		//if ($strValFK=='') $strValFK= " null ";
	//	else  $strValFK =$strValFK;
			//$strValFK =addslashes($strValFK);
	
		//$reqVerif="select * from $nomTable where $pkTable= '".$row[$pkTable]."'";
		//echo $reqVerif."<br>";
		
		/*$resVerif=mysql_query($reqVerif);
		echo mysql_error();
		*/
		
		$ins .=  	"INSERT IGNORE INTO $dbF.$nomTable (".substr($strNomFK,0,-1).") VALUES(".substr($strValFK,0,-1).") ; \n";
	
	}
	
	//echo "PK = ".$pk."<br>";
	$r = array();
	$r['ins'] = $ins;
	$r['nbre'] = $nbre;
	$r['pk'] = substr($pk,0,-1);
	$r['table'] = $nomTable;
	$r['tabPK'] = $tabPK ;
	return $r;
}

function fk($fk,$pk,$dbD,$dbF,$tabPK){

			$principale = 0 ;
			$aFK 	= $fk['aFK'];
			$pkAFK 	= $fk['pkAFK'];
			$chpFK 	= $fk['chpFK'];
			$dFK 	= $fk['dFK'];
			$besoin = $fk['besoin'];
			
			//$insFK  = "--/-- INSERTIONS FK $dbF.$aFK ----------------------------------------------------------------- \n";
			
			
			if($besoin == $aFK) {
				$sqlFK  = "	
					select 	
						*
					from 
						$dbD.$aFK besoin
					where 
						1
					
					";
				$chp = $chpFK;
				$pKey=$pkAFK;
				
				$key = rechTab($chp,$tabPK);
				if (isset($tabPK[$key]['pk'])) $in = substr($tabPK[$key]['pk'],0,-1);
				else $in=" '0' ";
		
				if($pk != "") $sqlFK .= " AND besoin.$chp IN ( ".$in." ) ";
				else $sqlFK .= " AND besoin.$chpFK IN ( '0' )  ";
				//echo $pkAFK."<br>";
				$sqlFK .=" group by besoin.$pKey ";
			
			}else{
				$sqlFK = "
					select 	
						*
					from 
						$dbD.$besoin besoin
						left join $dbD.$aFK aFK on aFK.$chpFK = besoin.$chpFK
					where 
						1
						
					";
				$chp = $pkAFK;
				$pKey=$chpFK;
				
				$key = rechTab($chp,$tabPK);
				if (isset($tabPK[$key]['pk'])) $in = substr($tabPK[$key]['pk'],0,-1);
				else $in=" '0' ";
					
				if($pk != "") $sqlFK .= " AND aFK.$chp IN ( ".$in." ) ";
				else $sqlFK .= " AND aFK.$chpFK IN ( '0' )  ";
				//echo $sqlFK."<br>";
				$sqlFK .=" group by aFK.$pKey ";
			}
		
			
			
		
			//echo "ins ".$in;
				
				
			//	echo $sqlFK." group by $pKey <br>";
			$tab = req2ins($sqlFK,$besoin,$dbD,$dbF,$pkAFK,$tabPK,$principale);
				
			//echo "<br/>sqlFK = ".$sqlFK;
			//return;
			//echo '<br/>'.($tabPK[$key]['pk']);	
			//echo '<br/>'.parcourir($tRech);
			
			//$insFK .= "--/-- FIN DES INSERTIONS FK $dbF.$aFK --------------------------------------------------------- \n\n";
			//parcourir($tab);
			return $tab;
			
}	

function reinit($fichierTampon){
	$fh = fopen($fichierTampon, 'w') or die("can't open file $sqlFile");
	fwrite($fh, '');
	fclose($fh);
}


function parcourirR($tab){

	 foreach($tab as $u=>$v){
				if(!is_array($v)) {
					echo "<li style='padding:3px;border-left:1px solid red;'> <strong>$u</strong> : $v </li>";
				}else {
					echo "<ul> ";
					   parcourirR($v);
					echo "</ul>";
				}
		}	
	
}
function insertion($dbD,$fichier){
$cmd='mysql --user="root" --password="root" '.$dbD.' < '.$fichier.' > log.txt';	
$list=shell_exec($cmd);
}


function testDA($idm,$codelien,$msg,$type){
	$sql = " select
				 ETATDA 
			from config_fa.droitsacces da
				LEFT JOIN config_fa.liens  liens on liens.IDLIEN=da.IDLIEN
			where 
				CODELIEN = '$codelien' and IDM ='$idm'
					" ;
		$res=mysql_query($sql);
		echo mysql_error();
		$row = mysql_fetch_assoc($res);
		$nbre=mysql_num_rows($res);
	$t = $msg;
	 //echo "ETAT = ".$row['ETATDA'];
	 //echo($sql);
		if (($row['ETATDA'] == '1' ) ){
			if($type == 'msgBox') {
				
				?><script language="javascript" type="text/javascript">
			
					var message="<?php echo "<div class='droitCadreA'><div class='txtCadreA'>Vous n'avez pas les privilèges nécessaires pour <strong>".$msg.".</strong><br><br>Veuillez 		contacter votre administrateur.</div></div>";?>";
					$('#testDA').html(message).dialog('open');
				 </script>
				 <?php 
			}else {
				 echo "<div class='droitCadre'><div class='txtCadre'>Vous n'avez pas les privilèges nécessaires pour <strong>".$msg.".</strong><br><br>Veuillez 		contacter votre administrateur.</div></div>";
			}
			return false;
		}else return true;
}

function NbrJourEntreDate($date1,$date2){
		$date1 = strtotime(($date1));
	$date2 = strtotime(($date2));
		
	// On récupère la différence de timestamp entre les 2 précédents
	$nbJoursTimestamp = $date2 - $date1;
	// ** Pour convertir le timestamp (exprimé en secondes) en jours **
	// On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
	$nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24
	return $nbJours;
}

function secondsToWords($seconds)
{
    
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");

    $ret = "";

    /*** get the days ***/
    $days = intval(intval($seconds) / (3600*24));
    if($days> 0)
    {
        $ret .= "$days ".$trad['label']['jour'];
    }

    /*** get the hours ***/
    $hours = (intval($seconds) / 3600) % 24;
    if($hours > 0)
    {
        $ret .= "$hours ".$trad['label']['hour'];
    }

    /*** get the minutes ***/
    $minutes = (intval($seconds) / 60) % 60;
    if($minutes > 0)
    {
        $ret .= "$minutes ".$trad['label']['min'] ;
    }

    /*** get the seconds ***/
    $seconds = intval($seconds) % 60;
    if ($seconds > 0) {
      //  $ret .= "$seconds s";
    }

    return $ret;
}

function NombreDeJour($date1,$date2)
{  

	$date1 = strtotime($date1);
$date2 = strtotime($date2);

// On récupère la différence de timestamp entre les 2 précédents
$nbJoursTimestamp = $date2 - $date1;
 
// ** Pour convertir le timestamp (exprimé en secondes) en jours **
// On sait que 1 heure = 60 secondes * 60 minutes et que 1 jour = 24 heures donc :
$nbJours = $nbJoursTimestamp/86400; // 86 400 = 60*60*24
return $nbJours;
}
function ChargerSelect($tab,$DslChp,$IdChp){
		
		$Options = "";
	
		$req="select * from $tab where Etat=1 order by $DslChp ";
		
		$res=mysql_query($req) ;
	
		 $i=0;$s="";	
	if(mysql_num_rows($res) !=0){
			while($row=mysql_fetch_assoc($res)){
			
			$Options.="<option value=".$row[$IdChp].">".$row[$DslChp]."</option>";
			$i=$i+1;
				//echo "</hr><br/>";
				//echo "</hr>".parcourir($tabRes);
			}
		}
	return $Options;
	}
	
function HoutToWords($seconds)
{
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");

    $ret = "";

    /*** get the days ***/
    $days = intval(intval($seconds) / (3600*24));
    if($days> 0)
    {
       // $ret .= "$days j ";
    }

    /*** get the hours ***/
    $hours = (intval($seconds) / 3600) % 24;
    if($hours > 0)
    {
        $ret .= "$hours ".$trad['label']['hour'];;
    }

    /*** get the minutes ***/
    $minutes = (intval($seconds) / 60) % 60;
    if($minutes > 0)
    {
        $ret .= "$minutes ".$trad['label']['min'] ;
    }

    /*** get the seconds ***/
    $seconds = intval($seconds) % 60;
    if ($seconds > 0) {
      //  $ret .= "$seconds s";
    }

    return $ret;
}
/*function creditClient($params3,$conn){
	$error;
	//Recuperer Credit du clt // etat=1 cad dernier credit
	$sql = "SELECT sum(Avance) Credit FROM Avance WHERE idClient=? AND idDepot=? AND ModePaiement='Credit' and Etat=1"; 
	$stmtR = sqlsrv_query( $conn, $sql,$params3 );
	if( $stmtR === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur recuperation Credit : ".$errors[0]['message'] . " <br/> ";
	}
	sqlsrv_fetch($stmtR) ;
$Credit = sqlsrv_get_field( $stmtR, 0);
//Recuperer avance du clt
	$sql = "SELECT sum(Avance) Avance FROM Avance WHERE idClient=? AND idDepot=? AND ModePaiement!='Credit' "; 
	$stmtR = sqlsrv_query( $conn, $sql,$params3 );
	if( $stmtR === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur recuperation Avance : ".$errors[0]['message'] . " <br/> ";
	}
	sqlsrv_fetch($stmtR) ;
	$AvanceClt = sqlsrv_get_field( $stmtR, 0);

	
//Recuperer Total des montants facture clt

	$sql = "SELECT sum(totalTTC) FROM factures WHERE idClient=? AND idDepot=? and EtatCmd=2 "; 
	$stmtR = sqlsrv_query( $conn, $sql,$params3 );
	if( $stmtR === false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur recuperation Montant facture : ".$errors[0]['message'] . " <br/> ";
	}
	sqlsrv_fetch($stmtR) ;
	$TotalMtcFacture = sqlsrv_get_field( $stmtR, 0);
	
	// Total avance  clt
	//$TotalAvanceClt=floatval($AvanceClt)-floatval($Credit);
	//if(floatval($TotalAvanceClt)< floatval($TotalMtcFacture)) return $Credit;
	//else return 1;
	
	//floatval($TotalMtcFacture)+$Credit  montant à payé par le clt (floatval($TotalMtcFacture)+
	
	$TotalAvanceClt=floatval($AvanceClt)-floatval($Credit);

	if(floatval($TotalAvanceClt)<floatval($TotalMtcFacture) ){ return array(0, $Credit);}
	else {
		$ResteAv=floatval($TotalAvanceClt)-floatval($TotalMtcFacture);		
		  return array(1, $ResteAv);
	}
		

}*/
function tofloat($num) {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : 
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
   
    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    } 

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}
function insertAvanceCredit($conn,$IdClient,$IdVendeur){
	$target_path="";$error="";
	$Imprime="";
	$IdDepot=$_SESSION['IdDepot'];
			//----------------------Insertion Avance --------------------------//
		if(tofloat($_POST['MtEspece'])!=0){
			$ModePaiement="Espece";
	//	ECHO $_POST['MtEspece'];return;
			$MtAvance=tofloat($_POST['MtEspece']);
				$reqInser3 = "INSERT INTO Avance ([IdClient]  ,[IdVendeur] ,[DateAvance],Avance,idDepot,ModePaiement,ImgCheque) 
								values 	(?,?,?,?,?,?,?)";
						//	echo $reqInser1;
					
				$params3= array(				
								$IdClient,
								$IdVendeur,				
								date("Y-m-d"),
								$MtAvance,
								$IdDepot,
								$ModePaiement,
								$target_path
								
				) ;
				//print_r(	$params3);
			$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );
			if( $stmt4== false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout Avance Espece ".$errors[0]['message'] . " <br/> ";
			}
			$Imprime.= "Espèce : ".str_pad(number_format($MtAvance, 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
		}else {
			$Imprime.= "Espèce : ".str_pad(number_format("0", 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
		}

		if(tofloat($_POST['MtCheque'])!=0){
			$ModePaiement="Cheque";
			$MtAvance=tofloat($_POST['MtCheque']);
			if(isset($_FILES['file']))
			{
				$ext = explode('.', basename($_FILES['file']['name']));   // Explode file name from dot(.)
				$file_extension = end($ext); // Store extensions in the variable.
				$nameFile=md5(uniqid()) . "." . $ext[count($ext) - 1];
				if (!file_exists("imgPaiement/")) {
					mkdir("imgPaiement/", 0777, true);
				}
				$target_path = "imgPaiement/" . $nameFile;     // Set the target path with a new name of image.
				
					$error="";
					
					if (! move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
						{
						?>
									<script type="text/javascript"> 
										alert("<?php echo $trad['msg']['echecDeplacementImage'] ; ?>");
									</script>
						<?php
						return;
						}
			}
			else
			{
				$target_path = "";     // Set the target path with a new name of image.
			}
			//echo $target_path;return;
			
			$reqInser3 = "INSERT INTO Avance ([IdClient]  ,[IdVendeur] ,[DateAvance],Avance,idDepot,ModePaiement,ImgCheque) 
							values 	(?,?,?,?,?,?,?)";
					//	echo $reqInser1;
				
			$params3= array(				
							$IdClient,
							$IdVendeur,				
							date("Y-m-d"),
							$MtAvance,
							$IdDepot,
							$ModePaiement,
							$target_path
							
			) ;
		$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );
			if( $stmt4== false ) {
				$errors = sqlsrv_errors();
				$error.="Erreur : Ajout Avance Cheque".$errors[0]['message'] . " <br/> ";
			}
		$Imprime.= "Chèque : ".str_pad(number_format($MtAvance, 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
		}
		else {
			$Imprime.= "Chèque : ".str_pad(number_format("0", 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
		}
		if(isset($_POST['CheckboxC'])){
			$MtCredit=tofloat($_POST['MtCredit'])	;
		}else 
		{
			if(isset($_POST['EncienCredit'])){
			$MtCredit=tofloat($_POST['MtCredit'])+tofloat($_POST['EncienCredit'])	;
			}else {
				$MtCredit=tofloat($_POST['MtCredit'])	;
			}
		}
		if($_POST['MtCredit']!=""){
			$Imprime.="Crédit : ".str_pad(number_format($MtCredit, 2, '.', ' '), 12, ' ', STR_PAD_LEFT). " DH".PHP_EOL;
			
			$Imprime.=PHP_EOL;
		}

		// on desactive le dernier credit
				$requpdate = "update Avance set [etat]= 0 where IdClient=? and IdDepot=? and ModePaiement=?";
				$param= array($_SESSION['IdClient'],$IdDepot,'Credit') ;
				$stmt1 = sqlsrv_query( $conn, $requpdate, $param );
				if( $stmt1 === false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
					
				}
			// on ajoute le nouveau credit
					$reqInser3 = "INSERT INTO Avance ([IdClient]  ,[IdVendeur] ,[DateAvance],Avance,idDepot,ModePaiement,ImgCheque,Etat) 
							values 	(?,?,?,?,?,?,?,?)";
					//	echo $reqInser1;
				
				$params3= array(				
								$_SESSION["IdClient"],
								$_SESSION["IdVendeur"],				
								date("Y-m-d"),
								$MtCredit,
								$IdDepot,
								"Credit",
								$target_path,
								1
								
				) ;
				$stmt4 = sqlsrv_query( $conn, $reqInser3, $params3 );
				if( $stmt4== false ) {
					$errors = sqlsrv_errors();
					$error.="Erreur : Ajout Credit ".$errors[0]['message'] . " <br/> ";
				}
				 return array($error, $Imprime);
			
	
}
?>
