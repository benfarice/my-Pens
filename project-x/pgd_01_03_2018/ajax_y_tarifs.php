	<?php
	require_once('connexion.php'); 

	//-------------------------------------------
	//------ Update Tarif with new Tarif
	//-------------------------------------------
	if(isset($_REQUEST['new_tarif'])){
		$new_tarif = $_REQUEST['new_tarif'];
		$id_tarif = $_REQUEST['id_tarif'];

		$query_update = "update tarifs set pvHT = $new_tarif where idTarif = $id_tarif";
		$resAffA_update = sqlsrv_query($conn,$query_update) ;
	}

	//-------------------------------------------
	//------ Update Tarif with new Tarif
	//-------------------------------------------






	//-------------------------------------------
	//------ Delete Tarif with id Tarif
	//-------------------------------------------
	if(isset($_REQUEST['id_tarif_to_delete'])){
		
		$id_tarif = $_REQUEST['id_tarif_to_delete'];

		$query_delete = "delete from tarifs  where idTarif = $id_tarif";
		$resAffA_delete = sqlsrv_query($conn,$query_delete) ;
	}

	//-------------------------------------------
	//------ Delete Tarif with id Tarif
	//-------------------------------------------







	//-------------------------------------------
	//------ Insert new Tarif with an article
	//-------------------------------------------


	if(isset($_REQUEST['searched_ref'])){

		$add_tarif = $_REQUEST['add_tarif'];
		$searched_ref = $_REQUEST['searched_ref'];
		$id_fiche = $_REQUEST['id_fiche'];
		$id_depot = -1;
		$query_depot = "select idDepot from ficheTarifs where idFiche = $id_fiche";
		$resAffA_depot = sqlsrv_query($conn,$query_depot) ;
		while($rowA_depot = sqlsrv_fetch_array($resAffA_depot, SQLSRV_FETCH_ASSOC)){
			$id_depot = $rowA_depot['idDepot'];
		}
		$query_add_tarif = "insert into tarifs(idArticle,pvHT,idFiche,qteMin,qteMax,controleQte,idDepot) values
		((select a.IdArticle from articles a where 
		a.Reference = '$searched_ref'),$add_tarif,$id_fiche,1,0,'false',$id_depot)";
		//echo $query_add_tarif;
		$resAffA_add_tarif = sqlsrv_query($conn,$query_add_tarif) ;
	}

		//-------------------------------------------
	//------ Insert new Tarif with an article
	//-------------------------------------------



	//-------------------------------------------
	//------ get data to Display in Table
	//-------------------------------------------


	$sqlA = "
		SELECT t.idTarif, Reference as CodeaBarre,t.qteMin as Qte,t.pvHt,a.designation as NomArt
		 FROM tarifs t 
		 inner join fichetarifs f on f.idFiche=t.idFiche 
		 inner join articles a on a.idArticle=t.idArticle 
		 where t.idDepot=1 and   t.idFiche = ?";

	 $params = array($_REQUEST['id_fiche']);
	 $resAffA = sqlsrv_query($conn,$sqlA,$params) ;
	 $i = 1 ;
	 while($rowA = sqlsrv_fetch_array($resAffA, SQLSRV_FETCH_ASSOC)){
	 //echo $sqlA;	
	 ?>
	 <tr class="animated bounce" id="row_tarif<?php echo $i; ?>">
	 	<th scope="row"><?php echo $rowA['CodeaBarre']; ?></th>
	 	<td><?php echo $rowA['NomArt']; ?></td>
	 	<td><?php echo number_format($rowA['pvHt'], 2, '.', ' '); ?></td>
	 	<td>
	 		<?php $t = number_format($rowA['pvHt'], 2, '.', ' '); ?>
	 		<?php $params = $rowA['idTarif'].' ,  '.$t ; ?>
	 		<div onclick="modifier_tarif(<?php echo $params ;?>,'<?php echo $rowA['CodeaBarre']; ?>','<?php echo $rowA['NomArt']; ?>');">
	 			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="20px" height="20px">
				<path d="M397.736,78.378c6.824,0,12.358-5.533,12.358-12.358V27.027C410.094,12.125,397.977,0,383.08,0H121.641    c-3.277,0-6.42,1.303-8.739,3.62L10.527,105.995c-2.317,2.317-3.62,5.461-3.62,8.738v370.239C6.908,499.875,19.032,512,33.935,512    h349.144c14.897,0,27.014-12.125,27.014-27.027V296.289c0.001-6.824-5.532-12.358-12.357-12.358    c-6.824,0-12.358,5.533-12.358,12.358v188.684c0,1.274-1.031,2.311-2.297,2.311H33.936c-1.274,0-2.311-1.037-2.311-2.311v-357.88    h75.36c14.898,0,27.016-12.12,27.016-27.017V24.716H383.08c1.267,0,2.297,1.037,2.297,2.311V66.02    C385.377,72.845,390.911,78.378,397.736,78.378z M109.285,100.075c0,1.269-1.032,2.301-2.3,2.301H49.107l60.178-60.18V100.075z" fill="#D80027"/>
				<path d="M492.865,100.396l-14.541-14.539c-16.304-16.304-42.832-16.302-59.138,0L303.763,201.28H103.559    c-6.825,0-12.358,5.533-12.358,12.358c0,6.825,5.533,12.358,12.358,12.358h175.488l-74.379,74.379H103.559    c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h76.392l-0.199,0.199c-1.508,1.508-2.598,3.379-3.169,5.433    l-19.088,68.747h-53.936c-6.825,0-12.358,5.533-12.358,12.358s5.533,12.358,12.358,12.358h63.332c0.001,0,2.709-0.306,3.107-0.41    c0.065-0.017,77.997-21.642,77.997-21.642c2.054-0.57,3.926-1.662,5.433-3.169l239.438-239.435    C509.168,143.228,509.168,116.7,492.865,100.396z M184.644,394.073l10.087-36.326l26.24,26.24L184.644,394.073z M244.69,372.752    l-38.721-38.721l197.648-197.648l38.722,38.721L244.69,372.752z M475.387,142.054l-15.571,15.571l-38.722-38.722l15.571-15.571    c6.669-6.668,17.517-6.667,24.181,0l14.541,14.541C482.054,124.54,482.054,135.388,475.387,142.054z" fill="#D80027"/>
				</svg>
	 		</div>
	 		
	 	</td>
	 	<td>
	 		<div onclick="del_tarif(<?php echo $params ;?>,'<?php echo $rowA['CodeaBarre']; ?>','<?php echo $rowA['NomArt']; ?>');">
	 		<svg version="1.1" width="20px" height="20px" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<polygon style="fill:#E21B1B;" points="404.176,0 256,148.176 107.824,0 0,107.824 148.176,256 0,404.176 107.824,512 256,363.824 
					404.176,512 512,404.176 363.824,256 512,107.824 "/>
					</svg>
			</div>
	 	</td>
	 </tr>
	<?php 
	$i++;
	}

	//-------------------------------------------
	//------ get data to Display in Table
	//-------------------------------------------
	?>
	<script type="text/javascript">
		function animate_table(){
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
				var i = 1 ;
				for(i=1;i<1000;i++){
					var elem = $("#row_tarif"+i);
					if(elem != null){
						//alert('here animate');
						elem.animateCss('fadeInUp');
						var secondes = (i/10)+"s";
						elem.css("animation-delay", secondes);
					}
				}
		}
		animate_table();
	</script>