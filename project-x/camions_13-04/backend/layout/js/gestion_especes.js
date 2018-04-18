//alert('yeah');
function ajax_func(){
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                document.getElementById('search_menu_select_family').innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open('GET','../Ajax/gestion_especes.php?get_families=yes',true);
        xmlhttp.send();
        }
function ajax_func_modal_update_family(code_f){
        xmlhttp_6 = new XMLHttpRequest();
        xmlhttp_6.onreadystatechange = function(){
            if(xmlhttp_6.readyState == 4 && xmlhttp_6.status == 200){
                document.getElementById('select_family_update_espece').innerHTML = xmlhttp_6.responseText;
                //console.log('code family');
                //console.log(code_f);
                $("#select_family_update_espece").val(code_f);
            }
        }
        xmlhttp_6.open('GET','../Ajax/gestion_especes.php?get_families=yes&update_family=yes',true);
        xmlhttp_6.send();
        }
function ajax_func_modal_add_family(){
        xmlhttp_7 = new XMLHttpRequest();
        xmlhttp_7.onreadystatechange = function(){
            if(xmlhttp_7.readyState == 4 && xmlhttp_7.status == 200){
                document.getElementById('select_family_add_espece').innerHTML = xmlhttp_7.responseText;
               
                
                
            }
        }
        xmlhttp_7.open('GET','../Ajax/gestion_especes.php?get_families=yes&update_family=yes',true);
        xmlhttp_7.send();
        }
