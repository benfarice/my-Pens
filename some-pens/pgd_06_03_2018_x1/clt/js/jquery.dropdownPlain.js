$(function(){

    $("ul.dropdown li").hover(function(){
    
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
    
    }, function(){
        $(this).removeClass("hover");
        $('ul:first',this).css('visibility', 'hidden');
    
    });
	$("ul.dropdown ul[niv=two]").hover(function(){
		var p = $(this).attr('parent');
		$('li[niv=one][parent='+p+'] a').addClass("hover");
    }, function(){
    	var p = $(this).attr('parent');
		$('li[niv=one][parent='+p+'] a').removeClass("hover");
    
    });
    


});