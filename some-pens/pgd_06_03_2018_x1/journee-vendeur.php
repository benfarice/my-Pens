<?php
require_once('connexion.php');
include("lang.php");
include("header_y.php");

?>
<form id="formRechF" method="post" name="formRechF" action="journee-vendeur.php"> 
          <div id="formRech" style=""> 
          <table border="0" cellspacing="10" 
          cellpadding="10" align="center" >
          <tr>
	          <td Align="right">Sélection de la journée &nbsp;
		          <input class="formTop" g="date" id="DateJ" tabindex="2" 
		          name="DateJ" type="text" size="10" maxlength="10"
		          onChange="verifier_date(this);"
		          value="<?php echo date('d-m-y'); ?>"/> 
		          
		          <input name="DATED" type="hidden" value=""/>    
		          <input name="DATED" type="hidden" value=""/>
	          </td>
	       </tr>
	       <tr>
	          <td align="CENTER" Colspan=4> 
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
	          </td><svg id="click_me_print" width="50px" height="50px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 		  viewBox="0 0 429.279 429.279" style="enable-background:new 0 0 429.279 429.279;" xml:space="preserve">

			  <rect x="113.161" y="34.717" style="fill:none;" width="202.957" height="114.953"/>
			<path style="fill:none;" d="M405.279,198.475c0-13.677-11.127-24.805-24.805-24.805H48.805C35.127,173.67,24,184.797,24,198.475
			v7.961h381.279V198.475z M384.123,198.542c-2.23,2.23-5.33,3.51-8.48,3.51c-3.16,0-6.25-1.28-8.49-3.51
			c-2.23-2.24-3.51-5.33-3.51-8.49c0-3.16,1.28-6.25,3.51-8.48c2.24-2.24,5.33-3.52,8.49-3.52c3.15,0,6.25,1.28,8.48,3.52
			c2.24,2.23,3.52,5.32,3.52,8.48C387.642,193.212,386.363,196.302,384.123,198.542z"/>
			<path style="fill:none;" d="M110.846,394.563h207.588V266.533H110.846V394.563z M141.998,292.908h140.514c6.627,0,12,5.372,12,12
			c0,6.627-5.373,12-12,12H141.998c-6.627,0-12-5.373-12-12C129.998,298.281,135.371,292.908,141.998,292.908z M141.998,344.189
			h65.641c6.628,0,12,5.373,12,12c0,6.627-5.372,12-12,12h-65.641c-6.627,0-12-5.373-12-12
			C129.998,349.562,135.371,344.189,141.998,344.189z"/>
			<path style="fill:#73D0F4;" d="M24,327.508c0,13.676,11.127,24.803,24.805,24.803h38.041v-97.777c0-6.628,5.372-12,12-12h231.588
			c6.628,0,12,5.372,12,12v97.777h38.041c13.678,0,24.805-11.126,24.805-24.803v-97.072H24V327.508z"/>
			<path style="fill:#3D6889;" d="M380.475,149.67h-40.357V22.717c0-6.627-5.372-12-12-12H101.161c-6.628,0-12,5.373-12,12V149.67
			H48.805C21.893,149.67,0,171.563,0,198.475v129.033c0,26.91,21.893,48.803,48.805,48.803h38.041v30.252c0,6.627,5.372,12,12,12
			h231.588c6.628,0,12-5.373,12-12V376.31h38.041c26.911,0,48.805-21.893,48.805-48.803V198.475
			C429.279,171.563,407.386,149.67,380.475,149.67z M405.279,327.508c0,13.676-11.127,24.803-24.805,24.803h-38.041v-97.777
			c0-6.628-5.372-12-12-12H98.846c-6.628,0-12,5.372-12,12v97.777H48.805C35.127,352.31,24,341.184,24,327.508v-97.072h381.279
			V327.508z M113.161,34.717h202.957V149.67H113.161V34.717z M24,198.475c0-13.677,11.127-24.805,24.805-24.805h331.67
			c13.678,0,24.805,11.127,24.805,24.805v7.961H24V198.475z M318.434,394.563H110.846V266.533h207.588V394.563z"/>
			<path style="fill:#3D6889;" d="M375.642,178.052c-3.16,0-6.25,1.28-8.49,3.52c-2.23,2.23-3.51,5.32-3.51,8.48
			c0,3.16,1.28,6.25,3.51,8.49c2.24,2.23,5.33,3.51,8.49,3.51c3.15,0,6.25-1.28,8.48-3.51c2.24-2.24,3.52-5.33,3.52-8.49
			c0-3.16-1.279-6.25-3.52-8.48C381.892,179.332,378.793,178.052,375.642,178.052z"/>
			<path style="fill:#3D6889;" d="M141.998,316.908h140.514c6.627,0,12-5.373,12-12c0-6.628-5.373-12-12-12H141.998
			c-6.627,0-12,5.372-12,12C129.998,311.536,135.371,316.908,141.998,316.908z"/>
			<path style="fill:#3D6889;" d="M141.998,368.189h65.641c6.628,0,12-5.373,12-12c0-6.627-5.372-12-12-12h-65.641
			c-6.627,0-12,5.373-12,12C129.998,362.817,135.371,368.189,141.998,368.189z"/>

			</svg>

	          <td>
	          	

	          </td>
          </tr>
          </table>
          </div>

         
