<div>
	<a 
	class="btn btn-success btn-share"
	href="<?php echo ROOT_URL; ?>/shares/add">
		Share Something
	</a>
	<?php foreach($viewmodel as $item) : ?>
		<div class="jumbotron">
			<h3><?php echo $item['title']; ?></h3>
			<small><?php echo $item['create_date']; ?></small>
			<hr>
			<p><?php echo $item['body']; ?></p>
			<br>
			<a href="<?php echo $item['link']; ?>" 
			class="btn btn-info"
			target="_blank">
				Go To Website
			</a>
		</div>
	<?php endforeach; ?>
</div>