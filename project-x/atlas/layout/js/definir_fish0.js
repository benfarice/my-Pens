var ar_ok = "موافق";
var ar_cancel = "إلغاء";
var ar_confirm_question_1="هل تريد الإستمرار";
var ar_message_delete_lot = 'هل تريد حقا حذف المنتوج ';
var ar_message_delete_lot_2 = 'الذي لديه الوزن ';
var ar_message_delete_lot_3 = 'من بيانات هذا البائع';
var ar_kg = 'كيلوغرام';
var update_title_para1 = 'تعديل الدفعة التي تحمل الرقم التسلسلي ';
var ar_fish_type = 'النوع السمكي';
var ar_the_type = 'النوع';

//*************************************************************************
//*************************************************************************
//*************************************************************************
//*************************************************************************

function confirm_delete_lot(num_lot,poids,espece_name){
        var message = ar_message_delete_lot;
        message+= ' '+ espece_name + ' ';
        message += ar_message_delete_lot_2;
        message+=' '+ poids +' '+ar_kg;
        message += ' '+ ar_message_delete_lot_3;
        $('#id_lot_to_delete').val(num_lot);
        $('#confirm_delete_message_lot').html(message);
        $('#myModal_delect_confirm').modal('show');
       }
        function update_func_lot(num_lot,poids,espece,family){
          //alert(num_lot);
          //console.log("update_func lot");
          //console.log(poids);
          //console.log(espece);
          //console.log(family);
          var update_title = update_title_para1+num_lot;
          $('#update_title').html(update_title);
          lot_description = ar_fish_type+" "+family;
          lot_description+= " "+ar_the_type+' '+espece;
          $('#id_lot_to_update').val(num_lot);
          $('#lot_description').html(lot_description);
          $('#new_qte').html(poids);
          new_qte ="0";
          $('#myModal_update_lot').modal('show');
        }
      //**************************************





      function delete_lot_func(){
        num_lot = $('#id_lot_to_delete').val();
        //alert(num_lot);
        var request = "Ajax/get_lot_data.php?id_vendeur="+id_vendeur+"&del_lot_id="+num_lot;
        xmlhttp2.onreadystatechange = function(){
                if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
                    document.getElementById('tbody_get_lot_date').innerHTML = xmlhttp2.responseText;
                }
            }
            xmlhttp2.open('GET',request,true);
            xmlhttp2.send();
            $('#myModal_delect_confirm').modal('hide');

      }
      function update_lot_func(){
          var id_lot_to_update = $('#id_lot_to_update').val();
          var new_qte = $('#new_qte').html().trim();
          var request = "Ajax/get_lot_data.php?id_vendeur="+id_vendeur+"&up_lot_id="+id_lot_to_update+"&new_qte="+new_qte;
          console.log(request);
          xmlhttp2.onreadystatechange = function(){
                if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
                    document.getElementById('tbody_get_lot_date').innerHTML = xmlhttp2.responseText;
                }
            }
            xmlhttp2.open('GET',request,true);
            xmlhttp2.send();
            $('#myModal_update_lot').modal('hide');
        }
      function afficher_lot_after_buy(){
         var request = "Ajax/get_lot_data.php?id_vendeur="+id_vendeur;
         //console.log('afficher after func');
         //console.log(request);
          xmlhttp_after = new XMLHttpRequest();
          xmlhttp_after.onreadystatechange = function(){
                if(xmlhttp_after.readyState == 4 && xmlhttp_after.status == 200){
                    document.getElementById('tbody_get_lot_date').innerHTML = xmlhttp_after.responseText;
                    //console.log('yeah after func');

                }
            }
            xmlhttp_after.open('GET',request,true);
            xmlhttp_after.send();
      }
      function ajax_func_lot(){

        Qte = $("#dialog #input_piece").html();
        seller_selected = $('#seller_selected #seller_has_selected').html().trim();
        var add = false;
        var request = "Ajax/get_lot_data.php?id_vendeur="+id_vendeur;

            if(id_vendeur !=  '0' && code_espece != '0' &&  Qte != '0'){
            var request = "Ajax/get_lot_data.php?add_lot=yes&id_vendeur="+id_vendeur+"&code_espece="+code_espece+"&qte="+Qte+"&seller="+seller_selected+"&espece="+espece.trim();
            add=true;
            code_espece == '0';
            Qte == '0';
          }


        //console.log(espece);
        //console.log("ajax func lot :>");
        //console.log(request);
            xmlhttp2 = new XMLHttpRequest();
            xmlhttp2.onreadystatechange = function(){
                if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200){
                    document.getElementById('tbody_get_lot_date').innerHTML = xmlhttp2.responseText;
                    //console.log(xmlhttp2.responseText);
                    if(add == true){
                      var link = $('#link_to_imprim').val();
                      //console.log('link : ajax_func-lot() : ');
                      //console.log(link);
              document.location.href=link;
                    }

                }
            }
            xmlhttp2.open('GET',request,true);
            xmlhttp2.send();
            }


        function ajax_func_lot_imprim_all(){

        Qte = $("#dialog #input_piece").html();
        seller_selected = $('#seller_selected #seller_has_selected').html();
        var request = "Ajax/get_lot_data.php?imprime_tout=yes&id_vendeur="+id_vendeur+"&code_espece="+code_espece+"&qte="+Qte+"&seller="+seller_selected;


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
            xmlhttp.open('GET','Ajax/get_vendeurs.php',true);
            xmlhttp.send();
        }
        function search_sellers_func(){
          //xmlhttp = new XMLHttpRequest();
          var searched_value = $('#searched_value').val();

          var request = 'Ajax/get_vendeurs.php?searched_value='+searched_value;
          /*if(family_part == true){
            request = 'Ajax/get_vendeurs.php?get_families=yes&id_family='+searched_value;
          }*/

          //***********************************
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_vendeurs').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                    restart_slider_vendeurs();
                    //slideIndex = 1;
            //end_slide_index = 2;
            $('#searched_value').val('');
                }
            }
            xmlhttp.open('GET',request,true);
            xmlhttp.send();
        }
        //family_part = false;
        //espece_part=false;
        function search_especes_func(){

          var searched_value = $('#searched_value_espece').val();
            request = 'Ajax/get_vendeurs.php?get_espece=yes&searched_value_espece='+searched_value;
            //console.log(request);
            xmlhttp3.onreadystatechange = function(){
                if(xmlhttp3.readyState == 4 && xmlhttp3.status == 200){
                    document.getElementById('get_data_espece').innerHTML = xmlhttp3.responseText;
                    //console.log('before : '+end_slide_index);
                    slider_espece();
                    //console.log('after : '+end_slide_index);

                    click_slide_e();
                    restart_slider_espece();

              }
          }
            xmlhttp3.open('GET',request,true);
            xmlhttp3.send();
        }

        function buy_func_lot(num_lot,poids,espece){
          ajax_func_buyers();
          //alert(num_lot);
          //console.log(num_lot);
          //console.log(poids);
          //console.log(espece);
          seller_selected_ = $('#seller_selected #seller_has_selected').html();
          num_lot_to_buy = num_lot;
          Qte_to_buy = poids;
          espece_to_buy = espece;
          $('#Modal_buy_process #lot_to_buy_span').html(num_lot_to_buy);
          $('#Buyers_modal #lot_to_buy_span').html(num_lot_to_buy);
          $('#Modal_buy_process #type_buy_span').html(espece_to_buy);
          $('#Buyers_modal #type_buy_span').html(espece_to_buy);
          $('#Modal_buy_process #poids_buy_span').html(Number(Qte_to_buy).toFixed(3));
          $('#Buyers_modal #poids_buy_span').html(Number(Qte_to_buy).toFixed(3));
          $('#seller_to_buy_span').html(seller_selected_);
          //console.log('--------------');
          //console.log(num_lot_to_buy);
          $('#Buyers_modal').modal('show');
          //$('#Modal_buy_process').modal('show');

        }

        function add_ADJUDICATION_buy(){

        var prix_unitaire = Number($('#price_buy_span').html()).toFixed(3);
        var prix_net = Number($('#total_to_buy_span').html()).toFixed(3);
        var poids_buy = Number($('#Modal_buy_process #poids_buy_span').html()).toFixed(3);
        var lot_to_buy = $('#Modal_buy_process #lot_to_buy_span').html();
        var seller_to_buy_span =$('#seller_to_buy_span').html();
        var buyer_to_buy_span = $('#buyer_to_buy_span').html();
        var type_buy_span = $('#Modal_buy_process #type_buy_span').html();

        if(prix_unitaire != 0){


        xmlhttp_ADJUDICATION = new XMLHttpRequest();
        //alert('yeah');
        var request = 'Ajax/ajax_ADJUDICATION.php?imprime_buy_nfo=yes&add=yes&prix_net='+prix_net+'&prix_unitaire='+prix_unitaire+'&poids_buy='+poids_buy+'&lot_to_buy='+lot_to_buy+'&id_buyer='+id_buyer+'&seller_to_buy_span='+seller_to_buy_span+'&buyer_to_buy_span='+buyer_to_buy_span+'&type_buy_span='+type_buy_span;
        //console.log('add adjudication func');
        //console.log(request);
        xmlhttp_ADJUDICATION.onreadystatechange = function(){
            if(xmlhttp_ADJUDICATION.readyState == 4 && xmlhttp_ADJUDICATION.status == 200){
               document.getElementById('get_data_from_ajax_adjudication').innerHTML = xmlhttp_ADJUDICATION.responseText;
               //link_to_print_buy_info

                $('#price_buy_span').html("1.000");
                $('#total_to_buy_span').html("1.000");
                price_to_buy="0";
                total_to_buy=0;

               var link = $('#link_to_print_buy_info').val();

               document.location.href=link;



               //console.log(xmlhttp_ADJUDICATION.responseText);
            }
        }
        xmlhttp_ADJUDICATION.open('GET',request,true);
        xmlhttp_ADJUDICATION.send();
        afficher_lot_after_buy();
        $('#Modal_buy_process').modal('hide');

        }

        }
