
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('#edit-email').on('show.bs.modal', function(event){
			var button = $(event.relatedTarget);
			var post_id = button.data('post-id');
			CKEDITOR.replace( 'email_body', {
				'extraPlugins' : 'imagebrowser',
				'imageBrowser_listUrl' : '<?= base_url() ?>img/<?= $this->session->userdata('user_id') ?>/images_list.json',
    			filebrowserUploadUrl: '<?= site_url('upload/upload_image/1/') ?>'
			});
			var modal = $(this);
			jQuery.ajax({
				url: '<?= site_url('user/get_email_template') ?>',
				type: 'POST',
				data: { 'post_id' : post_id },
				dataType: 'json',
				success: function(res) {
					CKEDITOR.instances.email_body.setData(res['message']);
					jQuery('#post_id').val(post_id);
					jQuery('#subject').val(res['subject']);
					jQuery('#coupon_code').val(res['code_lenght']);
					if(res['status'] == 1){
						jQuery('#email_sending').prop('checked', true);
					} else {
						jQuery('#email_sending').prop('checked', false);
					}
				}
			})
		})
		
		jQuery('#edit-email').on('hide.bs.modal', function(event){
			var instance = CKEDITOR.instances['email_body'];
			instance.destroy();
		})
		
		jQuery('#save_email').on('click', function(event){
			var post_id = jQuery('#post_id').val();
			var message = CKEDITOR.instances.email_body.getData();
			var subject = jQuery('#subject').val();
			var cupon_code = jQuery('#coupon_code').val();
			var status = 0;
			if(jQuery('#email_sending').is(':checked')){
				status = 1;
			}
			jQuery.ajax({
				url: '<?= site_url('user/save_email_template') ?>',
				type: 'POST',
				data: { 'message' : message, 'post_id' : post_id, 'subject' : subject, 'status' : status, 'code_lenght' : cupon_code},
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
<div class="page-header text-left">
    <h3><small>List of your posts that use #BrandTap referral program <a href="http://www.brandtap.co">Back to the BrandTap.co site</a></small></h3>
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
			                    <th><?= strtoupper('Post details'); ?> </th>
			                    <th class="text-center"><?= strtoupper('Winners'); ?> </th>
			                    <th class="text-center"><?= strtoupper('Hashtags'); ?> </th>
			                    <th class="text-center"><?= strtoupper('Stats'); ?> </th>
			                    <th class="text-center"> </th>
			                </tr>
			            </thead>
			            <tbody>
			                <?php foreach ($media as $row) { ?>
			                    <tr <?=!$row['email_template'] ? ' style="background: #F2F2F2" ' : ''?> >
			                        <td>
			                            <div class="pull-left">
			                                <a href="<?= $row['link'] ?>" class="thumbnail">
			                                    <img class="img-rounded img-responsive" src="<?= $row['image'] ?>" alt="<?= $row['caption'] ?>">
			                                </a>                            
			                                <p><?= $row['date'] ?></p>
			                            </div>
			                            <div class="pull-left">
			                                <p><?= $row['caption'] ?></p>
			                                <p><a href="<?= $row['link'] ?>">View on Instagram</a></p>
			                            </div>
			                        </td>
			                        <td>
			                            <?php foreach ($row['winners'] as $user) : ?>
			                                <p><?= $user ?></p>
			                            <?php endforeach ?>
			                        </td>
			                        <td>
			                            <?php foreach ($row['hashtags'] as $tag) : ?>
			                                <p><?= $tag ?></p>
			                            <?php endforeach ?>	
			                        </td>
			                        <td>                       
			                            <p><span class="badge"><?= $row['likes'] ?></span></p>
			                            <p><span class="badge"><?= $row['comments'] ?></span></p>
			                        </td>
			                        <td>
			                        	<button type="button"
			                        			class="btn btn-default"
			                        			data-toggle="modal" 
			                        			data-target="#edit-email"
			                        			data-post-id="<?= $row['id'] ?>">
		                            		Edit email
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
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
				<h3 class="modal-title" id="label">
					Edit email
				</h3>
			</div>
			<div class="modal-body">
				<p>Create custum email that will be sent to users that liked or commented this post</p>
				<div>
					<h4>Configuration</h4>
					<p>
						<input type="checkbox" name="email_sending" id="email_sending" value="Sending status">
						If the email should be sent for this post
					</p>
					<p>
						Coupon code lenght
						<input type="text" name="coupon_code" id="coupon_code" />
					</p>
				</div>
				<hr />
				<br />
				<div>
					<h4>Email subject</h4>
					<input type="text" id="subject"/>
				</div>
				<div>
					<br />{name} - name of user who liked post<br />
					{brand} - brand which posted image/post
				</div>
				<br />
				<div>
					<h4>Email message</h4>
					<textarea id="email_body" name="email_body" rows="5" cols="90"></textarea>
				</div>
				<div>
					<br />{name} - name of user who liked post<br />
					{coupon_code} - code which will be used when buying brand's product/service<br />
					{brand} - brand which posted image/post<br />
				</div>
				<input type="hidden" value="" id="post_id"/>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-default" data-toggle="modal" href="#test">Test</button>
       			<button type="button" class="btn btn-primary" id="save_email">Save changes</button>
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
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="send_test">Send</button>
			</div>
		</div>
	</div>
</div>