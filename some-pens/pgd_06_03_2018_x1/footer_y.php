<div class="clear"></div><br><br><br><br><br>
	<div class="footer fixed-bottom">
	  <?php echo $trad['label']['DroitsReserve']; ?> 		 
	</div>
<!--div id="elevator"> <img src="images/elevator.png" alt="UP" id="boutonUp"/></div>
<div id="elevator2"> <img src="images/elevator2.png" alt="Down" id="boutonDown"/></div-->
	<div id="brouillon"></div>
 </div>	<!-- fin page -->
<script type="text/javascript">
$(document).ready(function() {
	var $elem = $('body');
	$('#elevator2').click(function(){
		$('html, body').animate({scrollTop:$elem.height()}, 'slow');
	});
	$('#elevator').click(function(){
		$('html, body').animate({scrollTop:0}, 'slow');
	});
	});
</script>
</body>
</html>
		  