</form>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
    calendrier("DateJ");
    
    $( "#click_me_print" ).click(function() {
	  //alert( "Handler for .click() called." );
	  $("#my_print_div").print();
	  	
	});
    
});
</script>
<?php
$query_get_ca_id_v_nbr_clients = "";
if(isset($_POST['DateJ'])){
	$query_get_ca_id_v_nbr_clients = "select v.idVendeur as id ,sum(f.totalTTC)  as CA 
	,count(distinct f.idClient) as NBRclients
	from factures f inner join vendeurs v on v.idVendeur = f.idVendeur
	 where cast(f.date as date) = '$_POST[DateJ]'  group by v.idVendeur";
}else
$query_get_ca_id_v_nbr_clients = "select v.idVendeur as id ,sum(f.totalTTC)  as CA 
,count(distinct f.idClient) as NBRclients
from factures f inner join vendeurs v on v.idVendeur = f.idVendeur
where cast(f.date as date) = '".date("d.m.y")."'  group by v.idVendeur";
$params0 = array();
//echo $query_get_ca_id_v_nbr_clients;
//echo $query_get_cities;
$options0 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt0=sqlsrv_query($conn,$query_get_ca_id_v_nbr_clients,$params0,$options0);
$ntRes0 = sqlsrv_num_rows($stmt0);
if($ntRes0==0)
		{ ?>
					<div class="resAff">
						<br><br>
						<?php echo $trad['msg']['AucunResultat']; ?>
					</div>
					<?php
		}
