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
			<p>
				Your subscription has been cancelled.
				<a href="<?= base_url('paypal/pay') ?>" target="_top">Try again? &raquo;</a>
			</p>
		</div>
	</div>
</div>