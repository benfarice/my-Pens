<?php
session_start();
include "../connect.php";


if(isset($_REQUEST['insert_detail_voyages'])){
  $_query_get_activite_id = "select top 1 v.id from voyages v inner join
  activite a on a.id = v.id_activite
  where a.id_chauffeur = ".$_REQUEST['id_driver']."
  and a.id_camion = ".$_REQUEST['id_vehicule']." order by v.id desc";
  //echo $_query_get_activite_id;
  //*******************************************

  $params_query_get_activite_id = array();
  $options_query_get_activite_id =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt_query_get_activite_id = sqlsrv_query($con,$_query_get_activite_id,$params_query_get_activite_id,
  $options_query_get_activite_id);
  $ntRes_query_get_activite_id = sqlsrv_num_rows($stmt_query_get_activite_id);
  $id_activite = null;
  if($ntRes_query_get_activite_id>0){
    while($row_query_get_activite_id = sqlsrv_fetch_array($stmt_query_get_activite_id, SQLSRV_FETCH_ASSOC)){
      $id_activite = $row_query_get_activite_id['id'];
    }
    $les_clients_selected = $_REQUEST['les_clients_selected'];
    $les_site_dechargement_selected = $_REQUEST['les_site_dechargement_selected'];
    for ($i=0; $i < count($les_clients_selected); $i++) {
      //$query_insert = "insert into detail_activite(ID_ACTIVITE,ID_CLT,ID_SITE_DCHG)
      //values ($id_activite,".$les_clients_selected[$i].",".$les_site_dechargement_selected[$i].")";
      $query_insert = "insert into detail_voyages(id_voyages,ID_CLT,ID_SITE_DCHG,user_app,date_insert)
      values ($id_activite,".$les_clients_selected[$i].",".$les_site_dechargement_selected[$i]
      .",'".$_SESSION['username_camion']."','".date('Y-m-d')."')";
      echo $query_insert;
      $result_query_insert = sqlsrv_query($con,$query_insert) or die("error");
    }
  }
}


if(isset($_REQUEST['insert_activite'])){
  $query_insert_activite = "insert into activite(journee,id_chauffeur,id_camion,heure_depart
  ,km_depart,lieu_id,Id_Site_chg,user_app,date_insert)
  values('".date('Y-m-d')."',".$_REQUEST['id_driver'].",".$_REQUEST['id_vehicule'].",'".date('H:i:s')."',".$_REQUEST['km_depart'].",".$_REQUEST['ville_id'].",".$_REQUEST['site_c_id'].",'".$_SESSION['username_camion']."','". date('Y-m-d') . "')";
  //echo $query_insert_activite;
  $result_insert_activite = sqlsrv_query($con,$query_insert_activite) or die("error");
  $update_camion_etat = "update camions set etat = 2 where id = ".$_REQUEST['id_vehicule'];
  $result_update_camion_etat = sqlsrv_query($con,$update_camion_etat) or die("error");;
  $update_driver_etat = "update chauffeurs set etat = 2 where id=".$_REQUEST['id_driver'];
  $result_update_driver_etat = sqlsrv_query($con,$update_driver_etat) or die("error");;
}


if(isset($_REQUEST['insert_voyage'])){
  /*
  $_query_get_activite_id = "select top 1 a.id from activite a
  where a.id_chauffeur = ".$_REQUEST['id_driver']."
  and a.id_camion = ".$_REQUEST['id_vehicule']."
  and cast(a.journee as date) = '".date('d-m-Y')."'";
  */
  $_query_get_activite_id = "select top 1 a.id from activite a
  where a.id_chauffeur = ".$_REQUEST['id_driver']."
  and a.id_camion = ".$_REQUEST['id_vehicule']." order by a.id desc";

  //*******************************************

  $params_query_get_activite_id = array();
  $options_query_get_activite_id =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt_query_get_activite_id = sqlsrv_query($con,$_query_get_activite_id,$params_query_get_activite_id,
  $options_query_get_activite_id);
  $ntRes_query_get_activite_id = sqlsrv_num_rows($stmt_query_get_activite_id);
  $id_activite = null;
  if($ntRes_query_get_activite_id>0){
    while($row_query_get_activite_id = sqlsrv_fetch_array($stmt_query_get_activite_id, SQLSRV_FETCH_ASSOC)){
      $id_activite = $row_query_get_activite_id['id'];
    }
    $query_insert_voyage = "insert into voyages(pesee,nbr_voyages,id_activite,heure,user_app,date_insert)
    values(".$_REQUEST['poids_t_inset'].",".$_REQUEST['nbr_v_inset'].",$id_activite,'".date('H:i:s')."','".$_SESSION['username_camion']."','". date('Y-m-d') . "')";
    //echo $query_insert_voyage;
    $result_insert__voyage = sqlsrv_query($con,$query_insert_voyage) or die("error"); ;
    $update_camion_etat = "update camions set etat = 3 where id = ".$_REQUEST['id_vehicule'];
    $result_update_camion_etat = sqlsrv_query($con,$update_camion_etat) or die("error");;
  }
}

