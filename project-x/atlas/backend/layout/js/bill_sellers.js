$(document).ready(function() {
      //alert('yeah');
       $('#DateS').daterangepicker({
        singleDatePicker: true,
		    locale: {
            format: 'D/MM/YYYY'
        }
    	});

    });

function check_func(){
     $('#select_all_checkbox').click(function () {

     if (this.checked) {
      $('#details_f input[name="select_checkbox_f"]').prop('checked', true);
    }else{
      $('#details_f input[name="select_checkbox_f"]').prop('checked', false);
    }
  });
}

	function search_sellers_f(){
    ajax_func_vendeur();
		$('#facture_sellers_modal').modal('show');
	}
	function ajax_func_vendeur(){
            var date_selected = $('#DateS').val();
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_seller_f').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                     check_func();
                }
            }
            xmlhttp.open('GET','../Ajax/get_vendeurs.php?date_selected='+date_selected,true);
            xmlhttp.send();
        }
    window.onload = function(){
        ajax_func_vendeur();
        //ajax_dbfcf_func();
    }
    function slider(){
    showDivs(slideIndex);
    }
    function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");

           // console.log('n show divs : '+n);
           // console.log('end slide show divs before : '+end_slide_index);
          //  console.log('slide : '+slideIndex);
            if ((end_slide_index+4) > x.length && (end_slide_index!=x.length-1)) {
              //slideIndex = slideIndex - 11;
              end_slide_index=x.length-1;
             // console.log('here');
            } else if(end_slide_index==x.length-1){
              slideIndex=0;
              end_slide_index=4;
            }

            else if (n < 0)
            {
              slideIndex = x.length;
              end_slide_index=slideIndex+4;
          }
             else{
              end_slide_index=slideIndex+4;
            }


         // console.log('slideindex :'+slideIndex);
          //console.log('end :'+end_slide_index);

            for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex && i<= end_slide_index))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }

     }
     function plusDivs(n) {

         // console.log('slideindex plus div before'+slideIndex);

          slideIndex += 4;
        //  console.log('n : ' +n);

            showDivs(slideIndex);

    }
    slideIndex = 0;
    end_slide_index = 4;
    function search_sellers_func(){

          var searched_value = $('#searched_value_seller_f').val();

          var request = '../Ajax/get_vendeurs.php?searched_value='+searched_value;

            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_seller_f').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                    $('#searched_value_seller_f').val('');
                    check_func();
                }
            }
            xmlhttp.open('GET',request,true);
            xmlhttp.send();
        }
      function click_slide(){
          $('.mySlides').click(function(){
          id_vendeur = $(this).find('.id_seller').html();
          vendeur_selected = $(this).find('#seller_has_selected').html();
          $('.mySlides').css('border', 'solid 5px #fff');
          $(this).css('border', 'solid 5px #d35400');
          $('#facture_sellers_modal').modal('hide');
          $('#seller_searched').val(vendeur_selected.trim() + ' | '+id_vendeur.trim());
          id_vendeur = id_vendeur.trim();
          vendeur_selected = vendeur_selected.trim();
          ajax_dbfcf_func();
          });
        }
     var id_vendeur=null;
     var vendeur_selected=null;

     //ajax_details_before_click_facture
     function ajax_dbfcf_func(){
        var request = '../Ajax/get_details_before_click_facture.php';
        var date_selected = $('#DateS').val();
        if(id_vendeur != null){
           var request = '../Ajax/get_details_before_click_facture.php?id_vendeur='+id_vendeur+'&date_selected='+date_selected;
        }
        xmlhttp_dbfcf = new XMLHttpRequest();
        xmlhttp_dbfcf.onreadystatechange = function(){
            if(xmlhttp_dbfcf.readyState == 4 && xmlhttp_dbfcf.status == 200){

                document.getElementById('ajax_details_before_click_facture').innerHTML = xmlhttp_dbfcf.responseText;
                check_func();
                hide_show_btn();

            }
        }
        xmlhttp_dbfcf.open('GET',request,true);
        xmlhttp_dbfcf.send();
        }


function hide_show_btn(){
   if($('#ajax_details_before_click_facture').find('.resAff').length==0){
      $('#create_facture_btn').show();
   }else{
      $('#create_facture_btn').hide();
   }
}
$('#create_facture_btn').click(function(){
  if($('#ajax_details_before_click_facture').find('.resAff').length==0){
    do_math();
  }
  //console.log($('#ajax_details_before_click_facture').find('.resAff'));
  });
  function do_math(){
  var les_num_adjudication_array = [];
  var les_valeurs_adjudication = [];
  $( '#details_f input[name="select_checkbox_f"]').each(function( index ) {
  //console.log( index + ": " + $( this ).text() );
    if (this.checked) {
      self = $(this);
      //les_num_adjudication_array.push(self.parent().siblings().find(".n_adjudication_class" ).html().trim())
      //console.log(self.parent().parent().siblings().parent().find(".n_adjudication_class").html().trim());
      les_num_adjudication_array.push(self.parent().parent().siblings().parent().find(".n_adjudication_class").html().trim());
      //value_adjudication
      les_valeurs_adjudication.push(self.parent().parent().siblings().parent().find(".value_adjudication").html().trim());
    }
    });
    //console.log(les_num_adjudication_array);
     var request = '../Ajax/get_details_before_click_facture.php?add_fac=yes';
        var date_selected = $('#DateS').val();
        if(id_vendeur != null){
           var request = '../Ajax/get_details_before_click_facture.php?add_fac=yes&id_vendeur='+id_vendeur+'&date_selected='+date_selected;
        }
    if(les_valeurs_adjudication.length>0 && les_num_adjudication_array.length>0){
       $.ajax({
            url:request,
            data:{
              les_num_adjudication_array:les_num_adjudication_array,
              les_valeurs_adjudication:les_valeurs_adjudication
              //adress:adress,
              //email:email
            },
            type:"POST",
            success:function(data){
              //$('#result').html(data);
              //console.log(data);
              open_window_for_facture(data);
            }
          });
    }

    }


  function open_window_for_facture(data){
    /*console.log(data.trim().length);
    if(data!="" && data!= undefined && data != null && data.trim().length < 30){
       window.open("facture_print.php?num_facture="+data);
       ajax_dbfcf_func();
    }*/
  //  alert(data);
		window.open ('facture_vdr_pdf.php?IdFac='+data, "_newtab" );
		ajax_dbfcf_func();

  }

  $('#DateS').on('change.datepicker', function(ev){

   ajax_func_vendeur();
   ajax_dbfcf_func();

  });
  /*
  $.ajax({
        url:"../Ajax/get_details_before_click_facture.php?add_fac=yes",
        data:{
          name:user_name,
          adress:adress,
          email:email
        },
        type:"POST",
        success:function(data){
          //$('#result').html(data);
        }
      });
      */
