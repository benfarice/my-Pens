<?php
session_start();
include "../connect.php";
$lang = '../includes/languages/';
include_once $lang.$_SESSION['Lang'].'.php';

$query_today = "select TOP 1  a.id,a.Id_Site_chg,a.lieu_id,
(select l.DESIGNATION from lieu_exploitation l where l.ID=a.lieu_id) as ville,
(select s.DESIGNATION from site_chargement s where s.ID_CHG = a.Id_Site_chg) as site_c,
a.id_chauffeur,(select c.nom from chauffeurs c where c.id = a.id_chauffeur)
as chauffeur,a.heure_depart,a.heurre_fin,a.km_depart,a.kimometrage_fin,
(select isnull(sum(v.nbr_voyages),0) from  voyages v where v.id_activite=a.id) as nbr_voyage,
(select isnull(sum(v.pesee),0) from voyages v where v.id_activite = a.id) as poids_total,
(select isnull(sum(b.litres),0) from carburant b where b.id_activite = a.id) as total_gasoil,
(select isnull(sum(b.prix_u),0) from carburant b where b.id_activite = a.id) as total_prix,
(select isnull(sum(b.kilometrage),0) from carburant b where b.id_activite = a.id) as total_kilometrage
from activite a
where a.id_camion = ".$_REQUEST['id_vehicule']." order by a.id desc";


if(isset($_REQUEST['selected_day'])){
  //echo $_REQUEST['selected_day'];
  $query_today = "select TOP 1  a.id,a.Id_Site_chg,a.lieu_id,
  (select l.DESIGNATION from lieu_exploitation l where l.ID=a.lieu_id) as ville,
  (select s.DESIGNATION from site_chargement s where s.ID_CHG = a.Id_Site_chg) as site_c,
  a.id_chauffeur,(select c.nom from chauffeurs c where c.id = a.id_chauffeur)
  as chauffeur,a.heure_depart,a.heurre_fin,a.km_depart,a.kimometrage_fin,
  (select isnull(sum(v.nbr_voyages),0) from  voyages v where v.id_activite=a.id) as nbr_voyage,
  (select isnull(sum(v.pesee),0) from voyages v where v.id_activite = a.id) as poids_total,
  (select isnull(sum(b.litres),0) from carburant b where b.id_activite = a.id) as total_gasoil,
  (select isnull(sum(b.prix_u),0) from carburant b where b.id_activite = a.id) as total_prix,
  (select isnull(sum(b.kilometrage),0) from carburant b where b.id_activite = a.id) as total_kilometrage
  from activite a
  where a.id_camion = ".$_REQUEST['id_vehicule']."
  and cast(a.journee as date) = convert(date, '".$_REQUEST['selected_day']."',105)
   order by a.id desc";
}

$query_get_clients_data = null;

//echo $query_get_clients_data;
//echo $query_today;
$params_query_today = array();
$options_query_today =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_today=sqlsrv_query($con,$query_today,$params_query_today,$options_query_today)or die( print_r( sqlsrv_errors(), true));
$ntRes_query_today = sqlsrv_num_rows($stmt_query_today);




