
function toggleCheck(obj){
	

	if(obj.attr("checked") == "checked" ) { obj.attr("checked",false);}
	else { obj.attr("checked",true);};
	
}
jQuery.fn.toggleCheck = function(options){
	if(!options) options = '()';
	if(jQuery(this).length == 0) return false;
	var obj = this;	
	var c = obj.attr('chpDesactive');
	if(obj.attr("checked") == true ) {
		obj.attr("checked",false);
		$('input[chpDesactive='+c+']').attr('disabled','disabled');
	}else {
		obj.attr("checked",true);
		$('input[chpDesactive='+c+']').attr('disabled','');
		$('input[chpDesactive='+c+']').focus();
		//$('input#'+c).attr('disabled','disabled');
	}
}

jQuery.fn.slideToggle = function(options){
	if(!options) options = '()';
	if(jQuery(this).length == 0) return false;
	var obj = this;		
	var objCible = $(options.cible);
	
	
	$('.infoConn .sbCont').slideUp();
	$('.infoConn li').removeClass('on');
	
	if(objCible.css('display') == 'none') {
		objCible.slideDown();
		$(this).addClass('on');
	}else{
		objCible.slideUp();
		$(this).removeClass('on');
	}
}
/**
 * jQuery's Countdown Plugin
 *
 * display a countdown effect at given seconds, check out the following website for further information:
 * http://heartstringz.net/blog/posts/show/jquery-countdown-plugin
 *
 * @author Felix Ding
 * @version 0.1
 * @copyright Copyright(c) 2008. Felix Ding
 * @license http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @date 2008-03-09
 * @lastmodified 2008-03-09 17:48    		 
 * @todo error & exceptions handling
*/
jQuery.fn.countdown = function(options) {
	/**
	 * app init
	*/	
	if(!options) options = '()';
	if(jQuery(this).length == 0) return false;
	var obj = this;	

	/**
	 * break out and execute callback (if any)
	 */
	if(options.seconds < 0 || options.seconds == 'undefined')
	{
		if(options.callback) eval(options.callback);
		return null;
	}

	/**
	 * recursive countdown
	 */
	window.setTimeout(
		function() {
			jQuery(obj).html(String(options.seconds));
			--options.seconds;
			jQuery(obj).countdown(options);
		}
		, 1000
	);	

	/**
     * return null
     */
    return this;
}


function wait(){
	$("#preload").dialog('open');
	$("#preload").ajaxStop(function(){	$("#preload").dialog('close');	});	
}

// JavaScript Document
       function TestNombre(id){ 
	  //Fonction prenant nu nombre dans une zone de texte : test si c'est un chiffre si oui le convertit en decimal(10e-2) sinon tante de le convertir en decimal
      // efface le contenu de la zone de saisie si convertion impossible
      // arrondit a 2 chiffres si il s'agit deja d'un decimal
      		var d=document.getElementById(id);
      		if (d.value!=''){ 
				if (isNaN(d.value)==true){
				  //si on tombe sur une virgule la valeur n'est pas considre comme un nombre
				  Num=d.value.indexOf(',');
				  //on remplace la virgule par un point
				  Resultat=d.value.substring(0,Num)+'.'+d.value.substring(Num+1,d.value.length);
				  d.value=Resultat;
				  d.value=Math.round(d.value*100)/100;
				  if (isNaN(d.value)==true) {
					  d.value='';
					  alert('VOUS DEVEZ SAISIR UN NOMBRE DECIMAL OU ENTIER');
					  return false;
				  }
			}
		  	Temp=Math.round(d.value*100)/100;// on arrodi a 2 chiffres si decimal a plsu de 2 chiffres
		  	d.value=Temp;
			
			//on replace le nombre de zero necessaire derrier le chiffre
			if ((d.value.length-Math.abs(d.value.indexOf('.') ))==2 && d.value.indexOf('.')!=-1){
				d.value =Temp+'0';
				
			}else if (d.value.indexOf('.')==-1){
				// cas ou entier
				d.value=d.value+'.00';
			}else if ((d.value.length-d.value.indexOf('.'))==1){
				//cas ou point mais pas de chiffres derriere
				d.value=Temp+'.00';
			}
			return true;
			}
  } 


