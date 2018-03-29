</div><!-- fin div page -->
<div id="Ajax"></div>
<script language="javascript" type="text/javascript">

function autoUpdate() { 		   
	     if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                     pos = {lat: position.coords.latitude, lng: position.coords.longitude};
				//	alert(pos.lat); alert(pos.lng); 
					 $('#Ajax').load('traceVnd.php?trace&lat='+pos.lat+'&lng='+pos.lng);//traceVnd.php
		        });
				}
	 setTimeout(autoUpdate, 60000); // 1 minute
 }   
 autoUpdate();
</script>
</body>

</html>
