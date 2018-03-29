function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
function DateSysteme() {
    var d = new Date();
   // var x = document.getElementById("demo");
    var h = addZero(d.getHours());
    var m = addZero(d.getMinutes());
    var s = addZero(d.getSeconds());
	var dd = d.getDate();
	var mm = d.getMonth()+1; //January is 0!
	var yyyy = d.getFullYear();
	
	if(dd<10) {
		dd='0'+dd
	} 
	
	if(mm<10) {
		mm='0'+mm
	} 
	var today = dd+'/'+mm+'/'+yyyy;
 return today + "-" + h + ":" + m + ":" + s;
}
function getWindowHeight() {
    var windowHeight=0;
    if (typeof(window.innerHeight)=='number') {
        windowHeight=window.innerHeight;
    } else {
        if (document.documentElement&& document.documentElement.clientHeight) {
            windowHeight = document.documentElement.clientHeight;
        } else {
            if (document.body&&document.body.clientHeight) {
                windowHeight=document.body.clientHeight;
            }
        }
    }
    return windowHeight;
}
function getWindowWidth() {
 var windowWidth=0;
 if (typeof(window.innerWidth)=='number') {
  windowWidth=window.innerWidth;
    } else {
  if (document.documentElement&& document.documentElement.clientWidth) {
   windowWidth = document.documentElement.clientWidth;
        } else {
   if (document.body&&document.body.clientWidth) {
    windowWidth=document.body.clientWidth;
            }
        }
    }
 return windowWidth;
}

	   
		
function Display_Load()
{
	//$("#loadingv").fadeIn(900,0);

	$('#preload').html('<center>Merci de patienter quelques instants<br/><br/><img src="images/loading.gif" /></center>').dialog('open');
	
}

function Hide_Load()
{
	//$("#loadingv").fadeOut('slow');
		$("#preload").ajaxStop(function(){	$(this).html('').dialog('close');	});
};
	$(document).ready(function(){
/*	$('#preload').dialog({
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
				});*/
	
		  });
function AfficheVideo(url){
		var url="media_promo.php?AffVideo&url="+url;
		$('#video').html('').load(url).dialog('open');	
}
function isNumberKey(evt)
{
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
 return true;
}

function verifSelect2(NomSelect){
		//test Ville

	var Ville=$('select[id='+NomSelect).attr('class'); 

			if (Ville.indexOf("error") < 0)
			{$('#'+NomSelect).removeClass('error');	
				$('div.'+NomSelect+' button').css("border", "1px solid #ccc").css("background","#fff");
				return true;
			}
			else {
			
				$('div.'+NomSelect+' button').css("border", "1px solid  #990000").css("background","#FFECFF");
				$('.'+NomSelect).addClass('error');
				return false;
			}
}
function ChangeSelect2(NomSelect){
	$('body').on('change', '#'+NomSelect, function() {
		
			var Ville =$('input:radio[name=selectItem'+NomSelect+'[]]:checked').val() ;
			if(Ville!="") {
				$('div.'+NomSelect).removeClass('erroer');
				$('div.'+NomSelect+' button').css("border", "1px solid #ccc").css("background","#fff");
			}
	});
		
}

   function isDecimal(evt, obj) {
 
            var charCode = (evt.which) ? evt.which : event.keyCode
            var value = obj.value;
            var dotcontains = value.indexOf(".") != -1;
            if (dotcontains)
                if (charCode == 46) return false;
            if (charCode == 46) return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
  function isEntier(evt) {
	  var charCode = (evt.which) ? evt.which : evt.keyCode
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
  }
function CompareDate(date1,date2,msg) {


			if ($.datepicker.parseDate('dd/mm/yy', date2) <= $.datepicker.parseDate('dd/mm/yy', date1)) {

				   alert(msg);
			return false;
			}else 
				return true;
}
function CompareDateSupEgal(date1,date2,msg) {


			if ($.datepicker.parseDate('dd/mm/yy', date2) < $.datepicker.parseDate('dd/mm/yy', date1)) {

				   alert(msg);
			return false;
			}else 
				return true;
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

	