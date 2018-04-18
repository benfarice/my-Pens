  function ajax_func_vehicule(){
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_vehicules').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                    restart_slider_vehicules();
                }
            }
            xmlhttp.open('GET','Ajax/liste_des_vehicules.php',true);
            xmlhttp.send();
        }
function ajax_func_yesterday(id_vehicule){
                  xmlhttp_yesterday = new XMLHttpRequest();
                  xmlhttp_yesterday.onreadystatechange = function(){
                      if(xmlhttp_yesterday.readyState == 4 && xmlhttp_yesterday.status == 200){
                          document.getElementById('get_yesterday_data').innerHTML = xmlhttp_yesterday.responseText;

                      }
                  }
                  xmlhttp_yesterday.open('GET','Ajax/get_yesterday_data.php?id_vehicule='+id_vehicule,true);
                  xmlhttp_yesterday.send();
              }
function ajax_func_today(id_vehicule){
                                xmlhttp_today = new XMLHttpRequest();
                                xmlhttp_today.onreadystatechange = function(){
                                    if(xmlhttp_today.readyState == 4 && xmlhttp_today.status == 200){
                                        document.getElementById('get_today_data').innerHTML = xmlhttp_today.responseText;
                                        id_driver = $('#id_driver_today').val();
                                        if($('#id_ville_today').val() != '0'){
                                          id_ville = $('#id_ville_today').val();
                                        }
                                        if($('#id_site_c_today').val() != '0'){
                                          site_c_id = $('#id_site_c_today').val();
                                        }
                                        if($('#title_site_c_today').val() != '0'){
                                          site_c_title = $('#title_site_c_today').val();
                                        }
                                        if($('#ville_title_c_today').val() != '0'){
                                          ville_title = $('#ville_title_c_today').val();
                                        }


                                    }
                                }
                                xmlhttp_today.open('GET','Ajax/get_today_data.php?id_vehicule='+id_vehicule,true);
                                xmlhttp_today.send();
                            }
function ajax_func_drivers(){
                  xmlhttp_drivers = new XMLHttpRequest();
                  xmlhttp_drivers.onreadystatechange = function(){
                      if(xmlhttp_drivers.readyState == 4 && xmlhttp_drivers.status == 200){
                          document.getElementById('get_data_driver').innerHTML = xmlhttp_drivers.responseText;
                          slider_driver();
                          click_slide_driver();
                          restart_slider_drivers();
                          is_ville_displayed = false;
                          is_drivers_displyed=true;
                          is_client_displayed = false;
                          is_site_c_displayed = false;
                          is_site_d_displayed = false;
                          $('#searched_value_driver').attr("placeholder", "chauffeur");
                      }
                  }
                  xmlhttp_drivers.open('GET','Ajax/drivers.php?get_drivers=yes',true);
                  xmlhttp_drivers.send();
              }
function ajax_func_villes(){
                                xmlhttp_villes = new XMLHttpRequest();
                                xmlhttp_villes.onreadystatechange = function(){
                                    if(xmlhttp_villes.readyState == 4 && xmlhttp_villes.status == 200){
                                        document.getElementById('get_data_driver').innerHTML = xmlhttp_villes.responseText;
                                        slider_driver();
                                        click_slide_driver();
                                        restart_slider_drivers();
                                    }
                                }
                                xmlhttp_villes.open('GET','Ajax/drivers.php?get_villes=yes',true);
                                xmlhttp_villes.send();
                            }
function ajax_func_clients(){
                xmlhttp_clients = new XMLHttpRequest();
                xmlhttp_clients.onreadystatechange = function(){
                if(xmlhttp_clients.readyState == 4 && xmlhttp_clients.status == 200){
                  document.getElementById('get_data_driver').innerHTML = xmlhttp_clients.responseText;
                slider_driver();
                click_slide_driver();
                restart_slider_drivers();
                $('#searched_value_driver').attr("placeholder", "Client");
                $('.clients').each(function( index ) {
                  //alert('ok');
                  //console.log(element);
                  var client_id = $(this).find('.client_id').html().trim();
                  if(les_clients_selected.indexOf(client_id)!=-1){
                    $(this).css('border', 'solid 5px #d35400');
                    //alert("yeah");
                  }

                });
                }
                }
                xmlhttp_clients.open('GET','Ajax/drivers.php?get_clients=yes',true);
                xmlhttp_clients.send();
}
function ajax_func_site_d(){
                xmlhttp_site_d = new XMLHttpRequest();
                xmlhttp_site_d.onreadystatechange = function(){
                if(xmlhttp_site_d.readyState == 4 && xmlhttp_site_d.status == 200){
                  document.getElementById('get_data_driver').innerHTML = xmlhttp_site_d.responseText;
                slider_driver();
                click_slide_driver();
                restart_slider_drivers();

                }
                }
                xmlhttp_site_d.open('GET','Ajax/drivers.php?get_site_d=yes',true);
                xmlhttp_site_d.send();
}
function ajax_func_sites_chargement(){
            xmlhttp_sites_chargement = new XMLHttpRequest();
            xmlhttp_sites_chargement.onreadystatechange = function(){
            if(xmlhttp_sites_chargement.readyState == 4 && xmlhttp_sites_chargement.status == 200){
            document.getElementById('get_data_driver').innerHTML = xmlhttp_sites_chargement.responseText;
            slider_driver();
            click_slide_driver();
            restart_slider_drivers();
            }
            }
            xmlhttp_sites_chargement.open('GET','Ajax/drivers.php?get_lieu_chargement=yes',true);
            xmlhttp_sites_chargement.send();
}

