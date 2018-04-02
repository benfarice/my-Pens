//alert('yeah');
function ajax_func(){
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                $('#tab_families').DataTable().destroy();
                document.getElementById('div_get_family').innerHTML = xmlhttp.responseText;
                //console.log(xmlhttp.responseText);

                data_table_func();
            }
        }
        xmlhttp.open('GET','../Ajax/get_families.php?get_families=yes',true);
        xmlhttp.send();
        }


function ajax_func_add_family(){
        var add_family = $('#add_name_ar_family').val();
        $('#add_name_ar_family').val('');

        if(add_family != ""){
        xmlhttp_add = new XMLHttpRequest();
        xmlhttp_add.onreadystatechange = function(){
            if(xmlhttp_add.readyState == 4 && xmlhttp_add.status == 200){
                $('#tab_families').DataTable().destroy();
                document.getElementById('div_get_family').innerHTML = xmlhttp_add.responseText;
                //console.log(xmlhttp.responseText);

                data_table_func();
                $('#myModal_add_family').modal('hide');
            }
        }
        xmlhttp_add.open('GET','../Ajax/get_families.php?get_families=yes&add_family='+add_family,true);
        xmlhttp_add.send();
        }else{
          alert('لا يمكن إضافة عائلة بدون لقب');
        }
        
        }
function ajax_func_delete_family(){
        var delete_family = $('#deleted_code_family').val();
        $('#deleted_code_family').val('');
        xmlhttp_add = new XMLHttpRequest();
        xmlhttp_add.onreadystatechange = function(){
            if(xmlhttp_add.readyState == 4 && xmlhttp_add.status == 200){
                $('#tab_families').DataTable().destroy();
                document.getElementById('div_get_family').innerHTML = xmlhttp_add.responseText;
                //console.log(xmlhttp.responseText);

                data_table_func();
                $('#myModal_delete_family').modal('hide');
            }
        }
        xmlhttp_add.open('GET','../Ajax/get_families.php?get_families=yes&delete_family='+delete_family,true);
        xmlhttp_add.send();
        }
function ajax_func_update_family(){
        var update_code_family = $('#code_family_update').val();
        $('#code_family_update').val('');
        var update_new_value = $('#update_name_ar_family').val();
        $('#update_name_ar_family').val('');

        if(update_code_family != "" && update_new_value != ""){
        xmlhttp_add = new XMLHttpRequest();
        xmlhttp_add.onreadystatechange = function(){
            if(xmlhttp_add.readyState == 4 && xmlhttp_add.status == 200){
                $('#tab_families').DataTable().destroy();
                document.getElementById('div_get_family').innerHTML = xmlhttp_add.responseText;
                //console.log(xmlhttp.responseText);

                data_table_func();
                $('#myModal_update_family').modal('hide');
            }
        }
        xmlhttp_add.open('GET','../Ajax/get_families.php?get_families=yes&update_new_value='+update_new_value+'&update_code_family='+update_code_family,true);
        xmlhttp_add.send();
        }else{
            alert('لا يمكن تعديل عائلة لتكون بدون لقب');
        }
       
        }
 window.onload = function(){
    ajax_func();
    //ajax_func_espece_table();
 }
 function data_table_func(){
    $('#tab_families').DataTable({
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
 function modifier_family(code_f,nom_f){
     $('#code_family_update').val(code_f);
     $('#update_name_ar_family').val(nom_f);
     $('#myModal_update_family').modal('show');
 }
 function delete_family(code_f,nom_f){
    $('#deleted_code_family').val(code_f);
    var question_delete = "هل حقا تريد حذف عائلة الأسماك الملقبة ب"+nom_f;
    $('#delete_question_family').html(question_delete);
    $('#myModal_delete_family').modal('show');
 }
 function add_family_func(){
     $('#myModal_add_family').modal('show');
 }
