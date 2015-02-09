
<script type="text/javascript">
	jQuery(document).ready(function () {
		
		var count = 0;
		
		jQuery('#edit-email').on('show.bs.modal', function(event){
			var button = $(event.relatedTarget);
			var post_id = button.data('post-id');
			CKEDITOR.replace( 'email_body', {
				'extraPlugins' : 'imagebrowser',
				'imageBrowser_listUrl' : '<?= base_url() ?>img/<?= $this->session->userdata('user_id') ?>/images_list.json',
    			filebrowserUploadUrl: '<?= site_url('upload/upload_image/1/') ?>',
    			allowedContent: true
			});
			var modal = $(this);
			jQuery.ajax({
				url: '<?= site_url('user/get_email_template') ?>',
				type: 'POST',
				data: { 'post_id' : post_id },
				dataType: 'json',
				success: function(res) {
					//CKEDITOR.instances.email_body.setData(res['message']);
					if(res['template_custom'] == 0){
						CKEDITOR.instances.email_body.setData('<h1 style="color: rgb(170, 170, 170); font-style: italic;">Customize Your Email Body</h1>');
						CKEDITOR.instances.email_body.on('focus', function(){
							if(count == 0){
								CKEDITOR.instances.email_body.setData('');
								count = 1;	
							}
						})	
					} else {
						CKEDITOR.instances.email_body.setData(res['message']);
					}
					jQuery('#post_id').val(post_id);
					jQuery('#subject').val(res['subject']);
					jQuery('#coupon_code').val(res['code_lenght']);
				}
			})
		})
		
		jQuery('#edit-email').on('hide.bs.modal', function(event){
			var instance = CKEDITOR.instances['email_body'];
			instance.destroy();
			count = 0;
		})
		
		jQuery('#save_email').on('click', function(event){
			var message = CKEDITOR.instances.email_body.getData();
			var subject = jQuery('#subject').val();
			if(subject == '' || message == ''){
				alert('Missing email subject or message');
				return;
			}
			var post_id = jQuery('#post_id').val();
			var cupon_code = jQuery('#coupon_code').val();
			jQuery.ajax({
				url: '<?= site_url('user/save_email_template') ?>',
				type: 'POST',
				data: { 'message' : message, 'post_id' : post_id, 'subject' : subject, 'code_lenght' : cupon_code},
				dataType: 'json',
				success: function(res){
					alert("Email template has been successfully saved!");
				}
			})
		})
		
		jQuery('#send_test').on('click', function(event){
			var post_id = jQuery('#post_id').val();
			jQuery.ajax({
				url: '<?= site_url('user/test_email_template') ?>',
				type: 'POST',
				data: { 'post_id' : post_id},
				dataType: 'json',
				success: function(res){
					alert("Email template has been successfully sent!");
				}
			})
		})
		
		jQuery('input[type="checkbox"]').change( function(){
			var post_id = jQuery(this).attr('id');
			var status = 0;
			if(jQuery(this).prop('checked')){
				status = 1;
			}
			jQuery.ajax({
				url: '<?= site_url('user/email_sending_status_change') ?>',
				type: 'POST',
				data: { 'post_id' : post_id, 'status' : status},
				dataType: 'json',
				success: function(res){
					if(res == 1){
						jQuery('#tbl-row-' + post_id).css('background', '#E6F8E0');
					} else{
						jQuery('#tbl-row-' + post_id).css('background', '#F8E0E0');
					}
				}
			})
			
			if(status == 1){
				jQuery('#email_button_' + post_id).trigger('click');
			}
		})
		
		$.fn.modal.Constructor.prototype.enforceFocus = function () {
    		modal_this = this
    		$(document).on('focusin.modal', function (e) {
       			if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
        			&& !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && 
        			!$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
            		modal_this.$element.focus()
        		}
    		})
		};
	});