function insert_activite(){
      //xmlhttp_today = new XMLHttpRequest();
      var km_depart_p = $('#km_depart').html();
      $('#km_depart').html('0');
      km_depart = "0";
      if(Number(km_depart_p)!=0){
        xmlhttp_today.onreadystatechange = function(){
            if(xmlhttp_today.readyState == 4 && xmlhttp_today.status == 200){
                document.getElementById('get_today_data').innerHTML = xmlhttp_today.responseText;
                $('#myModal_demarrage_activite').modal('hide');
            }
        }
        xmlhttp_today.open('GET','Ajax/get_today_data.php?insert_activite=yes&id_vehicule='+id_vehicule+'&id_driver='+id_driver+'&km_depart='+km_depart_p+'&ville_id='+ville_id+'&site_c_id='+site_c_id,true);
        xmlhttp_today.send();
      }else{
        $("#my_alert_message").text('les champs n\'acceptons pas la valeur zéro');
        $('#myModal_myAlert').modal('show');
        //alert('les champs n\'acceptons pas la valeur zéro');
      }

}
function ajax_func_search_drivers(){
              var searched_driver = $('#searched_value_driver').val();
              $('#searched_value_driver').val('');
              xmlhttp_search = new XMLHttpRequest();
              if(is_drivers_displyed==true){
                var request = 'Ajax/drivers.php?get_drivers=yes&searched_driver='+searched_driver;
              }else if(is_ville_displayed == true){
                var request = 'Ajax/drivers.php?get_villes=yes&searched_ville='+searched_driver;
              }else if(is_site_c_displayed == true){
                var request = 'Ajax/drivers.php?get_lieu_chargement=yes&searched_site_c='+searched_driver;
              }else if(is_client_displayed == true){
                var request = 'Ajax/drivers.php?get_clients=yes&searched_client='+searched_driver;
              }else if(is_site_d_displayed == true){
                var request = 'Ajax/drivers.php?get_site_d=yes&searched_site_d='+searched_driver;
              }

              xmlhttp_search.onreadystatechange = function(){
              if(xmlhttp_search.readyState == 4 && xmlhttp_search.status == 200){
                    document.getElementById('get_data_driver').innerHTML = xmlhttp_search.responseText;
                    slider_driver();
                    click_slide_driver();
                    restart_slider_drivers();
                    if(is_client_displayed == true){
                    $('.clients').each(function( index ) {
                     var client_id = $(this).find('.client_id').html().trim();
                      if(les_clients_selected.indexOf(client_id)!=-1){
                        $(this).css('border', 'solid 5px #d35400');
                    }
                    });
                    }
              }
              }
              xmlhttp_search.open('GET',request,true);
              xmlhttp_search.send();
}

function search_vehicule_func(){
		var searched_value = $('#searched_value').val();
		$('#searched_value').val('');
	   	xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById('get_data_vehicules').innerHTML = xmlhttp.responseText;
                    slider();
                    click_slide();
                    restart_slider_vehicules();
                }
            }
            xmlhttp.open('GET','Ajax/liste_des_vehicules.php?searched_value='+searched_value,true);
            xmlhttp.send();
}
function save_clients_date(){
  //alert('ok');
  request = "Ajax/get_today_data.php?insert_detail_voyages=yes&id_vehicule="+id_vehicule+"&id_driver="+id_driver;
  //console.log(request);
  if(les_clients_selected.length>0 && les_site_dechargement_selected.length>0){
        $.ajax({
             url:request,
             data:{
               les_clients_selected:les_clients_selected,
               les_site_dechargement_selected:les_site_dechargement_selected

             },
             type:"POST",
             success:function(data){
               //$('#result').html(data);
               //console.log(data);
              console.log(data);
              $('#drivers_modal').modal('hide');
              les_clients_selected.splice(0,les_clients_selected.length);
              les_site_dechargement_selected.splice(0,les_site_dechargement_selected.length);
              $('#close_modal_x').show();
              $('#save_clients_btn_id').hide();
              //alert("success");
             }
           });
     }



}
window.onload = function(){
        ajax_func_vehicule();

}
var slideIndex = 0;
var end_slide_index = 5;
var   id_vehicule = null;
var   vehicule_design = null;
var   vehi_matricule = null;
var   vehicule_marque = null;
var driver_nom =  null;
var id_driver =  null;
var ville_id = null;
var site_c_id = null;
var site_c_title = null;
var ville_title=null;
var driver_matricule =  null;


