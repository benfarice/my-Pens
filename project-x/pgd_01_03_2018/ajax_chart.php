<?php
require_once('connexion.php');
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");

$where="";
$dateD = "";
$dateFin = "";
 if(isset($_REQUEST['DateD']) && isset($_REQUEST['DateFin'])  )
    {
      $dateD = substr($_REQUEST['DateD'], 0, 10);
      $dateFin = substr($_REQUEST['DateFin'], 0, 10);

      //echo $dateD;
      if($_REQUEST['DateD'] == $_REQUEST['DateFin'])
      { 
         $where.= "  cast(f.date AS date) = convert(date,'".$dateD."',105) ";
      }
      else
      {
         $where.= "  cast(f.date AS date)  between  convert(date,'".$dateD."',105) and convert(date,'".$dateFin."',105) ";
      }
    }
    else
    {
    $where="  cast(f.date AS date)=convert(date,'".(date('d/m/Y'))."',105)";
    }
  // echo $where."<hr>";
$sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = 0 group by convert(varchar(10),f.date,105)";
if(isset($_REQUEST['Vendeur_dh'])){
   if($where != ''){
   $sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = $_REQUEST[Vendeur_dh]
   and $where group by convert(varchar(10),f.date,105)";
  
   }
   else{
   $sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = $_REQUEST[Vendeur_dh] group by convert(varchar(10),f.date,105)";
   
   }
}else{
   if($where != ''){
   $sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = 0 
   and $where group by convert(varchar(10),f.date,105)";
   
   }
}
//echo $sql."<hr>";




$valeurs_vendeur = array();
  $jours_vendeurs = array();
  $vendeur_full_name ="";
	$params = array();
  if(isset($_REQUEST['DateD']) && isset($_REQUEST['DateFin'])){
    //$dateD = substr($_POST['DateD'], 0, 10);
    //$dateFin2 = substr($_POST['DateFin'], 0, 10);
    //echo $dateFin2;
    //echo $dateD;
    //$first_day = date("m-d-Y", strtotime($dateD)); 
    //$end_day = date("d-m-Y", strtotime($dateFin2)); 
    /*echo $_POST['DateFin'];
    echo 'first day : '.$first_day;*/
    //echo $end_day."<br>".$first_day;
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r(
	sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);
	if($nRes==0)
		{ ?>
          <br><br>
					<div class="alert alert-danger" role="alert"> AucunResultat  </div>
						<?php for($u=0;$u<8;$u++) {?>
							<br>
							<?php } ?>
				
					<?php
		}


  //echo "<hr>".$_REQUEST['DateD']."<hr>";
  //echo "<hr>".$_REQUEST['DateFin']."<hr>";


  $_REQUEST['DateD'] = str_replace('/', '-', $_REQUEST['DateD']);
  $_REQUEST['DateFin'] = str_replace('/', '-', $_REQUEST['DateFin']);

  //echo "<hr>".$_REQUEST['DateD']."<hr>";
  //echo "<hr>".$_REQUEST['DateFin']."<hr>";

  $first_day = date("Y-m-d", strtotime($_REQUEST['DateD']));
  $end_day = date("Y-m-d", strtotime($_REQUEST['DateFin']));

  //echo "<hr>".$first_day."<hr>";
  //echo "<hr>".$end_day."<hr>";

  $date1 = new DateTime($first_day);
  $date2 = new DateTime($end_day);
  //echo $end_day;
  //echo  "date 2 ". $date2->format('d-m-Y');
  //echo  "date 1 ".$date1->format('d-m-Y');
  while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){  
    
    $newDate = date("Y-m-d", strtotime($row['date']));
   
    $date3 = new DateTime($newDate);
    if($date1 != $date3){
      while($date1 < $date3){
        array_push($jours_vendeurs,$date1->format('d-m-Y'));
        array_push($valeurs_vendeur,0 );
        //date_modify($first_day, '+1 day');
        $date1->modify('+1 day');
        
      }

    }
    
    array_push($jours_vendeurs, $newDate);
    $t = $row['total'];
    array_push($valeurs_vendeur,$t );
    $date1 = $date3->modify('+1 day');
    //
  }
  
  }
   if(isset($_REQUEST['Vendeur_dh'])){

  $sql_full_name = "select v.nom+' '+v.prenom as 'fullname' from  vendeurs v
  where v.idVendeur = $_REQUEST[Vendeur_dh]";
  $params = array();
  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
  $resAff2 = sqlsrv_query($conn,$sql_full_name,$params,$options) or die( print_r(
  sqlsrv_errors(), true));
  while($row = sqlsrv_fetch_array($resAff2, SQLSRV_FETCH_ASSOC)){  
    $vendeur_full_name = $row['fullname'];
  }
  }
  //print_r($valeurs_vendeur);
 // echo "<hr>".count($valeurs_vendeur)."<hr>".count($jours_vendeurs)."<hr>";
  //echo implode( ',', $valeurs_vendeur);
  //echo $dateFin;
 // echo $vendeur_full_name ;
?>
<div id="text_c" style="display: none;">
  totalTTC De la période [ <?php echo $dateD; ?> à <?php echo $dateFin; ?>]
</div>
<div id="catg_c" style="display: none;"><?php echo "'".implode("','",$jours_vendeurs)."'" ?>
</div>
<div id="vendeur_full_name" style="display: none;">
  <?php echo $vendeur_full_name ; ?>
</div>
<div id="c_data" style="display: none;">
  <?php echo implode( ',', $valeurs_vendeur); ?>
</div>