function delete_confirm_espece(code_espece){
        var code_espece_delete = $('#deleted_code_espece').val();
        var request = '../Ajax/gestion_especes.php?get_espece=yes&delete_espece=yes&code_espece_delete='+code_espece_delete;
        //console.log(request);
        xmlhttp_4 = new XMLHttpRequest();
        xmlhttp_4.onreadystatechange = function(){
            if(xmlhttp_4.readyState == 4 && xmlhttp_4.status == 200){
                $('#tab_especes').DataTable().destroy();
                document.getElementById('div_get_especes').innerHTML = xmlhttp_4.responseText;
                //console.log(xmlhttp_4.responseText);
                $('#myModal_delete_espece').modal('hide');
                data_table_func();
            }
        }
        xmlhttp_4.open('GET',request,true);
        xmlhttp_4.send();
      
}
function update_confirm_espece(){
     var code_espece_update = $('#code_espece_update').val();


    var name_espece_ar = $('#update_name_ar_espece').val();
    var name_english = $('#update_name_en_espece').val();
    var desc = $('#update_desc_espece').val().trim();

    
    var code_family = $("#select_family_update_espece").val();

    if(name_espece_ar != ""){
        var request = '../Ajax/gestion_especes.php?get_espece=yes&update_espece=yes&code_espece_update='+code_espece_update+'&name_espece_ar='+name_espece_ar+'&desc='+desc+'&name_english='+name_english+'&code_family='+code_family;
        //console.log(request);
        xmlhttp_5 = new XMLHttpRequest();
        xmlhttp_5.onreadystatechange = function(){
            if(xmlhttp_5.readyState == 4 && xmlhttp_5.status == 200){
                $('#tab_especes').DataTable().destroy();
                document.getElementById('div_get_especes').innerHTML = xmlhttp_5.responseText;
                //console.log(xmlhttp_4.responseText);
                $('#myModal_update_espece').modal('hide');
                data_table_func();
            }
        }
        xmlhttp_5.open('GET',request,true);
        xmlhttp_5.send();
    }else{
        alert('لا يمكن تعديل نوع ليكون بدون لقب');
    }

   
}
function add_confirm_espece(){



    var name_espece_ar = $('#add_name_ar_espece').val();
    var name_english = $('#add_name_en_espece').val();
    var desc = $('#add_desc_espece').val().trim();
    var code_family = $("#select_family_add_espece").val();

    if(name_espece_ar != ""){
        var request = '../Ajax/gestion_especes.php?get_espece=yes&add_espece=yes&name_espece_ar='+name_espece_ar+'&desc='+desc+'&name_english='+name_english+'&code_family='+code_family;
        //console.log(request);
        xmlhttp_8 = new XMLHttpRequest();
        xmlhttp_8.onreadystatechange = function(){
            if(xmlhttp_8.readyState == 4 && xmlhttp_8.status == 200){
                $('#tab_especes').DataTable().destroy();
                document.getElementById('div_get_especes').innerHTML = xmlhttp_8.responseText;
                //console.log(xmlhttp_4.responseText);
                $('#myModal_add_espece').modal('hide');
                data_table_func();
                $('#add_name_ar_espece').val('');
                $('#add_name_en_espece').val('');
                $('#add_desc_espece').val('');
                $("#select_family_add_espece").val('');
            }
        }
        xmlhttp_8.open('GET',request,true);
        xmlhttp_8.send();
    }else{
        alert('لا يمكن إضافة نوع بدون لقب');
    }
   



}
 function ajax_func_espece_table(){
        xmlhttp_2 = new XMLHttpRequest();
        xmlhttp_2.onreadystatechange = function(){
            if(xmlhttp_2.readyState == 4 && xmlhttp_2.status == 200){
                document.getElementById('div_get_especes').innerHTML = xmlhttp_2.responseText;
                data_table_func();
            }
        }
        xmlhttp_2.open('GET','../Ajax/gestion_especes.php?get_espece=yes',true);
        xmlhttp_2.send();
        }
  function ajax_func_espece_table_search_family(){
  		var id_family = $('#search_menu_select_family').val();
  		var request = '../Ajax/gestion_especes.php?get_espece=yes&id_family='+id_family;
  		
  		//console.log(request);
        xmlhttp_3 = new XMLHttpRequest();
        xmlhttp_3.onreadystatechange = function(){
            if(xmlhttp_3.readyState == 4 && xmlhttp_3.status == 200){

            	$('#tab_especes').DataTable().destroy();

                document.getElementById('div_get_especes').innerHTML = xmlhttp_3.responseText;
                //$('#example').DataTable().ajax.reload();
                data_table_func();
            }
        }
        xmlhttp_3.open('GET',request,true);
        xmlhttp_3.send();

		
		/*
        $.ajax({
				url:request,
				data:{	name:user_name,
					adress:adress,
					email:email
				},
				type:"POST",
				success:function(data){
					$('#table_especes').html(data);
					$('#tab_especes').DataTable().ajax.reload();
				}
			});
		*/
        }

 window.onload = function(){
 	ajax_func();
 	ajax_func_espece_table();
 }
 $(document).ready(function() {
    //$('#tab_especes').DataTable();
} );
 function data_table_func(){
 	$('#tab_especes').DataTable({
 		"language": {
		    "search": " البحث : ",
		    "lengthMenu": " إظهار "+" _MENU_ "+" حقول ",
            "infoEmpty": " ليس هنالك بيانات لإظهارها حاليا ! ",
            "emptyTable": " ليس هنالك بيانات لإظهارها حاليا ! ",
            "info": "إظهار"+" _START_ "+"إلى"+" _END_ "+"من"+" _TOTAL_ "+"حقول",
            "paginate": {
                "first":      "أول",
                "last":       "آخر",
                "next":       "تابع",
                "previous":   "سابق"
            }
		  }
 	});
 	
 }
 $( "#search_menu_select_family" ).change(function() {
  //alert( "Handler for .change() called." );
  ajax_func_espece_table_search_family();
});
 function modifier_especes(nom_espece,nom_english,desc,code_f,code_espece){

    //alert(nom_espece+nom_english+desc+code_f);
    $('#update_name_ar_espece').val(nom_espece);
    $('#update_name_en_espece').val(nom_english);
    $('#update_desc_espece').val(desc);
    //*******************************
    //******* look here => not working
    //$("#select_family_update_espece").val(code_f);
    //********************************
    ajax_func_modal_update_family(code_f);
    //console.log(code_f);
    //document.getElementById('select_family_update_espece').value=code_f;
    //$("#select_family_update_espece").val(code_f);

    $('#code_espece_update').val(code_espece);
    $('#myModal_update_espece').modal('show');

 }
 function delete_especes(nom_espece,nom_english,desc,name_f,code_espece){
    //delete_confirm_espece(code_espece);
    var question_1 = "هل تريد حقا حذف نوع السمك "+nom_espece;
    var question_2 = "المنتمي للعائلة "+name_f;
    $('#deleted_code_espece').val(code_espece);
    $('#delete_question_espece').html(question_1);
    $('#delete_question_espece_1').html(question_2);
    $('#delete_question_espece_2').html(nom_english);
    $('#delete_question_espece_3').html(desc);
    $('#myModal_delete_espece').modal('show');
 }
function add_espece_func(){
    ajax_func_modal_add_family();
    $('#myModal_add_espece').modal('show');
}