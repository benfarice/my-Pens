var xmlDoc;
var ref_article_has_value = [];
var cb_article_has_value = [];
var design_article_has_value = [];
function readXml(){



if(typeof window.DOMParser != "undefined") {
    xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","xml/article_details.xml",false);
    if (xmlhttp.overrideMimeType){
        xmlhttp.overrideMimeType('text/xml');
    }
    xmlhttp.send();
    xmlDoc=xmlhttp.responseXML;
}
else{
    xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async="false";
    xmlDoc.load("xml/article_details.xml");
}
//storage_xml1 = xmlDoc;
//json1 = xml2json(storage_xml1);
tagObj=xmlDoc.getElementsByTagName("Article");
var typeValue = tagObj[0].getElementsByTagName("marque")[0].childNodes[0].nodeValue;
var titleValue = tagObj[0].getElementsByTagName("gamme")[0].childNodes[0].nodeValue;
var serializer = new XMLSerializer();
var xmlString_details_article = serializer.serializeToString(xmlDoc);
var parser = new DOMParser();
xmlDoc_details_article = parser.parseFromString(xmlString_details_article, "text/xml");
//alert(typeValue);
//alert("hello");
}
readXml();

var xmlDoc2;
/*if (typeof (Storage) !== "undefined") {
	//alert("ok");
    localStorage.setItem('first_json', json1);
}
else {
    alert("Sorry, your browser does not support web storage...");
}
if (typeof (Storage) !== "undefined") {

    var person = localStorage.getItem('first_json');
    xmlDoc = parseXml(json2xml(json1));
    console.log(typeof (xmlDoc));
    console.log(typeof (person));
}
else {
    alert("Sorry, your browser does not support web storage...");
}
function parseXml(xml) {
   var dom = null;
   if (window.DOMParser) {
      try { 
         dom = (new DOMParser()).parseFromString(xml, "text/xml"); 
      } 
      catch (e) { dom = null; }
   }
   else if (window.ActiveXObject) {
      try {
         dom = new ActiveXObject('Microsoft.XMLDOM');
         dom.async = false;
         if (!dom.loadXML(xml)) // parse error ..

            window.alert(dom.parseError.reason + dom.parseError.srcText);
      } 
      catch (e) { dom = null; }
   }
   else
      alert("cannot parse xml string!");
   return dom;
}*/
function readXml2(){



if(typeof window.DOMParser != "undefined") {
    xmlhttp2=new XMLHttpRequest();
    xmlhttp2.open("GET","xml/table_inventaire.xml",false);
    if (xmlhttp2.overrideMimeType){
        xmlhttp2.overrideMimeType('text/xml');
    }
    xmlhttp2.send();
    xmlDoc2=xmlhttp2.responseXML;
}
else{
    xmlDoc2 = new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc2.async="false";
    xmlDoc2.load("xml/table_inventaire.xml");
}
tagObj2=xmlDoc2.getElementsByTagName("Article");
var typeValue = tagObj2[0].getElementsByTagName("marque")[0].childNodes[0].nodeValue;
var serializer = new XMLSerializer();
var xmlString_first_step = serializer.serializeToString(xmlDoc2);
var parser = new DOMParser();
xmlDoc_first_step = parser.parseFromString(xmlString_first_step, "text/xml");
//var titleValue = tagObj2[0].getElementsByTagName("gamme")[0].childNodes[0].nodeValue;
//alert(typeValue);
//alert("hello");
}
readXml2();



$( ".large_button" ).click(function() {
  //alert( "Handler for .click() called." );
  document.getElementById("select_CB").focus();
});

function remplir_select_marque(){




var our_table = xmlDoc_first_step.getElementsByTagName("Article");
var inner_select_marque ="<option selected value='0'>Tous</option>";
var les_marques = [];

for (var i = 0; i < our_table.length; i++) {
	
	var nbr_piece_select = our_table[i].firstChild;
	o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
	var nbr_colisage_select = get_nextsibling(nbr_piece_select);
	o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
	var marque = get_nextsibling(nbr_colisage_select);
	o_marque = marque.childNodes[0].nodeValue;
	//if()($.inArray(o_RefArticle,ref_article_has_value)
	if($.inArray(o_marque,les_marques)==-1){
		les_marques.push(o_marque);
		inner_select_marque+="<option value='"+o_marque+"'>"+o_marque+"</option>";
	}
	
}

$("#inputmarque").html(inner_select_marque);

}

remplir_select_marque();

