<?php @session_start();?>

<div class="cadreA">
<div class="cadreSousBouton">
	<li class="sousBouton" cible="F" onclick="sousBouton('F');" >Fermer ma session</li>
		<div class="sbCont" id="F" >
			<form id="formFermerSession" method="post" >
			<table width="100%"  border="0">
			  <tr>
				<td align="center">Voulez-vous vraiment <br />
fermer votre session ?</td>
			  </tr>
			  <tr>
	<td  align="center"><input type="button" class="bouton32" action="cancel"  value="Fermer" onclick="charger('login.php?logout','brouillon',0);" />
      </td>
			  </tr>
			</table>
			</form>
		</div>
</div>

<div class="cadreSousBouton">
	<li class="sousBouton" cible="A" title="Modifier mon mot de passe"  onclick="sousBouton('A');">Modifier mon mot de passe</li>
		<div class="sbCont" id="A">
			<form id="formMDP" method="post" >
			<table width="100%"  border="0">
			  <tr>
				<td align="right">Ancien</td><td align="center"> <input name="ancMDP" type="password" class="chpPForm" value="<?php //echo $_SESSION['M']['PASSM'];?>" size="20" style="width:100px;"/> </td>
			  </tr>
			  <tr>
				<td align="right">Nouveau</td><td align="center"> <input name="nouvMDP" type="password" class="chpPForm" value="" size="20" style="width:100px;"/> </td>
			  </tr>
			  <tr>
				<td align="right">Confirmation</td><td align="center"> <input name="confMDP" type="password" class="chpPForm" value="" size="20" style="width:100px;"/> </td>
			  </tr>
			  <tr>
				<td align="center" colspan="2"><div id="resMDP" style="font-weight:bold;	"></div>
					<input id="changerMDP" type="button" class="boutonWide" value="Enregistrer" onclick="continuer();"/>
				</td>
			  </tr>
			</table>
			</form>

		</div>
</div>


<div class="cadreSousBouton">
	<li class="sousBouton" cible="E" onclick="sousBouton('E');">Mes infos</li>
		<div class="sbCont" id="E">
		  <table width="100%"  border="0">
			  
			  <tr>
			    <td width="4%" rowspan="3" align="center"><img src="images/user64.png" style="float:left;margin:3Opx;"  /></td>
				<td align="center" width="33%"><div align="right"><strong>Login : </strong></div></td>
				<td align="left" width="63%"><?php echo $_SESSION['M']['LOGINM']; ?></td>
			  </tr>
			  <tr>
			    <td align="center" width="33%"><div align="right"><strong>Nom : </strong></div></td>
				<td align="left" width="63%"><?php echo $_SESSION['M']['NOMM']; ?></td>
			  </tr>
			 <!-- <tr>
			    <td align="center" width="33%"><div align="right"><strong>Prénom : </strong></div></td>
				<td align="left" width="63%"><?php //echo $_SESSION['M']['PRENOMM']; ?></td>
			  </tr>-->
			  <tr>
				<td align="center" colspan="3"><strong>
					<?php 
						//if($_SESSION['M']['REPCOM'] == "1") echo "Représentant commercial" ;
						//else echo"Utilisateur normal";
					 ?>
				</strong></td>
			  </tr>
			</table><br />


		</div>
</div>


	<div style="font-size:10px;color:#0066CC;padding:4px; text-align:center;margin-bottom:10px;">
			Connecté depuis le : 			<strong><?php echo $_SESSION['M']['debutConnexion']; ?></strong>

		</div>
		
		<div style="clear:both"></div>
</div>
		
		
		
		
		<script language="javascript" type="text/javascript">
		$(document).ready(function(){
			$('.infoConn').mouseover(function(){	$('#fermerInfoConn').show();	});
			$('.infoConn').mouseout(function(){	$('#fermerInfoConn').hide();	});
			//$('#fermerInfoConn').mouseup(function(){	$('.infoConn').toggleClass('hover');$(this).toggleClass('hover');	});
			$('#zoneKlik').mouseup(function(){	$('.infoConn').toggleClass('hover');$('#fermerInfoConn').toggleClass('hover');	});
			
			//$('.sousBouton').click(function(){	$(this).slideToggle({cible:'.sbCont#'+$(this).attr('cible')});			});
			/*$('.sousBouton').click(function(){	
				var t='.sbCont#'+$('.sousBouton').attr('cible');
				alert(t);
				$('.sousBouton').slideToggle({cible:'.sbCont#'+$('.sousBouton').attr('cible')});		
				});*/

	
		
			$('.listePref').mouseup(function(){
				$(this).find('input[type=checkbox]').toggleCheck();
			});
		
			$('.detail').click(function(){	var cible = $(this).attr('cible');	$('div.detailOption#'+cible).slideDown();	});
			$('.detailOption').click(function(){	$(this).slideUp(); 	});
			$('#savePref').click(function(){
				//alert('tt');
					/*$('#formPref').ajaxSubmit({target:'#resPref',url:'login.php?savePref',clearForm:false});
					patienter('resPref');
					return false;*/
			});
			
			
		});
	
		function savepref(){
			$('#formPref').ajaxSubmit({target:'#resPref',url:'login.php?savePref',clearForm:false});
					patienter('resPref');
					return false;
		}
		
		function continuer(){
			if($('input[name=ancMDP]').attr('value')== "" || $('input[name=nouvMDP]').attr('value') != $('input[name=confMDP]').attr('value') ){
				$('#resMDP').html('Merci de corriger vos données');
			}else{
				$('#formMDP').ajaxSubmit({target:'#resMDP',url:'login.php?changerMDP',clearForm:true});
				patienter('resMDP');
				return false;
			}
		}
		function changer(){
				var exCourant = '<?php //echo $_SESSION['M']['base']; ?>';
				if($('#formEx #base').attr('value') != exCourant) {
					if($('#formEx #base').attr('value') != "" && confirm("Etes-vous sûr de vouloir quitter l'exercice en cours ?")){
					
					
						$('#formEx').ajaxSubmit({target:'#resEx',url:'login.php?changerEx',clearForm:true});
						patienter('resEx');
						return false;
					}
				}else	jAlert("L'exercice "+exCourant+" est déjà chargé.");
		}
		
		function sousBouton(cible){
		
				$('li.sousBouton').removeClass('koko');
				$('.sbCont[id!='+cible+']').slideUp();
				$('.sbCont[id='+cible+']').slideDown();
/*				$('.sousBouton').slideToggle({cible:$('#'+cible+'')});	
				 $('li.sousBouton[cible='+cible+']').addClass('koko');*/
				
		}
	
			
	</script>
	