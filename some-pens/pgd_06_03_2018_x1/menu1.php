	<?php //include("verifCnx.php");
	require_once('connexion.php');
	require_once('php.fonctions.php');
	$sql="
		select 
		* 
		from droitsacces da
		left join  liens  on da.idlien=liens.idlien
		left join  groupes on da.IDG=groupes.idG
		where  da.IDG =".$_SESSION['M']['IDG']." and ETATDA =1 ORDER BY codelien";
    
		echo $sql;
	$res=mysql_query($sql) or die(mysql_error());
?>
	<ul>
	<?php
	while ($row = mysql_fetch_array($res)) {

	?>
		
		
	<li <?php if($row["CODELIEN"]!="A")   echo "style=display:none;";   ?> >Gestion des données 
	
		<ul>
		<li <?php if($row["CODELIEN"]!="AA")  echo "style=display:none;";  ?> >Gestion des client</li>
		<li <?php if($row["CODELIEN"]!="AB")  echo "style=display:none;";  ?> >Lieu</li>
		</ul>
	</li>
	
	<li <?php if($row["CODELIEN"]!="B")  echo "style=display:none;";  ?> >Gestion des demandes</li>
	<li <?php if($row["CODELIEN"]!="C")  echo "style=display:none;";  ?> >Suivi</li>
	<li <?php if($row["CODELIEN"]!="D")  echo "style=display:none;";  ?> >Gestion des statistiques</li>
	<?php
}
	?>
		</ul>