function read_table(){
document.getElementById("select_CB").focus();
var total_ecart_colisage = 0;
var total_ecart_piece = 0;
var our_table = xmlDoc_first_step.getElementsByTagName("Article");

var inner_our_table_row = "";
var inner_our_table_row_green = "";
for (var i = 0; i < our_table.length; i++) {
	
	var nbr_piece_select = our_table[i].firstChild;
	o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
	var nbr_colisage_select = get_nextsibling(nbr_piece_select);
	o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
	var marque = get_nextsibling(nbr_colisage_select);
	o_marque = marque.childNodes[0].nodeValue;
	var RefArticle_ = get_nextsibling(marque);
	o_RefArticle = RefArticle_.childNodes[0].nodeValue;
	var colisagee = get_nextsibling(RefArticle_);
	o_colisagee = colisagee.childNodes[0].nodeValue;
	var qteDispoEnBoite = get_nextsibling(colisagee);
	o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
	var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
	o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
	var nbr_coli_ = get_nextsibling(DsgArticle_);
	o_nbr_coli = nbr_coli_.childNodes[0].nodeValue;
	//alert(o_nbr_piece_select);
	//console.log(o_nbr_coli);
	var total_theorique = (Number(o_colisagee) * Number(o_nbr_coli))
	 + Number(o_qteDispoEnBoite);
	 if(o_nbr_piece_select == -1) o_nbr_piece_select =0;
	 if(o_nbr_colisage_select == -1) o_nbr_colisage_select=0;
	 var total_input = (Number(o_nbr_colisage_select) * Number(o_nbr_coli)) +
	 Number(o_nbr_piece_select);
	 var real_ecart = total_input - total_theorique;
	 console.log(real_ecart);
	if(o_nbr_piece_select == 0 &&  o_nbr_colisage_select == 0){
		inner_our_table_row += "<tr class='red_back'>";
	}else{
		inner_our_table_row_green += "<tr class='green_back'>";

    if($.inArray(o_RefArticle,ref_article_has_value) == -1){
      ref_article_has_value.push(o_RefArticle);
      design_article_has_value.push(o_DsgArticle);
     
      var users = xmlDoc_details_article.getElementsByTagName("Article");
		var t_cb = "";
		for (var j = 0; j < users.length; j++) {   
		    //var user = users[i].firstChild.nodeValue;
		    //var user = users[i].firstChild.nodeName
		    
		    var article_id = users[j].firstChild;
		    var reference_m = get_nextsibling(article_id);
		    var design_m = get_nextsibling(reference_m);
		    var media_m = get_nextsibling(design_m);
		    var gamme_m = get_nextsibling(media_m);
		    var marque_m = get_nextsibling(gamme_m);
		    var cb = get_nextsibling(marque_m);
		    //console.log(cb.childNodes[0].nodeValue);
		    //test_ref = users.find("marque");
		    //alert("user : "+user+" test "+reference.nodeName);
		    //alert(reference.childNodes[0].nodeValue);
		    if( reference_m.childNodes[0].nodeValue == o_RefArticle) {
		    	t_cb = cb.childNodes[0].nodeValue;

		    } 
		    
		}  
		cb_article_has_value.push(t_cb); 
		

    }
	}
	var users_2 = xmlDoc_details_article.getElementsByTagName("Article");
		var t_cb_2 = "";
		var t_marque_2 = "";
		for (var j = 0; j < users_2.length; j++) {   
		    //var user = users[i].firstChild.nodeValue;
		    //var user = users[i].firstChild.nodeName
		    
		    var article_id = users_2[j].firstChild;
		    var reference_m = get_nextsibling(article_id);
		    var design_m = get_nextsibling(reference_m);
		    var media_m = get_nextsibling(design_m);
		    var gamme_m = get_nextsibling(media_m);
		    var marque_m = get_nextsibling(gamme_m);
		    var cb = get_nextsibling(marque_m);
		    //console.log(cb.childNodes[0].nodeValue);
		    //test_ref = users.find("marque");
		    //alert("user : "+user+" test "+reference.nodeName);
		    //alert(reference.childNodes[0].nodeValue);
		    if( reference_m.childNodes[0].nodeValue == o_RefArticle) {
		    	t_cb_2 = cb.childNodes[0].nodeValue;
		    	t_marque_2 = marque_m.childNodes[0].nodeValue;
		    } 
		    
		}
	//$( "select#foo" ).val(); 
	//console.log(cb_article_has_value);
	//$( "#our_table_body" ).append("<td>gg</td>"); DsgArticle qteDispoEnBoite
	// inner_our_table_row_green
	//inner_our_table_row += "<td>"+o_marque+"</td>";
	if($( "select#inputmarque" ).val()=="0" || $( "select#inputmarque" ).val() == t_marque_2){



	
	if(o_nbr_colisage_select == -1){
		o_nbr_colisage_select = 0;
	}
	
	if(o_nbr_piece_select == -1)
	o_nbr_piece_select = 0;	
	if(o_nbr_piece_select == 0 && o_nbr_colisage_select == 0){

	inner_our_table_row += "<td>"+o_RefArticle+"</td>";
	inner_our_table_row += "<td>"+o_DsgArticle+"<br>"+t_cb_2+"</td>";
	inner_our_table_row += "<td>"+o_nbr_coli+"</td>";
	inner_our_table_row += "<td>"+o_colisagee+"</td>";
	inner_our_table_row += "<td>"+o_qteDispoEnBoite+"</td>";
	//inner_our_table_row += "<td>"+o_nbr_coli+"</td>";
	
	var ecart_colisage = - Number(o_colisagee) + Number(o_nbr_colisage_select);
	var ecart_piece = - Number(o_qteDispoEnBoite) + Number(o_nbr_piece_select); 

		
	inner_our_table_row+="<td>"+o_nbr_colisage_select+"</td>";
	
	  	
	inner_our_table_row+="<td>"+o_nbr_piece_select+"</td>";
	    
	
	inner_our_table_row+="<td>"+real_ecart+"</td>";
    total_ecart_colisage += Number(ecart_colisage);
	
	
	
	inner_our_table_row += "</tr>";
	}else{
	inner_our_table_row_green += "<td>"+o_RefArticle+"</td>";
	inner_our_table_row_green += "<td>"+o_DsgArticle+"<br>"+t_cb_2+"</td>";
	inner_our_table_row_green += "<td>"+o_nbr_coli+"</td>";
	inner_our_table_row_green += "<td>"+o_colisagee+"</td>";
	inner_our_table_row_green += "<td>"+o_qteDispoEnBoite+"</td>";
	
	var ecart_colisage = - Number(o_colisagee) + Number(o_nbr_colisage_select);
	var ecart_piece = - Number(o_qteDispoEnBoite) + Number(o_nbr_piece_select); 

		
	inner_our_table_row_green+="<td>"+o_nbr_colisage_select+"</td>";
	
	  	
	inner_our_table_row_green+="<td>"+o_nbr_piece_select+"</td>";
	    
	
	inner_our_table_row_green+="<td>"+real_ecart+"</td>";
    total_ecart_colisage += Number(ecart_colisage);
	
	
	inner_our_table_row_green += "</tr>";
	}
	}
	
}
/*$("#for_pdf").html(inner_our_table_row_green + inner_our_table_row);
var pdf = new jsPDF('p','pt','a4');
//var test_element = document.createElement(inner_our_table_row_green);   
pdf.addHTML(document.getElementById("for_pdf"),function() {
    pdf.save('web.pdf');
});*/
$( "#our_table_body" ).html(inner_our_table_row_green + inner_our_table_row);
var inner_tfoot ="<tr><td colspan='5'>Total écart <td><td>écart</td>";
inner_tfoot+="<td>"+total_ecart_colisage+"</td>";
inner_tfoot+="</tr>";
$( "#our_tfoot" ).html(inner_tfoot);   
}
read_table();
$( "select#inputmarque" ).change(function() {
  //alert( "Handler for .change() called." );
  read_table();
});
function select_ref(ref){
	select_ref_v.value = "";
	var old_total_piece = "0";
	var old_total_colisage = "0";
	var old_input_colisage = "0";
	var old_input_piece ="0";
	var colisage_value ="0";
		var our_table = xmlDoc_first_step.getElementsByTagName("Article");

	    for (var i = 0; i < our_table.length; i++) {
		
		var nbr_piece_select = our_table[i].firstChild;
		o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
		var nbr_colisage_select = get_nextsibling(nbr_piece_select);
		o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
		var marque = get_nextsibling(nbr_colisage_select);
		o_marque = marque.childNodes[0].nodeValue;
		var RefArticle_ = get_nextsibling(marque);
		o_RefArticle = RefArticle_.childNodes[0].nodeValue;
		var colisagee = get_nextsibling(RefArticle_);
		o_colisagee = colisagee.childNodes[0].nodeValue;
		var qteDispoEnBoite = get_nextsibling(colisagee);
		o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
		var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
		o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
		var nbr_coli_ = get_nextsibling(DsgArticle_);
	    o_nbr_coli = nbr_coli_.childNodes[0].nodeValue;
		if(ref == o_RefArticle){
			old_total_colisage = o_colisagee;
			old_total_piece = o_qteDispoEnBoite;
			old_input_colisage = o_nbr_colisage_select;
			old_input_piece = o_nbr_piece_select;
			colisage_value = o_nbr_coli;
		}
	    }
	    $("#myModal_ref #total_existant .total_boites").html(old_total_colisage);
	    $("#myModal_ref #total_existant .total_pieces").html(old_total_piece);
	    $("#myModal_ref #total_existant .colisage_modal").html(colisage_value);
	    //
	    if(old_input_colisage == -1) old_input_colisage = 0;
	    $("#myModal_ref #input_colisage").html(old_input_colisage);
	    if(old_input_piece == -1 ) old_input_piece = 0 ;
	    $("#myModal_ref #input_piece").html(old_input_piece);
	    update_ecart_ref();
      /*
      if($.inArray(ref,ref_article_has_value)>-1){
      $( "#myModal_ref .operatores").show();
      //$("#myModal_ref #total_existant").show();
  
		
		  }else{
		      $( "#myModal_ref .operatores").hide();
		      //$("#myModal_ref #total_existant").hide();
		    }
		*/
		var users = xmlDoc_details_article.getElementsByTagName("Article");
		var isOK = false;
		var s_ref ="";
		var s_desig = "";
		var s_gamme = "";
		var s_marque = "";
		var s_media ="";
		for (var i = 0; i < users.length; i++) {   
		    //var user = users[i].firstChild.nodeValue;
		    var user = users[i].firstChild.nodeName
		    var article_id = users[i].firstChild;
		    var reference = get_nextsibling(article_id);
		    //test_ref = users.find("marque");
		    //alert("user : "+user+" test "+reference.nodeName);
		    //alert(reference.childNodes[0].nodeValue);
		    if(reference.childNodes[0].nodeValue == ref) {
		    	//alert(reference.nodeValue);
		    	isOK = true;
		    	var design = get_nextsibling(reference);
		    	s_desig = design.childNodes[0].nodeValue;
		    	s_ref = reference.childNodes[0].nodeValue;
		    	var media = get_nextsibling(design);
		    	s_media = media.childNodes[0].nodeValue;
		    	var gamme = get_nextsibling(media);
		    	s_gamme = gamme.childNodes[0].nodeValue;
		    	var marque = get_nextsibling(gamme);
		    	s_marque = marque.childNodes[0].nodeValue;
		    }  
		}  
		if(isOK) {
		$('#ref_design_select_ref_modal').html("Ref : <span id='updated_xml_ref'>"+s_ref+"</span>	| Désignation  :"+s_desig);
		//alert(s_media);
		$img_src = "../"+s_media;
		//alert($img_src);
		$(".img_article_modal").attr("src",$img_src);
		$('#gamme_marque_select_ref_modal').html("Gamme : "+s_gamme+" <br> Marque : "+s_marque);
		$('#myModal_ref').modal('show');	
		}else{
			alert("Aucun résultat");
		}
		
}
function update_ecart_ref(){
	var total_boites = $("#myModal_ref #total_existant .total_boites").html();
	var total_pieces = $("#myModal_ref #total_existant .total_pieces").html();
	var colisage_modal = $("#myModal_ref #total_existant .colisage_modal").html();
	
	var input_boites = $("#myModal_ref #input_colisage").html();
	var input_pices = $("#myModal_ref #input_piece").html();
	var ecart =  - (Number(total_boites)*Number(colisage_modal)+Number(total_pieces)) 
	+ (Number(input_boites)*Number(colisage_modal)+Number(input_pices));
	
	$("#myModal_ref #ecart_modal #ecart_pieces").html(ecart.toString());
}
function update_ecart_cb(){
	var total_boites = $("#myModal_cb #total_existant .total_boites").html();
	var total_pieces = $("#myModal_cb #total_existant .total_pieces").html();
	var colisage_modal = $("#myModal_cb #total_existant .colisage_modal").html();
	var input_boites = $("#myModal_cb #input_colisage").html();
	var input_pices = $("#myModal_cb #input_piece").html();
	var ecart =  - (Number(total_boites)*Number(colisage_modal)+Number(total_pieces)) 
	+ (Number(input_boites)*Number(colisage_modal)+Number(input_pices));
	$("#myModal_cb #ecart_modal #ecart_pieces").html(ecart.toString());
}
function update_ecart_design(){
	var total_boites = $("#myModal_design #total_existant .total_boites").html();
	var total_pieces = $("#myModal_design #total_existant .total_pieces").html();
	var colisage_modal = $("#myModal_design #total_existant .colisage_modal").html();
	var input_boites = $("#myModal_design #input_colisage").html();
	var input_pices = $("#myModal_design #input_piece").html();
	var ecart =  - (Number(total_boites)*Number(colisage_modal)+Number(total_pieces)) 
	+ (Number(input_boites)*Number(colisage_modal)+Number(input_pices));
	$("#myModal_design #ecart_modal #ecart_pieces").html(ecart.toString());
}
function get_nextsibling(n) {
    var x = n.nextSibling;
    while (x.nodeType != 1) {
        x = x.nextSibling;
    }
    return x;
}

