<?php

require_once('../connexion.php');

$sql = "select 
a.Reference,a.Designation,m.url as a_image ,d.tarif as PV ,d.UniteVente as type_v,
d.qte ,d.ttc as Total
from 
detailFactures d inner join articles a on a.IdArticle = d.idArticle
inner join media m on m.idArticle = a.IdArticle 
where d.idFacture = $_REQUEST[id_fac]";



$params_select_all = array();
$options_select_all =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_select_all=sqlsrv_query($conn,$sql,$params_select_all,$options_select_all);
$ntRes_select_all = sqlsrv_num_rows($stmt_select_all);
while($row_select_all= sqlsrv_fetch_array($stmt_select_all, SQLSRV_FETCH_ASSOC)){
?>
		 <tr class="animated bounce">
		      <th scope="row"><?php echo $row_select_all['Reference']; ?></th>
		      <td><?php echo $row_select_all['Designation']; ?></td>
		      <td>
		      	<img src="../<?php echo $row_select_all['a_image']; ?>" width="60px" height="60px">	
		      </td>
		      <td><?php echo $row_select_all['PV']; ?></td>
		      <td><?php echo $row_select_all['type_v']; ?></td>
		      <td><?php echo $row_select_all['qte']; ?></td>
		      <td><?php echo $row_select_all['Total']; ?></td>
		    </tr>

<?php
} 
?>