if(isset($_REQUEST['insert_carburant'])){
  /*
  $_query_get_activite_id = "select top 1 a.id from activite a
  where a.id_chauffeur = ".$_REQUEST['id_driver']."
  and a.id_camion = ".$_REQUEST['id_vehicule']."
  and cast(a.journee as date) = '".date('d-m-Y')."'";
*/
$_query_get_activite_id = "select top 1 a.id from activite a
where a.id_chauffeur = ".$_REQUEST['id_driver']."
and a.id_camion = ".$_REQUEST['id_vehicule']." order by a.id desc";

  $params_query_get_activite_id = array();
  $options_query_get_activite_id =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt_query_get_activite_id = sqlsrv_query($con,$_query_get_activite_id,$params_query_get_activite_id,
  $options_query_get_activite_id);
  $ntRes_query_get_activite_id = sqlsrv_num_rows($stmt_query_get_activite_id);
  $id_activite = null;
  if($ntRes_query_get_activite_id>0){
    while($row_query_get_activite_id = sqlsrv_fetch_array($stmt_query_get_activite_id, SQLSRV_FETCH_ASSOC)){
      $id_activite = $row_query_get_activite_id['id'];
    }
    $total = $_REQUEST['prix_u_insert']*$_REQUEST['litres_insert'];
    $query_insert_carburant = "insert into carburant(id_activite,kilometrage,total,prix_u,litres,user_app,date_insert)
    values ($id_activite,".$_REQUEST['kilometrage_carburant_insert'].",$total,".$_REQUEST['prix_u_insert'].",".$_REQUEST['litres_insert'].",'".$_SESSION['username_camion']."','". date('Y-m-d') . "')";
    //echo $query_insert_carburant;
    $result_insert__carburant = sqlsrv_query($con,$query_insert_carburant) or die("error"); ;
  }
}

if(isset($_REQUEST['fin_activite'])){
/*
  $_query_get_activite_id = "select top 1 a.id from activite a
  where a.id_chauffeur = ".$_REQUEST['id_driver']."
  and a.id_camion = ".$_REQUEST['id_vehicule']."
  and cast(a.journee as date) = '".date('d-m-Y')."'";
*/

  $_query_get_activite_id = "select top 1 a.id from activite a
  where a.id_chauffeur = ".$_REQUEST['id_driver']."
  and a.id_camion = ".$_REQUEST['id_vehicule']." order by a.id desc";

  $params_query_get_activite_id = array();
  $options_query_get_activite_id =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
  $stmt_query_get_activite_id = sqlsrv_query($con,$_query_get_activite_id,$params_query_get_activite_id,
  $options_query_get_activite_id);
  $ntRes_query_get_activite_id = sqlsrv_num_rows($stmt_query_get_activite_id);
  $id_activite = null;
  if($ntRes_query_get_activite_id>0){
    while($row_query_get_activite_id = sqlsrv_fetch_array($stmt_query_get_activite_id, SQLSRV_FETCH_ASSOC)){
      $id_activite = $row_query_get_activite_id['id'];
    }
    $heur_d = date('H:i:s');
    if($_REQUEST['fin_heur'] != 'now'){
      $heur_d = $_REQUEST['fin_heur'];
    }
    $query_update_ativite = "update activite set
    kimometrage_fin = ".$_REQUEST['km_fin']." ,heurre_fin ='".$heur_d."'
     ,etat = 2 where id = $id_activite";
    //echo $query_update_ativite;
    $result_update_ativite = sqlsrv_query($con,$query_update_ativite) or die("error");
    $update_camion_etat = "update camions set etat = 1 where id = ".$_REQUEST['id_vehicule'];
    $result_update_camion_etat = sqlsrv_query($con,$update_camion_etat) or die("error");
    $update_driver_etat = "update chauffeurs set etat = 1 where id=".$_REQUEST['id_driver'];
    $result_update_driver_etat = sqlsrv_query($con,$update_driver_etat) or die("error");
  }
}









/*
$query_yesterday = "select TOP 1  a.id,a.id_chauffeur,(select c.nom from chauffeurs c where c.id = a.id_chauffeur)
as chauffeur,a.heure_depart,a.heurre_fin,a.km_depart,a.kimometrage_fin,
(select isnull(sum(v.nbr_voyages),0) from  voyages v where v.id_activite=a.id) as nbr_voyage,
(select isnull(sum(v.pesee),0) from voyages v where v.id_activite = a.id) as poids_total,
(select isnull(sum(b.litres),0) from carburant b where b.id_activite = a.id) as total_gasoil
from activite a
where a.etat = 1 and a.id_camion = ".$_REQUEST['id_vehicule']." and
cast(a.journee as date)='".date('d-m-Y')."'";
*/
$query_yesterday = "select TOP 1  a.id,a.Id_Site_chg,a.lieu_id,
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
where a.etat = 1 and a.id_camion = ".$_REQUEST['id_vehicule']." order by a.id desc";

//echo $query_yesterday;

$driver = null;
$heur_debut = null;
$heur_f = null;
$km_d = null;
$km_f = null;
$nbr_voyage = null;
$poids_total = null;
$total_gasoil = null;
$id_driver = null ;
$id_ville = null;
$id_lieu_c = null;
$ville_title = null;
$lieu_c_title=null;
$total_prix = null;
$total_kilometrage = null;

$params_query_yesterday = array();
$options_query_yesterday =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_query_yesterday=sqlsrv_query($con,$query_yesterday,$params_query_yesterday,$options_query_yesterday)or die( print_r( sqlsrv_errors(), true));
$ntRes_query_yesterday = sqlsrv_num_rows($stmt_query_yesterday);

while($row_query_yesterday = sqlsrv_fetch_array($stmt_query_yesterday, SQLSRV_FETCH_ASSOC)){
  $driver = $row_query_yesterday['chauffeur'];
  $heur_debut = $row_query_yesterday['heure_depart'];
  $heur_f = $row_query_yesterday['heurre_fin'];
  $km_d = $row_query_yesterday['km_depart'];
  $km_f = $row_query_yesterday['kimometrage_fin'];
  $nbr_voyage = $row_query_yesterday['nbr_voyage'];
  $poids_total = $row_query_yesterday['poids_total'];
  $total_gasoil = $row_query_yesterday['total_gasoil'];
  $id_driver = $row_query_yesterday['id_chauffeur'];
  $id_ville = $row_query_yesterday['lieu_id'];
  $id_lieu_c = $row_query_yesterday['Id_Site_chg'];
  $ville_title =  $row_query_yesterday['ville'];
  $lieu_c_title =  $row_query_yesterday['site_c'];
  $total_prix = $row_query_yesterday['total_prix'];
  $total_kilometrage = $row_query_yesterday['total_kilometrage'];
}

