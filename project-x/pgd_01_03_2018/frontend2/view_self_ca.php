<?php

require_once('../connexion.php');
if(!isset($_SESSION))
{
session_start();
}
include("lang.php");
require_once("header_y.php");
$ancien_date = '';
if(isset($_POST['DateD']) ){
	$ancien_date =  $_POST['DateD'];
	//echo $ancien_date;
	//$ancien_date = date_create($ancien_date);
	//$ancien_date = date_format($ancien_date,'d/m/Y');
	?>
	<script type="text/javascript">
		$( document ).ready(function() {
		  $("#choisi_la_date").hide();
		  $("#ca_viewer").show();
		});
		
	</script>
	<?php
}
 	 
 else $ancien_date =  date('d/m/Y') ;

?>
<div class="container-fluid">
	<div class="Head row">
	<div  class="heaLeft col-6">
		<div class="Info"> 
			<a href="index.php"><img src="../images/home.png"></a>
			<?php echo $trad['index']['Bienvenu'] ;echo $_SESSION['Vendeur'];?>
		</div>
	</div>
	<div  class="headRight col-6">
		<a href="index.php?logout" class="signoutsignout" style="float: right;">
		<div class="signout">
		
		</div>
		</a>
	</div>
	</div>
<?php

$newDateString = date_format(date_create_from_format('d/m/Y', $ancien_date), 'Y-m-d');
//echo $newDateString;
 $d = new DateTime($newDateString);
 $d->modify('first day of this month');


$query_CA_1 = "select isnull(sum(dtf.ttc),0)
as CA 
 from detailFactures dtf inner join factures f 
on dtf.idFacture = f.IdFacture  and EtatCmd = 2
inner join vendeurs v on v.idVendeur = f.idVendeur and v.idDepot <> 1 where 
cast(f.date as date) = '".$newDateString."' and v.idVendeur = $_SESSION[IdVendeur]";
$Ca1 = 0;
//echo $query_CA_1."<br>";

$params_query_CA_1 = array();
$options_query_CA_1 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_CA_1=sqlsrv_query($conn,$query_CA_1,$params_query_CA_1,$options_query_CA_1);
$ntRes_query_CA_1 = sqlsrv_num_rows($stmt_query_CA_1);
while($row__query_CA_1 = 
	sqlsrv_fetch_array($stmt_query_CA_1, SQLSRV_FETCH_ASSOC)){
	$Ca1 = $row__query_CA_1['CA'];
}
if($Ca1 <= 0)
	$Ca1 = 0;




//$ancien_date = "2010-03-21";


//$newDate1 = date("Y-d-m", strtotime($ancien_date));
//echo $newDate1;




$lastday = //date('t',strtotime($newDateString));
   date("Y-m-t", strtotime($newDateString));
$query_CA_2 = "select isnull(sum(dtf.ttc),0) as CA from detailFactures dtf inner join 
factures f on dtf.idFacture = f.IdFacture and EtatCmd = 2 inner join 
vendeurs v on v.idVendeur = f.idVendeur and v.idDepot <> 1 
where cast(f.date as date) between '".$d->format('Y-m-d')."' and '".$lastday."' and v.idVendeur = $_SESSION[IdVendeur]	";
$Ca2 = 0;
//echo $query_CA_2;

$params_query_CA_2 = array();
$options_query_CA_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_CA_2=sqlsrv_query($conn,$query_CA_2,$params_query_CA_2,$options_query_CA_2);
$ntRes_query_CA_2 = sqlsrv_num_rows($stmt_query_CA_2);
while($row__query_CA_2 = 
	sqlsrv_fetch_array($stmt_query_CA_2, SQLSRV_FETCH_ASSOC)){
	$Ca2 = $row__query_CA_2['CA'];
}
if($Ca2 <= 0)
	$Ca2 = 0;



