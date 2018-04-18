<?php
require_once('connexion.php'); 
include("lang.php");
include("header_y.php");



?>



<script language="javascript" type="text/javascript">
function ajax_func(){
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                document.getElementById('get_ajax_data').innerHTML = xmlhttp.responseText;
            }
        }
        first_date = document.getElementById("first_date").value;
        second_date = document.getElementById("second_date").value;
        inputdepot = document.getElementById("inputdepot").value;
        inputvendeur =  document.getElementById("inputvendeur").value;
        xmlhttp.open('GET','ajax_frais.php?first_date='+first_date+'&second_date='+second_date+
        	'&inputdepot='+inputdepot+'&inputvendeur='+inputvendeur+'&yes=yes'
        	,false);
        xmlhttp.send();
        }

//ajax_func();
function ajax_depot_vendeur(){
		xmlhttp2 = new XMLHttpRequest();
        xmlhttp2.onreadystatechange = function(){
            if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
                document.getElementById('inputvendeur').innerHTML = xmlhttp2.responseText;
            }
        }
        
        inputdepot = document.getElementById("inputdepot").value;
       
        xmlhttp2.open('GET','ajax_depot_vendeur.php?inputdepot='+inputdepot+'&yes=yes'
        	,false);
        xmlhttp2.send();
}
</script>




<div id="myModal_img" class="modal_img">
  <span class="close_img">&times;</span>
  <img class="modal-content_img" id="img01">
  
</div>

<div class="row jumbotron" id="afficher_vidange_total" style="display: none;font-size: 25px;font-weight: bold;
margin: 25px 0;">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center text-info">
		Vidange
		<span id="title_vidange">Total : </span>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 
		id="svg_vidange" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">

			<g id="expand-more">
				<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35   " fill="#006DF0"/>
			</g>

		</svg>
	</div>
	<div class="col-md-3 text-right">
		


		<svg width="40px" height="40px" version="1.1" id="print_vidange_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
		<g>
			<path style="fill:#7383BF;" d="M494.345,88.276H17.655C7.91,88.276,0,96.177,0,105.931v300.138c0,9.746,7.91,17.655,17.655,17.655
				h70.621l17.655-88.276h300.138l17.655,88.276h70.621c9.746,0,17.655-7.91,17.655-17.655V105.931
				C512,96.177,504.09,88.276,494.345,88.276"/>
			<g>
				<path style="fill:#434C6D;" d="M0,300.138v105.931c0,9.746,7.91,17.655,17.655,17.655h70.621l17.655-88.276h300.138l17.655,88.276
					h70.621c9.746,0,17.655-7.91,17.655-17.655V300.138H0z"/>
				<path style="fill:#434C6D;" d="M388.414,220.69H123.586c-19.5,0-35.31-15.81-35.31-35.31V88.276h335.448v97.103
					C423.724,204.879,407.914,220.69,388.414,220.69"/>
			</g>
			<g>
				<path style="fill:#EDEBDA;" d="M430.611,512H81.392c-5.57,0-9.754-5.094-8.66-10.558l33.201-165.994h300.138l33.201,165.994
					C440.357,506.906,436.182,512,430.611,512"/>
				<path style="fill:#EDEBDA;" d="M397.241,88.276H114.759V8.828c0-4.873,3.955-8.828,8.828-8.828h264.828
					c4.873,0,8.828,3.955,8.828,8.828V88.276z"/>
			</g>
			<polygon style="fill:#A4E869;" points="282.483,185.379 388.414,185.379 388.414,132.414 282.483,132.414 	"/>
			<g>
				<path style="fill:#CEC9AE;" d="M326.621,388.414H185.379c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h141.241c4.882,0,8.828,3.946,8.828,8.828C335.448,384.468,331.502,388.414,326.621,388.414"/>
				<path style="fill:#CEC9AE;" d="M353.103,432.552H158.897c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h194.207c4.882,0,8.828,3.946,8.828,8.828C361.931,428.606,357.985,432.552,353.103,432.552"/>
				<path style="fill:#CEC9AE;" d="M379.586,476.69H132.414c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h247.172c4.882,0,8.828,3.946,8.828,8.828C388.414,472.744,384.468,476.69,379.586,476.69"/>
			</g>
		</g>

		</svg>



	</div>
	