var select_ref_v = document.getElementById("select_ref");
select_ref_v.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        validate2(e);
    }
});

function validate2(e) {
    //var text = ;
    select_ref(e.target.value);
    //validation of the input...
}
function select_CB(searched_cb){

//------------------------------
select_CB_v.value = "";

var s_ref ="";
var colisage_value ="0";

//-------------------------

		var users = xmlDoc_details_article.getElementsByTagName("Article");
		var isOK = false;
		
		var s_desig = "";
		var s_gamme = "";
		var s_marque = "";
		var s_media ="";
		for (var i = 0; i < users.length; i++) {   
		    //var user = users[i].firstChild.nodeValue;
		    //var user = users[i].firstChild.nodeName
		    var article_id = users[i].firstChild;
		    var reference_m = get_nextsibling(article_id);
		    var design_m = get_nextsibling(reference_m);
		    var media_m = get_nextsibling(design_m);
		    var gamme_m = get_nextsibling(media_m);
		    var marque_m = get_nextsibling(gamme_m);
		    var cb = get_nextsibling(marque_m);
		    var s_cb = cb.childNodes[0].nodeValue;
		    console.log(s_cb);
		    //test_ref = users.find("marque");
		    //alert("user : "+user+" test "+reference.nodeName);
		    //alert(reference.childNodes[0].nodeValue);
		    if( s_cb == searched_cb) {
		    	//alert(reference.nodeValue);

		    	isOK = true;
		    	//var design = get_nextsibling(reference);
		    	s_desig = design_m.childNodes[0].nodeValue;
		    	s_ref = reference_m.childNodes[0].nodeValue;
		    	//var media = get_nextsibling(design);
		    	s_media = media_m.childNodes[0].nodeValue;
		    	//var gamme = get_nextsibling(media);
		    	s_gamme = gamme_m.childNodes[0].nodeValue;
		    	//var marque = get_nextsibling(gamme);
		    	s_marque = marque_m.childNodes[0].nodeValue;
		    }  
		}  
		if(isOK) {
		$('#ref_design_select_cb_modal').html("Ref : <span id='updated_xml_ref'>"+s_ref+"</span> | Désignation  :"+s_desig);
		//alert(s_media);
		img_src = "../"+s_media;
		//alert(img_src);
		$(".img_article_modal").attr("src",img_src);
		$('#gamme_marque_select_cb_modal').html("Gamme : "+s_gamme+" <br> Marque : "+s_marque);
		$('#myModal_cb').modal('show');	
		}else{
			alert("Aucun résultat");
		}

var old_total_piece = "0";
		var old_total_colisage = "0";
		var our_table = xmlDoc_first_step.getElementsByTagName("Article");

	    for (var i = 0; i < our_table.length; i++) {
		
		var nbr_piece_select = our_table[i].firstChild;
		o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
		var nbr_colisage_select = get_nextsibling(nbr_piece_select);
		o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
		var marque = get_nextsibling(nbr_colisage_select);
		o_marque = marque.childNodes[0].nodeValue;
		var RefArticle_ = get_nextsibling(marque);
		o_RefArticle = RefArticle_.childNodes[0].nodeValue;

		var colisagee = get_nextsibling(RefArticle_);
		o_colisagee = colisagee.childNodes[0].nodeValue;
		var qteDispoEnBoite = get_nextsibling(colisagee);
		o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
		var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
		o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
		var nbr_coli_ = get_nextsibling(DsgArticle_);
	    o_nbr_coli = nbr_coli_.childNodes[0].nodeValue;
//******************************************


		if(s_ref == o_RefArticle){
			old_total_colisage = o_colisagee;
			old_total_piece = o_qteDispoEnBoite;
			old_input_colisage = o_nbr_colisage_select;
			old_input_piece = o_nbr_piece_select;
			colisage_value = o_nbr_coli;
		}
	    }
	    $("#myModal_cb #total_existant .total_boites").html(old_total_colisage);
	    $("#myModal_cb #total_existant .total_pieces").html(old_total_piece);
	    $("#myModal_cb #total_existant .colisage_modal").html(colisage_value);
	    if(old_input_colisage == -1) old_input_colisage = 0;
	    $("#myModal_cb #input_colisage").html(old_input_colisage);
	    if(old_input_piece == -1 ) old_input_piece = 0 ;
	    $("#myModal_cb #input_piece").html(old_input_piece);
	    update_ecart_cb();
		/*
			if($.inArray(searched_cb,cb_article_has_value)>-1){
		      //$( "#myModal_cb .operatores").show();
		      //$("#myModal_cb #total_existant").show();
		  
				
				  }else{
				      $( "#myModal_cb .operatores").hide();
				      $("#myModal_cb #total_existant").hide();
				    }
		*/
		
}	
var select_CB_v = document.getElementById("select_CB");
select_CB_v.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        validate1(e);
    }
});

