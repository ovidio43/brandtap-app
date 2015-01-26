<script>
	if (window != top) {
		top.location.replace(document.location);
	}
</script>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">My subscription</h3>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h2>Pay for unlimited posts</h2>
			<p><b>Description:</b> <?= $description ?></p>
			<p><b>Subscription details:</b> <?= $details ?></p>
			<?= $pp_btn['button'] ?>
			<?= $pp_btn['script'] ?>
		</div>
	</div>
</div>