//************************************************
/*$lastday2 = //date('t',strtotime($newDateString));
   date("m/t/Y", strtotime($newDateString));
$query_frais = "select isnull(sum(f.Montant),0) as frais from Frais f where f.IdVendeur = $_SESSION[IdVendeur]
and f.DateOperation between '".$d->format('m/d/Y')."  00:00:00 AM' and '".$lastday2."  00:00:00 AM'";
$frais = 0;
echo $query_frais;*/
//************************************************
 
//************************************************
$lastday2 = //date('t',strtotime($newDateString));
   date("Y-m-t", strtotime($newDateString));
$query_frais = "select isnull(sum(f.Montant),0) as frais from Frais f where f.IdVendeur = $_SESSION[IdVendeur]
and f.DateOperation between '".$d->format('Y-m-d')."' and '".$lastday2."' and  idDepot = $_SESSION[IdDepot]";
$frais = 0;
//echo $query_frais;
//************************************************
/*
$lastday2 = //date('t',strtotime($newDateString));
   date("t/m/Y", strtotime($newDateString));
$query_frais = "select isnull(sum(f.Montant),0) as frais from Frais f where f.IdVendeur = $_SESSION[IdVendeur]
and f.DateOperation between '".$d->format('d/m/Y')."' and '".$lastday2."'";
$frais = 0;
//echo $query_frais;
*/
$params_query_frais = array();
$options_query_frais =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_frais=sqlsrv_query($conn,$query_frais,$params_query_frais,$options_query_frais);
$ntRes_query_frais = sqlsrv_num_rows($stmt_query_frais);
while($row__query_frais = 
	sqlsrv_fetch_array($stmt_query_frais, SQLSRV_FETCH_ASSOC)){
	$frais = $row__query_frais['frais'];
}
if($frais <= 0)
	$frais = 0;

