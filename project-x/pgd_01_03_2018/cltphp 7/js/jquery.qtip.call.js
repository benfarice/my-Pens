$(document).ready(function() {	
						   
			
					
			$('*[bulle=custo][title]').qtip({
				style		: {		classes	: 'ui-tooltip-rounded ui-tooltip-shadow'	},
				position	: {
						my : 'bottom center',
						at	: 'top center'
				},
				show		: {
						effect: function(offset) {
							$(this).show('bounce', null, 10);
						}
				}


   		  
			});
			
	});