function slider(){
   showDivs(slideIndex);
}
function restart_slider_vehicules(){
/*
  console.log($("#get_data_vehicules .vehicule").filter(function(){
    return $(this).css('display') == 'block'
  }).length);
  console.log($('#get_data_vehicules .vehicule'));
  */
  if($("#get_data_vehicules .vehicule").filter(function(){
    return $(this).css('display') == 'block'
    }).length < 1){
    slideIndex = 0;
    end_slide_index = 5;
    slider();
  }
}
function restart_slider_drivers(){

  if($("#get_data_driver .driver").filter(function(){
    return $(this).css('display') == 'block'
    }).length < 1){
    slideIndex_driver = 0;
    end_slide_index_driver = 3;
    slider_driver();
  }
}
var slideIndex_driver = 0;
var end_slide_index_driver = 3;
function slider_driver(){
   showDivs_driver(slideIndex_driver);
}
function plusDivs(n) {
	slideIndex += 5;
	showDivs(slideIndex);
  restart_slider_vehicules();
}
function plusDivs_driver(n) {
	slideIndex_driver += 3;
	showDivs_driver(slideIndex_driver);
  restart_slider_drivers();
}
 function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("vehicule");

            if ((end_slide_index+5) > x.length && (end_slide_index!=x.length-1)) {

              end_slide_index=x.length-1;

            } else if(end_slide_index==x.length-1){
              slideIndex=0;
              end_slide_index=5;
            }

            else if (n < 0)
            {
              slideIndex = x.length;
              end_slide_index=slideIndex+5;
          }
             else{
              end_slide_index=slideIndex+5;
            }


            for (i = 0; i < x.length; i++) {
              if(!(i>= slideIndex && i<= end_slide_index))
              x[i].style.display = "none";
              else
              x[i].style.display = "block";
            }

            }

  function click_slide(){
          $('.vehicule').click(function(){
          id_vehicule = $(this).find('.id_vehicule').html();
          vehicule_design = $(this).find('.desc_vehicule').html();
          vehi_matricule = $(this).find('.vehi_matricule').html();
          vehicule_marque = $(this).find('.vehicule_marque').html();
          $('.vehicule').css('border', 'solid 5px #34495e');
          $(this).css('border', 'solid 5px #d35400');

          $('#camion_marque').html(vehicule_marque);
          $('#camion_matricule').html(vehi_matricule);
          $('#camion_designation').html(vehicule_design);
          $('#drivers_modal #camion_marque_span').html(vehicule_marque);
          $('#drivers_modal #camion_matricule_span').html(vehi_matricule);
          $('#drivers_modal #camion_designation_span').html(vehicule_design);
          $('#id_vehicule_selected').val(id_vehicule);

          ajax_func_yesterday(id_vehicule);
          ajax_func_today(id_vehicule);
          $('#search_vehicule').hide();
          $('#view_vehicule').show();

          });
        }
        function showDivs_driver(n) {
                   var i;
                   var x = document.getElementsByClassName("driver");

                   if ((end_slide_index_driver+3) > x.length && (end_slide_index_driver!=x.length-1)) {

                     end_slide_index_driver=x.length-1;

                   } else if(end_slide_index_driver==x.length-1){
                     slideIndex_driver=0;
                     end_slide_index_driver=3;
                   }

                   else if (n < 0)
                   {
                     slideIndex_driver = x.length;
                     end_slide_index_driver=slideIndex_driver+3;
                 }
                    else{
                     end_slide_index_driver=slideIndex_driver+3;
                   }


                   for (i = 0; i < x.length; i++) {
                     if(!(i>= slideIndex_driver && i<= end_slide_index_driver))
                     x[i].style.display = "none";
                     else
                     x[i].style.display = "block";
                   }

                   }
var is_ville_displayed = false;
var is_client_displayed = false;
var is_site_d_displayed = false;
var is_site_c_displayed = false;
var is_drivers_displyed = true;
var les_clients_selected = [];
var les_site_dechargement_selected = [];
         function click_slide_driver(){
                 $('.driver').click(function(){
                 if ($(this).hasClass('enabled')){
                   is_ville_displayed = true;
                   is_drivers_displyed=false;
                   is_client_displayed = false;
                   is_site_d_displayed = false;
                   is_site_c_displayed = false;
                   driver_nom = $(this).find('.driver_nom').html();
                   id_driver = $(this).find('.id_driver').html();
                   driver_matricule = $(this).find('.driver_matricule').html();

                   $('.driver').css('border', 'solid 5px #34495e');
                   $(this).css('border', 'solid 5px #d35400');
                   $('#driver_selected_p').html(driver_nom);
                   $('#chauffeur_p_buttons').html(driver_nom);

                   $('#searched_value_driver').attr("placeholder", "Ville");
                   ajax_func_villes();
                   //$('#drivers_modal').modal('hide');
                 }else if ($(this).hasClass('ville')){
                   //is_ville_displayed = true;
                   //alert('ok');
                   is_ville_displayed = false;
                   is_drivers_displyed=false;
                   is_client_displayed = false;
                   is_site_d_displayed = false;
                   is_site_c_displayed = true;
                   ajax_func_sites_chargement();
                   ville_id = $(this).find('.id_ville').html();
                   ville_title = $(this).find('.ville_designation').html();
                   $('#searched_value_driver').attr("placeholder", "site du chargement");
                 }else if ($(this).hasClass('site_chargement')){
                   /*
                   is_ville_displayed = false;
                   is_drivers_displyed=false;
                   is_client_displayed = true;
                   is_site_d_displayed = false;
                   is_site_c_displayed = false;
                   */
                   site_c_id = $(this).find('.site_chargement_id').html();
                   site_c_title = $(this).find('.site_chargement_title').html();
                   $('#drivers_modal').modal('hide');

                 }else if ($(this).hasClass('clients')){
                    var client_id = $(this).find('.client_id').html().trim();
                    if(les_clients_selected.indexOf(client_id)!=-1){
                      les_clients_selected.splice(les_clients_selected.indexOf(client_id),1);
                      les_site_dechargement_selected.splice(les_clients_selected.indexOf(client_id),1);
                      $(this).css('border', 'solid 5px #ecf0f1');
                      //--------------------
                    }else{
                      is_ville_displayed = false;
                      is_drivers_displyed=false;
                      is_client_displayed = false;
                      is_site_d_displayed = true;
                      is_site_c_displayed = false;
                      $('#searched_value_driver').attr("placeholder", "Site déchargement");

                      //alert('yeah');
                      //$('.driver').css('border', 'solid 5px #34495e');
                      $(this).css('border', 'solid 5px #d35400');
                      les_clients_selected.push(client_id);
                      ajax_func_site_d();
                    }


                 }else if ($(this).hasClass('site_d_chargement')){
                   //alert('yeah');
                   var site_d_id = $(this).find('.site_d_chargement_id').html().trim();
                   les_site_dechargement_selected.push(site_d_id);
                   is_ville_displayed = false;
                   is_drivers_displyed=false;
                   is_client_displayed = true;
                   is_site_d_displayed = false;
                   is_site_c_displayed = false;
                   ajax_func_clients();



                 }


                 });
               }