if($ntRes_query_today > 0){
  $chauffeur = null;
  $Ville = null;
  $Site_du_Chargement = null;
  $litre = null;
  $Prix = null;
  $Kilometrage = null;
  $Km_depart = null;
  $Pesse = null;
  $Nombre_de_voyages = null;
  $KM_Fin = null;
  while($row_query_today = sqlsrv_fetch_array($stmt_query_today, SQLSRV_FETCH_ASSOC)){
    //echo 'yeah';
    $chauffeur = $row_query_today['chauffeur'];
    $Ville = $row_query_today['ville'];
    $Site_du_Chargement = $row_query_today['site_c'];
    $litre = $row_query_today['total_gasoil'];
    $Prix = $row_query_today['total_prix'];
    $Kilometrage = $row_query_today['total_kilometrage'];
    $Km_depart = $row_query_today['km_depart'];
    $Pesse = $row_query_today['poids_total'];
    $Nombre_de_voyages =  $row_query_today['nbr_voyage'];
    $KM_Fin  =  $row_query_today['kimometrage_fin'];
    $id_activite  =  $row_query_today['id'];
    $heurre_fin =  $row_query_today['heurre_fin'];
    $query_get_clients_data = "select d.id_voyages
    ,(select c.NOM from clients c where c.ID=d.ID_CLT) as client
    ,(select c.ADRESSE from clients c where c.ID=d.ID_CLT) as address_client,
    (select sd.DESIGNATION from site_dechargement sd where sd.ID_CHG=d.ID_SITE_DCHG) as site_d
     from detail_voyages d where d.id_voyages in (select v.id from voyages v inner join activite ac
     on ac.id= v.id_activite where ac.id_camion=".$_REQUEST['id_vehicule']."
      and v.id_activite = $id_activite )";
if(isset($_REQUEST['selected_day'])){
  ?>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Date : <?php echo $_REQUEST['selected_day']; ?>
  </div>
<?php } ?>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    chauffeur : <?php echo $chauffeur; ?>
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Ville : <?php echo $Ville; ?>
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Site du Chargement : <?php echo $Site_du_Chargement; ?>
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    KM Départ : <?php echo $Km_depart ; ?> KM
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    litre : <?php echo $litre ; ?> L
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Prix : <?php echo $Prix ; ?>
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Kilométrage : <?php echo $Kilometrage ; ?> KM
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Pesse : <?php echo $Pesse ; ?> KG
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    Nombre de voyages : <?php echo $Nombre_de_voyages ; ?>
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    heure de clôture : <?php
    if(is_null($heurre_fin)){
      echo "---- ----";
    }else{
      if(is_a($heurre_fin, 'DateTime')){
      	echo $heurre_fin->format('H:i');
      }else{
        echo $heurre_fin;
       }
      //echo $heurre_fin ;
    }
    //echo  ;
    ?>
  </div>
  <div class="col-md-3 col-sm-3 col-xs-3 label_camion">
    KM Fin : <?php
    if(is_null($KM_Fin)){
      echo "---- ----";
    }else{
      echo $KM_Fin ;
    }

     ?>
  </div>
  <?php
  $params__get_clients_data = array();
  $options_get_clients_data =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt__get_clients_data=sqlsrv_query($con,$query_get_clients_data,$params__get_clients_data,$options_get_clients_data)or die( print_r( sqlsrv_errors(), true));
  $ntRes__get_clients_data = sqlsrv_num_rows($stmt__get_clients_data);
  if($ntRes__get_clients_data>0){
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12" id="table_container">
    <table class="table">
    <thead>
      <tr>
        <!--<th scope="col">ID Voyage</th>-->
        <th scope="col">Client</th>
        <th scope="col">Adresse</th>
        <th scope="col">Site du Déchargement</th>
      </tr>
    </thead>
    <tbody>
    <?php   while($row_query_clients_data = sqlsrv_fetch_array($stmt__get_clients_data, SQLSRV_FETCH_ASSOC)){ ?>
      <tr>
        <!--<th scope="row"><?php //echo $row_query_clients_data['id_voyages']; ?></th>-->
        <td><?php echo $row_query_clients_data['client']; ?></td>
        <td><?php echo $row_query_clients_data['address_client']; ?></td>
        <td><?php echo $row_query_clients_data['site_d']; ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
    <?php
  }else{

    for($i=0;$i<5;$i++)
    echo '<br>';
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-warning" role="alert" style="margin:15px">
    <?php echo lang('no_clients_data'); ?>
    </div>
    </div>
    <?php
    for($i=0;$i<5;$i++)
    echo '<br>';

  }
}
}else{
  for($i=0;$i<5;$i++)
  echo '<br>';
  ?>
  <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="alert alert-warning" role="alert" style="margin:15px">
  <?php echo lang('no_data'); ?>
  </div>
  </div>
  <?php
  for($i=0;$i<5;$i++)
  echo '<br>';
}