else{
while($row = sqlsrv_fetch_array($stmt0, SQLSRV_FETCH_ASSOC)){	
//echo $query_get_ca_id_v_nbr_clients;

//echo $row['city']."<br>";
$query_site = "select distinct v.Designation as city 
 from detailFactures dtf inner join 
factures fa on dtf.idFacture = fa.IdFacture inner join 
depots dpt on dpt.idDepot = fa.idDepot inner join villes v 
on v.idville = dpt.IdVille where cast(fa.date as date) = '$_POST[DateJ]'
 and fa.idVendeur = $row[id]";
 //echo $query_site;
 $params_site = array();
$options_site =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_site =sqlsrv_query($conn,$query_site,$params_site,$options_site);

 //echo $query_site;
$query_vendeur = " select v.nom +' '+v.prenom as vendeur from vendeurs v
 where v.idVendeur = $row[id]";
//echo $query_marque ;
$params_2 = array();
$options_2 =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marque=sqlsrv_query($conn,$query_vendeur,$params_2,$options_2);
$ntRes_marques = sqlsrv_num_rows($stmt_marque);
//echo $query_vendeur;
while($reader_vendeur = sqlsrv_fetch_array($stmt_marque, SQLSRV_FETCH_ASSOC)){	
//echo $query_vendeur;
?> 
<div id="my_print_div">
<div style="border: 5px solid black;margin-top: 10px">
<div class="row">
<div class="col-6">
<?php if(isset($_POST['DateJ'])){ ?>		
<p style="font-size: 23px;margin:10px;">
	Date & Heure <?php echo $_POST['DateJ']." | ".date('H:i'); ?>
</p>

<?php } ?> 
</div> 
<div class="col-6">
<?php
while($reader_site = sqlsrv_fetch_array($stmt_site, SQLSRV_FETCH_ASSOC)){	
?>
<p class="text-right" style="font-size: 23px;margin:10px;">
	Site : <?php echo ucwords($reader_site['city']); ?>
</p>
<?php
}?>
</div>
</div>
<table class="table table-striped" style="font-size: 20px;">
		<thead>
			<tr>
				<th>Vendeur : </th>
				<th colspan="2"><?php  echo $reader_vendeur['vendeur'];  ?></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td>CA : </td>
			<td colspan="2">
			<?php 
			$y_total = number_format($row['CA'], 2, ',', ' '); 
			echo $y_total ; ?> DH TTC
			</td>
		</tr>
	
<!--</div>-->
<?php 
$sql_espece ="select isnull(sum(f.Espece),0) as espece from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '$_POST[DateJ]'";
//echo $sql_espece."<br>";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt=sqlsrv_query($conn,$sql_espece,$params,$options);
$ntRes = sqlsrv_num_rows($stmt);

$sql_Cheque ="select isnull(sum(f.Cheque),0) as cheque from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '$_POST[DateJ]' ";
//echo $sql_Cheque."<br>";
$params_Cheque = array();
$options_Cheque =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Cheque=sqlsrv_query($conn,$sql_Cheque,$params_Cheque,$options_Cheque);
$ntRes_Cheque = sqlsrv_num_rows($stmt_Cheque);
$sql_credit = "select isnull(sum(f.Credit),0) as credit from  factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '$_POST[DateJ]'";
 $params_Credit = array();
$options_Credit =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_Credit=sqlsrv_query($conn,$sql_credit,$params_Credit,$options_Credit);
$ntRes_Credit = sqlsrv_num_rows($stmt_Credit);
//echo $sql_credit."<br>";
$sql_fact ="select count(f.IdFacture) as nbr_f from factures f
where f.idVendeur = $row[id] and cast(f.date as date) = '$_POST[DateJ]' ";
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
 where f.idVendeur = $row[id] and 
 cast(f.date as date)='$_POST[DateJ]' group by m.Designation";
//echo $sql_marque;
$params_marq = array();
$options_marq =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_marq =sqlsrv_query($conn,$sql_marque,$params_marq,$options_marq);
$ntRes_marq = sqlsrv_num_rows($stmt_marq);
?>
	  

         	
<tr>   		

<?php
while($reader = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){	
	
?> 			
        
        <td>Espece : <?php echo $reader['espece']; ?></td>
<?php
} 
?>
<?php
while($reader_c = sqlsrv_fetch_array($stmt_Cheque, SQLSRV_FETCH_ASSOC)){	
	
?> 			
     
       <td>Cheque : <?php echo $reader_c['cheque']; ?></td>
<?php
} 
?>
<?php
while($reader_d = sqlsrv_fetch_array($stmt_Credit, SQLSRV_FETCH_ASSOC)){	
?> 	
        <td>Credit : <?php echo $reader_d['credit']; ?></td>
<?php
} 
?>
</tr><tr>
<?php
while($reader_f = sqlsrv_fetch_array($stmt_fact, SQLSRV_FETCH_ASSOC)){	
	
?> 		
		
        <td colspan="2">
        	<strong>Nombre de factures : </strong>
        	<?php echo $reader_f['nbr_f']; ?></td> 		
         
<?php
} 
?>
			<td>Nombre de clients : 
			 <?php  echo $row['NBRclients'];  ?>	</td> 
		</tr>
	    </tbody>
</table>

      <table class="table table-striped" style="font-size: 19px;">
      	<tr>
      		<th>Marque</th>
      		<th>Nbr de Ref</th>
      		<th class="text-left">CA ( DH TTC )</th>
      	</tr>
<?php
while($reader_marq = sqlsrv_fetch_array($stmt_marq, SQLSRV_FETCH_ASSOC)){	
	
?> 		
			<tr>
				<td><?php  echo $reader_marq['mar'];  ?></td>
				<td><?php  echo $reader_marq['nbr_ref'];  ?></td>
				<td class="text-left"><?php   
					$t = number_format($reader_marq['total'], 2, ',', ' '); 
					echo $t;
					?>
				</td>
				
			</tr>
<?php
} 
?>	
	  </table>
</div>

<?php
}
}
}
?>
</div>
<?php include 'footer_y.php' ?>