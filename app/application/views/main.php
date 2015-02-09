<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
    <head>
        <title><?= $document_title ?></title>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <!-- Bootstrap CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">   
        <!-------------set style from http://brandtap.co  --------------->
        <link href="http://brandtap.co/wp-content/themes/twentyten_base/style.css" media="all" type="text/css" rel="stylesheet">
        <link href="http://brandtap.co/wp-content/themes/twentyten_base/css/common.css" rel="stylesheet">
        <!---------------------------->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/custom.css?v=<?= date('YmdHis'); ?>" type="text/css" media="screen"/>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <!----------Ckeditor-------------->
        <script type="text/javascript" src="<?= base_url() ?>third_party/ckeditor/ckeditor.js"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-59392965-1', 'auto');
            ga('send', 'pageview');

        </script>
        <!-- Facebook Conversion Code for BrandTap -->
        <script>(function() {
        var _fbq = window._fbq || (window._fbq = []);
        if (!_fbq.loaded) {
        var fbds = document.createElement('script');
        fbds.async = true;
        fbds.src = '//connect.facebook.net/en_US/fbds.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(fbds, s);
        _fbq.loaded = true;
        }
        })();
        window._fbq = window._fbq || [];
        window._fbq.push(['track', '6019057518469', {'value':'0.01','currency':'USD'}]);
        </script>
        <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6019057518469&amp;cd[value]=0.01&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
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
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Preferences</a></li>
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
    <script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
    <script type="text/javascript">
            twttr.conversion.trackPid('l5hcf');</script>
    <noscript>
    <img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=l5hcf&p_id=Twitter" />
    <img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=l5hcf&p_id=Twitter" /></noscript>
</body>

</html>
