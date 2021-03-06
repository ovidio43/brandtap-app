<!DOCTYPE html>
<html lang="en">
	
<head>
	
	<title><?= $document_title ?></title>
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	
	<!-- Bootstrap CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">      
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
</head>

<body>
	<div class="container">
		<div class="header">
			<div class="row">
				<div class="col-md-4">
					<a href="<?=base_url()?>">
						<img style="float: left; margin-top: 22px; height: 57px;" src="<?= base_url()?>/img/logo.png" />
					</a>
				</div>
				<div class="col-md-5">
					<h2>Headline, slogan or something</h2>
				</div>
				<div class="col-md-3">
					<?php if(isset($loged_name)) : ?>
						<p>Welcome @<?= $loged_name ?> | <a href="<?= site_url('user/logout') ?>">Logout</a></p>
					<?php endif ?>
				</div>
			</div>
		</div>
		<div class="text-center">
        	<?= $content ?>
  		</div>
		<footer class="footer text-center">			         
                    <div style="background: #F3F3F5; padding: 15px 0;">

                    <div>			<p>� 2014 BrandTap</p>
		</div>
                </div>  



                <div class="clear"></div>

            </div>

          

	</div>
		</footer>
	</div>
</body>

</html>