$('#Modal_buy_process').on('hidden.bs.modal', function () {
      $('#price_buy_span').html("1.000");
      $('#total_to_buy_span').html("1.000");
      price_to_buy="0";
      total_to_buy=0;
     afficher_lot_after_buy();
})
        function ajax_func_espece(){
            xmlhttp3 = new XMLHttpRequest();
            //if(id_vendeur != '0'){

            /*
            var request = 'Ajax/get_vendeurs.php?get_families=yes';
            if(family_part == false){
               family_part=true;
              $('#searched_value').attr("placeholder", "<?php  //echo lang('search_exemple_family_here');?>");
              $('#option_licence_group').html("<?php // echo lang('option_gr_family');?>");
              //var searched_value = $('#searched_value').val();

            }else if(id_family != '0'){
              espece_part=true;
               $('#searched_value').attr("placeholder", "<?php // echo lang('search_exemple_espece');?>");
              request = 'Ajax/get_vendeurs.php?get_espece=yes&id_family='+id_family;
            }

            if(id_vendeur != '0')
            */
            //var request = 'Ajax/get_vendeurs.php?get_families=yes&id_family='+searched_value;

            request = 'Ajax/get_vendeurs.php?get_espece=yes';


            if(id_family != '0'){
                  request = 'Ajax/get_vendeurs.php?get_espece=yes&id_family='+id_family;
                  //id_family='0';
            }
            xmlhttp3.onreadystatechange = function(){
                if(xmlhttp3.readyState == 4 && xmlhttp3.status == 200){
                    document.getElementById('get_data_espece').innerHTML = xmlhttp3.responseText;
                    //console.log('before : '+end_slide_index);
                    slider_espece();
                    //console.log('after : '+end_slide_index);
                    /*
                    if(espece_part == false){
                      click_slide_f();
                    }else if(espece_part == true){

                    }
                    */
                    click_slide_e();
                    restart_slider_espece();

              //  }
            }
        }
            xmlhttp3.open('GET',request,true);
            xmlhttp3.send();
           // }

        }


        function ajax_func_buyers(){
            xmlhttp_b = new XMLHttpRequest();
            request = 'Ajax/get_vendeurs.php?get_data_buyer=yes';
            xmlhttp_b.onreadystatechange = function(){
                if(xmlhttp_b.readyState == 4 && xmlhttp_b.status == 200){
                    document.getElementById('get_data_buyer').innerHTML = xmlhttp_b.responseText;
                    //console.log('before : '+end_slide_index);
                    //slider_espece();
                    //console.log('after : '+end_slide_index);
                    //click_slide_e();
                    slider_buyer();
                     click_slide_buyer();
                }
            }
            xmlhttp_b.open('GET',request,true);
            xmlhttp_b.send();
        }
        function search_buyerss_func(){
           var searched_buyer = $('#searched_value_buyer').val();
            request = 'Ajax/get_vendeurs.php?get_data_buyer=yes&searched_buyer='+searched_buyer;
            xmlhttp_b.onreadystatechange = function(){
                if(xmlhttp_b.readyState == 4 && xmlhttp_b.status == 200){
                    document.getElementById('get_data_buyer').innerHTML = xmlhttp_b.responseText;
                    //console.log('before : '+end_slide_index);
                    //slider_espece();
                    //console.log('after : '+end_slide_index);
                    //click_slide_e();
                    slider_buyer();
                     click_slide_buyer();
                }
            }
            xmlhttp_b.open('GET',request,true);
            xmlhttp_b.send();
        }


       window.onload = function(){
        ajax_func_vendeur();

        ajax_func_espece();
       }

      slideIndex = 0;
      end_slide_index = 11;
      slideIndex_e = 0;
      end_slide_index_e = 11;
      slideIndex_f = 0;
      end_slide_index_f = 11;
      slideIndex_buyer = 0;
      end_slide_index_buyer = 3;

      function slider(){
        //slideIndex = 0;
        //end_slide_index = 11;
        showDivs(slideIndex);


      }
        function slider_buyer(){

        showDivs_buyer(slideIndex_buyer);


      }
      function slider_espece(){
        //slideIndex_e = 0;
        //end_slide_index_e = 11;
        showDivs_espece(slideIndex_e);


      }
      function restart_slider_vendeurs(){

      if($("#get_data_vendeurs .vendeur_name_div").filter(function(){
          return $(this).css('display') == 'block'
          }).length < 1){
          slideIndex = 0;
          end_slide_index = 11;
          slider();
        }
      }
      function restart_slider_espece(){

      if($("#get_data_espece .mySlides_espece").filter(function(){
          return $(this).css('display') == 'block'
          }).length < 1){
          slideIndex_e = 0;
          end_slide_index_e = 11;
          slider_espece();
        }
      }
      //mySlides_espece
      //mySlides_f
      function plusDivs(n) {
          //end_slide_index=slideIndex+11;
          //console.log('slideindex plus div before'+slideIndex);
          /*if(n==-1 && slideIndex>11) slideIndex = slideIndex - 11;
          else */
          slideIndex += 11;
          //console.log('n : ' +n);

          //end_slide_index=slideIndex+11;
            showDivs(slideIndex);
            restart_slider_vendeurs();

        }
      function plusDivs_espece(n) {
          //end_slide_index_e=slideIndex_e+11;
            //showDivs_espece(slideIndex_e += n + end_slide_index_e);
            slideIndex_e+=11;
            showDivs_espece(slideIndex_e);
            restart_slider_espece();

        }
         function plusDivs_buyer(n) {
            //console.log('plus div buyer');
            //console.log(slideIndex_buyer);
            slideIndex_buyer+=3;
            //console.log(slideIndex_buyer);
            showDivs_buyer(slideIndex_buyer);

        }
      function change_title_to_family(){
        if(id_vendeur != '0'){

        if(espece_part == true){
        $('#h1_choose_title').html('<?php echo lang("h1_choose_espece"); ?>');
        }else{
        $('#h1_choose_title').html('<?php echo lang("h1_choose_family"); ?>');
        }
        }
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
            /*console.log(x.length);
            x[slideIndex-1].style.display = "block";
            if (n > 2){
              x[slideIndex].style.display = "block";
              x[slideIndex+1].style.display = "block";
            }else if(n==2){
              x[slideIndex].style.display = "block";
            }*/
            }
        function showDivs_espece(n) {
            var i;
            var x = document.getElementsByClassName("mySlides_espece");




          if ((end_slide_index_e+11) > x.length && (end_slide_index_e!=x.length-1)) {
            end_slide_index_e=x.length-1;
           } else if(end_slide_index_e==x.length-1){
              slideIndex_e=0;
              end_slide_index_e=11;
            }

             else if (n < 0)
            {
              slideIndex_e = x.length;
              end_slide_index_e=slideIndex_e+11;
          }
             else{
              end_slide_index_e=slideIndex_e+11;
            }

             for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex_e && i<= end_slide_index_e))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }
          /*
            if (n > x.length) {slideIndex_e = 0}

            if (n < 0) {slideIndex_e = x.length} ;
          end_slide_index_e=slideIndex_e+11;
            for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex_e && i<= end_slide_index_e))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }
            /*console.log(x.length);
            x[slideIndex-1].style.display = "block";
            if (n > 2){
              x[slideIndex].style.display = "block";
              x[slideIndex+1].style.display = "block";
            }else if(n==2){
              x[slideIndex].style.display = "block";
            }*/
            }
        function showDivs_buyer(n) {
            var i;
            var x = document.getElementsByClassName("buyer_name_div");



          if ((end_slide_index_buyer+3) > x.length && (end_slide_index_buyer!=x.length-1)) {
            end_slide_index_buyer=x.length-1;
           } else if(end_slide_index_buyer==x.length-1){
              slideIndex_buyer=0;
              end_slide_index_buyer=3;
            }

             else if (n < 0)
            {
              slideIndex_buyer = x.length;
              end_slide_index_buyer=slideIndex_buyer+3;
          }
             else{
              end_slide_index_buyer=slideIndex_buyer+3;
            }
             // console.log('-------------------------');
            //console.log('show divs buyer');
            //console.log(slideIndex_buyer);
            //console.log(end_slide_index_buyer);
            //console.log('-------------------------');
             for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex_buyer && i<= end_slide_index_buyer))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }
          }
        function click_slide(){
          $('.mySlides').click(function(){
          id_vendeur = $(this).find('.id_seller').html();
          vendeur_selected = $(this).html();
          $('.mySlides').css('border', 'solid 5px #fff');
          $(this).css('border', 'solid 5px #d35400');

          $('#seller_selected').html(vendeur_selected);
          $('#seller_selected_d').html(vendeur_selected);
          ajax_func_lot();
          $('#search_vendeur').hide();
          $('#input_poids').show();
          //***************
          //ajax_func_family();
          //change_title_to_family();
          //$('#searched_value').attr("placeholder","<?php //echo lang('search_exemple_espece');?>");
          //$('#option_licence_group').html("<?php //echo lang('option_gr_family');?>");
          //espece_part=true;
          //******************

          //alert(id);
          });
        }
        function select_another_seller(){
          id_vendeur = '0';
          id_family = '0';
          code_espece = '0';
          Qte = '0';
          ajax_func_vendeur();
          $('#input_poids').hide();
          $('#search_vendeur').show();
        }
        function click_slide_f(){
          $('.mySlides').click(function(){
          id_family = $(this).find('.id_family').html();
          $('.mySlides').css('border', 'solid 5px #fff');
          $(this).css('border', 'solid 5px #d35400');
          $("#dialog_family").dialog('close');
          ajax_func_espece();
          //alert(id);
          });
        }
        function click_slide_e(){
          $('.mySlides_espece').click(function(){
          code_espece = $(this).find('.Code_espece').html();
          espece = $(this).find('.titre_espece').html();
          $('.mySlides_espece').css('border', 'solid 5px #fff');
          $(this).css('border', 'solid 5px #d35400');
          $('#the_type_fish_selected').html(espece);
          $("#dialog #input_piece").html('00.000');
          $('#dialog').dialog('open');

          //alert(code_espece);
          });
        }
