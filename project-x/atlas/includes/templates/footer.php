	<div class="footer">
		<!-- This is footer -->
	</div>

	
	<?php if(isset($definir_fish_script)){?>
	<script type="text/javascript" src="<?php echo $js ?>definir_fish.js"></script>
	<?php } ?>
	<?php if(isset($report_sellers_fish_script)){?>
	<script type="text/javascript" src="<?php echo $js ?>report_sellers.js"></script>
	<?php } ?>
	<?php if(isset($report_buyers_fish_script)){?>
	<script type="text/javascript" src="<?php echo $js ?>report_buyers.js"></script>
	<?php } ?>
</body>
</html>