function number_format (number, decimals, dec_point, thousands_sep) {
    var n = number, prec = decimals;
 
    var toFixedFix = function (n,prec) {
        var k = Math.pow(10,prec);        return (Math.round(n*k)/k).toString();
    };
 
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 2 : Math.abs(prec);    var sep = (typeof thousands_sep === 'undefined') ? ' ' : thousands_sep;
    var dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
 
    var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
     var abs = toFixedFix(Math.abs(n), prec);
    var _, i;
 
    if (abs >= 1000) {
        _ = abs.split(/\D/);        i = _[0].length % 3 || 3;
 
        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
        s = _.join(dec);    } else {
        s = s.replace('.', dec);
    }
 
    var decPos = s.indexOf(dec);    if (prec >= 1 && decPos !== -1 && (s.length-decPos-1) < prec) {
        s += new Array(prec-(s.length-decPos-1)).join(0)+'0';
    }
    else if (prec >= 1 && decPos === -1) {
        s += dec+new Array(prec).join(0)+'0';    }
    return s;
}
function dhsJS(nombre){
		return number_format (nombre,'2', '.', ' ');
}
  function isDate(d) {
  // Cette fonction permet de vrifier la validit d'une date au format jj/mm/aa ou jj/mm/aaaa
  // Par Romuald
 
  if (d == "") // si la variable est vide on retourne faux
  {	  return true;  }
 
  e = new RegExp("^[0-9]{1,2}\/[0-9]{1,2}\/([0-9]{2}|[0-9]{4})$");
 
  if (!e.test(d)) // On teste l'expression rgulire pour valider la forme de la date
  return false; // Si pas bon, retourne faux
 
  // On spare la date en 3 variables pour vrification, parseInt() converti du texte en entier
  j = parseInt(d.split("/")[0], 10); // jour
  m = parseInt(d.split("/")[1], 10); // mois
  a = parseInt(d.split("/")[2], 10); // anne
 
  // Si l'anne n'est compose que de 2 chiffres on complte automatiquement
  if (a < 1000) {
  if (a < 89) a+=2000; // Si a < 89 alors on ajoute 2000 sinon on ajoute 1900
  else a+=1900;
  }
 
  // Dfinition du dernier jour de fvrier
  // Anne bissextile si annne divisible par 4 et que ce n'est pas un sicle, ou bien si divisible par 400
  if (a%4 == 0 && a%100 !=0 || a%400 == 0) fev = 29;
  else fev = 28;
 
  // Nombre de jours pour chaque mois
  nbJours = new Array(31,fev,31,30,31,30,31,31,30,31,30,31);
 
  // Enfin, retourne vrai si le jour est bien entre 1 et le bon nombre de jours, idem pour les mois, sinon retourn faux
  return ( m >= 1 && m <=12 && j >= 1 && j <= nbJours[m-1] );
  } 
  function verifier_date(objet){
	  if(objet.value != ''){
		  var date = formater_date(objet);
		  if(isDate(date)) {
			  objet.value=date;
			  $('input#'+objet.id).removeClass("errForm");
		  }else {
			  alert('Merci de saisir une date valide.');
			  $('input#'+objet.id).addClass("errForm");
		  }
	  }else{
		 $('input#'+objet.id).removeClass("errForm");
	  }
		  
  }
function formater_date(objet){
	
	var date = objet.value;
	if (date.indexOf("/")!=-1){
			var jour = date.substring(0,2);
			var mois = date.substring(3,5);
			var annee = date.substring(6,10);
			if(annee.length == 2){
				if(annee >= 50) annee = '19'+annee;
				else annee = '20'+annee;
			}
			var resultat =  jour+'/'+mois+'/'+annee;
			return resultat;
	}
	if(date.length == 6 || date.length == 8){
		var jour = date.substring(0,2);
		var mois = date.substring(2,4);
		var annee = date.substring(4,8);
		if(annee.length == 2){
				if(annee >= 50) annee = '19'+annee;
				else annee = '20'+annee;
		}
		//alert('j='+jour+', m='+mois+', a='+annee);
		var resultat =  jour+'/'+mois+'/'+annee;
		//alert(annee);
		return resultat;
	}else{
		return 'erreur';
	}
}

function patienter(destId){
	var dest = $('#'+destId);
	if(!destId){
		$('#preload').html('Merci de patienter quelques instants<br/><br/><img src="images/loading.gif" />').dialog('open');
		$("#preload").ajaxStop(function(){	$(this).html('').dialog('close');	});
	}else{
		var haut = parseInt(dest.css('height'))/2 -40;
		//alert(haut);
		var msg = '<div id="loadingBox" style="margin-top:'+haut+'px;" ></div>';
		dest.html(msg);	
	}
}

Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}
/*
function toggleCheck(obj){
	if(obj.attr("checked") == true ) obj.attr("checked",false);
	else obj.attr("checked",true);
}	*/


$(document).ready(function() {	
			$('#preload').dialog({
					autoOpen: false,
					width: 300,
					bgiframe:true,
					modal:true,
					resizable:false,
					closeOnEscape:false,
					draggable:false,
					title:'Chargement en cours ...',
					stack:true,
					zindex:1000,
					position:'center'
				});
			
	
	//	$("ul.dropdown").hide();

	


																		
});
 function validEmail(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(email) == false) {
      return false;
   }else return true;
}
function autoComplete(table,champDSG){
	$( "#project" ).autocomplete({
			minLength: 0,
			source: projects,
			focus: function( event, ui ) {
				$( "#project" ).val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				$( "#project" ).val( ui.item.label );
				$( "#project-id" ).val( ui.item.value );
				$( "#project-description" ).html( ui.item.desc );
				$( "#project-icon" ).attr( "src", "images/" + ui.item.icon );
				return false;
			}
		})
}

function calendrier(chp){
	 $( "#"+chp ).datepicker({
		 dateFormat: 'dd/mm/yyyy'		 
	});
}
function charger(url,destId,dialog){

	if((url=="none") ||(url=="") ||(destId=="")){return false;}
	$('#'+destId).load(url);
	if(dialog != 0){	
		$('#preload').html('Merci de patienter quelques instants<br/><br/><img src="images/loading.gif" />').dialog('open');
		
		$("#preload").ajaxStop(function(){	$(this).html('').dialog('close');	});
	}
	//$('#infosMem').load('infos.php');
		
}
function clearForm(formId,vider){

}	