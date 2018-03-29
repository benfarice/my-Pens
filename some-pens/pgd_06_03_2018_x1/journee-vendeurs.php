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
		        	
		            <button class="btn btn-primary btn-block" onclick="ajax_func();ajax_func_frais();">Recherche</button>  
		        </div>



		        <div class="form-group col-md-6" id="combobox_depot">
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
			    <div class="col-md-3">

		        	<div class="input-group">
					  <div class="input-group-prepend">
					    <div class="input-group-text">
					    <input type="radio" aria-label="Radio button for following text input" name="select_view" id="Graphe" checked="checked" onchange="handleChange1_Graphe();">
					    </div>
					  </div>
					  <input type="text" class="form-control" aria-label="Text input with radio button"
					  value="Graphe" readonly="readonly">
					</div>
		        </div>
		      
		        <div class="col-md-3">
		        		<div class="input-group">
						  <div class="input-group-prepend">
						    <div class="input-group-text">
						    <input type="radio" aria-label="Radio button for following text input" name="select_view" id="simple" onchange="handleChange1_simple();">
						    </div>
						  </div>
						  <input type="text" class="form-control" aria-label="Text input with radio button"
						  value="Affichage Simple" readonly="readonly">
						</div>
		        </div>



		  
		     
</div>
<script language="javascript" type="text/javascript">
	 function handleChange1_Graphe(){
                    $("#get_data").fadeOut();
                    $("#container_chart").fadeIn();
                }

                
       function handleChange1_simple(){
                    $("#container_chart").fadeOut();
                    $("#get_data").fadeIn();
                }
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
$(document).ready(function(){

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
					var r_vendeurs = do_math();
					make_chart(r_vendeurs);
					
				}
			}
			xmlhttp.open('GET','ajax_journee_vendeur.php?DateJ='+DateJ+'&second_date='+second_date+'&input_depot='+input_depot,true);
			xmlhttp.send();

		}
 function ajax_func_frais(){
    		var DateJ = $('#DateJ').val();
    		var second_date = $('#second_date').val();
    		var input_depot = $('#inputdepot').val();
			var xmlhttp2 = new XMLHttpRequest();
			xmlhttp2.onreadystatechange = function(){
				if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
					document.getElementById('get_data2').innerHTML = xmlhttp2.responseText;
					var r_vendeurs_frais = do_math_frais();
					console.log('r vendeurs frais ajax');
					console.log(r_vendeurs_frais);
					make_chart_frais(r_vendeurs_frais);
					
				}
			}
			xmlhttp2.open('GET','ajax_consultation_graphe_frais.php?DateJ='+DateJ+'&second_date='+second_date+'&input_depot='+input_depot,true);
			xmlhttp2.send();
		}
	
	window.onload = function(){
        // All code comes here 
        ajax_func();
        ajax_func_frais();
	 }
