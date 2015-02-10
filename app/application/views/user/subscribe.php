<div class="row j-content">
	<div class="col-md-12 text-left">
		<p>&nbsp;</p>
		<h2>Subscribe to Premium account</h2>
		<p><br><b>Price: <?=PREMIUM_PRICE?> USD per month</b>
		<p>&nbsp;</p>
	</div>
</div>

<div class="row j-content">
	<div class="col-sm-5 col-sm-offset-4">
		<p><b>Pay with PayPal</b></p>
		<a href="<?= site_url('paypal')?>" class="btn btn-default">PayPal</a>
		<p>&nbsp;</p>
	</div>
</div> 

<div class="row j-content">
	
	<?php echo form_open('user/free_code', $form_atributes); ?>
		<div class="col-sm-5 col-sm-offset-4">
			<div class="form-group">
				<label><p>... or enter your free code:</p></label>
				<input type="text" name="free_code" id="free_code" class="form-control" />
			</div>
			<div class="form-group">
				<input type="submit" name="submit" id="btn-submit" class="btn btn-default  btn-lg" value="Submit"  />
			</div>
		</div>
	<?php echo form_close(); ?>
</div>
