
<div class="row j-title">
    <div class="col-md-12 text-left">
        <h1 class="pull-left">Dashboard</h1> 
        <p class="pull-left">(List of your posts that use #BrandTap referral program) </p>
        <h4 class="pull-right">
            <a href="http://www.btandtap.co">Back to the BrandTap.co site</a>
        </h4>
    </div>
</div>
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
                <div class="j-data">
                    <div class="row j-data-header">
                        <div class="col-md-4 text-left">
                            <h2>Post details</h2>
                        </div>
                        <div class="col-md-3 text-left">
                            <h2>Winners</h2>
                        </div>
                        <div class="col-md-3 text-left">
                            <h2>Hashtags</h2>
                        </div>
                        <div class="col-md-2 text-left">
                            <h2>Stats</h2>
                        </div>
                    </div>
                    <?php foreach ($media as $row) : ?>
                        <div class="row text-left j-row-data">                    
                            <div class="col-md-4">
                                <div class="pull-left">
                                    <img src="<?= $row['image'] ?>" />
                                    <p><?= $row['date'] ?></p>
                                </div>
                                <div class="pull-left">
                                    <p><?= $row['caption'] ?></p>
                                    <p><a href="<?= $row['link'] ?>">View on Instagram</a></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <?php foreach ($row['winners'] as $user) : ?>
                                    <p><?= $user ?></p>
                                <?php endforeach ?>
                            </div>
                            <div class="col-md-3">
                                <?php foreach ($row['hashtags'] as $tag) : ?>
                                    <p><?= $tag ?></p>
                                <?php endforeach ?>		
                            </div>
                            <div class="col-md-2">                           
                                <p><?= $row['likes'] ?></p>
                                <p><?= $row['comments'] ?></p>
                            </div>
                        </div>
                        <hr />
                    <?php endforeach ?>
                <? else: ?>
                    <div class="row">
                        <div class="col-md-4 text-left">
                            <p style="margin:10px 0; font-weight: bold;">You have no promotions yet!</p>
                        </div>
                    </div>
                <? endif ?>
            </div>
        </div>
        <div id="discounts" class="tab-pane" role="tabpanel">
            <div class="row">
                <div class="col-md-4 text-left">
                    <h2>Post details</h2>
                </div>
                <div class="col-md-3 text-left">
                    <h2>Winners</h2>
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
