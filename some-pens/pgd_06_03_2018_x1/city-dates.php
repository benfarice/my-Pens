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
                    $("#container_ui3").fadeIn();
                }

                
       function handleChange1_simple(){
                    $("#container_chart").fadeOut();
                    $("#get_data").fadeIn();
                    $("#container_ui3").fadeOut();
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
                       
                        console.log('array here villes : ');
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
                        var  under_marge = $(this).find(".calc_result").find('.marge_under_total');

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

                        var marge_marque = [];
                        $(this).find('.marque_marge_one').each(function(){
                                var one_m_marge = [];
                                var one_marge_marque = $(this).find('.marque_name');
                                var one_marque_marge = $(this).find('.marque_marge');
                                one_m_marge.push(one_marge_marque.html());
                                one_m_marge.push(Number(one_marque_marge.html()));
                                marge_marque.push(one_m_marge);
                        });
                        

                        one_ville.push(result_marques);
                        one_ville.push(Number(under_marge.html()));
                        one_ville.push(marge_marque);
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

<div id="container_t"></div>

<div id="container_t2"></div>

<div id="container_ui"></div>

<div id="container_ui2"></div>

<div id="container_ui3"></div>

<script type="text/javascript">

   // Create the chart
   


    function make_chart(r_villes){

    var objects_result = {};
    var test_array = [];
    var test_array2 = [];
    var test_array3 = [];
    var array_drildown = [];
    var array_drildown2 = [];
    var dril1 = {};
     var dril2 = {};
    drilldowns_x = {};
    drilldowns_y = {};
    var city_chart_x = [];
    var city_chart_x_marge = [];

    var array_x_y = [];

    for (var x = 0; x < r_villes.length; x++) {
        var xy = Number(r_villes[x][1]);
        var xy_marge = Number(r_villes[x][3]);
        var marge = xy - xy_marge;
        if(marge<0) {
            marge=-marge;
        }
        marge = Math.floor(marge);
      objects_result[x] = {"name": r_villes[x][0],"y":xy,"drilldown":r_villes[x][0]};
      test_array.push({"name": r_villes[x][0],"y":marge,"drilldown":r_villes[x][0]});
      test_array2.push({"name": r_villes[x][0],"y":xy_marge,"drilldown":r_villes[x][0]});
      test_array3.push({"name": r_villes[x][0],"y":xy,"drilldown":r_villes[x][0]});
      city_chart_x.push({"name": r_villes[x][0],"y":xy_marge,"drilldown":true});
      city_chart_x_marge.push({"name": r_villes[x][0],"y":marge,"drilldown":true});
      //objects_result.push([r_villes[x][0],xy,r_villes[x][0]]);
      dri_array = [];
      console.log('here r ville 2');
      console.log(r_villes[x][2]);
       console.log('ville x');
      console.log(r_villes[x]);
      console.log('city chart x marge : ');
      console.log(city_chart_x_marge);
      console.log('city chart x');
      console.log(city_chart_x);
      console.log('r villes 4');
      console.log(r_villes[x][4]);
      //--------
          var date_array = [];
         var date_array2 = [];
       
      for(r=0;r<r_villes[x][4].length;r++){
     var data_array_marge =[];
        var date_array_under = [];
        data_array_marge.push(r_villes[x][4][r][0]);

      console.log('yeah '+r);
      console.log(r_villes[x][4][r][1]);
      var def = 0;
          for(s=0;s<r_villes[x][2].length;s++){
            if(r_villes[x][2][s][0] == r_villes[x][4][r][0]){
                def= r_villes[x][2][s][1]-r_villes[x][4][r][1];
            }
          }
    console.log('dif');
    console.log(def);
     if(def<0) def= -def;
     def = Math.floor(def);

     data_array_marge.push(def);
     date_array.push(data_array_marge);
     date_array_under.push(r_villes[x][4][r][0])
     date_array_under.push(r_villes[x][4][r][1]);
     date_array2.push(date_array_under);
     
         } 
        dril1[x] = {"name" : r_villes[x][0],
         'color' : '#e74c3c', 'data' : date_array}
          dril2[x] = {"name" : r_villes[x][0],
         'color' : '#1abc9c', 'data' : date_array2}
      //var marque_name = r_villes[x][4][r][0];
      //console.log(marque_name);
      var foo = String(r_villes[x][0]);
      drilldowns_x[foo]=dril1[x];
      drilldowns_y[foo]=dril2[x];
      console.log("14-11 oclock");
      console.log(drilldowns_x);
      console.log(drilldowns_y);
      /*

      for(r=0;r<r_villes[x][4].length;r++){
        var date_array = [];
        var data_array_marge =[];
        var date_array_under = [];
        data_array_marge.push("Marge");
        //*****************************
        var marque_marge_array = r_villes[x][][r][1] - r_villes[x][4][r][1] ;
        //**************************
        date_array.push()
         dril1[x] = {"name" : r_villes[x][4][r][0],"y" : r_villes[x][4][r][1],
         'color' : '#3150b4', 'data' : date_array}
      }
      
        */


      /*
      for(var e = 0;e<r_villes[2][2].length;e++){
        dri_array.push(r_villes[2][2][e]);
      }
      */
      var dri_obj = {"name" : r_villes[x][0] ,"id" : r_villes[x][0] ,"data" :r_villes[x][2] };
      array_drildown.push(dri_obj);
   
     
    }
    console.log('array drildown  obj 2: ');
    console.log(array_drildown);
    console.log('objet result');
    console.log(objects_result);
    console.log('test array');
    console.log(test_array);

    //array_x_y.push()
    console.log('r villes');
    console.log(r_villes);
/*
 $('#container_t').highcharts({
        chart: {
            type: 'column',
            style: {
                fontSize: '20px'
            }
        },
        title: {
            text: 'RAPPORT D\'ACTIVITE COMMERCIALE DISHOP n'
        },
        subtitle: {
            text: 'Clic sur une colonne de Ville pour Voir les détails.'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
        title: {
            text: 'Total DH TTC'
        }

         },
        plotOptions: {
            series: {
                stacking: 'normal',
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                      style: {
                        color: '#27ae60',
                        fontSize : '25px'
                    }
                }
            }
        },

        series: [{
            name: 'Marge',
            data: test_array
        }, {
            name: 'Prix de vente',
            data: test_array2
        }],
        drilldown: {
            series: array_drildown
        }
        
    });


*/




	Highcharts.chart('container_chart', {
    chart: {
        type: 'column',
        style: {
            fontSize: '20px'
        }
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
                format: '{point.y:.1f} DH',
                  style: {
                        color: '#27ae60',
                        fontSize : '25px'
                    }
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
        data: test_array3
    }],
    drilldown: {
        series: array_drildown
    }

});





console.log("drilldown x");
console.log(drilldowns_x);

 

console.log("console 14-47");
console.log(drilldowns_x);
     $('#container_ui3').highcharts({
        chart: {
            type: 'column',
            style: {
            fontSize: '20px'
             },
            events: {
                drilldown: function (e) {
                    if (!e.seriesOptions) {
                        var chart = this,
                            drilldowns =drilldowns_x,
                            drilldowns2 = drilldowns_y,
                            series = drilldowns[e.point.name],
                            series2 = drilldowns2[e.point.name];
                            chart.addSingleSeriesAsDrilldown(e.point, series); 
                            chart.addSingleSeriesAsDrilldown(e.point, series2);
                            chart.applyDrilldown();
                    }
                }
            }
        },
        title: {
            text: 'Rapport Des marges'
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
        column: {stacking: 'normal'},
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: { textShadow: false,
                        color: '#27ae60',
                        fontSize : '25px' }
                }
            }
        },
        series: [{
            name: 'Marge',
            color: '#3190b4',
            data: city_chart_x_marge
        },{
            name: 'Prix de vente',
            color: '#50B032',
            data:  city_chart_x
        }],
        drilldown: {
            series: []
        }
    });

}
Highcharts.setOptions({
    lang: {
        drillUpText: '◁ Retourner vers {series.name}'
    },
    colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
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