</div>
<table class="table" id="table_vidange_view" style="display: none;">
<table class="table" id="table_vidange_print" style="display: none;">
	
</table>
<div class="row jumbotron" id="afficher_vidange_gasoil" style="display: none;font-size: 25px;font-weight: bold;
margin: 25px 0;">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center text-info">
		Gasoil
		<span id="title_gasoil">Total : </span>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 
		id="svg_gasoil" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">

			<g id="expand-more">
				<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35   " fill="#006DF0"/>
			</g>

		</svg>
	</div>
	

	<div class="col-md-3 text-right">
		


		<svg width="40px" height="40px" version="1.1" id="print_gasoil_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
		<g>
			<path style="fill:#7383BF;" d="M494.345,88.276H17.655C7.91,88.276,0,96.177,0,105.931v300.138c0,9.746,7.91,17.655,17.655,17.655
				h70.621l17.655-88.276h300.138l17.655,88.276h70.621c9.746,0,17.655-7.91,17.655-17.655V105.931
				C512,96.177,504.09,88.276,494.345,88.276"/>
			<g>
				<path style="fill:#434C6D;" d="M0,300.138v105.931c0,9.746,7.91,17.655,17.655,17.655h70.621l17.655-88.276h300.138l17.655,88.276
					h70.621c9.746,0,17.655-7.91,17.655-17.655V300.138H0z"/>
				<path style="fill:#434C6D;" d="M388.414,220.69H123.586c-19.5,0-35.31-15.81-35.31-35.31V88.276h335.448v97.103
					C423.724,204.879,407.914,220.69,388.414,220.69"/>
			</g>
			<g>
				<path style="fill:#EDEBDA;" d="M430.611,512H81.392c-5.57,0-9.754-5.094-8.66-10.558l33.201-165.994h300.138l33.201,165.994
					C440.357,506.906,436.182,512,430.611,512"/>
				<path style="fill:#EDEBDA;" d="M397.241,88.276H114.759V8.828c0-4.873,3.955-8.828,8.828-8.828h264.828
					c4.873,0,8.828,3.955,8.828,8.828V88.276z"/>
			</g>
			<polygon style="fill:#A4E869;" points="282.483,185.379 388.414,185.379 388.414,132.414 282.483,132.414 	"/>
			<g>
				<path style="fill:#CEC9AE;" d="M326.621,388.414H185.379c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h141.241c4.882,0,8.828,3.946,8.828,8.828C335.448,384.468,331.502,388.414,326.621,388.414"/>
				<path style="fill:#CEC9AE;" d="M353.103,432.552H158.897c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h194.207c4.882,0,8.828,3.946,8.828,8.828C361.931,428.606,357.985,432.552,353.103,432.552"/>
				<path style="fill:#CEC9AE;" d="M379.586,476.69H132.414c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h247.172c4.882,0,8.828,3.946,8.828,8.828C388.414,472.744,384.468,476.69,379.586,476.69"/>
			</g>
		</g>

		</svg>



	</div>
	
</div>
<table class="table" id="table_gasoil_view" style="display: none;">
</table>
<table class="table" id="table_gasoil_print" style="display: none;">
</table>


