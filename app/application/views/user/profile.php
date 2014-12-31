<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-3 text-center">
		<h4>Dashbord</h4>
	</div>
	<div class="coll-md-6 text-left">
		<h4>
			<a href="http://www.btandtap.co">Back to the BrandTap.co site</a>
		</h4>
	</div>
</div>
<div class="row">
	<div class="col-md-4 text-left">
		<h1>Dashboard</h1>
		
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

			<div class="row">
				<div class="col-md-4 text-left">
					<p style="margin:10px 0;">(List of your posts that use #BrandTap referral program)</p>
				</div>
			</div>
			<? if( count($media)): ?>
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
					<div class="col-md-2 text-left">
						<h2>Stats</h2>
					</div>
				</div>
				<?php foreach($media as $row) : ?>
					<div class="row">
						<div class="col-md-4 text-left">
							<div class="pull-left">
								<img src="<?= $row['image'] ?>" />
								<p><?= $row['date'] ?></p>
							</div>
							<div class="pull-left">
								<p><?= $row['caption'] ?></p>
								<p><a href="<?= $row['link'] ?>">View on Instagram</a></p>
							</div>
						</div>
						<div class="col-md-3 text-left">
							<?php foreach($row['winners'] as $user) : ?>
								<p><?= $user ?></p>
							<?php endforeach ?>
						</div>
						<div class="col-md-3 text-left">
							<?php foreach($row['hashtags'] as $tag) : ?>
								<p><?= $tag ?></p>
							<?php endforeach ?>
						</div>
						<div class="col-md-2 text-left">
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
				<?php foreach($media_user as $row) : ?>
					<div class="row">
						<div class="col-md-4 text-left">
							<div class="pull-left">
								<img src="<?= $row['image'] ?>" />
								<p><?= $row['date'] ?></p>
							</div>
							<div class="pull-left">
								<p><?= $row['caption'] ?></p>
								<p><a href="<?= $row['link'] ?>">View on Instagram</a></p>
							</div>
						</div>
						<div class="col-md-3 text-left">
							<p><?= $row['winners'] ?></p>
						</div>
						<div class="col-md-3 text-left">
							<?php foreach($row['hashtags'] as $tag) : ?>
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