function do_math(){
	var i = 1 ;
	var r_vendeurs = [];
	var les_noms = [];
	  $('.one_data_vendeur').each(function(){
	  	 var one_vendeur = [];
	  	 var vendeur_nom = $(this).find('.vendeur_name');
	  	 var vendeur_ca = $(this).find('.vendeur_ca');
	  	 if(les_noms.indexOf(vendeur_nom.html()) == -1)
	  	 {
	  	 	one_vendeur.push(vendeur_nom.html());
	  	 	les_noms.push(vendeur_nom.html());
	  	 }
	  	 else
	  	 one_vendeur.push(vendeur_nom.html()+' -'+i);

	  	 var les_marques = [];

	  	  $(this).find(".marque_info").each(function(){
                var one_marque = [];
                var marque_name = $(this).find('.get_marque');
                var marque_total = $(this).find(".get_total_marque");
                one_marque.push(marque_name.html()); 
                one_marque.push(Number(marque_total.html()));
                les_marques.push(one_marque);
           });
         one_vendeur.push(vendeur_ca.html());
         one_vendeur.push(les_marques);
	  	 r_vendeurs.push(one_vendeur);
	  });
	  console.log(r_vendeurs);
	  return r_vendeurs;
}
function do_math_frais(){
	//alert('yeah');
	var i = 1;
	var les_frais_vendeurs = [];
	var les_noms = [];
	console.log('do math frais');
	$('.vendeur_frais').each(function(){
		var one_frais_v = [];
		var vendeur_nom = $(this).find('.vendeur_name');
	  	var vendeur_frais = $(this).find('.vendeur_total_frais');


	  	var vendeur_Autoroute = $(this).find('.vendeur_Autoroute');
	  	var vendeur_Gasoil = $(this).find('.vendeur_Gasoil');
	  	var vendeur_Divers = $(this).find('.vendeur_Divers');
	  	var vendeur_Vidange = $(this).find('.vendeur_Vidange');

	  	 if(les_noms.indexOf(vendeur_nom.html()) == -1)
	  	 {
	  	 	one_frais_v.push(vendeur_nom.html());
	  	 	les_noms.push(vendeur_nom.html());
	  	 	
	  	 }
	  	 else
	  	 one_frais_v.push(vendeur_nom.html()+' -'+i);
	  	one_frais_v.push(Number(vendeur_frais.html()));
	  	console.log('vendeur nom frais');
	  	console.log(vendeur_nom.html());

	  	les_details_frais = [];

	  	var t_Vidange = Number(vendeur_Vidange.html());
	  	var t_Divers = Number(vendeur_Divers.html());
	  	var t__Autoroute = Number(vendeur_Autoroute.html());
	  	var t_Gasoil = Number(vendeur_Gasoil.html());

	  	function do_autoroute(){
	  		var autoroute_array = [];
			autoroute_array.push("Autoroute");
			if(Number(vendeur_Autoroute.html())>0){
				autoroute_array.push(Number(vendeur_Autoroute.html()));
			    les_details_frais.push(autoroute_array);
			}
	  	}

	  	function do_vidange(){
	  		var Vidange_array = [];
		    Vidange_array.push("Vidange");
		    if(Number(vendeur_Vidange.html())>0){
				  		Vidange_array.push(Number(vendeur_Vidange.html()));
				  		les_details_frais.push(Vidange_array);
			}
	  	}

	  	function do_gasoil(){
	  		var Gasoil_array = [];
						  	Gasoil_array.push("Gasoil");
						  	if(Number(vendeur_Gasoil.html())>0){
						  		Gasoil_array.push(Number(vendeur_Gasoil.html()));
						  		les_details_frais.push(Gasoil_array);
						  	}
	  	}

	  	function do_divers(){
	  		var Divers_array = [];
							  	Divers_array.push("Divers");
							  	if(Number(vendeur_Divers.html())>0){
							  		Divers_array.push(Number(vendeur_Divers.html()));
							  		les_details_frais.push(Divers_array);
							  	}
	  	}
	  	if(!(isNaN(t_Vidange) || isNaN(t_Gasoil) || isNaN(t__Autoroute) || isNaN(t_Gasoil))){
	  		if(t_Vidange >= t_Gasoil && t_Vidange >= t__Autoroute && t_Vidange >= t_Divers){
	  				do_vidange();
					if(t_Gasoil >= t__Autoroute && t_Gasoil >= t_Divers){
							do_gasoil();
						  	if(t__Autoroute >= t_Divers){
						        do_autoroute();
						        do_divers();
						  	}else{
						  		do_divers();
						  	    do_autoroute();
						  	}
					}else if(t__Autoroute >= t_Gasoil && t__Autoroute >= t_Divers){
						    do_autoroute();
						  	if(t_Gasoil >= t_Divers){
						  	    do_gasoil();
							  	do_divers();
						  	}else{
						  		do_divers();
							  	do_gasoil();
						  	}
					}else if(t_Divers >= t__Autoroute && t_Divers >= t_Gasoil){
								do_divers();
							  	if(t__Autoroute >= t_Gasoil){
							  		do_autoroute();
								  	do_gasoil();
							  	}else{
							  		do_gasoil();
								  	do_autoroute();
							  	}
					}
	  		}else if(t_Gasoil >= t_Vidange && t_Gasoil >= t__Autoroute && t_Gasoil >= t_Divers){
	  				do_gasoil();
	  				if(t_Vidange >= t__Autoroute && t_Vidange >= t_Divers){
	  					do_vidange();
	  					if(t__Autoroute >= t_Divers){
	  						do_autoroute();
	  						do_divers();
	  					}else{
	  						do_divers();
	  						do_autoroute();
	  					}
	  				}else if(t__Autoroute >= t_Vidange && t__Autoroute >= t_Divers){
	  					do_autoroute();
	  					if(t_Vidange >= t_Divers){
	  						do_vidange();
	  						do_divers();
	  					}else{
	  						do_divers();
	  						do_vidange();
	  					}
	  				}else if(t_Divers >= t__Autoroute && t_Divers >= t_Vidange){
	  					do_divers();
	  					if(t__Autoroute>=t_Vidange){
	  						do_autoroute();
	  						do_vidange();
	  					}else{
	  						do_vidange();
	  						do_autoroute();
	  					}
	  				}
	  		}else if(t_Divers >= t_Vidange && t_Divers >= t__Autoroute && t_Divers >= t_Gasoil){
	  				do_divers();
	  				if(t_Vidange >= t__Autoroute && t_Vidange >= t_Gasoil){
	  					do_vidange();
	  					if(t__Autoroute >= t_Gasoil){
	  						do_autoroute();
	  						do_gasoil();
	  					}else{
	  						do_gasoil();
	  						do_autoroute();
	  					}
	  				}else if(t__Autoroute >= t_Vidange && t__Autoroute >= t_Gasoil){
	  					do_autoroute();
	  					if(t_Vidange >= t_Gasoil){
	  						do_vidange();
	  						do_gasoil();
	  					}else{
	  						do_gasoil();
	  						do_vidange();
	  					}
	  				}else if(t_Gasoil >= t__Autoroute && t_Gasoil >= t_Vidange){
	  					do_gasoil();
	  					if(t__Autoroute>=t_Vidange){
	  						do_autoroute();
	  						do_vidange();
	  					}else{
	  						do_vidange();
	  						do_autoroute();
	  					}
	  				}
	  		}else if(t__Autoroute >= t_Vidange && t__Autoroute >= t_Gasoil && t__Autoroute >= t_Divers){
	  				do_autoroute();
	  				if(t_Vidange >= t_Gasoil && t_Vidange >= t_Divers){
	  					do_vidange();
	  					if(t_Gasoil >= t_Divers){
	  						do_gasoil();
	  						do_divers();
	  					}else{
	  						do_divers();
	  						do_gasoil();
	  					}
	  				}else if(t_Gasoil >= t_Vidange && t_Gasoil >= t_Divers){
	  					do_gasoil();
	  					if(t_Vidange >= t_Divers){
	  						do_vidange();
	  						do_divers();
	  					}else{
	  						do_divers();
	  						do_vidange();
	  					}
	  				}else if(t_Divers >= t_Gasoil && t_Divers >= t_Vidange){
	  					do_divers();
	  					if(t_Gasoil>=t_Vidange){
	  						do_gasoil();
	  						do_vidange();
	  					}else{
	  						do_vidange();
	  						do_gasoil();
	  					}
	  				}
	  		}
	  	}


	  	

	  

	  	
	  	
	 
	  

	  	one_frais_v.push(les_details_frais);
	  	les_frais_vendeurs.push(one_frais_v);
	});
	return les_frais_vendeurs;
}
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
<div id="get_data" style="display: none;">
	 <?php for($p=0;$p<20;$p++){ ?>
    <br>
    <?php  } ?>
