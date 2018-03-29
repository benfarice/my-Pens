<?php
require_once('connexion.php');
if(!isset($_SESSION))
{
session_start();
}
include("header_y.php");
?>
<div class="row" style="background: #34495e;color: #ecf0f1;padding:15px;margin: 15px;"
				 id="recherche_form">
				
		        <div class="form-group col-md-4">
		        	<label>Date De</label>
		            <div class='input-group date' id='datetimepicker6'>
		                <input type='text' id="DateD" name="DateD" class="form-control" 
		                value="<?php echo date('01/m/Y') ?>" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		 	
		        <div class="form-group col-md-4">
		        	<label>à</label>
		            <div class='input-group date' id='datetimepicker7'>
		                <input type='text' id="DateFin" name="DateFin" class="form-control"
		                value="<?php echo date('t/m/Y') ?>" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>


				<div class="form-group col-md-4">
					<label>Vendeur</label>
		             <select  name="Vendeur_dh" id="Vendeur_dh" class="form-control">
		                  <?php $sql = "SELECT v.idVendeur ,v.nom+ ' ' +
		                   prenom AS Nom FROM vendeurs v";
		                       $reponse=sqlsrv_query( $conn, $sql, array());         
		                               while ($donnees =  sqlsrv_fetch_array($reponse))
		                               {
		                               ?>
		                               <option value="<?php echo $donnees['idVendeur'] ?>">
		                                <?php echo $donnees['Nom']?>   	
		                                </option>
				                         <?php
				                          }
				                         ?>
		            </select>
		          </div>



		        <div class="col-md-4"></div>
		        <div class="col-md-4">
		        <button class="btn btn-primary btn-block"  onclick="ajax_func()">Recherche</button>
		        </div>
		        <div class="col-md-4"></div>
</div>
<script type="text/javascript">
	  $(function () {
		        $('#datetimepicker6').datetimepicker({
		        	format: 'DD/MM/YYYY'
		        });
		        $('#datetimepicker7').datetimepicker({
		            useCurrent: false,
		            format: 'DD/MM/YYYY' //Important! See issue #1075
		        });
		        $("#datetimepicker6").on("dp.change", function (e) {
		            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
		        });
		        $("#datetimepicker7").on("dp.change", function (e) {
		            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
		        });
		    });
	  function ajax_func(){
	  		var date_a = $("#DateD").val();
	  		var date_b = $("#DateFin").val();
	  		var vendeur = $("#Vendeur_dh").val();
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					if(date_a != "" && date_b != "" && vendeur != "" && date_a != undefined && date_b != undefined && vendeur != undefined){
						//alert("yeah");
						document.getElementById('get_data').innerHTML = xmlhttp.responseText;
						view_chart();
					}
					
					//alert("ok");
				}
			}
			xmlhttp.open('GET','ajax_chart.php?DateD='+date_a+'&DateFin='+date_b+'&Vendeur_dh='+vendeur,true);
			xmlhttp.send();
		}
		//alert("ok");
</script>
<style type="text/css">
	#recherche_form .dropdown-menu {
    background: white;
    color: #34495e;
}
</style>
<div id="get_data">
		<?php 
		for($o=0;$o<20;$o++){
		 ?>
		 <br>
		 <?php 
		 }
		 ?>
</div>
<div id="graphG">
	

</div>

<script type="text/javascript">

	

function view_chart(){
	var text_var = '';
	if($("#text_c").html() != "" && $("#text_c") != undefined )
		text_var = $("#text_c").html();
	var catg_var = '';
	if($("#catg_c").html() != "" && $("#catg_c") != undefined)
		catg_var = $("#catg_c").html();
	catg_var = catg_var.substring(2, catg_var.length-1);
	var res = catg_var.split("','");
	console.log(catg_var);
	console.log(res);
	var vendeur_full_name_var = '';
	if($("#vendeur_full_name").html() != "" && $("#vendeur_full_name") != undefined)
		vendeur_full_name_var = $("#vendeur_full_name").html();
	var c_data_var = '';
	if($("#c_data").html() != "" && $("#c_data") != undefined)
		c_data_var = $("#c_data").html();

	c_data_var = c_data_var.substring(1, c_data_var.length);
	var res2 = c_data_var.split(',').map(Number);

	console.log(c_data_var);
	console.log(res2);
	$('#graphG').highcharts('Chart', {
        chart: {
        type: 'line'
    },
    title: {
        text: text_var
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: res
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
        name: vendeur_full_name_var,
        data: res2
    }]
    });
}



</script>
<?php include("footer_y.php"); ?>