//*********************************************************************
         function click_slide_buyer(){
          $('.buyer_name_div').click(function(){
          id_buyer = $(this).find('.id_buyer').html();
          buyer = $(this).find('#buyer_has_selected').html();

          $('.buyer_name_div').css('border', 'solid 5px #fff');
          $(this).css('border', 'solid 5px #d35400');
          //console.log('----------buyer-------------');
          //console.log(buyer);
          //console.log('.........this......');
          //console.log($(this).html());
          $('#buyer_to_buy_span').html(buyer);
          $('#Buyers_modal').modal('hide');
          $('#Modal_buy_process').modal('show');
          //$('#the_type_fish_selected').html(espece);
          //$("#dialog #input_piece").html('00.000');
          //$('#dialog').dialog('open');

          //alert(code_espece);
          });
        }

//************************************************************************
//************ you stoped here
      id_vendeur = '0';
      id_buyer = '0';
      buyer = '0';
      num_lot_to_buy = '0';
      espece_to_buy = '0';
      Qte_to_buy = null;
      id_family = '0';
      code_espece = '0';
      Qte = '0';
      espece = '0';
      //***************************************
      /*
      function Afficher_txt_lot(num_lot){
        //alert(id_facture);
        xmlhttp_txt = new XMLHttpRequest();
        var request = 'Ajax/ajax_txt_lot.php?num_lot='+num_lot;
        console.log(request);
        xmlhttp_txt.open('GET',request, true);
        console.log("func afficher txt lot");
        xmlhttp_txt.onreadystatechange = function (){
              if(xmlhttp_txt.readyState == 4 && xmlhttp_txt.status == 200){
                var filename = xmlhttp_txt.responseText;
                document.location.href=filename;
                console.log("response text afficher txt lot");
                console.log(xmlhttp_txt.responseText);
              }
            }
        xmlhttp_txt.send();
      }
      */

