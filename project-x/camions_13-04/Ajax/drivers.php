<?php
include "../connect.php";

$query_drivers = "select c.etat,c.id,c.nom,c.matricule from chauffeurs c";

if(isset($_REQUEST['searched_driver'])){
  $query_drivers = "select c.etat,c.id,c.nom,c.matricule from chauffeurs c
  where c.cin like '%".$_REQUEST['searched_driver']."%' or
  c.nom like '%".$_REQUEST['searched_driver']."%' or
  c.matricule like '%".$_REQUEST['searched_driver']."%'";
}



if(isset($_REQUEST['get_clients'])){
  $query_drivers = "select c.NOM,c.ADRESSE,c.ID from clients c";
}

if(isset($_REQUEST['get_site_d'])){
  $query_drivers = "select d.DESIGNATION,d.ID_CHG from site_dechargement d";
  if(isset($_REQUEST['searched_site_d'])){
  $query_drivers = "select d.DESIGNATION,d.ID_CHG from site_dechargement d
    where d.DESIGNATION like '%".$_REQUEST['searched_site_d']."%'";
  }
}

if(isset($_REQUEST['searched_client'])){
  $query_drivers = "select c.NOM,c.ADRESSE,c.ID from clients c
   where c.ADRESSE like '%".$_REQUEST['searched_client']."%' or
   c.NOM like '%".$_REQUEST['searched_client']."%'";
}

if(isset($_REQUEST['get_villes'])){
  $query_drivers = "select l.ID,l.DESIGNATION from lieu_exploitation l";
  if(isset($_REQUEST['searched_ville'])){
    $query_drivers = "select l.ID,l.DESIGNATION from lieu_exploitation l
    where l.DESIGNATION like '%".$_REQUEST['searched_ville']."%'";
  }
}

if(isset($_REQUEST['get_lieu_chargement'])){
  $query_drivers = "select s.ID_CHG,s.DESIGNATION from site_chargement s";
}

if(isset($_REQUEST['searched_site_c'])){
  $query_drivers = "select s.ID_CHG,s.DESIGNATION from site_chargement s
   where s.DESIGNATION like '%".$_REQUEST['searched_site_c']."%'";
}

$params_query_drivers = array();
$options_query_drivers =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query__drivers=sqlsrv_query($con,$query_drivers,$params_query_drivers,$options_query_drivers);
$ntRes_query_drivers = sqlsrv_num_rows($stmt_query__drivers);
//$i =0;
while($row_query__drivers = sqlsrv_fetch_array($stmt_query__drivers, SQLSRV_FETCH_ASSOC)){

    //$i++;
  if(isset($_REQUEST['get_drivers'])){
  $enabled_class = "enabled";
    if($row_query__drivers['etat']==2){
      $enabled_class = "not_enabled";
    }
  ?>

  	<div class="driver text-center mySlides <?php echo $enabled_class; ?>">
  		<p class="driver_nom"><?php echo $row_query__drivers['nom']/*.$i*/; ?></p>
  		<p class="id_driver" style="display: none;"><?php echo $row_query__drivers['id']; ?></p>
  		<p class="driver_matricule"><?php echo $row_query__drivers['matricule']; ?></p>

  	</div>
  	<?php
  }else if(isset($_REQUEST['get_villes'])){
      //$i++;
    ?>

      <div class="driver text-center mySlides ville">
        <p class="ville_designation"><?php echo $row_query__drivers['DESIGNATION']/*.$i/*.$i*/; ?></p>
        <p class="id_ville" style="display: none;"><?php echo $row_query__drivers['ID']; ?></p>


      </div>
      <?php
  }else if(isset($_REQUEST['get_lieu_chargement'])){
    ?>

      <div class="driver text-center mySlides site_chargement">
        <p class="site_chargement_title"><?php echo $row_query__drivers['DESIGNATION']/*.$i/*.$i*/; ?></p>
        <p class="site_chargement_id" style="display: none;"><?php echo $row_query__drivers['ID_CHG']; ?></p>


      </div>
      <?php
  }else if(isset($_REQUEST['get_clients'])){
    ?>
    <div class="driver text-center mySlides clients">
      <p class="client_name"><?php echo $row_query__drivers['NOM']/*.$i/*.$i*/; ?></p>
      <p class="client_ADRESSE"><?php echo $row_query__drivers['ADRESSE']/*.$i/*.$i*/; ?></p>
      <p class="client_id" style="display: none;"><?php echo $row_query__drivers['ID']; ?></p>


    </div>
    <?php
  }else if(isset($_REQUEST['get_site_d'])){
    ?>

      <div class="driver text-center mySlides site_d_chargement">
        <p class="site_d_chargement_title"><?php echo $row_query__drivers['DESIGNATION']/*.$i/*.$i*/; ?></p>
        <p class="site_d_chargement_id" style="display: none;"><?php echo $row_query__drivers['ID_CHG']; ?></p>


      </div>
      <?php
  }
}
