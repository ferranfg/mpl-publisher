<aside class="widget mpl-publisher">
	<h2 class="widget-title">My new book!</h2>
	<section itemscope="" itemtype="http://schema.org/Book">
		<?php if ($coverSrc): ?>
			<?php if ($landingUrl): ?>
				<a href="<?php echo $landingUrl; ?>" title="<?php echo $title; ?>">
					<img src="<?php echo $coverSrc; ?>" width="95" height="152" alt="<?php echo $title; ?>" itemprop="image" />
				</a>
			<?php else: ?>
				<img src="<?php echo $coverSrc; ?>" width="95" height="152" alt="<?php echo $title; ?>" itemprop="image" />
			<?php endif; ?>
		<?php endif; ?>
		<h5 itemprop="name">
			<?php if ($landingUrl): ?>
				<a href="<?php echo $landingUrl; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			<?php else: ?>
				<?php echo $title; ?>
			<?php endif; ?>
		</h5>
		<p itemprop="description"><?php echo $description; ?></p>
		<?php if ($authors): ?>
			<p itemprop="author" itemscope itemtype="http://schema.org/Person">By <span itemprop="name"><?php echo $authors; ?></span></p>
		<?php endif; ?>
		<?php if (false): ?>
			<p><a href="" class="btn btn-default"><?php _e("Get your eBook!", "publisher"); ?></a></p>
		<?php endif; ?>
		<?php if (true and ($ibooksUrl || $amazonUrl)): ?>
			<p>
				<?php if ($amazonUrl): ?>
					<a href="<?php echo $amazonUrl; ?>">
						<img src="<?php echo MPL_BASEURL; ?>assets/imgs/amazon-apps-store-us-black.png" />
					</a>
				<?php endif; ?>
				<?php if ($ibooksUrl): ?>
					<a href="<?php echo $ibooksUrl; ?>">
						<img src="<?php echo MPL_BASEURL; ?>assets/imgs/iBooks_icon_large.png" />
					</a>
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</section>
</aside>