var buy_value_number_elements = "0";

var new_qte = "0";

var price_to_buy = "0";

var total_to_buy = 0;

$(".ui-dialog-titlebar").hide();

//myModal_update_lot
function calcul_price_total(){
    var qte_to__buy = $("#Modal_buy_process #poids_buy_span").html();
    total_to_buy = Number(price_to_buy) * Number(qte_to__buy);
    $("#Modal_buy_process #total_to_buy_span").html(total_to_buy.toFixed(3));
    $("#Buyers_modal #total_to_buy_span").html(total_to_buy.toFixed(3));
}

$( "#Modal_buy_process #number1" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="1";
    }
    else{
      price_to_buy="1";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));

});

$( "#Modal_buy_process #number2" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="2";
    }
    else{
      price_to_buy="2";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});

$( "#Modal_buy_process #number3" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="3";
    }
    else{
      price_to_buy="3";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});


$( "#Modal_buy_process #number4" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="4";
    }
    else{
      price_to_buy="4";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});


$( "#Modal_buy_process #number5" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="5";
    }
    else{
      price_to_buy="5";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});


$( "#Modal_buy_process #number6" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="6";
    }
    else{
      price_to_buy="6";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});



$( "#Modal_buy_process #number7" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="7";
    }
    else{
      price_to_buy="7";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});