//Buyers_modal
function show_modal_chauffeurs(){
  ajax_func_drivers();
  $('#drivers_modal').modal('show');
}
function select_another_vehicule(){
$('#driver_selected_p').html('---- : ----');
$('#chauffeur_p_buttons').html('---- : ----');

slideIndex = 0;
end_slide_index = 5;
id_vehicule = null;
vehicule_design = null;
vehi_matricule = null;
vehicule_marque = null;
driver_nom =  null;
id_driver =  null;
ville_id=null;
ville_title=null;
site_c_id=null;
site_c_title=null;
driver_matricule =  null;
slideIndex_driver = 0;
end_slide_index_driver = 3;
ajax_func_vehicule();
$('#view_vehicule').hide();
$('#search_vehicule').show();

}

function demarrage_activite(){
  //myModal_demarrage_activite
  if($('#driver_selected_p').html()!='---- : ----'){
      $('#chauffeur_p_buttons').html($('#driver_selected_p').html());
      if(ville_title != null && ville_title != ""){
        $('#ville_p_buttons').html(ville_title);
      }else{
        $('#ville_p_buttons').html("Ville");
      }

      if(site_c_title != null && site_c_title != ""){
        $('#site_c_p_buttons').html(site_c_title);
      }else{
        $('#site_c_p_buttons').html("Site du Chargement");
      }

  }else{
      $('#chauffeur_p_buttons').html("chauffeur");
      $('#ville_p_buttons').html("Ville");
      $('#site_c_p_buttons').html("Site du Chargement");
  }
  if($('#km_depart_p').html()!='---- : ---- KM'){
    $('#km_depart_p_buttons').html($('#km_depart_p').html());
  }else{
    $('#km_depart_p_buttons').html("KM Départ");
  }
  if($('#poids_total_p').html().trim()!='---- : ---- KG'){
    //$('#pesse_p_buttons').html($('#poids_total_p').html());
    //$('#nbr_voyages_p_buttons').html($('#nbr_voyages_today_data_p').html());
  }else{
    $('#pesse_p_buttons').html("Pesse");
    $('#nbr_voyages_p_buttons').html("Nombre de voyages");
  }
  if($('#total_gasoil_p_show').html().trim()!='---- : ---- L'){
    $('#litres_p_buttons').html($('#total_gasoil_p_show').html());
    if($('#total_prix_today').val()!='0'){
      $('#prix_ui_p_buttons').html($('#total_prix_today').val());
    }else{
      $('#prix_ui_p_buttons').html('Prix');
    }
    if($('#total_kilometrage_today').val()!='0'){
      $('#kilometrage_p_buttons').html($('#total_kilometrage_today').val() + ' KM');
    }else{
      $('#kilometrage_p_buttons').html('Kilométrage');
    }
  }else{
    $('#litres_p_buttons').html("litre");
    $('#prix_ui_p_buttons').html('Prix');
    $('#kilometrage_p_buttons').html('Kilométrage');
  }
  $('#myModal_buttons').modal('show');
  /*  if($('#driver_selected_p').html()=='---- : ----'){
      show_modal_chauffeurs();
    }else if($('#km_depart_p').html()=='---- : ---- KM'){
      $('#myModal_demarrage_activite').modal('show');
    }else if($('#poids_total_p').html().trim()=='---- : ---- KG'){
      $('#myModal_insert_voyage').modal('show');
    }else if($('#total_gasoil_p_show').html().trim()=='---- : ---- L'){
      $('#myModal_insert_carburant').modal('show');
    }else if($('#km_f_p_show').html().trim()=='---- : ---- KM'){
      $('#myModal_arreter_activite').modal('show');
    }
*/


}

function cloture(){

  if(
    ($('#driver_selected_p').html()!='---- : ----') &&
    ($('#km_depart_p').html()!='---- : ---- KM') &&
    ($('#poids_total_p').html().trim()!='---- : ---- KG') &&
    ($('#total_gasoil_p_show').html().trim()!='---- : ---- L') &&
    ($('#km_f_p_show').html().trim()=='---- : ---- KM')
    )
    {
      $('#myModal_buttons').modal('hide');
      $('#myModal_arreter_activite').modal('show');
    }else{
      $("#my_alert_message").text('il faut d\'abord remplir tous les champs');
      $('#myModal_myAlert').modal('show');
      //alert('il faut d\'abord remplir tous les champs');
    }
}