<div class="row jumbotron" id="afficher_Autoroute" style="display: none;font-size: 25px;font-weight: bold;
margin: 25px 0;">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center text-info">
		Autoroute 
		<span id="title_Autoroute">Total : </span>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 
		id="svg_Autoroute" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">

			<g id="expand-more">
				<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35   " fill="#006DF0"/>
			</g>

		</svg>
	</div>
	

	<div class="col-md-3 text-right">
		


		<svg width="40px" height="40px" version="1.1" id="print_Autoroute_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
		<g>
			<path style="fill:#7383BF;" d="M494.345,88.276H17.655C7.91,88.276,0,96.177,0,105.931v300.138c0,9.746,7.91,17.655,17.655,17.655
				h70.621l17.655-88.276h300.138l17.655,88.276h70.621c9.746,0,17.655-7.91,17.655-17.655V105.931
				C512,96.177,504.09,88.276,494.345,88.276"/>
			<g>
				<path style="fill:#434C6D;" d="M0,300.138v105.931c0,9.746,7.91,17.655,17.655,17.655h70.621l17.655-88.276h300.138l17.655,88.276
					h70.621c9.746,0,17.655-7.91,17.655-17.655V300.138H0z"/>
				<path style="fill:#434C6D;" d="M388.414,220.69H123.586c-19.5,0-35.31-15.81-35.31-35.31V88.276h335.448v97.103
					C423.724,204.879,407.914,220.69,388.414,220.69"/>
			</g>
			<g>
				<path style="fill:#EDEBDA;" d="M430.611,512H81.392c-5.57,0-9.754-5.094-8.66-10.558l33.201-165.994h300.138l33.201,165.994
					C440.357,506.906,436.182,512,430.611,512"/>
				<path style="fill:#EDEBDA;" d="M397.241,88.276H114.759V8.828c0-4.873,3.955-8.828,8.828-8.828h264.828
					c4.873,0,8.828,3.955,8.828,8.828V88.276z"/>
			</g>
			<polygon style="fill:#A4E869;" points="282.483,185.379 388.414,185.379 388.414,132.414 282.483,132.414 	"/>
			<g>
				<path style="fill:#CEC9AE;" d="M326.621,388.414H185.379c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h141.241c4.882,0,8.828,3.946,8.828,8.828C335.448,384.468,331.502,388.414,326.621,388.414"/>
				<path style="fill:#CEC9AE;" d="M353.103,432.552H158.897c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h194.207c4.882,0,8.828,3.946,8.828,8.828C361.931,428.606,357.985,432.552,353.103,432.552"/>
				<path style="fill:#CEC9AE;" d="M379.586,476.69H132.414c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h247.172c4.882,0,8.828,3.946,8.828,8.828C388.414,472.744,384.468,476.69,379.586,476.69"/>
			</g>
		</g>

		</svg>



	</div>
	
</div>
<table class="table" id="table_Autoroute_view" style="display: none;">
</table>
<table class="table" id="table_Autoroute_print" style="display: none;">
</table>

<div class="row jumbotron" id="afficher_Divers" style="display: none;font-size: 25px;font-weight: bold;
margin: 25px 0;">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center text-info">
		Divers
		<span id="title_Divers">Total : </span>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" 
		id="svg_Divers" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 306 306" style="enable-background:new 0 0 306 306;" xml:space="preserve">

			<g id="expand-more">
				<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35   " fill="#006DF0"/>
			</g>

		</svg>
	</div>
	<div class="col-md-3 text-right">
		


		<svg width="40px" height="40px" version="1.1" id="print_divers_svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
		<g>
			<path style="fill:#7383BF;" d="M494.345,88.276H17.655C7.91,88.276,0,96.177,0,105.931v300.138c0,9.746,7.91,17.655,17.655,17.655
				h70.621l17.655-88.276h300.138l17.655,88.276h70.621c9.746,0,17.655-7.91,17.655-17.655V105.931
				C512,96.177,504.09,88.276,494.345,88.276"/>
			<g>
				<path style="fill:#434C6D;" d="M0,300.138v105.931c0,9.746,7.91,17.655,17.655,17.655h70.621l17.655-88.276h300.138l17.655,88.276
					h70.621c9.746,0,17.655-7.91,17.655-17.655V300.138H0z"/>
				<path style="fill:#434C6D;" d="M388.414,220.69H123.586c-19.5,0-35.31-15.81-35.31-35.31V88.276h335.448v97.103
					C423.724,204.879,407.914,220.69,388.414,220.69"/>
			</g>
			<g>
				<path style="fill:#EDEBDA;" d="M430.611,512H81.392c-5.57,0-9.754-5.094-8.66-10.558l33.201-165.994h300.138l33.201,165.994
					C440.357,506.906,436.182,512,430.611,512"/>
				<path style="fill:#EDEBDA;" d="M397.241,88.276H114.759V8.828c0-4.873,3.955-8.828,8.828-8.828h264.828
					c4.873,0,8.828,3.955,8.828,8.828V88.276z"/>
			</g>
			<polygon style="fill:#A4E869;" points="282.483,185.379 388.414,185.379 388.414,132.414 282.483,132.414 	"/>
			<g>
				<path style="fill:#CEC9AE;" d="M326.621,388.414H185.379c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h141.241c4.882,0,8.828,3.946,8.828,8.828C335.448,384.468,331.502,388.414,326.621,388.414"/>
				<path style="fill:#CEC9AE;" d="M353.103,432.552H158.897c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h194.207c4.882,0,8.828,3.946,8.828,8.828C361.931,428.606,357.985,432.552,353.103,432.552"/>
				<path style="fill:#CEC9AE;" d="M379.586,476.69H132.414c-4.882,0-8.828-3.946-8.828-8.828c0-4.882,3.946-8.828,8.828-8.828
					h247.172c4.882,0,8.828,3.946,8.828,8.828C388.414,472.744,384.468,476.69,379.586,476.69"/>
			</g>
		</g>

		</svg>



	</div>
	
