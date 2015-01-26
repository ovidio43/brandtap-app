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

			<p>Your subscription status is Active! <br>Your Payment Profile ID is <?= $_user; ?></p>
			<br>
			<a href="<?=base_url('index.php/paypal/cancel')?>">Cancel your subscription</a>

		</div>
	</div>
</div>