if($id_driver == null){
  $id_driver = '0';
}
if($total_prix == null){
  $total_prix = '0';
}
if($total_kilometrage == null){
  $total_kilometrage = '0';
}
if($ville_title == null){
  $ville_title = '0';
}
if($lieu_c_title == null){
  $lieu_c_title = '0';
}
if($id_ville == null){
  $id_ville = '0';
}
if($id_lieu_c == null){
  $id_lieu_c = '0';
}
if($driver == null){
  $driver = '---- : ----';
}

if($heur_debut==null){
  $heur_debut = '---- : ----';
}

if($heur_f == null){
  $heur_f = '---- : ----';
}

if($km_d == null){
  $km_d = '---- : ----';
}

if($km_f == null){
  $km_f = '---- : ----';
}

if($nbr_voyage == null){
  $nbr_voyage = '---- : ----';
}

if($poids_total == null){
  $poids_total = '---- : ----';
}

if($total_gasoil == null){
  $total_gasoil =  '---- : ----';
}



?>

									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 t_background text-center">
											<h2>
											   Activité Du Jour
											</h2>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>
												<svg width="60px" height="60px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
														 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
													<circle style="fill:#FF6262;" cx="256" cy="256" r="256"/>
													<path style="fill:#FF0F27;" d="M220.738,380.195l110.787,120.475c99.09-30.553,172.492-119.643,179.852-226.892L365.623,110.685
														L220.738,380.195z"/>
													<rect x="213.333" y="292.219" style="fill:#FCD088;" width="85.333" height="93.522"/>
													<rect x="256.293" y="292.219" style="fill:#DDAB62;" width="42.379" height="93.522"/>
													<path style="fill:#172956;" d="M360.867,358.541l-46.599-9.319l-8.235-12.021c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985
														c-19.361,19.328-50.731,19.285-70.039-0.098l-7.839-7.868c-2.489-1.084-5.398-0.271-6.961,1.95l-8.499,12.076l-46.599,9.319
														c-23.723,4.748-40.801,25.579-40.801,49.774v58.206C151.681,495.187,201.873,512,256,512s104.319-16.813,145.67-45.48v-58.206
														C401.67,384.119,384.591,363.287,360.867,358.541z"/>
													<path style="fill:#121149;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-8.235-12.021
														c-1.541-2.25-4.458-3.098-6.966-2.026l-7.999,7.985c-9.614,9.599-22.19,14.414-34.78,14.462v154.374
														c54.017-0.06,104.103-16.862,145.382-45.477V408.314z"/>
													<path style="fill:#6D4711;" d="M314.268,349.222l46.599,9.319c23.724,4.746,40.803,25.578,40.803,49.773v58.206
														C360.319,495.187,310.127,512,256,512s-104.319-16.813-145.67-45.48v-58.206c0-24.195,17.079-45.027,40.803-49.773l46.599-9.319
														l58.125,153.728L314.268,349.222z"/>
													<path style="fill:#56340C;" d="M401.67,408.314c0-24.195-17.079-45.027-40.803-49.773l-46.599-9.319l-57.98,152.593v10.181
														c54.017-0.06,104.103-16.862,145.382-45.477V408.314z"/>
													<path style="fill:#FFEDB5;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.974-41.293-97.728-92.229-97.728
														s-92.229,43.753-92.229,97.728c0,4.08,0.24,8.264,0.698,12.509c-8.945,2.326-13.202,16.087-9.506,30.817
														c3.708,14.776,14.014,24.921,23.018,22.661c0.821-0.205,1.586-0.538,2.326-0.927c16.667,33.452,44.351,60.594,75.693,60.594
														s59.027-27.139,75.693-60.594c0.74,0.39,1.505,0.722,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661
														C360.732,221.527,356.476,207.763,347.531,205.44z"/>
													<path style="fill:#E8CF89;" d="M347.531,205.44c0.459-4.244,0.698-8.428,0.698-12.509c0-53.872-41.138-97.554-91.941-97.721v223.365
														c31.223-0.167,58.79-27.236,75.405-60.585c0.74,0.39,1.505,0.721,2.326,0.927c9.004,2.26,19.309-7.885,23.018-22.661
														C360.732,221.527,356.476,207.763,347.531,205.44z"/>
													<path style="fill:#494948;" d="M161.127,181.119c0,0,1.724,24.442,6.034,39.253l8.045-0.371c0,0-2.012-19.997,14.941-40.365
														S161.127,181.119,161.127,181.119z"/>
													<path style="fill:#FEE187;" d="M378.397,140.802c0,31.194-54.936,42.894-122.534,42.894s-122.261-11.7-122.261-42.894
														S188.402,59.864,256,59.864S378.397,109.608,378.397,140.802z"/>
													<path style="fill:#FFC61B;" d="M378.397,140.802c0-31.149-54.644-80.789-122.109-80.93v123.823
														C323.691,183.644,378.397,171.929,378.397,140.802z"/>
													<path style="fill:#333333;" d="M350.873,181.119c0,0-1.724,24.442-6.034,39.253l-8.045-0.371c0,0,2.012-19.997-14.941-40.365
														S350.873,181.119,350.873,181.119z"/>
													<path style="fill:#172956;" d="M256,210.161c37.505,0,71.494-9.485,96.308-24.855c1.898-1.176,1.076-4.099-1.157-18.041H160.849
														c-2.232,13.941-3.055,16.865-1.157,18.041C184.508,200.676,218.495,210.161,256,210.161z"/>
													<path style="fill:#121149;" d="M351.151,167.265h-94.863v42.893c37.392-0.048,71.27-9.519,96.02-24.852
														C354.206,184.13,353.383,181.207,351.151,167.265z"/>
													<path style="fill:#6D4711;" d="M350.899,185.792c-62.828-8.919-126.561-8.938-189.392-0.057c-1.46,0.207-2.808-0.805-3.013-2.265
														c-0.938-6.728-1.874-13.455-2.812-20.183c-0.305-2.189,1.221-4.224,3.41-4.529c64.307-8.975,129.508-8.975,193.815,0
														c2.189,0.305,3.715,2.339,3.41,4.529c-0.957,6.863-1.912,13.726-2.869,20.589C353.278,185.11,352.135,185.966,350.899,185.792z"/>
													<path style="fill:#56340C;" d="M352.908,158.756c-32.058-4.474-64.338-6.715-96.62-6.728v27.062
														c31.613,0.014,63.224,2.248,94.61,6.703c1.236,0.176,2.379-0.681,2.551-1.919c0.957-6.863,1.912-13.726,2.869-20.589
														C356.624,161.096,355.097,159.061,352.908,158.756z"/>
													<path style="fill:#494948;" d="M273.984,270.6c-12.045-5.847-15.736-3.277-17.984-0.398c-2.248-2.879-5.939-5.449-17.984,0.398
														c-12.317,5.98-28.562-0.912-28.562-0.912s8.675,12.622,29.915,11.047c9.099-0.674,14.686-3.463,16.631-6.296
														c1.945,2.832,7.532,5.622,16.631,6.296c21.242,1.574,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z"/>
													<path style="fill:#333333;" d="M273.984,270.6c-11.552-5.608-15.415-3.472-17.696-0.748v4.925c2.146,2.713,7.608,5.31,16.343,5.958
														c21.242,1.576,29.915-11.047,29.915-11.047S286.303,276.58,273.984,270.6z"/>

														<rect x="220.729" y="105.831" style="fill:#494948;" width="14.288" height="14.288"/>
														<rect x="276.98" y="105.831" style="fill:#494948;" width="14.288" height="14.288"/>
														<rect x="234.882" y="119.967" style="fill:#494948;" width="14.288" height="14.288"/>
														<rect x="249.001" y="105.831" style="fill:#494948;" width="14.288" height="14.288"/>
														<rect x="263.154" y="119.967" style="fill:#494948;" width="14.288" height="14.288"/>

													</svg>
										</p>
										</div>

										<div class="col-md-4 col-sm-4 col-xs-4">
										<p id="driver_selected_p"><?php echo $driver; ?></p>
                    <input type="hidden" id="id_driver_today" value="<?php echo $id_driver; ?>">
                    <input type="hidden" id="id_ville_today" name="" value="<?php echo $id_ville; ?>">
                    <input type="hidden" id="id_site_c_today" name="" value="<?php echo $id_lieu_c; ?>">
                    <input type="hidden" id="title_site_c_today" name="" value="<?php echo $lieu_c_title; ?>">
                    <input type="hidden" id="ville_title_c_today" name="" value="<?php echo $ville_title; ?>">
                    <input type="hidden" id="total_prix_today" name="" value="<?php echo $total_prix; ?>">
                    <input type="hidden" id="total_kilometrage_today" name="" value="<?php echo $total_kilometrage; ?>">
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>
											<svg width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 236.91 236.91" style="enable-background:new 0 0 236.91 236.91;" xml:space="preserve" width="512px" height="512px">

											<path d="M202.842,90.894c-0.193-0.249-0.398-0.492-0.626-0.721c-0.229-0.228-0.472-0.433-0.72-0.626   c-21.391-21.053-50.716-34.066-83.028-34.069l-0.013-0.001l-0.013,0.001C86.13,55.481,56.805,68.494,35.415,89.547   c-0.249,0.193-0.492,0.397-0.72,0.626c-0.229,0.229-0.433,0.472-0.626,0.721C13.013,112.287,0,141.617,0,173.933   c0,4.142,3.357,7.5,7.5,7.5h221.91c4.143,0,7.5-3.358,7.5-7.5C236.91,141.617,223.897,112.287,202.842,90.894z M148.661,166.433   h-5.059c-1.445-4.851-4.255-9.111-7.984-12.341l2.214-4.49C143.105,153.81,146.985,159.689,148.661,166.433z M121.932,147.929   c-1.138-0.151-2.297-0.236-3.477-0.236c-11.885,0-21.919,7.904-25.147,18.741H88.25c3.366-13.551,15.629-23.625,30.206-23.625   c1.96,0,3.876,0.191,5.737,0.539L121.932,147.929z M158.882,166.433c-1.972-10.653-8.06-19.874-16.551-25.952l17.936-36.363   c1.832-3.715,0.306-8.212-3.409-10.044c-3.713-1.833-8.211-0.307-10.044,3.409l-18.067,36.63   c-3.291-0.851-6.739-1.304-10.292-1.304c-20.115,0-36.891,14.519-40.427,33.625H15.269c1.647-22.895,10.783-43.746,24.958-60.122   l5.117,5.117c1.465,1.464,3.385,2.197,5.304,2.197s3.839-0.732,5.304-2.197c2.929-2.929,2.929-7.678,0-10.606l-5.118-5.118   c16.375-14.174,37.227-23.308,60.122-24.956v8.541c0,4.142,3.357,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-8.541   c22.895,1.648,43.746,10.782,60.122,24.956l-5.118,5.118c-2.929,2.929-2.929,7.678,0,10.606c1.465,1.464,3.385,2.197,5.304,2.197   s3.839-0.732,5.304-2.197l5.117-5.117c14.175,16.376,23.311,37.227,24.958,60.122H158.882z" fill="#337a57"/>

											</svg>
										</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
										<p id="km_depart_p"><?php echo $km_d.' KM'; ?></p>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>

										<svg width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 28.808 28.808" style="enable-background:new 0 0 28.808 28.808;" xml:space="preserve" width="512px" height="512px">

											<path d="M21.473,6.081c1.105,0,2-0.889,2-1.994V1.998c0-1.106-0.895-1.998-2-1.998   c-1.104,0-1.996,0.892-1.996,1.998v2.089C19.477,5.192,20.369,6.081,21.473,6.081z" fill="#10591b"/>
											<path d="M28.572,3.457h-4.021v1.017c0,1.676-1.363,3.043-3.043,3.043c-1.682,0-3.041-1.367-3.041-3.043   V3.457H10.35v1.017c0,1.676-1.363,3.043-3.043,3.043S4.266,6.149,4.266,4.473V3.457L0.207,3.406v25.402h2.029h24.34l2.025-0.006   L28.572,3.457z M26.576,26.785H2.236V10.557h24.34V26.785z" fill="#10591b"/>
											<path d="M7.275,6.081c1.105,0,1.998-0.889,1.998-1.994V1.998C9.273,0.892,8.381,0,7.275,0   C6.172,0,5.279,0.892,5.279,1.998v2.089C5.279,5.192,6.172,6.081,7.275,6.081z" fill="#10591b"/>
											<rect x="10.357" y="12.735" width="3.195" height="2.821" fill="#10591b"/>
											<rect x="15.525" y="12.735" width="3.199" height="2.821" fill="#10591b"/>
											<rect x="20.318" y="12.735" width="3.195" height="2.821" fill="#10591b"/>
											<rect x="10.357" y="17.42" width="3.195" height="2.819" fill="#10591b"/>
											<rect x="15.525" y="17.42" width="3.199" height="2.819" fill="#10591b"/>
											<rect x="20.318" y="17.42" width="3.195" height="2.819" fill="#10591b"/>
											<rect x="10.357" y="22.198" width="3.195" height="2.819" fill="#10591b"/>
											<rect x="5.279" y="17.42" width="3.193" height="2.819" fill="#10591b"/>
											<rect x="5.279" y="22.198" width="3.193" height="2.819" fill="#10591b"/>
											<rect x="15.525" y="22.198" width="3.199" height="2.819" fill="#10591b"/>
											<rect x="20.318" y="22.198" width="3.195" height="2.819" fill="#10591b"/>

										</svg>
										</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
										<p><?php //echo $heur_debut; ?>
                    <?php
                    if(is_a($heur_debut, 'DateTime')){
                   	echo $heur_debut->format('H:i:s');
                   }else{
                   	echo $heur_debut;
                   	}

                     ?>

                    </p>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>
											<svg width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 236.91 236.91" style="enable-background:new 0 0 236.91 236.91;" xml:space="preserve" width="512px" height="512px">

												<path d="M202.842,90.894c-0.193-0.249-0.398-0.492-0.626-0.721c-0.229-0.228-0.472-0.433-0.72-0.626   c-21.391-21.053-50.716-34.066-83.028-34.069c-0.004,0-0.008-0.001-0.008-0.001c-0.009,0-0.013,0.001-0.017,0.001   C86.13,55.481,56.805,68.494,35.415,89.547c-0.249,0.193-0.492,0.397-0.72,0.626c-0.229,0.229-0.433,0.472-0.626,0.721   C13.013,112.287,0,141.617,0,173.933c0,4.142,3.357,7.5,7.5,7.5h221.91c4.143,0,7.5-3.358,7.5-7.5   C236.91,141.617,223.897,112.287,202.842,90.894z M118.455,147.692c-7.354,0-13.997,3.029-18.761,7.902l-4.723-2.054   c5.711-6.568,14.118-10.732,23.484-10.732c14.577,0,26.84,10.074,30.206,23.625h-5.059   C140.375,155.596,130.34,147.692,118.455,147.692z M158.882,166.433c-3.537-19.106-20.313-33.625-40.427-33.625   c-13.492,0-25.486,6.532-32.989,16.598l-42.477-18.475c-3.8-1.654-8.218,0.088-9.869,3.886s0.088,8.217,3.887,9.869l41.843,18.2   c-0.325,1.163-0.599,2.346-0.822,3.548H15.269c1.647-22.895,10.783-43.746,24.958-60.122l5.117,5.117   c1.465,1.464,3.385,2.197,5.304,2.197s3.839-0.732,5.304-2.197c2.929-2.929,2.929-7.678,0-10.606l-5.118-5.118   c16.375-14.174,37.227-23.308,60.122-24.956v8.541c0,4.142,3.357,7.5,7.5,7.5s7.5-3.358,7.5-7.5v-8.541   c22.895,1.648,43.746,10.782,60.122,24.956l-5.118,5.118c-2.929,2.929-2.929,7.678,0,10.606c1.465,1.464,3.385,2.197,5.304,2.197   s3.839-0.732,5.304-2.197l5.117-5.117c14.175,16.376,23.311,37.227,24.958,60.122H158.882z" fill="#D80027"/>

											</svg>

											</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<p id="km_f_p_show"><?php echo $km_f.' KM'; ?></p>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>
											<svg width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 28.808 28.808" style="enable-background:new 0 0 28.808 28.808;" xml:space="preserve" width="512px" height="512px">

											<path d="M21.473,6.081c1.105,0,2-0.889,2-1.994V1.998c0-1.106-0.895-1.998-2-1.998   c-1.104,0-1.996,0.892-1.996,1.998v2.089C19.477,5.192,20.369,6.081,21.473,6.081z" fill="#D80027"/>
											<path d="M28.572,3.457h-4.021v1.017c0,1.676-1.363,3.043-3.043,3.043c-1.682,0-3.041-1.367-3.041-3.043   V3.457H10.35v1.017c0,1.676-1.363,3.043-3.043,3.043S4.266,6.149,4.266,4.473V3.457L0.207,3.406v25.402h2.029h24.34l2.025-0.006   L28.572,3.457z M26.576,26.785H2.236V10.557h24.34V26.785z" fill="#D80027"/>
											<path d="M7.275,6.081c1.105,0,1.998-0.889,1.998-1.994V1.998C9.273,0.892,8.381,0,7.275,0   C6.172,0,5.279,0.892,5.279,1.998v2.089C5.279,5.192,6.172,6.081,7.275,6.081z" fill="#D80027"/>
											<rect x="10.357" y="12.735" width="3.195" height="2.821" fill="#D80027"/>
											<rect x="15.525" y="12.735" width="3.199" height="2.821" fill="#D80027"/>
											<rect x="20.318" y="12.735" width="3.195" height="2.821" fill="#D80027"/>
											<rect x="10.357" y="17.42" width="3.195" height="2.819" fill="#D80027"/>
											<rect x="15.525" y="17.42" width="3.199" height="2.819" fill="#D80027"/>
											<rect x="20.318" y="17.42" width="3.195" height="2.819" fill="#D80027"/>
											<rect x="10.357" y="22.198" width="3.195" height="2.819" fill="#D80027"/>
											<rect x="5.279" y="17.42" width="3.193" height="2.819" fill="#D80027"/>
											<rect x="5.279" y="22.198" width="3.193" height="2.819" fill="#D80027"/>
											<rect x="15.525" y="22.198" width="3.199" height="2.819" fill="#D80027"/>
											<rect x="20.318" y="22.198" width="3.195" height="2.819" fill="#D80027"/>

										</svg>

										</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<p>

                        <?php //echo $heur_f;

                      if(is_a($heur_f, 'DateTime')){
                      echo $heur_f->format('H:i:s');
                      }else{
                      echo $heur_f;
                      }
                       ?></p>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>
											<svg width="60px" height="60px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
										<rect x="30.923" y="473.98" style="fill:#B0BEC5;" width="304.158" height="30.416"/>
										<g>
											<rect x="61.339" y="210.376" style="fill:#FDD844;" width="243.327" height="263.604"/>
											<rect x="41.061" y="7.604" style="fill:#FDD844;" width="283.881" height="202.772"/>
										</g>
										<rect x="71.477" y="38.02" style="fill:#80D8FF;" width="223.05" height="141.941"/>
										<rect x="435.453" y="232.681" style="fill:#B0BEC5;" width="30.416" height="30.416"/>
										<polygon style="fill:#81C784;" points="450.661,141.434 450.661,161.711 420.246,181.988 420.246,232.681 481.077,232.681
											481.077,141.434 "/>
										<rect x="223.556" y="249.917" style="fill:#B0BEC5;" width="50.693" height="81.109"/>
										<polygon style="fill:#90A4AE;" points="223.556,249.917 223.556,270.194 253.972,270.194 253.972,331.026 274.25,331.026
											274.25,249.917 "/>
										<polygon style="fill:#40C4FF;" points="71.477,38.02 71.477,58.297 274.25,58.297 274.25,179.96 294.527,179.96 294.527,38.02 "/>
										<g>
											<rect x="61.339" y="210.376" style="fill:#FDAB08;" width="243.327" height="20.277"/>
											<rect x="61.339" y="453.703" style="fill:#FDAB08;" width="243.327" height="20.277"/>
										</g>
										<rect x="420.246" y="212.404" style="fill:#4CAF50;" width="60.832" height="20.277"/>
										<path d="M63.873,187.564h238.257V30.416H63.873V187.564z M79.081,45.624h207.842v126.733H79.081V45.624z"/>
										<rect x="96.824" y="139.913" width="20.277" height="15.208"/>
										<rect x="248.903" y="139.913" width="20.277" height="15.208"/>
										<rect x="238.703" y="101.886" transform="matrix(-0.866 0.5 -0.5 -0.866 519.0898 79.8867)" width="20.277" height="15.208"/>
										<rect x="213.425" y="71.532" transform="matrix(0.866 0.5 -0.5 0.866 70.4497 -99.5746)" width="15.208" height="20.277"/>
										<rect x="175.398" y="61.339" width="15.208" height="20.277"/>
										<rect x="137.371" y="71.524" transform="matrix(-0.866 0.5 -0.5 -0.866 311.3586 79.8952)" width="15.208" height="20.277"/>
										<rect x="107.017" y="101.89" transform="matrix(0.866 0.5 -0.5 0.866 70.4448 -43.9089)" width="20.277" height="15.208"/>
										<rect x="144.977" y="117.97" transform="matrix(0.5 0.866 -0.866 0.5 193.9088 -84.7172)" width="50.692" height="15.208"/>
										<path d="M488.681,133.83h-15.208V84.883l-30.452-22.556l-9.053,12.22l24.297,17.997v41.286h-15.208v23.812l-30.416,20.277v62.367
											h15.208v30.416h15.208v159.683c0,12.019-9.779,21.798-21.798,21.798c-12.019,0-21.798-9.779-21.798-21.798V317.846
											c0-20.405-16.601-37.006-37.006-37.006h-50.186V217.98h20.277V0H33.457v217.98h20.277v248.396H23.319V512h319.366v-45.624h-30.416
											V296.048h50.186c12.019,0,21.798,9.779,21.798,21.798v112.539c0,20.405,16.601,37.006,37.006,37.006
											c20.405,0,37.006-16.601,37.006-37.006V270.701h15.208v-30.416h15.208V133.83z M48.665,15.208h268.673v187.564H48.665V15.208z
											 M327.477,496.792H38.527v-15.208h288.951V496.792z M68.943,466.376V217.98h228.119v248.396H68.943z M458.265,255.493h-15.208
											v-15.208h15.208V255.493z M473.473,225.077H427.85v-39.019l30.416-20.277v-16.743h15.208V225.077z"/>
										<path d="M215.952,242.313v96.317h65.901v-96.317H215.952z M266.646,323.422H231.16v-65.901h35.485V323.422z"/>

											<path style="fill:#FFFFFF;" d="M221.022,451.168h-15.208V435.96h15.208V451.168z M190.606,451.168h-15.208V435.96h15.208V451.168z
												 M160.19,451.168h-15.208V435.96h15.208V451.168z"/>
											<path style="fill:#FFFFFF;" d="M99.358,444.578H84.15V429.37h15.208V444.578z M99.358,414.162H84.15v-30.416h15.208V414.162z"/>
											<path style="fill:#FFFFFF;" d="M281.853,414.162h-15.208v-15.208h15.208V414.162z M281.853,383.747h-15.208v-30.416h15.208V383.747
												z"/>
											<path style="fill:#FFFFFF;" d="M190.606,363.469h-15.208v-15.208h15.208V363.469z M190.606,333.053h-15.208v-30.416h15.208V333.053
												z"/>
											<path style="fill:#FFFFFF;" d="M99.358,301.624H84.15v-15.208h15.208V301.624z M99.358,271.208H84.15v-38.02h38.02v15.208H99.358
												V271.208z M152.586,248.396h-15.208v-15.208h15.208V248.396z"/>
											<polygon style="fill:#FFFFFF;" points="109.497,90.741 94.289,90.741 94.289,60.832 124.198,60.832 124.198,76.04 109.497,76.04
												"/>

										</svg>
										</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<p id="total_gasoil_p_show"><?php echo $total_gasoil.' L'; ?></p>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
										<p>
											<svg width="60px" height="60px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
												 viewBox="0 0 468.192 468.192" style="enable-background:new 0 0 468.192 468.192;" xml:space="preserve">
											<path style="fill:#3A556A;" d="M298.609,114.596c-78.4,14.2-100.8,39.3-106.2,57c-3.1,10,0.1,20.7,7.6,28
												c11.4,11.1,35.9,30.7,71.9,38.2c30,6.2,6.8,18.7-17.8,28c-17.3,6.5-35.2,11.4-53.3,14.9c-70,13.5-316.8,68.8-135.7,147.3
												c0.8,0.3,1.7,0.5,2.5,0.5h355.9c6.2,0,8.6-8,3.5-11.4c-30.1-20.2-75.3-65.3,10.1-118.3c107.3-66.6-94.6-109.3-132.6-114.8
												c-8-1.2-15.8-3.6-22.8-7.7c-17.5-10.3-32.1-31.3,41.1-61.6h-24.2V114.596z"/>
											<path style="fill:#44C4A1;" d="M123.109,275.496c-15.3,26.2-25.9,54.9-31.9,73.6c-1.9,5.8-10,5.8-11.9,0
												c-6-18.6-16.6-47.3-31.9-73.6c-16.5-28.4,0.6-65.8,33.4-68.4c1.5-0.1,2.9-0.2,4.4-0.2s2.9,0.1,4.4,0.2
												C122.509,209.696,139.709,247.096,123.109,275.496z"/>
											<circle style="fill:#EBF0F3;" cx="85.309" cy="250.596" r="31.2"/>
											<path style="fill:#27A2DB;" d="M435.309,172.696c-15.3,26.2-25.9,54.9-31.9,73.6c-1.9,5.8-10,5.8-11.9,0
												c-6-18.6-16.6-47.3-31.9-73.6c-16.5-28.4,0.6-65.8,33.4-68.4c1.5-0.1,2.9-0.2,4.4-0.2s2.9,0.1,4.4,0.2
												C434.709,106.896,451.909,144.196,435.309,172.696z"/>
											<circle style="fill:#EBF0F3;" cx="397.509" cy="147.696" r="31.2"/>
											<path style="fill:#E56353;" d="M261.309,102.496c-14,24-23.7,50.2-29.2,67.3c-1.7,5.3-9.1,5.3-10.9,0c-5.5-17.1-15.2-43.3-29.2-67.3
												c-15.1-26,0.6-60.2,30.6-62.6c1.3-0.1,2.7-0.2,4-0.2s2.7,0.1,4,0.2C260.709,42.296,276.409,76.496,261.309,102.496z"/>
											<circle style="fill:#EBF0F3;" cx="226.609" cy="79.696" r="28.6"/>

												<path style="fill:#D5D6DB;" d="M356.909,304.096l-12-7.6c10.7-7,15.9-14.4,16.2-21.7c0.5-11.2-10.3-20.9-13.9-23.5l5.4-4.3
													c0.6,0.4,17.9,11.1,18.8,27.9C371.909,286.396,370.909,292.696,356.909,304.096z"/>
												<path style="fill:#D5D6DB;" d="M316.509,232.596c-4.2-1.2-35.7-10.9-40.4-22.1l8.2-4.4c3,5.1,27.3,16.1,39,18.9L316.509,232.596z"
													/>
												<path style="fill:#D5D6DB;" d="M229.209,366.396l2-11.4c48.7,0.3,76.2-29.7,76.4-30l8.7,5.4
													C315.209,331.696,286.009,366.496,229.209,366.396z"/>

											</svg>
										</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<p id="nbr_voyages_today_data_p"><?php echo $nbr_voyage; ?></p>
										</div>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<p>
													<svg width="60px" height="60px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
													 viewBox="0 0 496.246 496.246" style="enable-background:new 0 0 496.246 496.246;" xml:space="preserve">

													<path style="fill:#768989;" d="M137.035,477.393c0,4.939-4.009,8.956-8.956,8.956H89.246c-4.947,0-8.972-4.017-8.972-8.956V456.48
														c0-4.939,4.025-8.964,8.972-8.964h38.833c4.947,0,8.956,4.025,8.956,8.964V477.393z"/>
													<path style="fill:#768989;" d="M479.067,477.393c0,4.939-4.009,8.956-8.964,8.956h-38.833c-4.939,0-8.956-4.017-8.956-8.956V456.48
														c0-4.939,4.017-8.964,8.956-8.964h38.833c4.955,0,8.964,4.025,8.964,8.964V477.393z"/>

												<path style="fill:#C17E52;" d="M421.329,398.096c0,6.262-5.081,11.343-11.351,11.343H222.09c-6.286,0-11.351-5.081-11.351-11.343
													V210.2c0-6.27,5.073-11.351,11.351-11.351h187.888c6.278,0,11.351,5.081,11.351,11.351V398.096z"/>
												<path style="fill:#B56841;" d="M421.329,210.2v187.896c0,6.262-5.081,11.343-11.351,11.343H222.09"/>
												<path style="fill:#A86743;" d="M210.739,210.2c0-6.27,5.073-11.351,11.351-11.351h187.888c6.278,0,11.351,5.081,11.351,11.351
													v187.896c0,6.262-5.081,11.343-11.351,11.343"/>
												<path style="fill:#FF7F00;" d="M96.327,175.691c-7.412,0-13.43,6.018-13.43,13.438v216.576c0,7.42,6.018,13.438,13.43,13.438
													c7.428,0,13.446-6.018,13.446-13.438V189.129C109.781,181.709,103.763,175.691,96.327,175.691z"/>
												<path style="fill:#E56505;" d="M96.327,175.691c-7.412,0-13.43,6.018-13.43,13.438v84.393c0,7.42,6.018,13.446,13.43,13.446
													c7.428,0,13.446-6.018,13.446-13.446v-84.393C109.781,181.709,103.763,175.691,96.327,175.691z"/>
												<path style="fill:#93A4A5;" d="M496.246,456.85c0,4.939-4.017,8.964-8.956,8.964H72.066c-4.947,0-8.964-4.025-8.964-8.964v-43.307
													c0-4.955,4.017-8.964,8.964-8.964H487.29c4.939-0.008,8.956,4.001,8.956,8.964V456.85z"/>
												<path style="fill:#A9BFBF;" d="M487.29,404.571H72.066c-4.947,0-8.964,4.009-8.964,8.964v4.466c0,4.939,4.017,8.964,8.964,8.964
													H487.29c4.939,0,8.956-4.025,8.956-8.964v-4.458C496.246,408.58,492.229,404.571,487.29,404.571z"/>
												<circle style="fill:#D3C794;" cx="96.335" cy="106.24" r="82.889"/>
												<path style="fill:#EAE0B7;" d="M179.232,106.24c0,45.773-37.116,82.889-82.897,82.889s-82.881-37.116-82.881-82.889
													c0-45.781,37.108-47.049,82.881-47.049C142.115,59.199,179.232,60.459,179.232,106.24z"/>

													<path style="fill:#FFA300;" d="M96.327,202.575C43.221,202.575,0,159.354,0,106.24S43.221,9.897,96.327,9.897
														c53.13,0,96.327,43.221,96.327,96.343C192.662,159.354,149.465,202.575,96.327,202.575z M96.327,36.789
														c-38.282,0-69.443,31.161-69.443,69.451s31.161,69.451,69.443,69.451c38.298,0,69.459-31.161,69.459-69.451
														S134.632,36.789,96.327,36.789z"/>
													<path style="fill:#FFA300;" d="M103.613,99.237c0,3.868-3.119,7.01-6.995,7.01s-7.018-3.135-7.018-7.01
														c0-3.86,3.143-59.085,7.018-59.085S103.613,95.37,103.613,99.237z"/>

												<path style="fill:#E56505;" d="M96.626,40.153c3.868,0,6.995,55.225,6.995,59.085c0,3.868-3.119,7.01-6.995,7.01"/>
												<circle style="fill:#768989;" cx="96.343" cy="106.24" r="12.32"/>

													<path style="fill:#FFA300;" d="M280.875,110.415l28.396-33.296c1.126-1.331,1.394-3.198,0.646-4.773
														c-0.725-1.599-2.316-2.607-4.057-2.607h-13.548c-1.339,0-2.631,0.599-3.474,1.638l-24.915,30.602V74.205
														c0-2.458-1.993-4.466-4.458-4.466h-10.925c-2.465,0-4.482,2.009-4.482,4.466v84.543c0,2.465,2.016,4.474,4.482,4.474h10.925
														c2.465,0,4.458-2.001,4.458-4.474v-30.562l3.111-3.592l24.56,36.643c0.835,1.237,2.229,1.977,3.734,1.977h12.918
														c1.662,0,3.174-0.914,3.954-2.379c0.78-1.473,0.677-3.23-0.244-4.6L280.875,110.415z"/>
													<path style="fill:#FFA300;" d="M389.971,109.879h-27.837c-2.481,0-4.466,1.993-4.466,4.474v8.775c0,2.473,1.985,4.482,4.466,4.482
														h12.705v17.558c-2.261,0.536-5.624,0.961-10.075,0.961c-17.605,0-28.136-11.083-28.136-29.649
														c0-18.464,11.043-29.515,29.515-29.515c8.483,0,13.47,1.749,16.88,3.261c1.174,0.52,2.505,0.512,3.686-0.024
														c1.174-0.544,2.056-1.56,2.418-2.788l2.655-8.901c0.614-2.111-0.37-4.364-2.355-5.309c-4.348-2.095-12.666-4.34-23.016-4.34
														c-29.909,0-50.113,19.33-50.263,48.128c0,13.982,4.797,26.624,13.17,34.721c8.751,8.342,20.102,12.398,34.698,12.398
														c11.382,0,21.228-2.655,27.467-4.899c1.788-0.638,2.97-2.324,2.97-4.214v-40.645C394.445,111.872,392.444,109.879,389.971,109.879z
														"/>

												</svg>

											</p>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<p id="poids_total_p"><?php echo $poids_total; ?> KG </p>
										</div>
									</div>
