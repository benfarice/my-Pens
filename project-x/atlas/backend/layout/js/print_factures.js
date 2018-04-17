$(document).ready(function() { 
      //alert('yeah');
       $('#DateS').daterangepicker({
        singleDatePicker: true,
		    locale: {
            format: 'D/MM/YYYY'
        }
    	});
       
    });

function ajax_func(){
		var dates = $('#DateS').val();
        var request = '../Ajax/print_factures.php?get_factures=yes&searched_date='+dates;
		var seller_checked = false;
		if($('#radio_seller').is(':checked')) 
            { /*alert("it's checked");*/
            seller_checked = true;
             }
        if(seller_checked==true){
            request+='&seller=yes';
        }else{
            request+='&buyer=yes';
        }
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                $('#view_factures').DataTable().destroy();
                document.getElementById('tbody_print_factures').innerHTML = xmlhttp.responseText;
                console.log(xmlhttp.responseText);
                data_table_func();
            }
        }
        xmlhttp.open('GET',request,true);
        xmlhttp.send();
        }
window.onload = function(){
    ajax_func();   
}
 $('#radio_seller').click(function () {
    ajax_func(); 
});
$('#radio_buyer').click(function () {
    ajax_func(); 
});
$('#DateS').on('change.datepicker', function(ev){
   ajax_func(); 
});
 function data_table_func(){
    $('#view_factures').DataTable({
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
 function print_bill_func(num_facture,Type){
//	alert(Type);
  //  alert("num facure = "+num_facture);
	if(Type=="Seller")
		window.open ('facture_vdr_pdf.php?IdFac='+num_facture, "_newtab" );
	else if(Type=="Buyer")
		window.open ('facture_acht_pdf.php?IdFac='+num_facture, "_newtab" );
 }