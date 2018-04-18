<?php
require_once('connexion.php');
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
include("header_y.php");
$where="";
$dateD = "";
$dateFin = "";
 if(isset($_POST['DateD']) && isset($_POST['DateFin'])  )
    {
      $dateD = substr($_POST['DateD'], 0, 10);
      $dateFin = substr($_POST['DateFin'], 0, 10);

      //echo $dateD;
      if($_POST['DateD'] == $_POST['DateFin'])
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
    $where="  cast(fa.date AS date)=convert(date,'".(date('d/m/Y'))."',105)";
    }
  // echo $where."<hr>";
$sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = 0 group by convert(varchar(10),f.date,105)";
if(isset($_POST['Vendeur_dh'])){
   if($where != ''){
   $sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = $_POST[Vendeur_dh]
   and $where group by convert(varchar(10),f.date,105)";
  
   }
   else{
   $sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = $_POST[Vendeur_dh] group by convert(varchar(10),f.date,105)";
   
   }
}else{
   if($where != ''){
   $sql = "select CAST(sum(f.totalTTC)AS DECIMAL(18,2)) as [total] ,convert(varchar(10),f.date,105) as [date] from factures f inner join
   vendeurs v on f.idVendeur=v.idVendeur and EtatCmd = 2 and v.idDepot <> 1 where v.idVendeur = 0 
   and $where group by convert(varchar(10),f.date,105)";
   
   }
}
 // echo $sql."<hr>";
	$valeurs_vendeur = array();
  $jours_vendeurs = array();
  $vendeur_full_name ="";
	$params = array();
  if(isset($_POST['DateD']) && isset($_POST['DateFin'])){
    $dateD = substr($_POST['DateD'], 0, 10);
    $dateFin2 = substr($_POST['DateFin'], 0, 10);
    //echo $dateFin2;
    //echo $dateD;
    $first_day = date("m-d-Y", strtotime($dateD)); 
    $end_day = date("d-m-Y", strtotime($dateFin2)); 
    /*echo $_POST['DateFin'];
    echo 'first day : '.$first_day;*/
    //echo $end_day."<br>".$first_day;
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
	$resAff = sqlsrv_query($conn,$sql,$params,$options) or die( print_r(
	sqlsrv_errors(), true));
	$nRes = sqlsrv_num_rows($resAff);
	if($nRes==0)
		{ ?>
					<div class="resAff">
						<br><br>
							AucunResultat 
					</div>
					<?php
		}
  //echo $_POST['DateD'];
  //echo $first_day;
  $date1 = new DateTime($first_day);
  $date2 = new DateTime($end_day);
  //echo $end_day;
  //echo $date2->format('d-m-Y');
  //echo $date1->format('d-m-Y');
 	while($row = sqlsrv_fetch_array($resAff, SQLSRV_FETCH_ASSOC)){	
    
    $newDate = date("d-m-Y", strtotime($row['date']));
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
  /*print_r($valeurs_vendeur);
  echo '<br>';
  print_r($jours_vendeurs);
  echo '<br>';*/
  if(isset($_POST['Vendeur_dh'])){

  $sql_full_name = "select v.nom+' '+v.prenom as 'fullname' from  vendeurs v
  where v.idVendeur = $_POST[Vendeur_dh]";
  $params = array();
  $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
  $resAff2 = sqlsrv_query($conn,$sql_full_name,$params,$options) or die( print_r(
  sqlsrv_errors(), true));
  while($row = sqlsrv_fetch_array($resAff2, SQLSRV_FETCH_ASSOC)){  
    $vendeur_full_name = $row['fullname'];
  }
  }
  
  //echo '<br>'.$vendeur_full_name;
  //echo("<script>console.log('PHP: id you ".$_POST['Vendeur_dh']."');</script>");
  //echo $sql.'<br>';
  //echo $vendeur_full_name.'<br>';
  //echo implode( ',', $valeurs_vendeur);
?>

  <div class="row">
    <div class="col-12">
        <div class="pageBack" >
            <div id="boxpwd">
            </div>
        <div class="contenuBack">
            <div id="brouillon" style="display:block">
            </div> 
            <div id="infosGPS" style="border-bottom:1px dashed #778; ">
                &nbsp;<?php echo $trad['Menu']['statistic']; ?>&nbsp;
                  <img src="images/tri.png" />
                &nbsp;    
                <span style="font-weight: bold">
                  <?php echo "Total TTC par Vendeur"; ?>
                </span> &nbsp;
            </div>
        </div>
        </div>
    </div>
  </div>
<style>
input[action=ajout]{background:transparent url(images/add24.png) top center no-repeat;}
input[action=down]{background:transparent url(images/down3.png) top center no-repeat;}
input[action=up]{background:transparent url(images/up3.png) top center no-repeat;}
input.button16  {
  cursor:pointer;
  height:24px;
  width:30px;
  opacity:0.6;
  border:0px; 
  text-align:center;
  }
#formRes td{
  padding:7px 2px;
}

</style>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
    calendrier("DateD");
    calendrier("DateFin");
   
  function filtrer(){

    $('#formRechF').ajaxSubmit({target:'#graphG',
    url:'date_vendeur_chart.php?go',clearForm:false});
    patienter('graphG');
    return false; 
  }
  function rechercher(){
    $('#formRechF').ajaxSubmit({target:'#graphG',url:'date_vendeur_chart.php?go'})
  }
  $('body').on('keypress', '#NomComplet', function(args) {
   if (args.keyCode == 13) {
       $("#Rechercher").click();
       return false;
    }
   });
//------------

});
</script>
<div class="row">
<div class="col-12">
<form id="formRechF" method="post" name="formRechF" action="date_vendeur_chart.php"> 
          <div id="formRech" style=""> 
         
          <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12 text-right"
          style="margin-bottom: 15px">
            <?php echo $trad['label']['de']; ?> &nbsp;
          <input class="formTop" g="date" id="DateD" tabindex="2" 
          name="DateD" class="formTop" g="date" type="text" size="10" maxlength="10"
          
          value="<?php echo '01/12/2017'; ?>"/> 
          &nbsp;<?php echo $trad['label']['a']; ?> &nbsp;
           
          <input  id="DateFin" tabindex="2"
          name="DateFin" class="formTop" g="date" type="text" size="10" maxlength="10"
          value="<?php echo date('d/m/Y'); ?>"
          >
          </div>
          <div class="col-md-1 col-sm-1 col-xs-4 text-left">
          <span class="etiqForm" id="" > 
          <strong>Vendeur </strong> : </span>
          </div>
          <div class="col-md-5 col-sm-5 col-xs-8 text-left">
             <select  name="Vendeur_dh" id="Vendeur_dh"  tabindex="3" 
             class="Select Vendeur selectpicker"  data-live-search="true" 
             style="display:visible;">
                  <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' +
                   prenom AS Nom FROM vendeurs v";
                       $reponse=sqlsrv_query( $conn, $sql, array());         
                               while ($donnees =  sqlsrv_fetch_array($reponse))
                               {
                               ?>
                               <option data-tokens="<?php echo $donnees['idVendeur'] ?>"
                                value="<?php echo $donnees['idVendeur'] ?>"><?php echo $donnees['Nom']?></option>
                         <?php
                          }
                         ?>
            </select>
          </div>
          </div>
          <div class="row">
          <div class="col-4"></div>
          <div class="col-4">
          <span class="actionForm">      
          <input name="button" type="submit"  
          value="<?php echo $trad['button']['rechercher']; ?>" 
          class="bouton32" action="rech" 
          title="<?php echo $trad['button']['rechercher']; ?>" id="Rechercher" />
          <input name="button2" type="reset" onClick=""
          value="<?php echo $trad['label']['vider']; ?>" 
          class="bouton32" action="effacer" 
          title="<?php echo $trad['label']['vider']; ?>"/>
          </span>
          </div>
          <div class="col-4"></div>
          </div>
         
          </div>

         
</form>
</div>
<div class="col-12">
<input type="hidden" id="act"/>

<div style="margin:10px; text-align:center;">
  <span id="resG" class="vide"></span>
</div>
<table width="100%" border="0"  style="direction:ltr;" align="center">
  		<tr>
			<td>
			     <div class="titreG"></div>
			</td>
		</tr>
		<tr>
			<td width="40%">
            <div id="graphG"></div>
			</td>
		</tr>
</table>
</div>
</div>

<style>
label.error  {
color:#990000;
}
</style>
<script language="javascript" type="text/javascript">

$(document).ready(function(){

	 $('#graphG').highcharts('Chart', {
        chart: {
        type: 'line'
    },
    title: {
        text: 'totalTTC De la période [ <?php echo $dateD; ?> à <?php echo $dateFin; ?>]'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [<?php echo "'".implode("','",$jours_vendeurs)."'" ?>]
    },
    yAxis: {
        title: {
            text: 'Total DH'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    tooltip: {
        valueDecimals: 2
    },
    series: [{
        name: '<?php echo $vendeur_full_name ; ?>',
        data: [<?php echo implode( ',', $valeurs_vendeur); ?>]
    }]
    });
	
	
});

</script>
<?php include("footer_y.php"); ?>