function third_step(){

  if($('#driver_selected_p').html()!='---- : ----' && $('#km_depart_p').html()!='---- : ---- KM'){
  if($('#total_gasoil_p_show').html().trim()=='---- : ---- L'){
    $('#myModal_buttons').modal('hide');
    $('#myModal_insert_carburant').modal('show');
  }else{
    $("#my_alert_message").text('vous avez déjà enregistré les frais');
    $('#myModal_myAlert').modal('show');
    //alert('vous avez déjà enregistré les frais');
  }
  }else{
    $("#my_alert_message").text('il faut d\'abord sélectionner un chauffeur et le km de départ');
    $('#myModal_myAlert').modal('show');
    //alert('il faut d\'abord sélectionner un chauffeur et le km de départ');
  }
}

function second_step(){


  /*
  if($('#driver_selected_p').html()!='---- : ----' && $('#km_depart_p').html()!='---- : ---- KM'){
  if($('#poids_total_p').html().trim()=='---- : ---- KG'){
    $('#myModal_insert_voyage').modal('show');
  }else{
    alert('vous avez déjà enregistré le voyage');
  }
}else{
  alert('il faut d\'abord sélectionner un chauffeur et le km de départ');
}
*/
//***
if($('#driver_selected_p').html()!='---- : ----' && $('#km_depart_p').html()!='---- : ---- KM'){
    $('#myModal_buttons').modal('hide');
    $('#myModal_insert_voyage').modal('show');
}else{
  $("#my_alert_message").text('il faut d\'abord sélectionner un chauffeur et le km de départ');
  $('#myModal_myAlert').modal('show');
  //alert('il faut d\'abord sélectionner un chauffeur et le km de départ');
}
}

function demarrer_action(){

   if($('#driver_selected_p').html()=='---- : ----'){
      $('#myModal_buttons').modal('hide');
      show_modal_chauffeurs();
    }else{
      $("#my_alert_message").text('vous avez déjà enregistré le chauffeur');
      $('#myModal_myAlert').modal('show');
      //alert('vous avez déjà enregistré le chauffeur');
    }
}
function saissier_km_depart(){

  if($('#driver_selected_p').html()!='---- : ----'){
  if($('#km_depart_p').html()=='---- : ---- KM'){
    $('#myModal_buttons').modal('hide');
    $('#myModal_demarrage_activite').modal('show');
  }else{
    $("#my_alert_message").text('vous avez déjà enregistré le km de départ');
    $('#myModal_myAlert').modal('show');
    //alert('vous avez déjà enregistré le km de départ');
  }
}else{
  $("#my_alert_message").text('il faut d\'abord sélectionner le chauffeur');
  $('#myModal_myAlert').modal('show');
  //alert('il faut d\'abord sélectionner le chauffeur');
}
}
function insert_voyages(){
  //xmlhttp_today = new XMLHttpRequest();
  var poids_t_inset = $('#myModal_insert_voyage #poids_total_insert_p').html();
  $('#myModal_insert_voyage #poids_total_insert_p').html('0');
  var nbr_v_inset = $('#myModal_insert_voyage #nbr_voyage_insert_p').html();
  $('#myModal_insert_voyage #nbr_voyage_insert_p').html('0');

  if(Number(poids_t_inset) != 0 && Number(nbr_v_inset) > 0){
    xmlhttp_today.onreadystatechange = function(){
        if(xmlhttp_today.readyState == 4 && xmlhttp_today.status == 200){
            document.getElementById('get_today_data').innerHTML = xmlhttp_today.responseText;
            //id_driver = $('#id_driver_today').val();
            $('#myModal_insert_voyage').modal('hide');
            select_clients();

        }
    }
    xmlhttp_today.open('GET','Ajax/get_today_data.php?insert_voyage=yes&id_vehicule='+id_vehicule+'&poids_t_inset='+poids_t_inset+'&nbr_v_inset='+nbr_v_inset+'&id_driver='+id_driver,true);
    xmlhttp_today.send();
  }else{
    $("#my_alert_message").text('les champs n\'acceptons pas la valeur zéro');
    $('#myModal_myAlert').modal('show');
    //alert('les champs n\'acceptons pas la valeur zéro');
  }
  poids = "0";
  nbr_voyage = "0";
  is_poids_selected = true;
  select_poids();

}

function select_clients(){
  //alert("ok");
  is_ville_displayed = false;
  is_drivers_displyed=false;
  is_client_displayed = true;
  is_site_d_displayed = false;
  is_site_c_displayed = false;
  $('#close_modal_x').hide();
  $('#save_clients_btn_id').show();
  ajax_func_clients();
  $('#drivers_modal').modal('show');
}

function insert_carburant(){
  var prix_u_insert_value = $('#myModal_insert_carburant #prix_u_insert_p').html();
  $('#myModal_insert_carburant #prix_u_insert_p').html('0');
  var litres_insert_value = $('#myModal_insert_carburant #litres_insert_p').html();
  $('#myModal_insert_carburant #litres_insert_p').html('0');
  var kilometrage_carburant_insert_value = $('#myModal_insert_carburant #kilometrage_carburant_insert_p').html();
  $('#myModal_insert_carburant #kilometrage_carburant_insert_p').html('0');
  litre_value = "0";
  prix_value = "0";
  kilometrage_value = "0";
  is_litres_selected = true;
  is_prix_selected = false;
  is_kilometrage_selected = false;
  svg_litres_selected();
  if(Number(prix_u_insert_value) != 0 && Number(litres_insert_value) != 0 && Number(kilometrage_carburant_insert_value) != 0){
    xmlhttp_today.onreadystatechange = function(){
        if(xmlhttp_today.readyState == 4 && xmlhttp_today.status == 200){
            document.getElementById('get_today_data').innerHTML = xmlhttp_today.responseText;
            //id_driver = $('#id_driver_today').val();
            $('#myModal_insert_carburant').modal('hide');
        }
    }
    xmlhttp_today.open('GET','Ajax/get_today_data.php?insert_carburant=yes&id_vehicule='+id_vehicule+'&prix_u_insert='+prix_u_insert_value+'&litres_insert='+litres_insert_value+'&kilometrage_carburant_insert='+kilometrage_carburant_insert_value+'&id_driver='+id_driver,true);
    xmlhttp_today.send();
  }else{
    $("#my_alert_message").text('les champs n\'acceptons pas la valeur zéro');
    $('#myModal_myAlert').modal('show');
    //alert('les champs n\'acceptons pas la valeur zéro');
  }

}
//*******
//console.log('ok');