</div>
<table class="table" id="table_Divers_view" style="display: none;">
</table>
<table class="table" id="table_Divers_print" style="display: none;">
</table>
<div class="row jumbotron" id="afficher_total_frais" style="display: none;font-size: 25px;font-weight: bold;
margin: 25px 0;">
	<div class="col-md-3"></div>
	<div class="col-md-6 text-center text-info">
		Total Global : 
		<span id="title_total_frais"></span>
	
	</div>
	<div class="col-md-3 text-right">
		

	</div>
	
</div>
<div class="row" id="get_ajax_data">
	<!-- ajax here -->
</div>

<script type="text/javascript">
			$.fn.extend({
			  animateCss: function(animationName, callback) {
			    var animationEnd = (function(el) {
			      var animations = {
			        animation: 'animationend',
			        OAnimation: 'oAnimationEnd',
			        MozAnimation: 'mozAnimationEnd',
			        WebkitAnimation: 'webkitAnimationEnd',
			      };

			      for (var t in animations) {
			        if (el.style[t] !== undefined) {
			          return animations[t];
			        }
			      }
			    })(document.createElement('div'));

			    this.addClass('animated ' + animationName).one(animationEnd, function() {
			      $(this).removeClass('animated ' + animationName);

			      if (typeof callback === 'function') callback();
			    });

			    return this;
			  },
			});
			function view_image(){
			var modal = document.getElementById('myModal_img');

			// Get the image and insert it inside the modal - use its "alt" text as a caption
			var img = document.getElementById("myImg");
			var modalImg = document.getElementById("img01");

			/*img.onclick = function(){
			    modal.style.display = "block";
			    modalImg.src = this.src;
			    alert("yeah");
			    close_image_dialog();
			}*/
			$(".myImg").click(function(){
				modal.style.display = "block";
			    modalImg.src = this.src;
			    //alert("yeah");
			    close_image_dialog();
			});
			/*$("#myImg").click(function(){
				modal.style.display = "block";
			    modalImg.src = this.src;
			    alert("yeah");

			});*/
			// Get the <span> element that closes the modal
			
			}
			function close_image_dialog(){
					var span = document.getElementsByClassName("close_img")[0];

				// When the user clicks on <span> (x), close the modal
				var modal = document.getElementById('myModal_img');
				span.onclick = function() { 
				    modal.style.display = "none";
				}
			}
			$("#svg_vidange").click(function(){
				$("#table_vidange_view").toggle();
				var i = 1 ;
				for(i=1;i<1000;i++){
					var elem = $("#row_vidange"+i);
					if(elem != null){
						elem.animateCss('fadeInUp');
						var secondes = (i/10)+"s";
						elem.css("animation-delay", secondes);
					}
				}
			});
			$("#svg_gasoil").click(function(){
				$("#table_gasoil_view").toggle();
				
				var i = 1 ;
				for(i=1;i<1000;i++){
					var elem = $("#row_gasoil"+i);
					if(elem != null){
						elem.animateCss('fadeInUp');
						var secondes = (i/10)+"s";
						elem.css("animation-delay", secondes);
					}
				}
			});
			
			$("#svg_Autoroute").click(function(){
				$("#table_Autoroute_view").toggle();
				
				var i = 1 ;
				for(i=1;i<1000;i++){
					var elem = $("#autoroute_id_row"+i);
					if(elem != null){
						elem.animateCss('fadeInUp');
						var secondes = (i/10)+"s";
						elem.css("animation-delay", secondes);
					}
				}
			});

			//*****************************************************
			$("#svg_Divers").click(function(){
				$("#table_Divers_view").toggle();
				
				var i = 1 ;
				for(i=1;i<1000;i++){
					var elem = $("#divers_id_row"+i);
					if(elem != null){
						elem.animateCss('fadeInUp');
						var secondes = (i/10)+"s";
						elem.css("animation-delay", secondes);
					}
				}
			});
			//******************************************************
			
			
			
			$("#print_vidange_svg").click(function(){
					vidange_table = $("#vidange_table").html();
					vidange_title = $("#title_vidange").html();
					date_debut = $("#date_debut").val();
					date_fin = $("#date_fin").val();
					afficher_date = '<h2 class="text-center">Date de '+date_debut+' jusqu\'à '+date_fin+' </h2>';
					
                    $("#table_vidange_print").html('<h1 class="text-center">Vidange <span>'+vidange_title+'</span></h1>' + afficher_date+
					 vidange_table);
					$("#table_vidange_print").show();
					$('#table_vidange_print tr').find('td:eq(3),th:eq(3)').remove();
					$("#table_vidange_print").print();
					$("#table_vidange_print").hide();
					view_image();
			});

			
			$("#print_gasoil_svg").click(function(){
					vidange_table = $("#gasoil_table").html();
					vidange_title = $("#title_gasoil").html();
					date_debut = $("#date_debut").val();
					date_fin = $("#date_fin").val();
					afficher_date = '<h2 class="text-center">Date de '+date_debut+' jusqu\'à '+date_fin+' </h2>';
                    $("#table_gasoil_print").html('<h1 class="text-center">Gasoil <span>'+vidange_title+'</span></h1>' + afficher_date+
					 vidange_table);
					$("#table_gasoil_print").show();
					$('#table_gasoil_print tr').find('td:eq(3),th:eq(3)').remove();
					$("#table_gasoil_print").print();
					$("#table_gasoil_print").hide();
					view_image();

			});


			$("#print_Autoroute_svg").click(function(){
					vidange_table = $("#autoroute_table").html();
					vidange_title = $("#title_Autoroute").html();
					date_debut = $("#date_debut").val();
					date_fin = $("#date_fin").val();
					afficher_date = '<h2 class="text-center">Date de '+date_debut+' jusqu\'à '+date_fin+' </h2>';
                    $("#table_Autoroute_print").html('<h1 class="text-center">Autoroute <span>'+vidange_title+'</span></h1>' + afficher_date+
					 vidange_table);
					$("#table_Autoroute_print").show();
					$('#table_Autoroute_print tr').find('td:eq(2),th:eq(2)').remove();
					$("#table_Autoroute_print").print();
					$("#table_Autoroute_print").hide();
					view_image();
			});


			$("#print_divers_svg").click(function(){
					vidange_table = $("#divers_table").html();
					vidange_title = $("#title_Divers").html();
					date_debut = $("#date_debut").val();
					date_fin = $("#date_fin").val();
					afficher_date = '<h2 class="text-center">Date de '+date_debut+' jusqu\'à '+date_fin+' </h2>';
                    $("#table_Divers_print").html('<h1 class="text-center">Divers <span>'+vidange_title+'</span></h1>' + afficher_date+
					 vidange_table);
					$("#table_Divers_print").show();
					
					$("#table_Divers_print").print();
					$("#table_Divers_print").hide();
					view_image();
			});

			
			function get_title_vidange(){

			vidange_t = $("#vidange_ajax").html();
			gasoil_t = $("#gasoil_ajax").html();
			autoroute_t = $("#Autoroute_ajax").html();
			Divers_t = $("#Divers_ajax").html();
			total_global = $("#total_global_ajax").html();

			vidange_table = $("#vidange_table").html();
			gasoil_table = $("#gasoil_table").html();
			autoroute_table = $("#autoroute_table").html();
			divers_table = $("#divers_table").html();

			$("#title_vidange").html(vidange_t);
			$("#title_gasoil").html(gasoil_t);
			$("#title_Autoroute").html(autoroute_t);
			$("#title_Divers").html(Divers_t);
			$("#title_total_frais").html(total_global);


			$("#table_vidange_view").html(vidange_table);
			$("#table_gasoil_view").html(gasoil_table);
			$("#table_Autoroute_view").html(autoroute_table);
			$("#table_Divers_view").html(divers_table);

			$("#vidange_ajax").hide();
			$("#gasoil_ajax").hide();
			$("#Autoroute_ajax").hide();
			$("#Divers_ajax").hide();
			$("#total_global_ajax").hide();

			$("#afficher_vidange_total").show();
			$("#afficher_vidange_gasoil").show();
			$("#afficher_Autoroute").show();
			$("#afficher_Divers").show();
			$("#afficher_total_frais").show();
			
			$('#afficher_vidange_total').addClass('animated fadeInRight');
			$('#afficher_vidange_gasoil').addClass('animated fadeInRight');
			$('#afficher_Autoroute').addClass('animated fadeInRight');
			$('#afficher_Divers').addClass('animated fadeInRight');
			$('#afficher_total_frais').addClass('animated fadeInRight');

			if(vidange_t == "nothing to show"){
				$("#afficher_vidange_total").hide();
				$("#table_vidange_view").hide();
			}

			if(gasoil_t == "nothing to show"){
				$("#afficher_vidange_gasoil").hide();
				$("#table_gasoil_view").hide();
			}

			if(autoroute_t == "nothing to show"){
				$("#afficher_Autoroute").hide();
				$("#table_Autoroute_view").hide();
			}

			if(Divers_t == "nothing to show"){
				$("#afficher_Divers").hide();
				$("#table_Divers_view").hide();
			}

			if(total_global  == "nothing to show"){
				$("#title_total_frais").hide();
				$("#afficher_total_frais").hide();
			}
			//console.log(vidange_t);
			//alert(vidange_t);
			}
			
		    $(function () {
		        $('#datetimepicker6').datetimepicker();
		        $('#datetimepicker7').datetimepicker({
		            useCurrent: false //Important! See issue #1075
		        });
		        $("#datetimepicker6").on("dp.change", function (e) {
		            $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
		        });
		        $("#datetimepicker7").on("dp.change", function (e) {
		            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
		        });
		    });
