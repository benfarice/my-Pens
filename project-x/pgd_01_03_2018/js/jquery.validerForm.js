function bougerMois(d,sens){
	nbJours = new Array(31,29,31,30,31,30,31,31,30,31,30,31);
	j = parseInt(d.split("/")[0], 10); // jour
  	m = parseInt(d.split("/")[1], 10); // mois
  	a = parseInt(d.split("/")[2], 10); // anne
	var mP = 0;
	var jP = 0;
	var aP = 'x';
	if(sens == '1' && m == 12){
		mP = 1;
		aP = parseInt(a + 1);		
	}else if(sens == '-1' && m == 1){
		mP = 12;
		aP = parseInt(a - 1);
	}else{
		mP = parseInt(m + sens);
		aP = a ;
	}
	jP = nbJours[mP-1];
	//alert( mP+1);
	if(mP<=9) mP ='0'+mP;
	if(jP<=9) jP ='0'+jP; 
	plage = new Array();
	plage[0] = '01/'+mP+'/'+aP;
	plage[1] = jP+'/'+mP+'/'+aP;
	plage[2] = aP;
	return plage;
	
}
$(document).ready(function() {	
	$('.bougerMois').click(function(){
		var sens = parseInt($(this).attr('sens'));
		var dj= $('#dj').attr('value');
		//alert(dj);
		plage = new Array();
		plage = bougerMois(dj,sens);
		//alert(plage[0]+' --> '+plage[1]);
		$('input[name=DATED]').attr('value',plage[0]);
		$('input[name=DATEF]').attr('value',plage[1]);
		dj= $('#dj').attr('value',plage[1]);
	});
	
		var sens = 0;
		var dj= $('#dj').attr('value');
		if(dj != undefined){
			plage = new Array();
			plage = bougerMois(dj,sens);
			$('input[name=DATED]').attr('value','01/01/'+plage[2]);
			$('input[name=DATEF]').attr('value',plage[1]);
		}
});
function signalerErreur(nomChamps,message){
	$("div[id="+nomChamps+"].errForm").html("");
	$("div[id="+nomChamps+"].errForm").html(message);
	$("input[id="+nomChamps+"].formTop").addClass("errForm");
}
function corrigerErreur(nomChamps){
	$("div[id="+nomChamps+"].errForm").html("<br/>");
	$("input[id="+nomChamps+"]").removeClass("errForm");
	//alert('toutOKI') ;
}

function validerTout(formData, jqForm, options){
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
        if (formData[i].value == '') {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			//alert('erreur = '+formData[i].name);
        }else{
			corrigerErreur(formData[i].name);
		}
		
    } 
	//if(toutOK) {alert('tout est OK');}
	return toutOK;
}
function printR(obj, maxDepth, prefix){
   var result = '';
   if (!prefix) prefix='';
   for(var key in obj){
       if (typeof obj[key] == 'object'){
           if (maxDepth !== undefined && maxDepth <= 1){
               result += (prefix + key + '=object [max depth reached]\n');
           } else {
               result += printR(obj[key], (maxDepth) ? maxDepth - 1: maxDepth, prefix + key + '.');
           }
       } else {
           result += (prefix + key + '=' + obj[key] + '\n');
       }
   }
   return result;
}
function validerToutConcours(formData, jqForm, options){
	var toutOK = true;
	//alert('new');
	for (var i=0; i < formData.length; i++) { 
        if (formData[i].name != 'IDC' && formData[i].value == '') {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			alert('erreur = '+formData[i].name);
			//alert(formData[i].bq);
			//$('form[name='+formData.name+'] input[name='+formData[i].name+']').addClass('err');
        }else{
			corrigerErreur(formData[i].name);
		}
		
    } 
	//if(toutOK) {alert('tout est OK');}
	return toutOK;
}
function validerLigne(formData, jqForm, options){
	//alert('valider ligne FN');
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
		
        if ((formData[i].name!='C_FLOW' && formData[i].value == '' )|| (formData[i].value==0 && formData[i].name=='Q_FLOW')) {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			//alert('erreur = '+formData[i].name);
        }else{
			corrigerErreur(formData[i].name);
		}
		
    } 
	//if(toutOK) {alert('tout est OK');}
	//else 
	return toutOK;
}
function validerLigneInv(formData, jqForm, options){
	//alert('valider ligne FN');
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
		
        if (
			(formData[i].name!='C_FLOW' && formData[i].value == '' )
			|| (formData[i].value=='' && formData[i].name=='IDP')
			|| (formData[i].value==0 && formData[i].name=='Q_FLOW') 
			|| (formData[i].value==0 && formData[i].name=='NBRE_FLOW')
		) {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			//alert('erreur = '+formData[i].name);
        }else{
			corrigerErreur(formData[i].name);
		}
		
    } 
	//if(toutOK) {alert('tout est OK');}
	//else 
	return toutOK;
}
function validerRechP(formData, jqForm, options){
	var toutOK = true;
	var T = 0;
	for (var i=0; i < formData.length; i++) { 
        if (formData[i].value == '') {
			
			if(formData[i].name == 'IDSOUCHE'){
				alert('Vous devez choisir une souche.');
				signalerErreur(formData[i].name,"Champs obligatoire");
				toutOK = false;
			}
        }else{	T++;	}
		
    } 
	if(T>1 && toutOK) return true;	
	else return false;
	
}

