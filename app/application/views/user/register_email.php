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
    <h3>Email registration</h3>
    <?php echo form_open('user/email_activation', $form_atributes); ?>
    <div class="col-sm-4 col-sm-offset-4">
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
            <p>
                <a href="#" id="brand-yes" class="btn-brand">I'M A BRAND</a>
                <a href="#" id="brand-no" class="btn-brand influencer">I'M AN INFLUENCER</a>
                <input type="hidden" name="brand" id="brand" value="0">
            </p>
            <br><br>
            <input type="submit" name="submit" id="btn-submit" class="btn btn-primary" value="Register" disabled />
        </div>
    </div>
    <?php echo form_close(); ?>    
</div>