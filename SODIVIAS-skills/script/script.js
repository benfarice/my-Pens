$(function() {
  $('.skitter-large').skitter({
    numbers: true,
    theme:"minimalist"
  });
});
$( ".elevage" ).mouseover(function() {
   $(this).attr('src', "images/x-1.png");
});
$( ".Abattage" ).mouseover(function() {
   $(this).attr('src', "images/x-2.png");
});
$( ".La_Decoupe" ).mouseover(function() {
   $(this).attr('src', "images/x-3.png");
});
$( ".La_Transformation" ).mouseover(function() {
   $(this).attr('src', "images/x-4.png");
});
$( ".La_Distribution" ).mouseover(function() {
   $(this).attr('src', "images/x-5.png");
});
//*************
$( ".elevage" ).mouseleave(function() {
   $(this).attr('src', "images/1.png");
});
$( ".Abattage" ).mouseleave(function() {
   $(this).attr('src', "images/2.png");
});
$( ".La_Decoupe" ).mouseleave(function() {
   $(this).attr('src', "images/3.png");
});
$( ".La_Transformation" ).mouseleave(function() {
   $(this).attr('src', "images/4.png");
});
$( ".La_Distribution" ).mouseleave(function() {
   $(this).attr('src', "images/5.png");
});
$(".dropdown-trigger").dropdown();
document.addEventListener('DOMContentLoaded', function() {
   var elems = document.querySelectorAll('.sidenav');
   options={};
   var instances = M.Sidenav.init(elems, options);
 });
 document.addEventListener('DOMContentLoaded', function() {
     var elems = document.querySelectorAll('.dropdown-trigger');
     options={};
     var instances = M.Dropdown.init(elems, options);
   });
 // Or with jQuery

 $(document).ready(function(){
   $('.sidenav').sidenav();
 });