$( "#Modal_buy_process #number8" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="8";
    }
    else{
      price_to_buy="8";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});



$( "#Modal_buy_process #number9" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="9";
    }
    else{
      price_to_buy="9";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});

$( "#Modal_buy_process #number0" ).click(function() {
 if(price_to_buy != "0"){
      price_to_buy+="0";
    }
    else{
      price_to_buy="0";
    }
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});

$( "#Modal_buy_process #numberx" ).click(function() {

    price_to_buy="0";
    calcul_price_total();
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});

$( "#Modal_buy_process #number_v" ).click(function() {

    if(price_to_buy.indexOf(".") == -1){
       price_to_buy+=".";
    }
    $("#Modal_buy_process #price_buy_span").html(Number(price_to_buy).toFixed(3));
});
$( "#myModal_update_lot #number1" ).click(function() {
 if(new_qte != "0"){
      new_qte+="1";
    }
    else{
      new_qte="1";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number2" ).click(function() {
 if(new_qte != "0"){
      new_qte+="2";
    }
    else{
      new_qte="2";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number3" ).click(function() {
 if(new_qte != "0"){
      new_qte+="3";
    }
    else{
      new_qte="3";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number4" ).click(function() {
 if(new_qte != "0"){
      new_qte+="4";
    }
    else{
      new_qte="4";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number5" ).click(function() {
 if(new_qte != "0"){
      new_qte+="5";
    }
    else{
      new_qte="5";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number6" ).click(function() {
 if(new_qte != "0"){
      new_qte+="6";
    }
    else{
      new_qte="6";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number7" ).click(function() {
 if(new_qte != "0"){
      new_qte+="7";
    }
    else{
      new_qte="7";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number8" ).click(function() {
 if(new_qte != "0"){
      new_qte+="8";
    }
    else{
      new_qte="8";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number9" ).click(function() {
 if(new_qte != "0"){
      new_qte+="9";
    }
    else{
      new_qte="9";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number0" ).click(function() {
 if(new_qte != "0"){
      new_qte+="0";
    }
    else{
      new_qte="0";
    }
    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #numberx" ).click(function() {

    new_qte="0";

    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});
$( "#myModal_update_lot #number_v" ).click(function() {

     if(new_qte.indexOf(".") == -1){
       new_qte+=".";
    }

    $("#myModal_update_lot #new_qte").html(Number(new_qte).toFixed(3));
});

$( "#dialog #number1" ).click(function() {


     if(buy_value_number_elements != "0"){
      buy_value_number_elements+="1";
    }
    else{
      buy_value_number_elements="1";
    }



    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));


});
//---------------------


$( "#dialog #number2" ).click(function() {

    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="2";
    }
    else{
      buy_value_number_elements="2";
    }


    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));


});

$( "#dialog #number3" ).click(function() {
 /*
  if(is_colisage_calcul_checked_cb == true){
    if(input_colisage_cb != "0"){
      input_colisage_cb+="3";
    }
    else{
      input_colisage_cb="3";
    }
    $("#dialog #input_colisage").html(input_colisage_cb);
  }
  else
  {
  */
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="3";
    }
    else{
      buy_value_number_elements="3";
    }

    //$("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
 // }

});

$( "#dialog #number4" ).click(function() {
  /*if(is_colisage_calcul_checked_cb == true){
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
  	*/
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="4";
    }
    else{
      buy_value_number_elements="4";
    }

   // $("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
//  }

});

$( "#dialog #number5" ).click(function() {
  /*
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
  	*/
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="5";
    }
    else{
      buy_value_number_elements="5";
    }

    //$("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
  //}
  //update_ecart_cb();
});

$( "#dialog #number6" ).click(function() {
  /*if(is_colisage_calcul_checked_cb == true){
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
  	*/
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="6";
    }
    else{
      buy_value_number_elements="6";
    }

 //$("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
 $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
  //}
  //update_ecart_cb();
});

