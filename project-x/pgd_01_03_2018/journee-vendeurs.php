<?php
require_once('connexion.php');
include("lang.php");
include("header_y.php");
 
 
?>

<div class="row" style="background: #34495e;color: #ecf0f1;padding:15px;margin: 15px;"
				 id="recherche_form">
				
				        <div class="form-group col-md-4">
				        	<label>Date De</label>
				            <div class='input-group date' id='datetimepicker6'>
				                <input type='text' id="DateJ" name="DateJ" class="form-control" 
				                value="<?php echo date('01/m/Y'); ?>" />
				                <span class="input-group-addon">
				                    <span class="glyphicon glyphicon-calendar"></span>
				                </span>
				            </div>
				        </div>
				 		
				        <div class="form-group col-md-4">
				        	<label>à</label>
				            <div class='input-group date' id='datetimepicker7'>
				                <input type='text' id="second_date" name="second_date" class="form-control"
				                value="<?php echo date('t/m/Y'); ?>"  />
				                <span class="input-group-addon">
				                    <span class="glyphicon glyphicon-calendar"></span>
				                </span>
				            </div>
				        </div>


		 		<div class="form-group col-md-1">
		 			<svg id="click_me_print" width="50px" height="50px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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

		 		</div>
		        <div class="form-group col-md-3">
		        	
		            <button class="btn btn-primary btn-block" onclick="ajax_func();">Recherche</button>  
		        </div>



		        <div class="form-group col-md-6" id="combobox_depot" style="margin-top: 15px;">
			      <label for="inputdepot">choisir dépôt</label>
			      <select id="inputdepot" name="inputdepot" class="form-control">
			        <option selected value="tous">Tous</option>

			        <?php 
			       	$query_depot = "select distinct d.Designation,d.idDepot from depots d where d.idDepot <> 1";

						$params_query_depot = array();
						$options_query_depot =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
						$stmt_query_depot=sqlsrv_query($conn,$query_depot,$params_query_depot,$options_query_depot);
						$ntRes_query_depot = sqlsrv_num_rows($stmt_query_depot);
						while($row__query_depot = 
							sqlsrv_fetch_array($stmt_query_depot, SQLSRV_FETCH_ASSOC)){

						
			         ?>
			        <option value="<?php echo $row__query_depot['idDepot']; ?>">
			        	<?php echo $row__query_depot['Designation']; ?>
			        </option>
			        <?php 
			        }
			         ?>
			      </select>
			    </div>
			    <div class="col-md-6">
			    	
			    </div>
		  
		     
</div>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
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
    $( "#click_me_print" ).click(function() {
    $("#my_print_div").print();
	  	
	});
   
});
 function ajax_func(){
    		var DateJ = $('#DateJ').val();
    		var second_date = $('#second_date').val();
    		var input_depot = $('#inputdepot').val();
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					document.getElementById('get_data').innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open('GET','ajax_journee_vendeur.php?DateJ='+DateJ+'&second_date='+second_date+'&input_depot='+input_depot,true);
			xmlhttp.send();
		}
	window.onload = ajax_func;
</script>
<style type="text/css">
	#recherche_form .dropdown-menu {
    background: white;
    color: #34495e;
}
svg{
	cursor: pointer;
	background: white;
}
</style>
<div id="get_data"></div>
<?php include 'footer_y.php' ?>