<div class="row">
	<h3>Email registration</h3>
	<?php echo form_open('user/emali_activation' , $form_atributes); ?>
		<div class="form-group">
			<label for="input_email" class="col-sm-2 control-label">Email*</label>
			<div class="class="col-sm-10"">
				<input type="text" class="form-control" id="input_email" required placeholder="<?= $email ?>" name="email" />
			</div>
		</div>
		<div class="form-group">
			<input type="submit" name="submit" class="btn btn-primary" value="Register" />
		</div>
	</form>
</div>