$( "#dialog #number7" ).click(function() {
  /*if(is_colisage_calcul_checked_cb == true){
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
  	*/
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="7";
    }
    else{
      buy_value_number_elements="7";
    }

 //$("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
$("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
  //}

});

$( "#dialog #number8" ).click(function() {
  /*if(is_colisage_calcul_checked_cb == true){
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
  	*/
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="8";
    }
    else{
      buy_value_number_elements="8";
    }

// $("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
$("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
 // }

});

$( "#dialog #number9" ).click(function() {
  /*if(is_colisage_calcul_checked_cb == true){
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
  	*/
    if(buy_value_number_elements != "0"){
      buy_value_number_elements+="9";
    }
    else{
      buy_value_number_elements="9";
    }

 //$("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
 $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
  //}

});

$( "#dialog #number0" ).click(function() {
	 if(buy_value_number_elements != "0"){
      buy_value_number_elements+="0";
    }
    else{
      buy_value_number_elements="0";
    }

    // $("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
 });

$( "#dialog #numberx" ).click(function() {

    //buy_value_number_elements="00.000";
    buy_value_number_elements="0";

    //$("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
 });

$( "#dialog #number_v" ).click(function() {

    if(buy_value_number_elements.indexOf(".") == -1){
       buy_value_number_elements+=".";
    }


 //   $("#dialog #input_piece").html(new Intl.NumberFormat("de-DE").format(Number(buy_value_number_elements)));
    $("#dialog #input_piece").html(Number(buy_value_number_elements).toFixed(3));
 });

