<?php
session_start();
if(!isset($_SESSION['username'])){
header('Location: index.php');
exit();
}
include_once "init.php";
include $tpl."header.php";


if(!isset($noNavbar)){
		include $tpl."Navbar.php";
}

?>
<div class="row label_camion" id="titles_container">
  <div class="col-md-2 col-sm-2 col-xs-2 label_camion">
     <?php echo $_GET['designation']; ?>
  </div>
  <div class="col-md-2 col-sm-2 col-xs-2 label_camion">
    <?php echo $_GET['marque'] ?>
  </div>
  <div class="col-md-2 col-sm-2 col-xs-2 label_camion">
    <?php echo $_GET['matricule'] ?>
  </div>
	<div class="col-md-3 col-sm-3 col-xs-3 label_camion">
			<button class="btn btn-primary btn-block" name="button" id="rechercher_date"
			 onclick="get_another_day_data();">rechercher</button>
			 <br>
		 <input id="datetimepicker" type="text" value="<?php echo date('d/m/Y') ?>" />
	</div>
  <div class="col-md-1 col-sm-1 col-xs-1 label_camion">
								<a href="etat_camion.php">
									<svg  width="60px" height="60px"  version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 383.869 383.869" style="enable-background:new 0 0 383.869 383.869;" xml:space="preserve">
									<path style="fill:#00BBD3;" d="M269.976,123.38H50.547l56.947-64.784c5.747-6.269,5.224-16.196-1.567-21.943
									c-6.269-5.747-16.196-5.224-21.943,1.567L4.049,128.604c0,0.522-0.522,0.522-0.522,1.045c-0.522,0.522-0.522,0.522-1.045,1.045
									c-0.522,0.522-0.522,1.045-0.522,1.567c0,0.522-0.522,0.522-0.522,1.045c0,0.522-0.522,1.045-0.522,1.567
									c0,0.522,0,0.522-0.522,1.045c-0.522,2.09-0.522,4.18,0,5.747c0,0.522,0,0.522,0.522,1.045c0,0.522,0.522,1.045,0.522,1.567
									s0.522,0.522,0.522,1.045c0,0.522,0.522,1.045,0.522,1.567s0.522,0.522,1.045,1.045c0,0.522,0.522,0.522,0.522,1.045l79.412,90.906
									c3.135,3.657,7.314,5.225,12.016,5.225c3.657,0,7.314-1.045,10.449-3.657c6.269-5.747,7.314-15.673,1.567-21.943l-56.947-64.784
									h219.429c45.453,0,82.547,37.094,82.547,82.547s-37.094,82.547-82.547,82.547H36.963c-8.882,0-15.673,6.792-15.673,15.673
									s6.792,15.673,15.673,15.673h233.012c62.694,0,113.894-51.2,113.894-113.894S332.669,123.38,269.976,123.38z"/>

								</svg>
								</a>

			</div>

</div>
<hr>
<div class="row label_camion" id="get_data">
      <!-- Ajax here -->
</div>


<?php include $tpl."footer.php"; ?>
<script type="text/javascript">
function get_data_today(){
  $.ajax({
          url:"../Ajax/get_etat_actuel.php?id_vehicule=<?php echo $_GET['id_camion'] ?>",
          data:{
            //name:user_name,
            //adress:adress,
            //email:email
          },
          type:"POST",
          success:function(data){
            $('#get_data').html(data);
          }
        });
}
function get_another_day_data(){
	var selected_day = $('#datetimepicker').val();
  $.ajax({
          url:"../Ajax/get_etat_actuel.php?id_vehicule=<?php echo $_GET['id_camion'] ?>",
          data:{
            //name:user_name,
            //adress:adress,
            //email:email
						selected_day:selected_day
          },
          type:"POST",
          success:function(data){
            $('#get_data').html(data);
          }
        });
}
</script>
<script type="text/javascript" src="<?php echo $js ?>get_etat_actuel.js"></script>
<link rel="stylesheet" href="<?php echo $css ?>etat_actuel.css">