function validate1(e) {
    //var text = e.target.value;
    //validation of the input...
    select_CB(e.target.value);
}
function select_designation(searched_designation){

//*************************************
var colisage_value ="0";
	var old_total_piece = "0";
		var old_total_colisage = "0";
		var our_table = xmlDoc_first_step.getElementsByTagName("Article");

	    for (var i = 0; i < our_table.length; i++) {
		
		var nbr_piece_select = our_table[i].firstChild;
		o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
		var nbr_colisage_select = get_nextsibling(nbr_piece_select);
		o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
		var marque = get_nextsibling(nbr_colisage_select);
		o_marque = marque.childNodes[0].nodeValue;
		var RefArticle_ = get_nextsibling(marque);
		o_RefArticle = RefArticle_.childNodes[0].nodeValue;
		var colisagee = get_nextsibling(RefArticle_);
		o_colisagee = colisagee.childNodes[0].nodeValue;
		var qteDispoEnBoite = get_nextsibling(colisagee);
		o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
		var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
		o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
		var nbr_coli_ = get_nextsibling(DsgArticle_);
	    o_nbr_coli = nbr_coli_.childNodes[0].nodeValue;
		if(searched_designation == o_DsgArticle){
			old_total_colisage = o_colisagee;
			old_total_piece = o_qteDispoEnBoite;
			old_input_colisage = o_nbr_colisage_select;
			old_input_piece = o_nbr_piece_select;
			colisage_value = o_nbr_coli;
		}
	    }
	    $("#myModal_design #total_existant .total_boites").html(old_total_colisage);
	    $("#myModal_design #total_existant .total_pieces").html(old_total_piece);
	    $("#myModal_design #total_existant .colisage_modal").html(colisage_value);
	    if(old_input_colisage == -1) old_input_colisage = 0;
	    $("#myModal_design #input_colisage").html(old_input_colisage);
	    if(old_input_piece == -1 ) old_input_piece = 0 ;
	    $("#myModal_design #input_piece").html(old_input_piece);
	    update_ecart_design();
    /*
    if($.inArray(searched_designation,design_article_has_value)>-1){
      //$( "#myModal_design .operatores").show();
      //$("#myModal_design #total_existant").show();
  
		
		  }else{
		      $( "#myModal_design .operatores").hide();
		      $("#myModal_design #total_existant").hide();
		    }

	*/


//*************************************
		select_designation_v.value = "";
		var users = xmlDoc_details_article.getElementsByTagName("Article");
		var isOK = false;
		var s_ref ="";
		var s_desig = "";
		var s_gamme = "";
		var s_marque = "";
		var s_media ="";
		for (var i = 0; i < users.length; i++) {   
		    //var user = users[i].firstChild.nodeValue;
		    //var user = users[i].firstChild.nodeName
		    var article_id = users[i].firstChild;
		    var reference_m = get_nextsibling(article_id);
		    var design_m = get_nextsibling(reference_m);
		    var media_m = get_nextsibling(design_m);
		    var gamme_m = get_nextsibling(media_m);
		    var marque_m = get_nextsibling(gamme_m);
		    var cb = get_nextsibling(marque_m);
		    //test_ref = users.find("marque");
		    //alert("user : "+user+" test "+reference.nodeName);
		    //alert(reference.childNodes[0].nodeValue);
		    if(design_m.childNodes[0].nodeValue == searched_designation) {
		    	//alert(reference.nodeValue);
		    	isOK = true;
		    	//var design = get_nextsibling(reference);
		    	s_desig = design_m.childNodes[0].nodeValue;
		    	s_ref = reference_m.childNodes[0].nodeValue;
		    	//var media = get_nextsibling(design);
		    	s_media = media_m.childNodes[0].nodeValue;
		    	//var gamme = get_nextsibling(media);
		    	s_gamme = gamme_m.childNodes[0].nodeValue;
		    	//var marque = get_nextsibling(gamme);
		    	s_marque = marque_m.childNodes[0].nodeValue;
		    }  
		}  
		if(isOK) {
		$('#ref_design_select_design_modal').html("Ref : <span id='updated_xml_ref'>"+s_ref+"</span>	| Désignation  :"+s_desig);
		//alert(s_media);
		$img_src = "../"+s_media;
		//alert($img_src);
		$(".img_article_modal_design").attr("src",$img_src);
		$('#gamme_marque_select_design_modal').html("Gamme : "+s_gamme+" <br> Marque : "+s_marque);
		$('#myModal_design').modal('show');	
		}else{
			alert("Aucun résultat");
		}
		
}	
var select_designation_v = document.getElementById("select_Designation");
select_designation_v.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        validate3(e);
    }
});

