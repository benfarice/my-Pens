<?php
require_once('../connexion.php');

$query_select_all = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+
 v.prenom Vendeur,c.IdClient ,c.intitule IntituleClt,f.totalTTC,f.date
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	where f.EtatCmd = 2 and cast(f.date as date) between '".date('Y-m-01')."' and '".date('Y-m-t')."'
	and f.idDepot = $_REQUEST[id_depot] and f.idVendeur = $_REQUEST[id_vendeur] and EtatCmd = 2";

 
if(isset($_REQUEST['searched_date']) && isset($_REQUEST['DateFin']) && (isset($_REQUEST['searched_client'])==false)){
	$searched_date = substr($_REQUEST['searched_date'], 0, 10);
	$date_f =  substr($_REQUEST['DateFin'], 0, 10);
	$searched_date =  date_format(date_create_from_format('d/m/Y', $searched_date), 'Y-m-d');
	$date_f =  date_format(date_create_from_format('d/m/Y', $date_f), 'Y-m-d');
	$query_select_all = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+
 v.prenom Vendeur,c.IdClient ,c.intitule IntituleClt,f.totalTTC,f.date
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	where f.EtatCmd = 2 and cast(f.date as date) between '$searched_date' and '$date_f' 
	and f.idDepot = $_REQUEST[id_depot] and f.idVendeur = $_REQUEST[id_vendeur] and EtatCmd = 2";
}else if(isset($_REQUEST['searched_date'])  && isset($_REQUEST['DateFin']) && isset($_REQUEST['searched_client'])){
	$searched_date = substr($_REQUEST['searched_date'], 0, 10); 
	$date_f =  substr($_REQUEST['DateFin'], 0, 10);
	$searched_date =  date_format(date_create_from_format('d/m/Y', $searched_date), 'Y-m-d');
	$date_f =  date_format(date_create_from_format('d/m/Y', $date_f), 'Y-m-d');
	$query_select_all = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+
 v.prenom Vendeur,c.IdClient ,c.intitule IntituleClt,f.totalTTC,f.date
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	where f.EtatCmd = 2 and cast(f.date as date) between '$searched_date' and '$date_f' 
	and f.idDepot = $_REQUEST[id_depot] and f.idVendeur = $_REQUEST[id_vendeur] and 
	c.intitule like '%$_REQUEST[searched_client]%' and EtatCmd = 2";
}else if(isset($_REQUEST['searched_client'])){
	$query_select_all = " SELECT IdFacture IdFacture,NumFacture as NumFacture ,v.nom+ ' '+
 v.prenom Vendeur,c.IdClient ,c.intitule IntituleClt,f.totalTTC,f.date
	FROM 
	factures f  INNER JOIN clients c  ON c.IdClient=f.idClient
	INNER JOIN vendeurs v ON v.idVendeur = f.idVendeur
	inner join depots d on d.idDepot=c.idDepot
	inner join villes v2 on v2.idVille=d.idVille
	where f.EtatCmd = 2 and cast(f.date as date) between '".date('Y-m-01')."' and '".date('Y-m-t')."' 
	and f.idDepot = $_REQUEST[id_depot] and f.idVendeur = $_REQUEST[id_vendeur] and 
	c.intitule like '%$_REQUEST[searched_client]%' and EtatCmd = 2";
}

//echo $query_select_all;