$('#getTimecloture').timepicker();

$('#getTimecloture').on('changeTime', function() {
  var fin_heur_var = $('#getTimecloture').timepicker('getTime').getHours()+':';
  fin_heur_var+=$('#getTimecloture').timepicker('getTime').getMinutes()/*+':'*/;
  //fin_heur_var+=$('#getTimecloture').timepicker('getTime').getSeconds();
  $('#select_fin_heur').text(fin_heur_var);
});

function fin_activite(){

  var km_fin_value = $('#myModal_arreter_activite #km_fin').html();
  $('#myModal_arreter_activite #km_fin').html('0');
  var fin_heur = null;
  if($('#getTimecloture').timepicker('getTime') instanceof Date){
    fin_heur = $('#getTimecloture').timepicker('getTime').getHours()+':';
    fin_heur+=$('#getTimecloture').timepicker('getTime').getMinutes()+':';
    fin_heur+=$('#getTimecloture').timepicker('getTime').getSeconds();
  }else{
      fin_heur = 'now';
  }

  km_fin = "0";
  if(Number(km_fin_value)!=0){
    xmlhttp_today.onreadystatechange = function(){
        if(xmlhttp_today.readyState == 4 && xmlhttp_today.status == 200){
            document.getElementById('get_today_data').innerHTML = xmlhttp_today.responseText;
            //id_driver = $('#id_driver_today').val();
            $('#myModal_arreter_activite').modal('hide');
            ajax_func_yesterday(id_vehicule);
        }
    }
    xmlhttp_today.open('GET','Ajax/get_today_data.php?fin_activite=yes&id_vehicule='+id_vehicule+'&km_fin='+km_fin_value+'&id_driver='+id_driver+'&fin_heur='+fin_heur,true);
    xmlhttp_today.send();
  }else{
    $("#my_alert_message").text('les champs n\'acceptons pas la valeur zéro');
    $('#myModal_myAlert').modal('show');
    //alert('les champs n\'acceptons pas la valeur zéro');
  }

}







//********************************************************************
var km_fin = "0";