function validate3(e) {
    //var text = e.target.value;
    //validation of the input...
    select_designation(e.target.value);
}
function update_xml_modal_ref(){
	new_colisage = $("#myModal_ref #input_colisage").html();
	new_piece = $("#myModal_ref #input_piece").html();
	updated_xml_ref = $("#myModal_ref #updated_xml_ref").html();

	//alert(updated_xml_ref);
	//alert(new_colisage+" "+new_piece);
	if(isNaN(new_piece) || isNaN(new_colisage) || new_piece == null || new_colisage == null || new_piece == "" || new_colisage == ""){
		alert("il faut remplir les deux champs avec des valeurs numériques")
	}else{

		//alert(new_piece);



	var our_table = xmlDoc_first_step.getElementsByTagName("Article");

	for (var i = 0; i < our_table.length; i++) {
		
		var nbr_piece_select = our_table[i].firstChild;
		o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
		var nbr_colisage_select = get_nextsibling(nbr_piece_select);
		o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
		var marque = get_nextsibling(nbr_colisage_select);
		o_marque = marque.childNodes[0].nodeValue;
		var RefArticle_ = get_nextsibling(marque);
		o_RefArticle = RefArticle_.childNodes[0].nodeValue;
		var colisagee = get_nextsibling(RefArticle_);
		o_colisagee = colisagee.childNodes[0].nodeValue;
		var qteDispoEnBoite = get_nextsibling(colisagee);
		o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
		var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
		o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
		if(o_RefArticle == updated_xml_ref){
			//alert("is_find_it");
			/*if($.inArray(updated_xml_ref,ref_article_has_value)>-1){
				if(ref_add_more_colisage == true){
						new_colisage =  Number(o_nbr_colisage_select) +
						 Number(new_colisage);
				}else{
						new_colisage =  Number(o_nbr_colisage_select) - 
						Number(new_colisage);
				}
				if(ref_add_more_piece == true){
					new_piece =  Number(o_nbr_piece_select) +
						 Number(new_piece);
				}else{
					new_piece =  Number(o_nbr_piece_select) - 
						Number(new_piece);
				}
			}*/
			
			nbr_piece_select.childNodes[0].data = new_piece;
			nbr_colisage_select.childNodes[0].data = new_colisage;
		}


	}
	read_table();
	$('#myModal_ref').modal('hide');
	document.getElementById("select_CB").focus();
  $("#myModal_ref #input_colisage").html("0");
  $("#myModal_ref #input_piece").html("0");
   input_piece_ref="0";
  input_colisage_ref="0";
  input_piece_ds = "0";
  input_colisage_ds = "0";
  input_piece_cb="0";
  input_colisage_cb="0";

    }
}
$("#give_new_data_to_xml_ref_modal").click(update_xml_modal_ref);
$("#give_new_data_to_xml_design_modal").click(update_xml_modal_design);
$("#give_new_data_to_xml_cb_modal").click(update_xml_modal_cb);
function update_xml_modal_cb(){
	new_colisage = $("#myModal_cb #input_colisage").html();
	new_piece = $("#myModal_cb #input_piece").html();
	updated_xml_ref = $("#myModal_cb #updated_xml_ref").html();
	//alert(updated_xml_ref);
	//alert(new_colisage+" "+new_piece);
	if(isNaN(new_piece) || isNaN(new_colisage) || new_piece == null || new_colisage == null || new_piece == "" || new_colisage == ""){
		alert("il faut remplir les deux champs avec des valeurs numériques")
	}else{

		//alert(new_piece);



	var our_table = xmlDoc_first_step.getElementsByTagName("Article");

	for (var i = 0; i < our_table.length; i++) {
		
		var nbr_piece_select = our_table[i].firstChild;
		o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
		var nbr_colisage_select = get_nextsibling(nbr_piece_select);
		o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
		var marque = get_nextsibling(nbr_colisage_select);
		o_marque = marque.childNodes[0].nodeValue;
		var RefArticle_ = get_nextsibling(marque);
		o_RefArticle = RefArticle_.childNodes[0].nodeValue;
		var colisagee = get_nextsibling(RefArticle_);
		o_colisagee = colisagee.childNodes[0].nodeValue;
		var qteDispoEnBoite = get_nextsibling(colisagee);
		o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
		var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
		o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
		if(o_RefArticle == updated_xml_ref){
			/*
			if($.inArray(updated_xml_ref,ref_article_has_value)>-1){
				if(cb_add_more_colisage == true){
						new_colisage =  Number(o_nbr_colisage_select) +
						 Number(new_colisage);
				}else{
						new_colisage =  Number(o_nbr_colisage_select) - 
						Number(new_colisage);
				}
				if(cb_add_more_piece == true){
					new_piece =  Number(o_nbr_piece_select) +
						 Number(new_piece);
				}else{
					new_piece =  Number(o_nbr_piece_select) - 
						Number(new_piece);
				}
			}
			*/
			//alert("is_find_it");
			nbr_piece_select.childNodes[0].data = new_piece;
			nbr_colisage_select.childNodes[0].data = new_colisage;
		}


	}
	read_table();
	$('#myModal_cb').modal('hide');
	document.getElementById("select_CB").focus();
  $("#myModal_cb #input_colisage").html("0");
  $("#myModal_cb #input_piece").html("0");
   input_piece_ref="0";
  input_colisage_ref="0";
  input_piece_ds = "0";
  input_colisage_ds = "0";
   input_piece_cb="0";
  input_colisage_cb="0";
    }
}
function update_xml_modal_design(){
	new_colisage = $("#myModal_design #input_colisage").html();
	new_piece = $("#myModal_design #input_piece").html();
	updated_xml_ref = $("#myModal_design #updated_xml_ref").html();
	//alert(updated_xml_ref);
	//alert(new_colisage+" "+new_piece);
	if(isNaN(new_piece) || isNaN(new_colisage) || new_piece == null || new_colisage == null || new_piece == "" || new_colisage == ""){
		alert("il faut remplir les deux champs avec des valeurs numériques")
	}else{





	var our_table = xmlDoc_first_step.getElementsByTagName("Article");

	for (var i = 0; i < our_table.length; i++) {
		
		var nbr_piece_select = our_table[i].firstChild;
		o_nbr_piece_select = nbr_piece_select.childNodes[0].nodeValue;
		var nbr_colisage_select = get_nextsibling(nbr_piece_select);
		o_nbr_colisage_select = nbr_colisage_select.childNodes[0].nodeValue;
		var marque = get_nextsibling(nbr_colisage_select);
		o_marque = marque.childNodes[0].nodeValue;
		var RefArticle_ = get_nextsibling(marque);
		o_RefArticle = RefArticle_.childNodes[0].nodeValue;
		var colisagee = get_nextsibling(RefArticle_);
		o_colisagee = colisagee.childNodes[0].nodeValue;
		var qteDispoEnBoite = get_nextsibling(colisagee);
		o_qteDispoEnBoite = qteDispoEnBoite.childNodes[0].nodeValue;
		var DsgArticle_ = get_nextsibling(qteDispoEnBoite);
		o_DsgArticle = DsgArticle_.childNodes[0].nodeValue;
		if(o_RefArticle == updated_xml_ref){
			//alert("is_find_it");
			/*
			if($.inArray(updated_xml_ref,ref_article_has_value)>-1){
				if(design_add_more_colisage == true){
						new_colisage =  Number(o_nbr_colisage_select) +
						 Number(new_colisage);
				}else{
						new_colisage =  Number(o_nbr_colisage_select) - 
						Number(new_colisage);
				}
				if(design_add_more_piece == true){
					new_piece =  Number(o_nbr_piece_select) +
						 Number(new_piece);
				}else{
					new_piece =  Number(o_nbr_piece_select) - 
						Number(new_piece);
				}
			}
			*/
			nbr_piece_select.childNodes[0].data = new_piece;
			nbr_colisage_select.childNodes[0].data = new_colisage;
		}


	}
	read_table();
	$('#myModal_design').modal('hide');
	document.getElementById("select_CB").focus();
   $("#myModal_design #input_colisage").html("0");
  $("#myModal_design #input_piece").html("0");
  input_piece_ref="0";
  input_colisage_ref="0";
  input_piece_ds = "0";
  input_colisage_ds = "0";
   input_piece_cb="0";
  input_colisage_cb="0";
    }
}

