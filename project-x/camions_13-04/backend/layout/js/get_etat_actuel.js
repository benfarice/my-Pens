//alert("ok");

window.onload=function(){
  //alert('ok');
  get_data_today();
}
//$.datetimepicker.setLocale('en');
//$('#datetimepicker').datetimepicker();

$('#datetimepicker').datetimepicker({
   // dateFormat: 'dd-mm-yy',
   timepicker:false,
   format:'d/m/Y',
   minDate: getFormattedDate(new Date()),
  
});

function getFormattedDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear().toString().slice(2);
    return day + '/' + month + '/' + year;
}
