/*function ecouterSte(idChp,table){	

$("#DSGDELEGATION").autocomplete("auto2.php", {
width: 260,
matchContains: true,
mustMatch: true,
selectFirst: false,
extraParams:  {idDELEGATION : function(){ return $("#DSGDELEGATION").val(); } }
});
$("#DSGDELEGATION").result(function(event, data, formatted) {
$("#IDDELEGATION").val(data[1]);
});

	}
$(document).ready(function () {			



ecouterSte("IDDELEGATION","DELEGATION");

ecouterSte("IDFOUR","four");
ecouterSte("IDFOUR_r","four");
ecouterSte("IDCLT","clt");
ecouterSte("IDCLT_r","clt");

			
});
*/
function ecouterSte(idChp,table){	
		
			var t = table;
			var dsg="";
 	if(idChp=='NumMarche') dsg='Num'+t; else dsg='Dsg'+t;
			$("#"+idChp).autocomplete("auto2.php", {
					matchContains:false,
					highlight:false,
					scrollHeight:101,
					scroll:true,
					delay:0,
					selectFirst:true,
					mustMatch:true,
					extraParams:{
						table		:	table.toLowerCase()+'s',
						idTable		:	'Id'+t,
						affTable	:	dsg
					}
			});

		$("#"+idChp).result(function(event, data, formatted) {
			if (data) {
			
				$("input[name=Id"+t+"]").attr("value",data[1]);
/*				$("input[name=IDCLT]").attr("value",data[1]);
				$("input[name=ID_STE]").attr("value",data[1]);*/
				
				//$("span#PLAFCLT").html('<b>'+number_format(data[2])+' DHS</b>');
			}
		});
		$("#"+idChp).change(function(){

			if(!$(this).attr('value')){
				$("input[name=Id"+t+"]").attr("value","");
			}
		});
	
	}
	
	function ecouterMbr(idChp,table){	
		
			var t = table;
			var dsg="";

			$("#"+idChp).autocomplete("autoMbr.php", {
					matchContains:false,
					highlight:false,
					scrollHeight:101,
					scroll:true,
					delay:0,
					selectFirst:true,
					mustMatch:true,
					extraParams:{
						table		:	table.toLowerCase()+'s',
						idTable		:	'IDM',
						affTable	:	"CONCAT(NOMM ,' ', PRENOMM)"
					}
			});

		$("#"+idChp).result(function(event, data, formatted) {
			if (data) {
			
				$("input[name=IDM]").attr("value",data[1]);
/*				$("input[name=IDCLT]").attr("value",data[1]);
				$("input[name=ID_STE]").attr("value",data[1]);*/
				
				//$("span#PLAFCLT").html('<b>'+number_format(data[2])+' DHS</b>');
			}
		});
		$("#"+idChp).change(function(){

			if(!$(this).attr('value')){
				$("input[name=IDM]").attr("value","");
			}
		});
	
	}
	
$(document).ready(function () {

jQuery.extend(jQuery.validator.messages, {
			  required: ""
			});

<!--ecouterSte("NumMarche","Marche");-->

ecouterSte("DsgSite","Site");		
});
