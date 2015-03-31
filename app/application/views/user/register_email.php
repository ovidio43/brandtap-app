<script type="text/javascript">
    $(document).ready(function () {
        $('.btn-brand').click(function () {

            $('.btn-brand').removeClass('active');
            $(this).addClass('active');

            $('#brand').val(0);
            if ($(this).prop('id') == 'brand-yes') {
                $('#brand').val(1);
            }


            $('#btn-submit').removeAttr('disabled');
            return false;
        });
    });
</script>
<style type="text/css">
	.brand-checkbox-container .brand-checkbox{
		width: 50px;
		height: 50px;
		background: #f1f1f1;
		border-radius: 14px;
		border: 4px solid #d7d7d7;
		margin: 0px auto;

		display: block;
		float: left;
	}
	.brand-checkbox-container .brand-checkbox-label{
		display: block;
		float: left;
		width: 90%;
		padding-top: 10px;
	}
	.brand-checkbox-container .brand-checkbox-clear{
		clear: both;
	}
	.chbBrand-checkbox{
		display: none;
	}
	.onoff-label{
		display: block;
		width: 50px;
		height: 50px;
	}
	.ok-sign{
		color: #e81a1a;
		margin-left: 0px;
		margin-top: 6px;
		font-size: 26px;
		display: none;
	}
	.no-sign{
		display: block;
		width: 50px;
		height: 50px;
	}
	.chbBrand-checkbox:checked + .onoff-label .no-sign{
		display: none;
	}
	.chbBrand-checkbox:checked + .onoff-label .ok-sign{
		display: block;
	}
</style>

<? if( $this->input->get('error')): ?>
	<div class="alert alert-warning">
		<?=$this->input->get('error')?>
	</div>
<? endif ?>

<div class="row j-content" style="margin-top: 10px;">
	<h3>
		Please provide the email address you would like us to send emails from.
	</h3>
	<p>&nbsp;</p>
	<div class="col-sm-8 col-sm-offset-2">
   		<?php echo form_open('user/email_activation', $form_atributes); ?>
    		<!--<div class="col-sm-5 col-sm-offset-4">-->
        	<div class="form-group">
            	<label class="col-sm-2 control-label"><h3>Email*</h3></label>
            	<div class="col-sm-10">
            		<input type="text" 
                		class="form-control"
                		id="input_email"
                		required
                		placeholder="Enter your email..."
                		name="email" />
            	</div>
            </div>
            <!--<div class="form-group">
				<div class="row">
					<div class="col-md-12 brand-checkbox-container">
						<p>&nbsp;</p>
						<div class="brand-checkbox">
							<input type="checkbox" name="chbBrand" id="chbBrand" class="chbBrand-checkbox" />
							<label class="onoff-label" for="chbBrand">
								<span class="glyphicon glyphicon-ok ok-sign"></span>
								<span class="no-sign"></span>
							</label>
						</div>
						<div class="brand-checkbox-label">
							<h3>I am a Brand, Celebrity, Small Business, or Influencer</h3>
						</div>
						<div class="brand-checkbox-clear"> </div>
					</div>
				</div>-->
            	<!--<div class="paggers">
                	<a href="#home-forms" id="brand-yes" class="no-decore" onclick="jQuery('.tabs').removeClass('current');
                        jQuery('.tab-0').addClass('current');">
                    	<div class="tabs tab-0 inline-top">I'M A BRAND</div>
                	</a>
                	<a href="#home-forms" id="brand-no" class="no-decore" onclick="jQuery('.tabs').removeClass('current');
                        jQuery('.tab-1').addClass('current');">
                    	<div class="tabs tab-1 inline-top current">I'M AN INFLUENCER</div>
                	</a>
                	<input type="hidden" name="brand" id="brand" value="0">
         </div> -->
<!--            <p>
                <a href="#" id="brand-yes" class="btn-brand">I'M A BRAND</a>
                <a href="#" id="brand-no" class="btn-brand influencer">I'M AN INFLUENCER</a>
                <input type="hidden" name="brand" id="brand" value="0">
            </p>-->
				<div class="form-group">
					<p>&nbsp;</p>
					<input type="submit" name="submit" id="btn-submit" class="btn btn-default  btn-lg" value="Register"  />
				</div>
       		</div>
    	<?php echo form_close(); ?>
    </div>    
</div>