//---------------------
function modal_func(){
          $("#dialog #input_piece").html('00.000');
          buy_value_number_elements = '0';
          $('#Espece_modal').modal('show');
        }




$( "#dialog" ).dialog({
      autoOpen: false,
      width: '90%',
      buttons: [
        {
          text: ar_ok ,
          click: function() {
            //console.log("not allow 0 here");
            //console.log(Number($("#dialog #input_piece").html()));
            if(Number($("#dialog #input_piece").html())!=0){
            ajax_func_lot();
            buy_value_number_elements = '0';
            $("#dialog #input_piece").html('00.000');



            var txt;
            var r = confirm(ar_confirm_question_1);
            if (r == true) {
                txt = "You pressed OK!";
            } else {
                txt = "You pressed Cancel!";
                $('#Espece_modal').modal('hide');
                $( this ).dialog( "close" );
            }


            }




          }
        },
        {
          text: ar_cancel,
          click: function() {
            buy_value_number_elements='0';
            $("#dialog #input_piece").html('00.000');
            $( this ).dialog( "close" );
          }
        }
      ]
    });
   $(".ui-dialog-titlebar").hide();

   function func_close_dialog_family(){

    $("#dialog_family").dialog('close');
  }
function filter_family(){
  var request = 'Ajax/get_vendeurs.php?get_families=yes';
    xmlhttp3.onreadystatechange = function(){
                if(xmlhttp3.readyState == 4 && xmlhttp3.status == 200){
                    document.getElementById('get_data_family').innerHTML = xmlhttp3.responseText;
                    /*console.log('before : '+end_slide_index);
                    slider_espece();
                    console.log('after : '+end_slide_index);

                    click_slide_e();*/
                    slider_family();
                    click_slide_f();
                    //alert(id_family);

            }
        }
  xmlhttp3.open('GET',request,true);
  xmlhttp3.send();

  $(".ui-dialog-titlebar").hide();
  $('#dialog_family').dialog('open');
}
$( "#dialog_family" ).dialog({

    autoOpen:false,
    modal: true,
    resizable: false,
     width: 'auto',
    draggable: false,

     hide: 'explode',

     title: "Trazoo",
        open: function(){
     $(this).parent().css({'top':10,'left':5});
        },
   close: function( event, ui ) {
        ajax_func_espece();
  }
    //});
  });
//myModal_update_lot
function plusDivs_family() {
         slideIndex_f+=11;
         showDivs_family(slideIndex_f);
      }
function slider_family(){
        showDivs_family(slideIndex_f);
      }
function showDivs_family(n) {
            var i;
            var x = document.getElementsByClassName("family_name_div_f");




          if ((end_slide_index_f+11) > x.length && (end_slide_index_f!=x.length-1)) {
            end_slide_index_f=x.length-1;
           } else if(end_slide_index_f==x.length-1){
              slideIndex_f=0;
              end_slide_index_f=11;
            }

             else if (n < 0)
            {
              slideIndex_f = x.length;
              end_slide_index_f=slideIndex_f+11;
          }
             else{
              end_slide_index_f=slideIndex_f+11;
            }

             for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex_f && i<= end_slide_index_f))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }
          }