var xml_download = document.getElementById("save_xml");
xml_download.onclick = function(){
       //alert("it_is_clicked");
      var serializer = new XMLSerializer();
      var xmlString = serializer.serializeToString(xmlDoc_first_step);
       //var file = new File([xmlString], "hello world.txt", {type: "text/plain;charset=utf-8"});
      //FileSaver.saveAs(file); 
      var blob = new Blob([xmlString], {type: "text/plain;charset=utf-8"});
      saveAs(blob, "unsaved data.txt");     

};
var is_colisage_calcul_checked_ref = true;
var input_colisage_ref = "0";
var input_piece_ref = "0";
var is_colisage_calcul_checked_ds = true;
var input_colisage_ds = "0";
var input_piece_ds = "0";
var is_colisage_calcul_checked_cb = true;
var input_colisage_cb = "0";
var input_piece_cb = "0";
$( "#myModal_ref #check_colisage_for_calc" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #3498db');
  $(this).css('border', '5px solid #3498db');
  $( "#myModal_ref #check_piece_for_calc" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_ref #check_piece_for_calc" ).css('border', '5px solid #fff');
  is_colisage_calcul_checked_ref = true;
});

$( "#myModal_ref #check_piece_for_calc" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #3498db');
  $(this).css('border', '5px solid #3498db');
  $( "#myModal_ref #check_colisage_for_calc" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_ref #check_colisage_for_calc" ).css('border', '5px solid #fff');
  is_colisage_calcul_checked_ref = false;
});

//---------------------
$( "#myModal_cb #check_colisage_for_calc" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #3498db');
  $(this).css('border', '5px solid #3498db');
  $( "#myModal_cb #check_piece_for_calc" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_cb #check_piece_for_calc" ).css('border', '5px solid #fff');
  is_colisage_calcul_checked_cb = true;
});

$( "#myModal_cb #check_piece_for_calc" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #3498db');
  $(this).css('border', '5px solid #3498db');
  $( "#myModal_cb #check_colisage_for_calc" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_cb #check_colisage_for_calc" ).css('border', '5px solid #fff');
  is_colisage_calcul_checked_cb = false;
});


//-------------------------
$( "#myModal_design #check_colisage_for_calc" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #3498db');
  $(this).css('border', '5px solid #3498db');
  $( "#myModal_design #check_piece_for_calc" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_design #check_piece_for_calc" ).css('border', '5px solid #fff');
  is_colisage_calcul_checked_ds = true;
});

$( "#myModal_design #check_piece_for_calc" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #3498db');
  $(this).css('border', '5px solid #3498db');
  $( "#myModal_design #check_colisage_for_calc" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_design #check_colisage_for_calc" ).css('border', '5px solid #fff');
  is_colisage_calcul_checked_ds = false;
});



ref_add_more_colisage = true;
ref_add_more_piece = true;

$( "#myModal_ref #add_more_colisage" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #2ecc71');
  $(this).css('border', '5px solid #2ecc71');
  $( "#myModal_ref #substract_colisage" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_ref #substract_colisage" ).css('border', '5px solid #fff');
  ref_add_more_colisage = true;
  var x = $("#myModal_ref #input_colisage").html();
  var y = Number(x)+1;
  $("#myModal_ref #input_colisage").html(y.toString());
  update_ecart_ref();
  
});