</div>
<div id="get_data2"></div>
<div id="container_chart"></div>
<div id="container_chart_frais">
</div>
<script type="text/javascript">
	function make_chart(r_vendeurs){
    var array1 = [];
    var array_dri2 = [];
	for (var x = 0; x < r_vendeurs.length; x++) {
        var xy = Number(r_vendeurs[x][1]);
        array1.push({"name": r_vendeurs[x][0],"y":xy,"drilldown":r_vendeurs[x][0]});
         console.log(array1);
	    dri_array = [];
	    console.log('r_v x 2');
	    console.log(r_vendeurs[x][2]);
	    var dri_obj = {"name" : r_vendeurs[x][0] ,"id" : r_vendeurs[x][0] ,"data" : r_vendeurs[x][2] };
	    array_dri2.push(dri_obj);
	    /*
	    for(var e = 0;e<r_vendeurs[0][2].length;e++){
	        dri_array.push(r_vendeurs[0][2]);
	    }
	    */
	     /*
	    var dri_obj = {"name" : r_vendeurs[x][0] ,"id" : r_vendeurs[x][0] ,"data" : dri_array };
	      array_dri2.push(dri_obj);
	    */
    }
    console.log('array dri 2');
  	console.log(array_dri2);

	Highcharts.chart('container_chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'RAPPORT D\'ACTIVITE COMMERCIALE DISHOP'
    },
    subtitle: {
        text: 'Clic sur une colonne de Vendeur pour Voir les détails'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'CHIFFRE D\'AFFAIRES'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}DH'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}DH</b> | Total<br/>'
    },

    series: [{
        name: 'Vendeur',
        colorByPoint: true,
        data: array1
    }],
    drilldown: {
        series: array_dri2
    }
});

}

function make_chart_frais(les_vendeurs_frais){

	console.log('les vendeurs frais');
	console.log(les_vendeurs_frais);
 	var array1 = [];
    var array_dri2_frais = [];
	for (var x = 0; x < les_vendeurs_frais.length; x++) {
        var xy = Number(les_vendeurs_frais[x][1]);
        array1.push({"name": les_vendeurs_frais[x][0],"y":xy,"drilldown":les_vendeurs_frais[x][0]});
         console.log(array1);
         console.log('dri frais details');
         console.log(les_vendeurs_frais[x][2]);
         var dri_obj = {"name" : les_vendeurs_frais[x][0] ,"id" : les_vendeurs_frais[x][0] ,"data" : les_vendeurs_frais[x][2] };
	     array_dri2_frais.push(dri_obj);
     }
	



	Highcharts.chart('container_chart_frais', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'RAPPORT D\'ACTIVITE COMMERCIALE DISHOP'
    },
    subtitle: {
        text: 'Clic sur une colonne de Frais pour Voir les détails'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total Frais'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}DH'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}DH</b> | total<br/>'
    },

    series: [{
        name: 'Frais',
        colorByPoint: true,
        data: array1
    }],
    drilldown: {
        series: array_dri2_frais
    }
});
}
Highcharts.setOptions({
    lang: {
        drillUpText: '◁ Retourner vers {series.name}'
    }

});
</script>
<?php include 'footer_y.php' ?>