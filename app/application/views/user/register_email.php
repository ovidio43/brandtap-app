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
<div class="row j-content">
    <h3>Email registration</h3><br>
    <?php echo form_open('user/email_activation', $form_atributes); ?>
    <div class="col-sm-5 col-sm-offset-4">
        <div class="form-group">
            <label>E-mail *</label>
            <input type="text" 
                   class="form-control"
                   id="input_email"
                   required
                   placeholder="Enter your e-mail..."
                   name="email" />
            <br>
            <label>Are you a brand? *</label><br>

            <div class="paggers">
                <a href="#home-forms" id="brand-yes" class="no-decore" onclick="jQuery('.tabs').removeClass('current');
                        jQuery('.tab-0').addClass('current');">
                    <div class="tabs tab-0 inline-top">I'M A BRAND</div>
                </a>
                <a href="#home-forms" id="brand-no" class="no-decore" onclick="jQuery('.tabs').removeClass('current');
                        jQuery('.tab-1').addClass('current');">
                    <div class="tabs tab-1 inline-top current">I'M AN INFLUENCER</div>
                </a>
                <input type="hidden" name="brand" id="brand" value="0">
            </div>
<!--            <p>
                <a href="#" id="brand-yes" class="btn-brand">I'M A BRAND</a>
                <a href="#" id="brand-no" class="btn-brand influencer">I'M AN INFLUENCER</a>
                <input type="hidden" name="brand" id="brand" value="0">
            </p>-->
            <br>
            <input type="submit" name="submit" id="btn-submit" class="btn btn-default  btn-lg" value="Register"  />
        </div>
    </div>
    <?php echo form_close(); ?>    
</div>