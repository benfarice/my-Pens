<?php 
require_once('connexion.php'); 
include("lang.php");
include("header_y.php");?>
<?php 
$a_date_d =date('01/m/Y');
$a_date_f = date('t/m/Y');
?>
<div class="row" style="background: #34495e;color: #ecf0f1;padding:15px;margin: 15px;"
				 id="recherche_form">
				
		        <div class="form-group col-md-6">
		        	<label>Date De</label>
		            <div class='input-group date' id='datetimepicker6'>
		                <input type='text' id="DateD" name="DateD" class="form-control" value="<?php echo $a_date_d; ?>" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		 	
		        <div class="form-group col-md-6">
		        	<label>à</label>
		            <div class='input-group date' id='datetimepicker7'>
		                <input type='text' id="DateFin" name="DateFin" class="form-control" value="<?php echo $a_date_f; ?>" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>

		        <div class="col-md-4">
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
		      
		        <div class="col-md-4">
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
		        <div class="col-md-4">
		        	<button class="btn btn-primary btn-block"  onclick="ajax_func()">Recherche</button>
		        </div>
</div>
<script type="text/javascript">
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
	  function ajax_func(){
	  		var date_a = $("#DateD").val();
	  		var date_b = $("#DateFin").val();
	  		
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					if(date_a != "" && date_b != ""  && date_a != undefined && date_b != undefined ){
						//alert("yeah");
						document.getElementById('get_data').innerHTML = xmlhttp.responseText;
                        tog();
                        var r_villes = do_math();
                       
                        console.log('r villes : ');
                        console.log(r_villes);
                     
                        make_chart(r_villes);


						//view_chart();
					}
					
					//alert("ok");
				}
			}
			xmlhttp.open('GET','ajax_ville_chart.php?DateD='+date_a+'&DateFin='+date_b,true);
			xmlhttp.send();
		}
		//alert("ok");
        window.onload = ajax_func;
        function do_math(){
            var resultat_villes = [];
             $('.result_one_ville').each(function(){
                    
                        var one_ville = [];
                        var city_name = $(this).find(".calc_result").find(".city_name");
                        var city_total = $(this).find(".calc_result").find(".city_total");
                        one_ville.push(city_name.html()); 
                        one_ville.push(city_total.html());
                        

                        var result_marques = [];
           
                        
                        
                        $(this).find(".marque_info").each(function(){
                                var one_marque = [];
                                var marque_name = $(this).find('.get_marque');
                                var marque_total = $(this).find(".get_total_marque");
                                one_marque.push(marque_name.html()); 
                                one_marque.push(Number(marque_total.html()));
                                result_marques.push(one_marque);
                         });
                        
                        

                        one_ville.push(result_marques);
                        resultat_villes.push(one_ville);
                    });
                    return resultat_villes;
       
            }
     
</script>
<style type="text/css">
	#recherche_form .dropdown-menu {
    background: white;
    color: #34495e;
}
</style>
<div id="get_data" style="display: none;">
    <?php for($p=0;$p<20;$p++){ ?>
    <br>
    <?php  } ?>
</div>
<div id="container_chart"></div>
<script type="text/javascript">
    function make_chart(r_villes){

    var objects_result = {};
    var test_array = [];
    var array_drildown = [];

    for (var x = 0; x < r_villes.length; x++) {
        var xy = Number(r_villes[x][1]);
      objects_result[x] = {"name": r_villes[x][0],"y":xy,"drilldown":r_villes[x][0]};
      test_array.push({"name": r_villes[x][0],"y":xy,"drilldown":r_villes[x][0]});
      //objects_result.push([r_villes[x][0],xy,r_villes[x][0]]);
      dri_array = [];
      console.log('here r ville 2');
      console.log(r_villes[2]);
      for(var e = 0;e<r_villes[2][2].length;e++){
        dri_array.push(r_villes[2][2][e]);
      }
      var dri_obj = {"name" : r_villes[x][0] ,"id" : r_villes[x][0] ,"data" : dri_array };
      array_drildown.push(dri_obj);
    }
    console.log('dri obj : ');
    console.log(array_drildown);
    console.log(objects_result);
    console.log(test_array);

	Highcharts.chart('container_chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'RAPPORT D\'ACTIVITE COMMERCIALE DISHOP'
    },
    subtitle: {
        text: 'Clic sur une colonne de Ville pour Voir les détails'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total DH TTC'
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
                format: '{point.y:.1f} DH'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}DH</b> | Total<br/>'
    },

    series: [{
        name: 'Ville',
        colorByPoint: true,
        data: test_array
    }],
    drilldown: {
        series: array_drildown
    }

});
}
Highcharts.setOptions({
    lang: {
        drillUpText: '◁ Retourner vers {series.name}'
    }
});
</script>
<style type="text/css">
    .see_here_more_class{
        display: none;
    }

</style>
<script type="text/javascript">
$( document ).ready(function() {
  $( ".see_more_class" ).click(function() {
    $(this).parent().parent().next().toggle();
  });
});

function tog(){
     $( ".see_more_class" ).click(function() {
            $(this).parent().parent().next().toggle();
          });

}
</script>
<style type="text/css">
     *{
        letter-spacing: -.05em;
        font-weight: bold;
        font-family: 'Montserrat', sans-serif;
        }
</style>
<?php include 'footer.php' ?>