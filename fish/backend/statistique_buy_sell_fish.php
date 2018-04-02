<?php  
		session_start();
    if(!isset($_SESSION['username'])){
    header('Location: index.php');
    exit();
    }
		include 'init.php';
		include $tpl."header.php";
		include $tpl."Navbar.php";
		?>
<div class="container-fluid text-right" id="statistiques_container">			
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<p id="facture_menu_title"><?php echo lang('Statistic'); ?></p>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form id="formRech" name="formRech"  class="bootstrap-iso row"> 
						<div class="middleLabel centerLabel col-md-2 col-sm-2 col-xs-2">
							<label><?php echo lang('Periode');?>
								&nbsp;
								<?php echo lang('de');?>
							</label> 
						</div>	 
						<div class="col-md-2 col-sm-2 col-xs-2 middleLabel"  >	
							<input class="form-control"  id="DateD" tabindex="2" name="DateD" type="text" size="10" maxlength="10"  value="<?php echo date('01/m/Y') ?>" />	
						</div>
						<div  class=" col-md-1 col-sm-1 col-xs-1 centerLabel" > 
							<label  Style="margin-top:10px">
								<?php echo lang('a');?>
							</label>	
						</div>
					    <div  class="col-md-2 col-sm-2 col-xs-2  middleLabel form-group " >		
							<input  id="DateF" tabindex="2" name="DateF" type="text"  class="form-control" 
							size="10" maxlength="10" value="<?php echo date('t/m/Y') ?>" />	
						</div>
					
						<div  class="col-md-3 col-sm-3 col-xs-3 middleLabel form-group " style="font-size:19px">		
							<div class="radio">
								<label>
									<input type="radio" name="optradio" value="Tableau" checked  id="radio_tableau"> 
									<?php echo lang('Tableau');?>
								</label>
								&nbsp;&nbsp;&nbsp;&nbsp;
						 		<label>
						 			<input type="radio" name="optradio" value="Graphe" id="radio_graphe"> 
						 			<?php echo lang('Graphe');?>
						 		</label>
						  	</div>
					    </div>
						
						<div  class="col-md-1 col-sm-1 col-xs-1 centerLabel">				
							<!--
							&nbsp;
							<input type="button" class="btn btn-primary"  id="rech" action="rech" 
							onclick="rechercher()"; />
							&nbsp;
							-->
							<!--<input type="reset" value="<?php echo lang('Annuler');?>" class="btn btn-primary chpinvisible"   id="reset"
							 action="effacer"  />-->
						</div>
					</form>
				</div>
			</div>
			<div class="row" id="get_data_by_ajax">
			<!-- *********** Ajax here ******* -->
			</div>
			<div class="row text-right" id="container_static" style="display: none;">
				
			</div>
			
			
</div>


<link rel="stylesheet" type="text/css" href="<?php echo $css ?>gestion_especes.css">
	

<script type="text/javascript" src="<?php echo $js ?>statistique_buy_sell_fish.js"></script>

<style type="text/css">
	*,body{
		direction: rtl;
	}
	#container_static *{
		direction: ltr;
	}
	@media print {
		.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
		.col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
		.col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
		.col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
		.col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
		.col-xl-auto {
		  position: relative;
		  width: 100%;
		  min-height: 1px;
		  padding-right: 15px;
		  padding-left: 15px;
		}
		.col-md {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .col-md-auto {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: none;
  }
  .col-md-1 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }
  .col-md-2 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }
  .col-md-3 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .col-md-4 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
  .col-md-5 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 41.666667%;
    flex: 0 0 41.666667%;
    max-width: 41.666667%;
  }
  .col-md-6 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-md-7 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 58.333333%;
    flex: 0 0 58.333333%;
    max-width: 58.333333%;
  }
  .col-md-8 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 66.666667%;
    flex: 0 0 66.666667%;
    max-width: 66.666667%;
  }
  .col-md-9 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .col-md-10 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 83.333333%;
    flex: 0 0 83.333333%;
    max-width: 83.333333%;
  }
  .col-md-11 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
  }
  .col-md-12 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
 .website_title_print p {
    font-size: 25px;
    margin-bottom: 0;
}
table td,table th{
	text-align: right;
}

	}
	@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
.website_title_print p {
    font-size: 25px;
    margin-bottom: 0;
}
</style>
<script type="text/javascript">
	
</script>
<?php for($i = 0;$i<7;$i++){
	echo "<br>";
}
include $tpl ."footer.php";
?>