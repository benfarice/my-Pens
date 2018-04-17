 //**************************************************
  function ajax_func_vendeur(){
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_vendeurs').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                    restart_slider_vendeurs();
                }
            }
            xmlhttp.open('GET','Ajax/get_vendeurs.php?report_buyers=yes',true);
            xmlhttp.send();
        }

  window.onload = function(){
        ajax_func_vendeur();

 }

  //***********************************************************
  function search_buyers_func(){

          var searched_value = $('#searched_value').val();

          var request = 'Ajax/get_vendeurs.php?report_buyers=yes&searched_value='+searched_value;

          //***********************************
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_vendeurs').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                    restart_slider_vendeurs();

            $('#searched_value').val('');
                }
            }
            xmlhttp.open('GET',request,true);
            xmlhttp.send();
        }

  //****************
      slideIndex = 0;
      end_slide_index = 11;
       id_vendeur = '0';
 function slider(){

        showDivs(slideIndex);
  }
  function restart_slider_vendeurs(){

  if($("#get_data_vendeurs .mySlides").filter(function(){
      return $(this).css('display') == 'block'
      }).length < 1){
      slideIndex = 0;
      end_slide_index = 11;
      slider();
    }
  }
    function plusDivs(n) {

          //console.log('slideindex plus div before'+slideIndex);

          slideIndex += 11;
          //console.log('n : ' +n);

            showDivs(slideIndex);
            restart_slider_vendeurs();
        }

function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            //slideIndex = n;
            //console.log('n show divs : '+n);
            //console.log('end slide show divs before : '+end_slide_index);
            //console.log('slide : '+slideIndex);
            if ((end_slide_index+11) > x.length && (end_slide_index!=x.length-1)) {
              //slideIndex = slideIndex - 11;
              end_slide_index=x.length-1;
              //console.log('here');
            } else if(end_slide_index==x.length-1){
              slideIndex=0;
              end_slide_index=11;
            }

            else if (n < 0)
            {
              slideIndex = x.length;
              end_slide_index=slideIndex+11;
          }
             else{
              end_slide_index=slideIndex+11;
            }

          //console.log('slideindex :'+slideIndex);
          //console.log('end :'+end_slide_index);

            for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex && i<= end_slide_index))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }

            }

             function click_slide(){
          $('.mySlides').click(function(){
          id_vendeur = $(this).find('.id_buyer').html();
          vendeur_selected = $(this).find('#buyer_has_selected').html();
          $('.mySlides').css('border', 'solid 5px #fff');
          $(this).css('border', 'solid 5px #d35400');

          $('#buyer_selected').html(vendeur_selected);
          //$('#seller_selected_d').html(vendeur_selected);
          ajax_func_lot();
          $('#search_vendeur').hide();
          $('#input_poids').show();

          });
        }

        function ajax_func_lot(){

        //radio_lot_not_selled
        //var request = "Ajax/get_lot_data.php?id_vendeur="+id_vendeur;
        var selected_date = $('#datepicker').val();
        request="Ajax/print_report_seller_front.php?report_buyer_lot_has_buyed=yes&id_vendeur="+id_vendeur+"&selected_date="+selected_date;




            xmlhttp2 = new XMLHttpRequest();
            xmlhttp2.onreadystatechange = function(){
                if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
                    document.getElementById('tbody_get_lot_date').innerHTML = xmlhttp2.responseText;



                }
            }
            xmlhttp2.open('GET',request,true);
            xmlhttp2.send();
            }

         function select_another_seller(){
          id_vendeur = '0';


          ajax_func_vendeur();
          $('#input_poids').hide();
          $('#search_vendeur').show();
        }

        //********************


        function ajax_func_lot_imprim_all(){

        var selected_date = $('#datepicker').val();
        buyer_selected = $('#buyer_selected').html();

        request="Ajax/print_report_seller_front.php?imprime_tout=yes&report_buyer_lot_has_buyed=yes&id_vendeur="+id_vendeur+"&selected_date="+selected_date+"&buyer_selected="+buyer_selected;


        //console.log(request);
            //xmlhttp2 = new XMLHttpRequest();
            xmlhttp2.onreadystatechange = function(){
                if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
                    document.getElementById('tbody_get_lot_date').innerHTML = xmlhttp2.responseText;

                    var link = $('#link_to_imprim_all').val();
                    //console.log('link : ajax_func-lot() : ');
                    //console.log(link);
            document.location.href=link;


                }
            }
            xmlhttp2.open('GET',request,true);
            xmlhttp2.send();


            }

$( function() {





$("#datepicker").datepicker({
  dateFormat: 'dd/mm/yy',
  onSelect: function(dateText) {

    ajax_func_lot();
  }
});

} );
