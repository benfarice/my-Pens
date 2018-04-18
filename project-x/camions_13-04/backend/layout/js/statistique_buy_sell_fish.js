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
                     $('#report_bill_seller_table').DataTable().destroy();
                    document.getElementById('get_data_by_ajax').innerHTML = xmlhttp.responseText; 
                    //console.log(xmlhttp.responseText);
                    do_math_graphe();
                    if(if_no_result()){
                       data_table_func();
                    }
                     if(if_no_result_seller()){
                       data_table_func_seller();
                    }
                    
                  
                     
                }
            }
            xmlhttp.open('GET','../Ajax/statistique_buy_sell_fish.php?date_d_selected='+date_d_selected+'&date_f_selected='+date_f_selected,true);
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
 function data_table_func_seller(){
     $('#report_bill_seller_table').DataTable({
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
   // console.log($('#report_bill_buyer_table tbody tr'));
    if($('#report_bill_buyer_table tbody tr').length==0){
       // console.log("no way");
        $('#report_bill_buyer_table').hide();
        return false;
    }else{
        $('#report_bill_buyer_table').show();
        return true;
    }
 }
 function if_no_result_seller(){
    
      if($('#report_bill_seller_table tbody tr').length==0){
      //  console.log("no way");
        $('#report_bill_seller_table').hide();
        return false;
    }else{
        $('#report_bill_seller_table').show();
        return true;
    }
 }
 $('#radio_graphe').click(function(){
    display_graphe();
    //do_math_graphe();
 });
  $('#radio_tableau').click(function(){
    display_graphe();
    //do_math_graphe();
 });
  function display_graphe(){
    if($('input[name=optradio]:checked', '#formRech').val()=="Tableau"){
        $('#get_data_by_ajax').show();
        $('#container_static').hide();
    }else{
        $('#get_data_by_ajax').hide();
        $('#container_static').show();
    }
  }
  function do_math_graphe(){
    var les_types = [];
    var les_poids_sell = [];
    var les_in_fish = [];
    if($('#report_bill_buyer_table tbody tr').length>0 && $('#report_bill_seller_table tbody tr').length>0){
        $( "#report_bill_buyer_table tbody tr" ).each(function() {
          var type_one = $(this).find(".the_type_td").html().trim();
          
          var poids_sell_one = $(this).find(".poids_sell_td").html().trim();
          
          if(les_types.indexOf(type_one) == -1){
            les_types.push(type_one);
            les_poids_sell.push(Number(poids_sell_one));
          }else{
            var new_poids = Number(les_poids_sell[les_types.indexOf(type_one)]) + Number(poids_sell_one);
            les_poids_sell[les_types.indexOf(type_one)] = new_poids;
          }
          var it_is_in = false;
          $('#report_bill_seller_table tbody tr').each(function() {
              var the_type_in = $(this).find(".type_name_in").html().trim();
              var the_poids_in =  Number($(this).find(".poids_in").html().trim());
              if(type_one == the_type_in){
                it_is_in = true;
                if(isNaN(les_in_fish[les_types.indexOf(type_one)])){
                    les_in_fish[les_types.indexOf(type_one)] = Number(the_poids_in);
                }else{
                    les_in_fish[les_types.indexOf(type_one)] += the_poids_in;
                }

              }
          });
          if(!it_is_in){
            les_in_fish[les_types.indexOf(type_one)]=0;
          }
       
          
        });


    }
   // console.log(les_types);
   // console.log(les_poids_sell);
   // console.log(les_in_fish);
    if($('#report_bill_buyer_table tbody tr').length==0 && $('#report_bill_seller_table tbody tr').length>0){
         $( "#report_bill_seller_table tbody tr" ).each(function() {
          var type_one = $(this).find(".type_name_in").html().trim();
          
          var poids_sell_one = $(this).find(".poids_in").html().trim();
          
          if(les_types.indexOf(type_one) == -1){
            les_types.push(type_one);
            les_in_fish.push(Number(poids_sell_one));
          }else{
            var new_poids = Number(les_in_fish[les_types.indexOf(type_one)]) + Number(poids_sell_one);
            les_in_fish[les_types.indexOf(type_one)] = new_poids;
          }
          var it_is_in = false;
          $('#report_bill_buyer_table tbody tr').each(function() {
              var the_type_in = $(this).find(".the_type_td").html().trim();
              var the_poids_in =  Number($(this).find(".poids_sell_td").html().trim());
              if(type_one == the_type_in){
                it_is_in = true;
                if(isNaN(les_poids_sell[les_types.indexOf(type_one)])){
                    les_poids_sell[les_types.indexOf(type_one)] = Number(the_poids_in);
                }else{
                    les_poids_sell[les_types.indexOf(type_one)] += the_poids_in;
                }

              }
          });
          if(!it_is_in){
            les_poids_sell[les_types.indexOf(type_one)]=0;
          }
       
          
        });

    }
    
    Highcharts.chart('container_static', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'إحصائيات بيع و إنزال السمك',
        style: {
                    color: 'red',
                    fontSize:'30px'
                },
        useHTML: true
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: les_types,
        crosshair: true,
        labels: {
                style: {
                    color: 'red',
                    fontSize:'17px'
                },
                useHTML: true
            }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'الوزن ',
            useHTML: true
        },
        labels: {
          useHTML: true
        }
    },
    tooltip: {
        useHTML: true,
        split: false,
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} كغ</b></td></tr>',
        footerFormat: '</table>',
        shared: true
        
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            useHTML: true
        },
        useHTML: true
    },
    exporting: { enabled: false },
    series: [{
        name: 'البيع',
        useHTML: true,
        data: les_poids_sell
        

    }, {
        name: 'الإنزال',
        useHTML: true,
        data: les_in_fish


    }]
});
  }