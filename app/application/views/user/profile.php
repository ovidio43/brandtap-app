
<div class=" text-left">
    <h3><small>List of your posts that use #BrandTap referral program <a href="http://www.btandtap.co">Back to the BrandTap.co site</a></small></h3>
</div><br>
<? if (count($media)) { ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-left">
            <thead>
                <tr class="active">
                    <th><?= strtoupper('Post details'); ?> </th>
                    <th class="text-center"><?= strtoupper('Winners'); ?> </th>
                    <th class="text-center"><?= strtoupper('Hashtags'); ?> </th>
                    <th class="text-center"><?= strtoupper('Stats'); ?> </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($media as $row) { ?>
                    <tr>
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
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php }else { ?>
    <div class="row">
        <div class="col-md-4 text-left">
            <p style="margin:10px 0; font-weight: bold;">You have no promotions yet!</p>
        </div>
    </div>
<?php } ?>

