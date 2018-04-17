$(document).ready(function() {

       $('#DateD').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
    });
		
		     $('#DateF').daterangepicker({
        singleDatePicker: true,
		 locale: {
            format: 'D/MM/YYYY'
        }
    });
});
function ajax_func_report_bill(){
            var date_d_selected = $('#DateD').val();
            var date_f_selected = $('#DateF').val();
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    $('#report_bill_buyer_table').DataTable().destroy();
                    document.getElementById('get_data_by_ajax').innerHTML = xmlhttp.responseText; 
                    //console.log(xmlhttp.responseText);
                    if(if_no_result()){
                       data_table_func();
                    }
                  
                    
                }
            }
            xmlhttp.open('GET','../Ajax/report_bill_buyers.php?date_d_selected='+date_d_selected+'&date_f_selected='+date_f_selected,true);
            xmlhttp.send();
        }

function print_f(){
            var date_d_selected = $('#DateD').val();
            var date_f_selected = $('#DateF').val();
            //xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    $('#get_data_by_ajax_print').show();
                    document.getElementById('get_data_by_ajax_print').innerHTML = xmlhttp.responseText;
                   // console.log(xmlhttp.responseText); 
                    $('#get_data_by_ajax_print').print();
                    $('#get_data_by_ajax_print').hide();
                }
            }
            xmlhttp.open('GET','../Ajax/report_bill_buyers.php?print=yes&date_d_selected='+date_d_selected+'&date_f_selected='+date_f_selected,true);
            xmlhttp.send();
}
window.onload = function(){
        ajax_func_report_bill();
}
function data_table_func(){
    $('#report_bill_buyer_table').DataTable({
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
  $('#DateD').on('change.datepicker', function(ev){
  
   ajax_func_report_bill();

  
  });
 $('#DateF').on('change.datepicker', function(ev){
  
   ajax_func_report_bill();
  
  });
 function if_no_result(){
    //console.log($('#report_bill_buyer_table tbody tr'));
    if($('#report_bill_buyer_table tbody tr').length==3){
       // console.log("no way");
        $('#report_bill_buyer_table').hide();
        return false;
    }else{
        $('#report_bill_buyer_table').show();
        return true;
    }
 }