$( "#myModal_arreter_activite #number1" ).click(function() {
 if(km_fin != "0"){
      km_fin+="1";
    }
    else{
      km_fin="1";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number2" ).click(function() {
 if(km_fin != "0"){
      km_fin+="2";
    }
    else{
      km_fin="2";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number3" ).click(function() {
 if(km_fin != "0"){
      km_fin+="3";
    }
    else{
      km_fin="3";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number4" ).click(function() {
 if(km_fin != "0"){
      km_fin+="4";
    }
    else{
      km_fin="4";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number5" ).click(function() {
 if(km_fin != "0"){
      km_fin+="5";
    }
    else{
      km_fin="5";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number6" ).click(function() {
 if(km_fin != "0"){
      km_fin+="6";
    }
    else{
      km_fin="6";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number7" ).click(function() {
 if(km_fin != "0"){
      km_fin+="7";
    }
    else{
      km_fin="7";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number8" ).click(function() {
 if(km_fin != "0"){
      km_fin+="8";
    }
    else{
      km_fin="8";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number9" ).click(function() {
 if(km_fin != "0"){
      km_fin+="9";
    }
    else{
      km_fin="9";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #number0" ).click(function() {
 if(km_fin != "0"){
      km_fin+="0";
    }
    else{
      km_fin="0";
    }

    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});
$( "#myModal_arreter_activite #numberx" ).click(function() {

    km_fin="0";


    //$("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));
    $("#myModal_arreter_activite #km_fin").html(Number(km_fin));

});

$( "#myModal_arreter_activite #number_v" ).click(function() {

    if(km_fin.indexOf(".") == -1){
       km_fin+=".";
    }

    $("#myModal_arreter_activite #km_fin").html(Number(km_fin).toFixed(3));

});


//********************************************************************

var km_depart = "0";

$( "#myModal_demarrage_activite #number1" ).click(function() {
 if(km_depart != "0"){
      km_depart+="1";
    }
    else{
      km_depart="1";
    }
    //calcul_price_total();

    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));

});

$( "#myModal_demarrage_activite #number2" ).click(function() {
 if(km_depart != "0"){
      km_depart+="2";
    }
    else{
      km_depart="2";
    }
    //calcul_price_total();
    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});

$( "#myModal_demarrage_activite #number3" ).click(function() {
 if(km_depart != "0"){
      km_depart+="3";
    }
    else{
      km_depart="3";
    }
    //calcul_price_total();
    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});


$( "#myModal_demarrage_activite #number4" ).click(function() {
 if(km_depart != "0"){
      km_depart+="4";
    }
    else{
      km_depart="4";
    }
    //calcul_price_total();
    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});


$( "#myModal_demarrage_activite #number5" ).click(function() {
 if(km_depart != "0"){
      km_depart+="5";
    }
    else{
      km_depart="5";
    }
    //calcul_price_total();

    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});


$( "#myModal_demarrage_activite #number6" ).click(function() {
 if(km_depart != "0"){
      km_depart+="6";
    }
    else{
      km_depart="6";
    }
    //calcul_price_total();
    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});



$( "#myModal_demarrage_activite #number7" ).click(function() {
 if(km_depart != "0"){
      km_depart+="7";
    }
    else{
      km_depart="7";
    }
    //calcul_price_total();
    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});


$( "#myModal_demarrage_activite #number8" ).click(function() {
 if(km_depart != "0"){
      km_depart+="8";
    }
    else{
      km_depart="8";
    }
    //calcul_price_total();
    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});



$( "#myModal_demarrage_activite #number9" ).click(function() {
 if(km_depart != "0"){
      km_depart+="9";
    }
    else{
      km_depart="9";
    }
    //calcul_price_total();

    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});

$( "#myModal_demarrage_activite #number0" ).click(function() {
 if(km_depart != "0"){
      km_depart+="0";
    }
    else{
      km_depart="0";
    }
    //calcul_price_total();

    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});

$( "#myModal_demarrage_activite #numberx" ).click(function() {

    km_depart="0";
    //calcul_price_total();

    //$("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart));
});

$( "#myModal_demarrage_activite #number_v" ).click(function() {

    if(km_depart.indexOf(".") == -1){
       km_depart+=".";
    }
    $("#myModal_demarrage_activite #km_depart").html(Number(km_depart).toFixed(3));
});
//******************************************************************************
var poids = "0";
var nbr_voyage = "0";
var is_poids_selected = true;
$( "#myModal_insert_voyage #number1" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="1";
      }
      else{
        poids="1";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="1";
      }
      else{
        nbr_voyage="1";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});
$( "#myModal_insert_voyage #number2" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="2";
      }
      else{
        poids="2";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="2";
      }
      else{
        nbr_voyage="2";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});

$( "#myModal_insert_voyage #number3" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="3";
      }
      else{
        poids="3";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="3";
      }
      else{
        nbr_voyage="3";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});

$( "#myModal_insert_voyage #number4" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="4";
      }
      else{
        poids="4";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="4";
      }
      else{
        nbr_voyage="4";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});

$( "#myModal_insert_voyage #number5" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="5";
      }
      else{
        poids="5";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="5";
      }
      else{
        nbr_voyage="5";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});

$( "#myModal_insert_voyage #number6" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="6";
      }
      else{
        poids="6";
      }

    //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
    $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="6";
      }
      else{
        nbr_voyage="6";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});
$( "#myModal_insert_voyage #number7" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="7";
      }
      else{
        poids="7";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="7";
      }
      else{
        nbr_voyage="7";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});

$( "#myModal_insert_voyage #number8" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="8";
      }
      else{
        poids="8";
      }

    //  $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
     $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="8";
      }
      else{
        nbr_voyage="8";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});

$( "#myModal_insert_voyage #number9" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="9";
      }
      else{
        poids="9";
      }

    //  $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
     $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="9";
      }
      else{
        nbr_voyage="9";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});