</script>
<style type="text/css">
	.onoffswitch {
    	position: relative; 
    	width: 80px;
    	-webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
    	text-align: left;
    	margin: 0 auto;
    }
    .onoffswitch-checkbox {
    	display: none;
    }
    .onoffswitch-label {
    	display: block; 
    	overflow: hidden; 
    	cursor: pointer;
    	border: 2px solid #999999; 
    	border-radius: 4px;
    	height: 30px;
    }
    .onoffswitch-inner {
   		display: block; 
   		width: 200%; 
   		margin-left: -100%;
    	-moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
    	-o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
    }
    .onoffswitch-inner:before, .onoffswitch-inner:after {
    	display: block; 
    	float: left; 
    	width: 50%; 
    	height: 30px; 
    	padding: 0; 
    	line-height: 20px;
    	font-size: 12px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    	-moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
    }
    .onoffswitch-inner:before {
    	content: "Yes";
    	padding-left: 10px;
    	background-color: #84a54c; color: #FFFFFF;
    	padding-top: 4px;
    }
    .onoffswitch-inner:after {
    	content: "No";
    	padding-right: 10px;
    	background-color: #d4392a; color: #FFFFFF;
    	text-align: right;
    	padding-top: 4px;
    }
    .onoffswitch-switch {
    	display: block; 
    	width: 35px; 
    	margin: 1px;
    	background: #FFFFFF; padding: -2px;
    	border: 2px solid #999999; 
    	border-radius: 4px;
    	position: absolute; 
    	top: 0; 
    	bottom: 0; 
    	right: 43px;
    	-moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
    	-o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s;
		-moz-box-shadow: inset -1px -1px 2px #CCC; 
		-webkit-box-shadow: inset -1px -1px 2px #CCC;
    	box-shadow: inset -1px -1px 2px #CCC;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    	margin-left: 0;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    	right: 0px;
    }
    .inner-lines{
    	text-align: center;
    	margin-top: 1px;
    }
</style>
<div class="page-header text-left">
    <h3><small>
    	List of your posts that use #BrandTap referral program
    </small></h3>
</div>

