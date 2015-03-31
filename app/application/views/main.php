<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
    <head>
        <title><?= $document_title ?></title>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
        <!-- Bootstrap CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">   
        <!-------------set style from http://brandtap.co  --------------->
        <link href="http://brandtap.co/wp-content/themes/twentyten_base/style.css" media="all" type="text/css" rel="stylesheet">
        <link href="http://brandtap.co/wp-content/themes/twentyten_base/css/common.css" rel="stylesheet">
        <!---------------------------->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css?v=<?= date('YmdHis');?>" type="text/css" media="screen"/>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <!----------Ckeditor-------------->
        <script type="text/javascript" src="<?= base_url() ?>third_party/ckeditor/ckeditor.js"></script>
    </head>
    <body style="margin: 0 0 60px;">
        <div class="container">
            <div class="header">
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?= base_url() ?>">
                            <img style="float: left; margin-top: 22px; height: 57px;" src="<?= base_url() ?>/img/logo.png" />
                        </a>
                    </div>
                    <div class="col-md-4">
                        <!--<img src="<?= base_url() ?>/img/slogan.png" />-->
<!--                        <br>                        <br>                   
                        <h2>The new word of mouth</h2>-->
                    </div>
                    <div class="col-md-4">                        
                        <?php if (isset($loged_name)) : ?> 
                            <h4>    
                                <div class="dropdown">
                                    <button class="btn  dropdown-toggle j-background-none j-full-width text-right" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">                                
                                       Welcome: @<?= $loged_name ?>
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu j-full-width" role="menu" aria-labelledby="dropdownMenu1">
                                    	<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= site_url('user/profile') ?>">Profile</a></li>
                                    	<? if($brand_id != 0): ?>
                                        	<li role="presentation"><a role="menuitem" tabindex="-1" href="<?= site_url('user/preferences/' . $brand_id) ?>">Preferences</a></li>
                                        <? endif ?>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= site_url('reports') ?>">Create report</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= site_url('user/logout') ?>">Logout</a></li>     
                                    </ul>
                                </div>
                            </h4>
                        <?php endif ?> 
                        <?php // if (isset($loged_name)) : ?>
<!--<p>Welcome @<?= $loged_name ?> | <a href="<?= site_url('user/logout') ?>">Logout</a></p>-->
                        <?php // endif ?>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <p>&nbsp;</p>
                <?= $content ?>
            </div>
       	</div>
    <footer class="footer text-center" style="position: absolute; bottom: 0px; background: inherit; width: 100%">			         
    	<div style="padding: 15px 0;">
        	<p>Brand Tap 2015</p>
       	</div>  
       	<div class="clear"></div>
        <!--</div>-->
    </footer>
</div>
</body>

</html>
