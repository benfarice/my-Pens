$(".panel-heading").parent('.panel').hover(
  function() {
    $('.collapse').collapse('hide');
    $(this).children('.collapse').collapse('show');
  }, function() {
    $(this).children('.collapse').collapse('hide');
  }
);
$("#top-div").mouseleave(function() {
  $('.collapse').collapse('hide');
});
$(".bkg").mouseleave(function() {
  $('.collapse').collapse('hide');
});

$(function() {
  $('.skitter-large').skitter({

  });
});
