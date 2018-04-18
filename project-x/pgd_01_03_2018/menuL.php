<?php  @session_start(); 
if(isset($_SESSION['M'])){
	require_once('php.fonctions.php');
	?>
<div class="menuLogin">
	<div class="btnLogin">
   
     <span style="padding-left:8px;"><?php echo $_SESSION['M']['NOMM'];	?></span></div>

	<div class="boxLogin"> <?php include("menuLogin.php"); ?> </div>
	
</div>
<script language="javascript" type="text/javascript">
		
	
	$('.btnLogin').click(function(event){
		
		if($('.boxLogin').css('display') == 'none'){
			$('.boxLogin').show();
			$('.btnLogin').addClass('open');
			
		}else {	
			$('body').one('click',function() {
				$('.sbCont').hide();
				$('.boxLogin').hide();
				$('.btnLogin').removeClass('open');
				$('.sousBouton').removeClass('active');
			});
			//event.stopPropagation();
		}
	
	});
		</script>
<?php }
?>