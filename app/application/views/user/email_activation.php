<?php if(isset($error)) : ?>
	<div class="row">
		<div class="col-md-12">
			<h4><?= $error ?></h4>
		</div>
	</div>
<?php elseif(isset($email)) : ?>
	<div class="row">
		<div class="col-md-12">
			<h4>Activation</h4>
			<p>Your activation code has been sent to "<?= $email ?>" that you provided</p>
		</div>
	</div>
<?php elseif(isset($succes)) : ?>
	<div class="row">
		<div class="col-md-12">
			<h4>Activation successfully</h4>
			<p><?= $succes ?></p>
		</div>
		<div class="col-md-12">
			<a href="<?= $url ?>">Home</a>
		</div>
	</div>
<?php endif; ?>