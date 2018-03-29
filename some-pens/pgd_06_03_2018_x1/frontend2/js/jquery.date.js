function nbJour(mois)
{
	if (mois==1) nbrjour=31;
	if (mois==2) nbrjour=28;
	if (mois==3) nbrjour=31;
	if (mois==4) nbrjour=30;
	if (mois==5) nbrjour=31;
	if (mois==6) nbrjour=30;
	if (mois==7) nbrjour=31;
	if (mois==8) nbrjour=31;
	if (mois==9) nbrjour=30;
	if (mois==10) nbrjour=31;
	if (mois==11) nbrjour=30;
	if (mois==12) nbrjour=31;
	return nbrjour;
}
$(document).ready(function() {	
						   
	$('.bougerJ').click(function(){
								 								 
		var DATED=$('#DATED').val();
		var DATEF=$('#DATEF').val();
		//$('input[name=DATED]').attr('value',DATED);
		sens=parseInt($(this).attr('sens'));
		if (sens==-1){
			var j = parseInt(DATED.split("/")[0], 10); // jour
			var m = parseInt(DATED.split("/")[1], 10); // mois
			var a = parseInt(DATED.split("/")[2], 10); // anne
			if (j==1) { 
				if (m==1){
					m=12;
					a=a-1;
				}else	m=m-1;		
				jour = nbJour(m);
			}else	jour=j-1;
		}
		if( sens == 1){
		
			var j = parseInt(DATEF.split("/")[0], 10); // jour
			var m = parseInt(DATEF.split("/")[1], 10); // mois
			var a = parseInt(DATEF 	.split("/")[2], 10); // anne
			if ( j== nbJour(m)) { 
				if (m==12){
					m=1;
					a=a+1;
				}else	m=m+1;		
				jour = 1;
			}else	jour=j+1;
			
		}
	
		if (jour<=9) jour='0'+jour;
		if(m<=9) m='0'+m;
		DATE=jour+'/'+m+'/'+a;
		$('input[name=DATED]').attr('value',DATE);
		$('input[name=DATEF]').attr('value',DATE);
		
	});
	
	$('.bougerM').click(function(){
		var DATED=$('#DATED').val();
		var DATEF=$('#DATEF').val();
		//$('input[name=DATED]').attr('value',DATED);
		sens=parseInt($(this).attr('sens'));
		if (sens==-1){
			var j = parseInt(DATED.split("/")[0], 10); // jour
			var m = parseInt(DATED.split("/")[1], 10); // mois
			var a = parseInt(DATED.split("/")[2], 10); // anne
			if (j == 1) {
				if(m==1){
					nM=12;
					a=a-1;
				}else{
					nM=m-1;
					a=a;
				}
				var jD=1;
				var mD=nM;
				var aD=a;
				
				var jF = nbJour(nM);
				var mF = nM ;
				var aF = a ;
			}else{
				var jD = 1;
				var mD = m;
				var aD = a;
				
				var jF = nbJour(m);
				var mF = m ;
				var aF = a ;
			}
		}
		if( sens == 1){
		
			var j = parseInt(DATEF.split("/")[0], 10); // jour
			var m = parseInt(DATEF.split("/")[1], 10); // mois
			var a = parseInt(DATEF 	.split("/")[2], 10); // anne
			if ( j == nbJour(m)) { 
				if(m==12){
					nM=1;nA=a+1;
				}else{
					nM=m+1;
					nA=a;
				}
				var jF = nbJour(nM);
				var mF = nM ;
				var aF = a ;
				
				var jD = 1;
				var mD = nM;
				var aD = a;
			}else{
				var jF = nbJour(m);
				var mF = m ;
				var aF = a ;
				
				var jD = 1;
				var mD = m;
				var aD = a;
			}
			
		}
	
		
	
		if(mD<=9) mD='0'+mD;
		if(jD<=9) jD='0'+jD;
		if(mF<=9) mF='0'+mF;
		if(jF<=9) jF='0'+jF;
		NDATED=jD+'/'+mD+'/'+aD;
		NDATEF=jF+'/'+mF+'/'+aF;
		//DATE=jour+'/'+m+'/'+a;
		$('input[name=DATED]').attr('value',NDATED);
		$('input[name=DATEF]').attr('value',NDATEF);
		
	});
	
});