$params_select_all = array();
$options_select_all =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_all=sqlsrv_query($conn,$query_select_all,$params_select_all,$options_select_all);
$ntRes_select_all = sqlsrv_num_rows($stmt_select_all);
while($row_select_all= sqlsrv_fetch_array($stmt_select_all, SQLSRV_FETCH_ASSOC)){
?>

<tr class="animated bounce">
		      <th scope="row"><?php echo $row_select_all['NumFacture']; ?> </th>
		      <td><?php echo $row_select_all['Vendeur']; ?></td>
		      <td><?php echo $row_select_all['IntituleClt']; ?></td>
		      <td><?php
		        $ttc = number_format($row_select_all['totalTTC'], 2, ',', ' ');
		        echo $ttc; 
		       ?></td>
		      <td><?php 

		      $_date =  date_format(date_create_from_format('Y-m-d', $row_select_all['date']), 'd/m/Y');
		      echo $_date; ?></td>
		      <td>
		      	<div onclick="Afficher_details_facture(<?php echo $row_select_all['IdFacture']; ?>)">
		      	<svg width="35px" height="35px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 482.2 482.2" style="enable-background:new 0 0 482.2 482.2;" xml:space="preserve">
                        <circle style="fill:#324A5E;" cx="241.1" cy="241.1" r="241.1"/>
                        <polygon style="fill:#FFFFFF;" points="155,73.4 155,127.4 100.9,127.4 100.9,408.8 356.3,408.8 356.3,73.4 "/>
                            <polygon style="fill:#E6E9EE;" points="155,73.4 100.9,127.4 155,127.4 	"/>
                            <rect x="132.7" y="283.3" style="fill:#E6E9EE;" width="191.2" height="14.7"/>
                            <rect x="132.7" y="314.2" style="fill:#E6E9EE;" width="191.2" height="14.7"/>
                            <rect x="132.7" y="345.2" style="fill:#E6E9EE;" width="113.3" height="14.7"/>
                        <rect x="285.794" y="218.043" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 680.0255 203.6097)" style="fill:#2B3B4E;" width="24.1" height="49.2"/>
                        <circle style="fill:#324A5E;" cx="240.2" cy="184.9" r="76.5"/>
                        <circle style="fill:#84DBFF;" cx="240.2" cy="184.9" r="59.1"/>
                        <path style="fill:#E6E9EE;" d="M322.9,241.6l-26,26c-0.9,0.9-0.9,2.5,0,3.4l70.2,70.2c0.9,0.9,2.5,0.9,3.4,0l26-26
                         c0.9-0.9,0.9-2.5,0-3.4l-70.2-70.2C325.4,240.7,323.9,240.7,322.9,241.6z"/>
                        <rect x="312.563" y="270.724" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 385.8226 742.8599)" style="fill:#FF7058;" width="68.399" height="41.6"/>
                        </svg>
                  </div>
		      </td>
		      <td>
		      	<div onclick="Afficher_txt_facture(<?php echo $row_select_all['IdFacture']; ?>)">
		      	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="35px" height="35px" viewBox="0 0 475.078 475.077" style="enable-background:new 0 0 475.078 475.077;" xml:space="preserve">
				<g>
					<path d="M458.959,217.124c-10.759-10.758-23.654-16.134-38.69-16.134h-18.268v-73.089c0-7.611-1.91-15.99-5.719-25.122   c-3.806-9.136-8.371-16.368-13.699-21.698L339.18,37.683c-5.328-5.325-12.56-9.895-21.692-13.704   c-9.138-3.805-17.508-5.708-25.126-5.708H100.5c-7.614,0-14.087,2.663-19.417,7.993c-5.327,5.327-7.994,11.799-7.994,19.414V200.99   H54.818c-15.037,0-27.932,5.379-38.688,16.134C5.376,227.876,0,240.772,0,255.81v118.773c0,2.478,0.905,4.609,2.712,6.426   c1.809,1.804,3.951,2.707,6.423,2.707h63.954v45.68c0,7.617,2.664,14.089,7.994,19.417c5.33,5.325,11.803,7.994,19.417,7.994   h274.083c7.611,0,14.093-2.669,19.418-7.994c5.328-5.332,7.994-11.8,7.994-19.417v-45.68h63.953c2.471,0,4.613-0.903,6.42-2.707   c1.807-1.816,2.71-3.948,2.71-6.426V255.81C475.082,240.772,469.708,227.876,458.959,217.124z M365.449,420.262H109.636v-73.087   h255.813V420.262z M365.449,237.537H109.636V54.816h182.726v45.679c0,7.614,2.669,14.083,7.991,19.414   c5.328,5.33,11.799,7.993,19.417,7.993h45.679V237.537z M433.116,268.656c-3.614,3.614-7.898,5.428-12.847,5.428   c-4.949,0-9.233-1.813-12.848-5.428c-3.613-3.61-5.42-7.898-5.42-12.847s1.807-9.232,5.42-12.847   c3.614-3.617,7.898-5.426,12.848-5.426c4.948,0,9.232,1.809,12.847,5.426c3.613,3.614,5.427,7.898,5.427,12.847   S436.733,265.046,433.116,268.656z" fill="#D80027"/>

				</svg>
			    </div>
		      </td>
		    </tr>


<?php

	
}
?>