$( "#myModal_insert_voyage #number0" ).click(function() {
    if(is_poids_selected == true){
      if(poids != "0"){
        poids+="0";
      }
      else{
        poids="0";
      }

      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********
      if(nbr_voyage != "0"){
        nbr_voyage+="0";
      }
      else{
        nbr_voyage="0";
      }

      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});
$( "#myModal_insert_voyage #numberx" ).click(function() {
    if(is_poids_selected == true){

        poids="0";


      //$("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids));
    }else{
      //***********

        nbr_voyage="0";


      $("#myModal_insert_voyage #nbr_voyage_insert_p").html(nbr_voyage);
    }


});
$( "#myModal_insert_voyage #number_v" ).click(function() {
    if(is_poids_selected == true){
      if(poids.indexOf(".") == -1){
         poids+=".";
      }

      $("#myModal_insert_voyage #poids_total_insert_p").html(Number(poids).toFixed(3));
    }


});
function select_poids(){

 $("#myModal_insert_voyage #poids_svg").css('box-shadow', '5px 5px 2px #3498db');
 $("#myModal_insert_voyage #poids_svg").css('border', '5px solid #3498db');
 $("#myModal_insert_voyage #svg_nbr_voyage" ).css('box-shadow', '5px 5px 2px #fff');
 $("#myModal_insert_voyage #svg_nbr_voyage" ).css('border', '5px solid #fff');
 is_poids_selected = true;
}

function select_nbr_voyages(){
  $("#myModal_insert_voyage #svg_nbr_voyage").css('box-shadow', '5px 5px 2px #3498db');
  $("#myModal_insert_voyage #svg_nbr_voyage").css('border', '5px solid #3498db');
  $("#myModal_insert_voyage #poids_svg" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_voyage #poids_svg" ).css('border', '5px solid #fff');
  is_poids_selected = false;
}

//****************************************************************************
//svg_litres_selected
var is_litres_selected = true;
var is_prix_selected = false;
var is_kilometrage_selected = false;
function svg_litres_selected(){
  $("#myModal_insert_carburant #svg_litres_id").css('box-shadow', '5px 5px 2px #3498db');
  $("#myModal_insert_carburant #svg_litres_id").css('border', '5px solid #3498db');
  //svg_prix_id
  $("#myModal_insert_carburant #svg_prix_id" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_carburant #svg_prix_id" ).css('border', '5px solid #fff');
  $("#myModal_insert_carburant #svg_kilometrage_carburant_id" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_carburant #svg_kilometrage_carburant_id" ).css('border', '5px solid #fff');

  is_litres_selected = true;
  is_prix_selected = false;
  is_kilometrage_selected = false;

}

function svg_prix_selected(){
  $("#myModal_insert_carburant #svg_prix_id").css('box-shadow', '5px 5px 2px #3498db');
  $("#myModal_insert_carburant #svg_prix_id").css('border', '5px solid #3498db');
  //svg_prix_id
  $("#myModal_insert_carburant #svg_litres_id" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_carburant #svg_litres_id" ).css('border', '5px solid #fff');
  $("#myModal_insert_carburant #svg_kilometrage_carburant_id" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_carburant #svg_kilometrage_carburant_id" ).css('border', '5px solid #fff');

  is_litres_selected = false;
  is_prix_selected = true;
  is_kilometrage_selected = false;
}

function svg_kilometrage_carburant_selected(){
  $("#myModal_insert_carburant #svg_kilometrage_carburant_id").css('box-shadow', '5px 5px 2px #3498db');
  $("#myModal_insert_carburant #svg_kilometrage_carburant_id").css('border', '5px solid #3498db');
  //svg_prix_id
  $("#myModal_insert_carburant #svg_litres_id" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_carburant #svg_litres_id" ).css('border', '5px solid #fff');
  $("#myModal_insert_carburant #svg_prix_id" ).css('box-shadow', '5px 5px 2px #fff');
  $("#myModal_insert_carburant #svg_prix_id" ).css('border', '5px solid #fff');

  is_litres_selected = false;
  is_prix_selected = false;
  is_kilometrage_selected = true;
}

litre_value = "0";
prix_value = "0";
kilometrage_value = "0";

$( "#myModal_insert_carburant #number1" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="1";
      }
      else{
        litre_value="1";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="1";
      }
      else{
        prix_value="1";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="1";
      }
      else{
        kilometrage_value="1";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});

//******************
$( "#myModal_insert_carburant #number2" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="2";
      }
      else{
        litre_value="2";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="2";
      }
      else{
        prix_value="2";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="2";
      }
      else{
        kilometrage_value="2";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
///**********************
$( "#myModal_insert_carburant #number3" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="3";
      }
      else{
        litre_value="3";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="3";
      }
      else{
        prix_value="3";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="3";
      }
      else{
        kilometrage_value="3";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//*****************************
$( "#myModal_insert_carburant #number4" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="4";
      }
      else{
        litre_value="4";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="4";
      }
      else{
        prix_value="4";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="4";
      }
      else{
        kilometrage_value="4";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//*************************
$( "#myModal_insert_carburant #number5" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="5";
      }
      else{
        litre_value="5";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="5";
      }
      else{
        prix_value="5";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="5";
      }
      else{
        kilometrage_value="5";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//****************************
$( "#myModal_insert_carburant #number6" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="6";
      }
      else{
        litre_value="6";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="6";
      }
      else{
        prix_value="6";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="6";
      }
      else{
        kilometrage_value="6";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//*****************
$( "#myModal_insert_carburant #number7" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="7";
      }
      else{
        litre_value="7";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="7";
      }
      else{
        prix_value="7";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="7";
      }
      else{
        kilometrage_value="7";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//***********************
$( "#myModal_insert_carburant #number8" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="8";
      }
      else{
        litre_value="8";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="8";
      }
      else{
        prix_value="8";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="8";
      }
      else{
        kilometrage_value="8";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//*********************
$( "#myModal_insert_carburant #number9" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="9";
      }
      else{
        litre_value="9";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="9";
      }
      else{
        prix_value="9";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="9";
      }
      else{
        kilometrage_value="9";
      }
    //  $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//************************
$( "#myModal_insert_carburant #number0" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value != "0"){
        litre_value+="0";
      }
      else{
        litre_value="0";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value != "0"){
        prix_value+="0";
      }
      else{
        prix_value="0";
      }

      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      if(kilometrage_value != "0"){
        kilometrage_value+="0";
      }
      else{
        kilometrage_value="0";
      }
      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//*************************
$( "#myModal_insert_carburant #numberx" ).click(function() {
    if(is_litres_selected == true){

      litre_value="0";


      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********

      prix_value="0";


      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){

      kilometrage_value="0";

      //$("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value));
    }


});
//**********************
$( "#myModal_insert_carburant #number_v" ).click(function() {
    if(is_litres_selected == true){
      if(litre_value.indexOf(".") == -1){
         litre_value+=".";
      }

      $("#myModal_insert_carburant #litres_insert_p").html(Number(litre_value).toFixed(3));
    }else if(is_prix_selected == true){
      //***********
      if(prix_value.indexOf(".") == -1){
         prix_value+=".";
      }
      $("#myModal_insert_carburant #prix_u_insert_p").html(Number(prix_value).toFixed(3));
    }else if(is_kilometrage_selected == true){
      /*
      if(kilometrage_value.indexOf(".") == -1){
         kilometrage_value+=".";
      }
      $("#myModal_insert_carburant #kilometrage_carburant_insert_p").html(Number(kilometrage_value).toFixed(3));
      */
    }


});