$( "#myModal_ref #substract_colisage" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #e74c3c');
  $(this).css('border', '5px solid #e74c3c');
  $( "#myModal_ref #add_more_colisage" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_ref #add_more_colisage" ).css('border', '5px solid #fff');
  ref_add_more_colisage = false;
  var x = $("#myModal_ref #input_colisage").html();
  var y = Number(x)-1;
  if(y<0) y=0;
  $("#myModal_ref #input_colisage").html(y.toString());
  update_ecart_ref();
});



$( "#myModal_ref #add_more_pieces" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #2ecc71');
  $(this).css('border', '5px solid #2ecc71');
  $( "#myModal_ref #substract_pieces" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_ref #substract_pieces" ).css('border', '5px solid #fff');
 	ref_add_more_piece = true;
  var x = $("#myModal_ref #input_piece").html();
  var y = Number(x)+1;
  $("#myModal_ref #input_piece").html(y.toString());
  update_ecart_ref();
});

$( "#myModal_ref #substract_pieces" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #e74c3c');
  $(this).css('border', '5px solid #e74c3c');
  $( "#myModal_ref #add_more_pieces" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_ref #add_more_pieces" ).css('border', '5px solid #fff');
  ref_add_more_piece = false;
  var x = $("#myModal_ref #input_piece").html();
  var y = Number(x)-1;
  if(y<0) y=0;
  $("#myModal_ref #input_piece").html(y.toString());
  update_ecart_ref();
});
 //-----------------------------------------------------------

cb_add_more_colisage = true;
cb_add_more_piece = true;

$( "#myModal_cb #add_more_colisage" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #2ecc71');
  $(this).css('border', '5px solid #2ecc71');
  $( "#myModal_cb #substract_colisage" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_cb #substract_colisage" ).css('border', '5px solid #fff');
  cb_add_more_colisage = true;
  var x = $("#myModal_cb #input_colisage").html();
  var y = Number(x)+1;
  $("#myModal_cb #input_colisage").html(y.toString());
  update_ecart_cb();
});

$( "#myModal_cb #substract_colisage" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #e74c3c');
  $(this).css('border', '5px solid #e74c3c');
  $( "#myModal_cb #add_more_colisage" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_cb #add_more_colisage" ).css('border', '5px solid #fff');
  cb_add_more_colisage = false;
  var x = $("#myModal_cb #input_colisage").html();
  var y = Number(x)-1;
  if(y<0) y=0;
  $("#myModal_cb #input_colisage").html(y.toString());
  update_ecart_cb();
});



$( "#myModal_cb #add_more_pieces" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #2ecc71');
  $(this).css('border', '5px solid #2ecc71');
  $( "#myModal_cb #substract_pieces" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_cb #substract_pieces" ).css('border', '5px solid #fff');
 	cb_add_more_piece = true;
  var x = $("#myModal_cb #input_piece").html();
  var y = Number(x)+1;
  $("#myModal_cb #input_piece").html(y.toString());
  update_ecart_cb();
});

$( "#myModal_cb #substract_pieces" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #e74c3c');
  $(this).css('border', '5px solid #e74c3c');
  $( "#myModal_cb #add_more_pieces" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_cb #add_more_pieces" ).css('border', '5px solid #fff');
  cb_add_more_piece = false;
  var x = $("#myModal_cb #input_piece").html();
  var y = Number(x)-1;
  if(y<0) y=0;
  $("#myModal_cb #input_piece").html(y.toString());
  update_ecart_cb();
});


//********************************************************

design_add_more_colisage = true;
design_add_more_piece = true;

$( "#myModal_design #add_more_colisage" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #2ecc71');
  $(this).css('border', '5px solid #2ecc71');
  $( "#myModal_design #substract_colisage" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_design #substract_colisage" ).css('border', '5px solid #fff');
  design_add_more_colisage = true;
  var x = $("#myModal_design #input_colisage").html();
  var y = Number(x)+1;
  $("#myModal_design #input_colisage").html(y.toString());
  update_ecart_design();
});

$( "#myModal_design #substract_colisage" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #e74c3c');
  $(this).css('border', '5px solid #e74c3c');
  $( "#myModal_design #add_more_colisage" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_design #add_more_colisage" ).css('border', '5px solid #fff');
  design_add_more_colisage = false;
   var x = $("#myModal_design #input_colisage").html();
  var y = Number(x)-1;
  if(y<0) y=0;
  $("#myModal_design #input_colisage").html(y.toString());
  update_ecart_design();
});



$( "#myModal_design #add_more_pieces" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #2ecc71');
  $(this).css('border', '5px solid #2ecc71');
  $( "#myModal_design #substract_pieces" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_design #substract_pieces" ).css('border', '5px solid #fff');
 	design_add_more_piece = true;
 	var x = $("#myModal_design #input_piece").html();
  var y = Number(x)+1;
  $("#myModal_design #input_piece").html(y.toString());
  update_ecart_design();
});

$( "#myModal_design #substract_pieces" ).click(function() {
  $(this).css('box-shadow', '5px 5px 2px #e74c3c');
  $(this).css('border', '5px solid #e74c3c');
  $( "#myModal_design #add_more_pieces" ).css('box-shadow', '5px 5px 2px #fff');
  $( "#myModal_design #add_more_pieces" ).css('border', '5px solid #fff');
  design_add_more_piece = false;
  var x = $("#myModal_design #input_piece").html();
  var y = Number(x)-1;
  if(y<0) y=0;
  $("#myModal_design #input_piece").html(y.toString());
  update_ecart_design();
});



 //-----------------------------------------------------------


$( "#myModal_ref #number1" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
    if(input_colisage_ref != "0"){
      input_colisage_ref+="1";
    }
    else{
      input_colisage_ref="1";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="1";
    }
    else{
      input_piece_ref="1";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
  }
  update_ecart_ref();
});
//---------------------
$( "#myModal_cb #number1" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="1";
    }
    else{
      input_colisage_cb="1";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="1";
    }
    else{
      input_piece_cb="1";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number2" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="2";
    }
    else{
      input_colisage_cb="2";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="2";
    }
    else{
      input_piece_cb="2";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number3" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="3";
    }
    else{
      input_colisage_cb="3";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="3";
    }
    else{
      input_piece_cb="3";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number4" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="4";
    }
    else{
      input_colisage_cb="4";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="4";
    }
    else{
      input_piece_cb="4";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number5" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="5";
    }
    else{
      input_colisage_cb="5";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="5";
    }
    else{
      input_piece_cb="5";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number6" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="6";
    }
    else{
      input_colisage_cb="6";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="6";
    }
    else{
      input_piece_cb="6";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number7" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="7";
    }
    else{
      input_colisage_cb="7";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="7";
    }
    else{
      input_piece_cb="7";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number8" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="8";
    }
    else{
      input_colisage_cb="8";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="8";
    }
    else{
      input_piece_cb="8";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});