</script>



  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Veuillez Choisir</h4>
          
          
        </div>
        

        <form>
        	


     
           <div class="modal-body">
         

				<p><b>Date De</b></p>
		        <div class="form-group">
		            <div class='input-group date' id='datetimepicker6'>
		                <input type='text' id="first_date" name="first_date" class="form-control" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		 		<p><b>à</b></p>
		        <div class="form-group">
		            <div class='input-group date' id='datetimepicker7'>
		                <input type='text' id="second_date" name="second_date" class="form-control" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		   	
		   		<a class="btn btn-info" id="show_depot_for_select">choisir dépôt | Vendeur</a>
		   		<!--<a class="btn btn-info" id="show_vendeur_for_select">choisir Vendeur</a>-->

		   		<div class="form-group" id="combobox_depot" style="display: none;margin-top: 15px;">
			      <label for="inputdepot">choisir dépôt</label>
			      <select id="inputdepot" name="inputdepot" class="form-control">
			        <option selected value="tous">Tous</option>

			        <?php 
			       	$query_depot = "select distinct d.Designation,d.idDepot from depots d";

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
				
				<div class="form-group" id="combobox_vendeur" style="display: none;margin-top: 15px;">
			      <label for="inputvendeur">choisir Vendeur</label>
			      <select id="inputvendeur" name="inputvendeur" class="form-control">
			        

			       
			      </select>
			    </div>


        </div>
        <div class="modal-footer">
          <input  onclick="ajax_func();close_modal();get_title_vidange();view_image();" name="submit_choisir" class="btn btn-success" value="Okay">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>

        </form>
      </div>
      
    </div>
  </div>

