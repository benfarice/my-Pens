<?php

include("../php.fonctions.php");
require_once('../connexion.php');
if(!isset($_SESSION))
{
session_start();
} 
unset( $_SESSION['IdTypeVente']);
unset( $_SESSION['lignesCat']);
unset($_SESSION['lignesFam']);
$IdDepot=$_SESSION['IdDepot'];
include("lang.php");
/*
if (isset($_GET['GetIdTypeVente'])){
	
	$_SESSION['IdTypeVente']=$_GET["IdTypeVente"];
	  
	  header('Location: catalogue4.php');      
	exit;
}*/
if (isset($_GET['TypeVente'])){
?>
<DIV style="  display:flex;  align-items:center;" class="headVente">
<a href="index.php"><img src="../images/home.png" height="64" width="64" style="float:left;"> </a>&nbsp;&nbsp;
<div Class="TitleHead" ><?php  echo $trad['label']['TypeVente'];?> <?php  //echo date("d/m/y h:i");?></div></div>
<div class="clear"></div>
<?php
$sql="select  * from TypeVente";
			 $params = array();	
	//parcourir($params);
	//echo "<br>".$sql;
	//parcourir($params);
			$stmt=sqlsrv_query($conn,$sql,$params,array("Scrollable" =>SQLSRV_CURSOR_KEYSET));
			if( $stmt === false ) {
										$errors = sqlsrv_errors();
										echo "Erreur : ".$errors[0]['message'] . " <br/> ";
										return;
									}							
			
			//echo $sql;
				$nRes = sqlsrv_num_rows($stmt);	
				if($nRes!=0)
					  { 							
			?>
			<div style="  text-align:center;margin-top:20px">
<?php			
					 while($row=sqlsrv_fetch_array($stmt)){					
?>

  <div class="" style="display:inline-block;margin:0 20px;CURsor:pointer" id="cadreType<?php  echo $row['IdType'];?>" 
		  onclick="GetCatalogue('<?php  echo $row['IdType'];?>','<?php  echo $_SESSION['Vente'];?>')">
			<div  class="titleCadre"> 
			 <img src="../<?php 	echo ( $row['UrlImg']);?>" width="225" height="226"/><br>
				<div class="titleCadre"><?php 	echo $row['Dsg_'.$_SESSION['lang']];?></div>
			</div>
		  </div>
<?php
		}					
			?></div>
			
<?php	 }else { ?>
<div class="resAffCat" style="text-align:center;min-height:200px;font-size:16px;">
								<br><br><br><br>
										<?php  echo $trad['msg']['AucunResultat'];?>
							</div>
<?php }		
	exit;
}	
include("header.php");
?>
<Style>
.cadreIndex{
	margin:10px 15px;
}
.childIndex{
	width:225px;
max-width:226px;
}
</style>
<div id="formRes" style="MAX-height:790px;">
</div>


<script language="javascript" type="text/javascript">	

$(document).ready(function(){
		$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('type_vente.php?TypeVente');
});
function GetCatalogue(IdTypeVente,Supperviseur){	
			if(Supperviseur==1)
			{
				
				var url='catalogue4.php?IdTypeVente='+IdTypeVente;}
	else {
		var url='catalogue5.php?IdTypeVente='+IdTypeVente;
	}
			//$('#formRes').html('<center><br/><br/><img src="../images/loading2.gif" /></center>').load('catalogue4.php?GetIdTypeVente&&IdTypeVente='+IdTypeVente);
	window.location = url;
	
}
</script>

<?php include("footer.php");?>