$( "#myModal_cb #number9" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="9";
    }
    else{
      input_colisage_cb="9";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="9";
    }
    else{
      input_piece_cb="9";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);
  }
  update_ecart_cb();
});
//---------------------

$( "#myModal_design #number1" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
    if(input_colisage_ds != "0"){
      input_colisage_ds+="1";
    }
    else{
      input_colisage_ds="1";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="1";
    }
    else{
      input_piece_ds="1";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
  }
  update_ecart_design();
});

$( "#myModal_ref #number2" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="2";
    }
    else{
      input_colisage_ref="2";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="2";
    }
    else{
      input_piece_ref="2";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
  }
  update_ecart_ref();
});

$( "#myModal_design #number2" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="2";
    }
    else{
      input_colisage_ds="2";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="2";
    }
    else{
      input_piece_ds="2";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
  }
  update_ecart_design();
});


$( "#myModal_ref #number3" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="3";
    }
    else{
      input_colisage_ref="3";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="3";
    }
    else{
      input_piece_ref="3";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);

  }
  update_ecart_ref();
});

$( "#myModal_design #number3" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="3";
    }
    else{
      input_colisage_ds="3";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="3";
    }
    else{
      input_piece_ds="3";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);

  }
  update_ecart_design();
});


$( "#myModal_ref #number4" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="4";
    }
    else{
      input_colisage_ref="4";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="4";
    }
    else{
      input_piece_ref="4";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
  }
  update_ecart_ref();
});

$( "#myModal_design #number4" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="4";
    }
    else{
      input_colisage_ds="4";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="4";
    }
    else{
      input_piece_ds="4";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
  }
  update_ecart_design();
});


$( "#myModal_ref #number5" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="5";
    }
    else{
      input_colisage_ref="5";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="5";
    }
    else{
      input_piece_ref="5";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
  }
  update_ecart_ref();
});


$( "#myModal_design #number5" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="5";
    }
    else{
      input_colisage_ds="5";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="5";
    }
    else{
      input_piece_ds="5";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
  }
  update_ecart_design();
});


$( "#myModal_ref #number6" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="6";
    }
    else{
      input_colisage_ref="6";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="6";
    }
    else{
      input_piece_ref="6";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
  }
  update_ecart_ref();
});

$( "#myModal_design #number6" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="6";
    }
    else{
      input_colisage_ds="6";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="6";
    }
    else{
      input_piece_ds="6";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
  }
  update_ecart_design();
});

$( "#myModal_ref #number7" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="7";
    }
    else{
      input_colisage_ref="7";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="7";
    }
    else{
      input_piece_ref="7";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
   }
   update_ecart_ref();
});

$( "#myModal_design #number7" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="7";
    }
    else{
      input_colisage_ds="7";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="7";
    }
    else{
      input_piece_ds="7";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
   }
   update_ecart_design();
});

$( "#myModal_ref #number8" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="8";
    }
    else{
      input_colisage_ref="8";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="8";
    }
    else{
      input_piece_ref="8";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
    }
    update_ecart_ref();
});

$( "#myModal_design #number8" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="8";
    }
    else{
      input_colisage_ds="8";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="8";
    }
    else{
      input_piece_ds="8";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
    }
    update_ecart_design();
});

$( "#myModal_ref #number9" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="9";
    }
    else{
      input_colisage_ref="9";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="9";
    }
    else{
      input_piece_ref="9";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);
    }
    update_ecart_ref();
});

$( "#myModal_design #number9" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="9";
    }
    else{
      input_colisage_ds="9";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="9";
    }
    else{
      input_piece_ds="9";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);
    }
    update_ecart_design();
});

$( "#myModal_ref #number0" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
     if(input_colisage_ref != "0"){
      input_colisage_ref+="0";
    }
    else{
      input_colisage_ref="0";
    }
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    if(input_piece_ref != "0"){
      input_piece_ref+="0";
    }
    else{
      input_piece_ref="0";
    }
    $("#myModal_ref #input_piece").html(input_piece_ref);

  }
  update_ecart_ref();
});

$( "#myModal_cb #number0" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
     if(input_colisage_cb != "0"){
      input_colisage_cb+="0";
    }
    else{
      input_colisage_cb="0";
    }
    $("#myModal_cb #input_colisage").html(input_colisage_cb);
  } 
  else
  {
    if(input_piece_cb != "0"){
      input_piece_cb+="0";
    }
    else{
      input_piece_cb="0";
    }
    $("#myModal_cb #input_piece").html(input_piece_cb);

  }
  update_ecart_cb();
});

$( "#myModal_design #number0" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
     if(input_colisage_ds != "0"){
      input_colisage_ds+="0";
    }
    else{
      input_colisage_ds="0";
    }
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    if(input_piece_ds != "0"){
      input_piece_ds+="0";
    }
    else{
      input_piece_ds="0";
    }
    $("#myModal_design #input_piece").html(input_piece_ds);

  }
  update_ecart_design();
});

$( "#myModal_ref #numberx" ).click(function() {
  if(is_colisage_calcul_checked_ref == true){
    input_colisage_ref="0";
    $("#myModal_ref #input_colisage").html(input_colisage_ref);
  } 
  else
  {
    input_piece_ref ="0";
    $("#myModal_ref #input_piece").html(input_piece_ref);
  }
  update_ecart_ref();
});

$( "#myModal_design #numberx" ).click(function() {
  if(is_colisage_calcul_checked_ds == true){
    input_colisage_ds="0";
    $("#myModal_design #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    input_piece_ds ="0";
    $("#myModal_design #input_piece").html(input_piece_ds);
  }
  update_ecart_design();
});

$( "#myModal_cb #numberx" ).click(function() {
  if(is_colisage_calcul_checked_cb == true){
    input_colisage_cb="0";
    $("#myModal_cb #input_colisage").html(input_colisage_ds);
  } 
  else
  {
    input_piece_cb ="0";
    $("#myModal_cb #input_piece").html(input_piece_ds);
  }
  update_ecart_cb();
});
//});

//*******************************
/*$(function() {
    var table = $("table");

    $(window).scroll(function() {
        var windowTop = $(window).scrollTop();

        if (windowTop > table.offset().top) {
            $("thead", table).addClass("Fixed").css("top", windowTop);
            $("thead th:eq( 1 )", table).addClass("fixed_designation");
        }
        else {
            $("thead", table).removeClass("Fixed");
            $("thead th:eq( 1 )", table).removeClass("fixed_designation");
        }
    });
});   */