<!--<div class="row j-title">
    <div class="col-md-12 text-left">
        <h1 class="pull-left">Dashboard</h1> 
        <p class="pull-left">(List of your posts that use #BrandTap referral program) </p>
        <h4 class="pull-right">
            <a href="http://www.btandtap.co">Back to the BrandTap.co site</a>
        </h4>
    </div>
</div>-->
<div role="tabpanel">
    <!--<ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                    <a href="#promotions" role="tab" data-toggle="tab" aria-controls="promotions" aria-expanded="true">
                            Promotions
                    </a>
            </li>
            <li role="presentation">
                    <a href="#discounts" role="tab" data-toggle="tab" aria-controls="discounts" aria-expanded="false">
                            Discounts
                    </a>
            </li>
    </ul>
    -->
    <div class="tab-content">
        <div id="promotions" class="tab-pane active" role="tabpanel">           
            <? if (count($media)): ?>   

				<div class="table-responsive">
			        <table class="table table-bordered table-hover text-left">
			            <thead>
			                <tr class="active">
			                    <th class="text-center" style="width: 100px"><?= strtoupper('Post details'); ?> </th>
			                    <th class="text-center"><?= strtoupper('Username'); ?></th>
			                    <th class="text-center"><?= strtoupper('Email'); ?></th>
			                    <th class="text-center" style="width: 11%;"><?= strtoupper('Promo Codes Awarded'); ?> </th>
			                    <!--<th class="text-center"><?= strtoupper('Hashtags'); ?> </th>-->
			                    <th class="text-center"><?= strtoupper('Stats'); ?> </th>
			                    <th class="text-center"><?= strtoupper('Send Email'); ?></th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php foreach ($media as $row) { ?>
			                    <tr <?=!$row['sending_status'] ? ' style="background: #F8E0E0" ' : ' style="background: #E6F8E0"'?> 
			                    	id="tbl-row-<?= $row['id'] ?>">
			                        <td>
			                            <div>
			                                <a href="<?= $row['link'] ?>" class="thumbnail" style="margin-bottom: 5px;">
			                                    <img class="img-rounded img-responsive" src="<?= $row['image'] ?>" alt="<?= $row['caption'] ?>">
			                                </a>                            
			                                <?= $row['date'] ?>
			                                <p>
			                                	<?php foreach ($row['hashtags'] as $tag) : ?>
			                                		#<?= ucfirst($tag) ?><br />
			                           			 <?php endforeach ?>
			                                	<a href="<?= $row['link'] ?>">View on Instagram</a>
			                                </p>
			                            </div>
			                        </td>
	                        		<td>
	                        			<?php foreach($row['winners'] as $data) : ?>
	                        				<?= $data['username'] ?>
	                        			<?php endforeach ?>
	                        		</td>
	                        		<td>
	                        			<?php foreach($row['winners'] as $data) : ?>
	                        				<?= isset($data['email']) ? $data['email'] : '' ?>
	                        			<?php endforeach ?>
	                        		</td>
	                        		<td>
	                        			<?php foreach($row['winners'] as $data) : ?>
	                        				<?= $data['code'] ?>
	                        			<?php endforeach ?>
	                        		</td>
			                        <!--<td>
			                            <?php foreach ($row['hashtags'] as $tag) : ?>
			                                <p><?= $tag ?></p>
			                            <?php endforeach ?>	
			                        </td>-->
			                        <td>                       
			                            <p><span class="badge"><?= $row['likes'] ?></span></p>
			                            <p><span class="badge"><?= $row['comments'] ?></span></p>
			                        </td>
			                        <td class="text-center">
		                            	<p>
											<div class="onoffswitch" data-toggle="tooltip" data-placement="top" title="Send out Email for this Post">
												<input type="checkbox" name="email_status" class="onoffswitch-checkbox" id="<?= $row['id'] ?>"
													<?= !$row['sending_status'] ? '' : 'checked' ?> />
		 										<label class="onoffswitch-label" for="<?= $row['id'] ?>">
													<span class="onoffswitch-inner"></span>
													<span class="onoffswitch-switch">
														<div class="inner-lines">
															|||
														</div>
													</span>
												</label>
											</div>
		                            	</p>
		                            	<button type="button"
			                        			class="btn btn-default"
			                        			data-toggle="modal" 
			                        			data-target="#edit-email"
			                        			data-post-id="<?= $row['id'] ?>"
			                        			id="email_button_<?= $row['id'] ?>">
		                            		Create Custom Email
		                            	</button>
			                        </td>
			                    </tr>
			                <?php } ?>
			            </tbody>
			        </table>
			    </div>
            <? else: ?>
                <div class="row">
                    <div class="col-md-4 text-left">
                        <p style="margin:10px 0; font-weight: bold;">You have no promotions yet!</p>
                    </div>
                </div>
            <? endif ?>
        </div>
    	<div id="discounts" class="tab-pane" role="tabpanel">
        	<div class="row">
            	<div class="col-md-4 text-left">
                	<h2>Post details</h2>
            	</div>
            	<div class="col-md-3 text-left">
                	<h2>Coupons Awarded</h2>
           		</div>
            	<div class="col-md-3 text-left">
                	<h2>Hashtags</h2>
            	</div>
            	<?php foreach ($media_user as $row) : ?>
                	<div class="row">
                    	<div class="col-md-4 text-left">
                        	<div class="col-md-4">
                            	<img src="<?= $row['image'] ?>" />
                            	<p><?= $row['date'] ?></p>
                        	</div>
                        	<div class="col-md-4">
                            	<p><?= $row['caption'] ?></p>
                            	<p><a href="<?= $row['link'] ?>">View on Instagram</a></p>
                        	</div>
                    	</div>
                    	<div class="col-md-3 text-left">
                        	<p><?= $row['winners'] ?></p>
                    	</div>
                    	<div class="col-md-3 text-left">
                        	<?php foreach ($row['hashtags'] as $tag) : ?>
                            	<p><?= $tag ?></p>
                        	<?php endforeach ?>
                    	</div>
                	</div>
                	<hr />
            	<?php endforeach ?>
        	</div>
    	</div>
   </div>
</div>

<div id="edit-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label" aria-hidden="true" data-focus-on="input:first">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
				<h3 class="modal-title" id="label">
					Create Custom Email to your Users
				</h3>
			</div>
			<div class="modal-body">
				<div>
					<h4 style="float: left">Email Subject Line*</h4>
					<input type="text" id="subject" style="float: left; margin-left: 10px"/>
				</div>
				<div style="clear: both"></div>
				<div class="text-left">
					<br />{name} - name of user who liked post<br />
					{brand} - brand which posted image/post
				</div>
				<hr />
				<br />
				<div class="text-left">
					<h4>Create Custom Coupon Code</h4>
					<br />
					<p>
						Coupon Code Length/Specifications
						<input type="text" name="coupon_code" id="coupon_code" />
					</p>
				</div>
				<hr />
				<br />
				<div>
					<h4>Message to Send to your Followers*</h4>
					<br />
					<p class="text-left">Send the Latest Buzz, Prepare a Newsletter, Email Product information, etc. 
						You can add images, pictures, links, designs, coupon codes, and checkout buttons here!</p>
					<textarea id="email_body" name="email_body" rows="5" cols="90"></textarea>
				</div>
				<div class="text-left">
					<br />{name} - name of user who liked post<br />
					{coupon_code} - code which will be used when buying brand's product/service<br />
					{brand} - brand which posted image/post<br />
				</div>
				<input type="hidden" value="" id="post_id"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Close</button>
				<button type="button" class="btn btn-default" data-toggle="modal" href="#test">Test - Send Email to My Email Address on File</button>
       			<button type="button" class="btn btn-primary" id="save_email">Save Changes</button>
			</div>
			</div>
		</div>
	</div>
</div>
<div id="test" class="modal fade" tabindex="-1" data-focus-on="input:first">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
				<h3 class="modal-title" id="label">
					Test email confirm
				</h3>
			</div>
			<div class="modal-body">
				<p>Test email will be sent only to your email</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Close</button>
				<button type="button" class="btn btn-primary" id="send_test">Send</button>
			</div>
		</div>
	</div>
</div>