<style type="text/css">
	.view{
		border: 1px solid black;
		margin:15px;
		background: #c7ecee;
		color: #34495e;
		border-radius: 30px;
		padding: 15px;
	}
	.view p{
		font-size: 25px;
	}
	*:not(span){
				 color: #2c3e50;
				 letter-spacing: -.05em;
				 /*font-weight: 200;*/
				 font-weight: bold;
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
 	body{
 		overflow-x: hidden;
 	}
	

	#afficher_vidange_total{

  				 -webkit-animation-delay: 0.1s;
  		
  				 -moz-animation-delay: 0.1s;
  		
  				 -o-animation-delay: 0.1s;
  		
  				 -ms-animation-delay: 0.1s;
  	
  				 animation-delay: 0.1s;
	}
	#afficher_vidange_gasoil{
				-webkit-animation-delay: 0.3s;
  		
  				 -moz-animation-delay: 0.3s;
  		
  				 -o-animation-delay: 0.3s;
  		
  				 -ms-animation-delay: 0.3s;
  	
  				 animation-delay: 0.3s;
	}
	#afficher_Autoroute{
				-webkit-animation-delay: 0.5s;
  		
  				 -moz-animation-delay: 0.5s;
  		
  				 -o-animation-delay: 0.5s;
  		
  				 -ms-animation-delay: 0.5s;
  	
  				 animation-delay: 0.5s;
	}
	#afficher_Divers{
				-webkit-animation-delay: 0.7s;
  		
  				 -moz-animation-delay: 0.7s;
  		
  				 -o-animation-delay: 0.7s;
  		
  				 -ms-animation-delay: 0.7s;
  	
  				 animation-delay: 0.7s;
	}
	#afficher_total_frais{
				-webkit-animation-delay: 0.9s;
  		
  				 -moz-animation-delay: 0.9s;
  		
  				 -o-animation-delay: 0.9s;
  		
  				 -ms-animation-delay: 0.9s;
  	
  				 animation-delay: 0.9s;
	}
</style>

<script type="text/javascript">

	function close_modal(){
		$('#myModal').modal('hide');
	}
	$('#myModal').modal('show');
	

	$( "#show_depot_for_select" ).click(function() {
		if($("#first_date").val()!='' &&  $("#second_date").val()!=''){
			$("#combobox_depot").toggle();
		}
	  
	});
	

	$( "#inputdepot" ).change(function() {

	 if($("#first_date").val()!='' &&  $("#second_date").val()!=''){
	 		ajax_depot_vendeur();
			$("#combobox_vendeur").show();
		}

	});
	//alert("ok");
	$(function () {
        $('#datetimepicker4').datetimepicker();
        
    });
</script>



 <style type="text/css">
#myImg {
  
    cursor: pointer;
    transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal_img {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content_img {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}



/* Add Animation */
.modal-content_img{    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

/* The Close Button */
.close_img {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close_img:hover,
.close_img:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    .modal-content_img {
        width: 100%;
    }
}
</style>

<script type="text/javascript">


</script>
<?php
include 'footer_frais.php' 
 ?>