function valider_001(formData, jqForm, options){
	
	//Nom Champs obligatoires
	var champsOblig = ['IDM','LOGINM','PASSM','PASSM2'];
	
	var chOrig 	= 'PASSM';
	var chVerif	= 'PASSM2';
	var indiceChOrig = indiceChVerif = 0;
		
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
		if(formData[i].name == chOrig) indiceChOrig = i;
		if(formData[i].name == chVerif) indiceChVerif = i;
		
        if (formData[i].value == '' && champsOblig.in_array(formData[i].name)) {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			
			//alert(formData[i].value);
        }else{
			corrigerErreur(formData[i].name);
		}
		
		
    } 
	if(formData[indiceChOrig].value != formData[indiceChVerif].value) {
			signalerErreur(formData[indiceChOrig].name,"Ces champs ne sont pas identiques");
			signalerErreur(formData[indiceChVerif].name,"Ces champs ne sont pas identiques");
			toutOK = false;
			alert('errrr');
	} 
	//alert(formData[indiceChVerif].value+' ->'+formData[indiceChOrig].value);return false;
	return toutOK;
}
function valider_002(formData, jqForm, options){
	
	//Nom Champs obligatoires
	var champsOblig = ['NOMC','FONCTIONC'];
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
		if (formData[i].value == '' && champsOblig.in_array(formData[i].name)) {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
        }else{ corrigerErreur(formData[i].name); }
    } 
	return toutOK;
}
function valider_003(formData, jqForm, options){
	
	//Nom Champs obligatoires
	var champsOblig = ['DATE_PIECE','SOUCHE_PIECE','ID_STE','NUM_PIECE','IDM_PIECE','numManu'];
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
		if (formData[i].value == '' && champsOblig.in_array(formData[i].name)) {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			//alert(formData[i].name);
        }else{ corrigerErreur(formData[i].name); }
    } 
	return toutOK;
}

function valider_000(formData, jqForm, options){
	
	//Nom Champs obligatoires
	var champsOblig = ['IDV','DSGCLT'];
	
	
		
	var toutOK = true;
	for (var i=0; i < formData.length; i++) { 
		
        if (formData[i].value == '' && champsOblig.in_array(formData[i].name)) {
			signalerErreur(formData[i].name,"Champs obligatoire");
			toutOK = false;
			
			//alert(formData[i].value);
        }else{
			corrigerErreur(formData[i].name);
		}
		
		
    } 
	
	//alert(formData[indiceChVerif].value+' ->'+formData[indiceChOrig].value);
	return toutOK;
}

			function autoComplete($obj,$nomTable,$idTable,$labelTable,$cible){
				
				var sourceUrl = "autoComplete.php?nomTable="+$nomTable+"&nomId="+$idTable+"&nomAff="+$labelTable;
				   $obj.autocomplete({
						source: sourceUrl,
						minLength: 2,
						select: function(event, ui) {
							$cible.val(ui.item.id);
							$obj.val(ui.item.label);
						}
					});
				
		}