$mois_titre = date("F", strtotime($newDateString)) ;
//echo $mois_titre;
if($mois_titre == "January")
$mois_titre = "Janvier";
if($mois_titre == "February")
$mois_titre = "Février";
if($mois_titre == "March")
$mois_titre = "Mars";
if($mois_titre == "April")
$mois_titre = "Avril";
if($mois_titre == "May")
$mois_titre = "Mai";
if($mois_titre == "June")
$mois_titre = "Juin";
if($mois_titre == "July")
$mois_titre = "Juillet";
if($mois_titre == "August")
$mois_titre = "Août";
if($mois_titre == "September")
$mois_titre = "Septembre";
if($mois_titre == "October")
$mois_titre = "Octobre";
if($mois_titre == "November")
$mois_titre = "Novembre";
if($mois_titre == "December")
$mois_titre = "Décembre";
?>

	<p class="text-center animated fadeInUp">CHIFFRE D'AFFAIRES</p>
	<p class="text-center animated fadeInUp">Monsieur :  <?php echo $_SESSION['Vendeur']; ?></p>
	<br>

	<div class="row" id="choisi_la_date">
		<div class="col-3"></div>
		<div class="col-6">
			<div class="col-12 text-center animated fadeInLeft" style="font-size: 20px">
			<button class="btn btn-primary btn-block" id="show_date_time_picker">Choisi une date :</button>
			
			</div>
			<br><br>
			<div class="row">
			
			<div class="col-12 animated fadeInLeft" id="show_picker">
				<form method="post" id="submit_on_change" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		
		                <input class="form-control"  maxlength="10" size="10" 
		                id="DateD" name="DateD" type="text" onchange="verifier_date_y(this)"
		                value="<?php echo $ancien_date; ?>" style="display:none;"
		                 />	
						
			    </form>
			    
			    	<div id="datepicker" ></div>
			   
			   
			</div>
			


			</div>
			
		</div>
		<div class="col-3"></div>
		
		

	</div>
	<br>




		<script type="text/javascript">
			    $(document).ready(function () {
			       // $("#my-calendar").zabuto_calendar();
			    var width_x = $("#datepicker").width();
			    var width_y = $("#show_picker").width();
			    margin_x = (width_y - width_x)/2;
			    $("#datepicker").css( { marginLeft : margin_x, marginRight : margin_x } );
			    });
			//alert("here");
			//$( "#datepicker" ).datepicker();

			//show_date_time_picker

			$( "#show_date_time_picker" ).click(function() {
			  //alert( "Handler for .click() called." );
			  //$('#datepicker').datepicker('show');
			  $('#datepicker').toggle();
			});

			$("#datepicker").datepicker( "option", "disabled", true );
			$("#datepicker").datepicker({
				 dateFormat: "dd/mm/yy",
				  altFormat: "dd/mm/yy",
				  altField: "#alt-date",
			    onSelect: function(dateText, inst) {
			        var date = $(this).val();
			        
			        //alert('on select triggered');
			        $("#DateD").val(date);
			        verifier_date_y(date);

			    }
			}).hide();
			calendrier("DateD");
			function verifier_date_y(date){
				if(date != ''){
		                // var date = formater_date(objet);
		                 date = date.substring(0,10);
		                 if(isDate(date)) {
		                         //objet.value=date;
		                         //$('input#'+objet.id).removeClass("errForm");
		                         document.getElementById("submit_on_change").submit();
		                 }else {
		                         alert('Merci de saisir une date valide.');
		                        // $('input#'+objet.id).addClass("errForm");
		                 }
		         }else{
		                //$('input#'+objet.id).removeClass("errForm");
		         }
			}
		</script>
		<style type="text/css">
			*{
				 color: #223b3e;;
				 letter-spacing: -.05em;
				 font-weight: 100;
				 font-family: 'Montserrat', sans-serif;
				 -webkit-animation-duration: 4s;
  				 -webkit-animation-delay: 0.1s;
  				 -moz-animation-duration: 4s;
  				 -moz-animation-delay: 0.1s;
  				 -o-animation-duration: 4s;
  				 -o-animation-delay: 0.1s;
  				 -ms-animation-duration: 4s;
  				 -ms-animation-delay: 0.1s;
  				 animation-duration: 4s;
  				 animation-delay: 0.1s;
				
			}
			p{
				 font-size: 50px;
			}
			#ca_viewer{
				display: none;
				 font-size: 30px;
				 overflow: hidden;
			}
			#ca_viewer .col-4 div{
				padding: 30px;
			}
			#ca_viewer{
				color: #223b3e;
			}
			div.ui-datepicker{
				 font-size:25px;
				}
		</style>
<?php 

	$Ca1 = number_format($Ca1, 2, ',', ' ');
	$Ca2 = number_format($Ca2, 2, ',', ' ');
	$frais = number_format($frais, 2, ',', ' ');

 ?>
<div id="ca_viewer" class="text-center" >
	<div class="row">
		<div class="col-12 text-center animated fadeInLeft">
		Mois : <?php echo $mois_titre;echo '   '.$ancien_date;?>
		<?php //echo $ancien_date; ?>
	    </div>

	</div>	
	<div class="row">
		<div class="col-4 animated fadeInRight">
		<div>
			CA journée
		      
		</div>
		<div class="money">
			<?php echo $Ca1; ?> DH
		</div>
	</div>
	<div class="col-4 animated fadeInRight">
		<div>
			CA Global
		     
		</div>
		<div class="money">
			   <?php echo $Ca2; ?> DH
		</div>
	</div>
	<div class="col-4 animated fadeInRight">
		<div>
			Frais Global
		        
		</div>
		<div class="money">
			   <?php echo $frais; ?> DH
		</div>
	</div>

	</div>
	

   </div>
</div>
<style type="text/css">
	.money{
		font-size: 25px;
		color: #12c18b;
		border: .5px solid #777;
		border-radius: 20px;
	}
</style>
<?php
include("footer.php");
?>