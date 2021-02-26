<?php echo $args['before_widget']; ?>

<?php if (!empty($instance['title'])): ?>
	<?php echo $args['before_title'] . apply_filters('widget_title', $instance['title']). $args['after_title']; ?>
<?php endif; ?>

<section itemscope="" itemtype="http://schema.org/Book">
	<?php if ($coverSrc): ?>
		<?php if ($landingUrl): ?>
			<a href="<?php echo $landingUrl; ?>" title="<?php echo $title; ?>">
				<img class="book-cover" src="<?php echo $coverSrc; ?>" width="95" height="152" alt="<?php echo $title; ?>" itemprop="image" />
			</a>
		<?php else: ?>
			<img class="book-cover" src="<?php echo $coverSrc; ?>" width="95" height="152" alt="<?php echo $title; ?>" itemprop="image" />
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
		<p itemprop="author" itemscope itemtype="http://schema.org/Person"><?php _e("By", "publisher"); ?> <span itemprop="name"><?php echo $authors; ?></span></p>
	<?php endif; ?>
	<?php if (isset($instance['download']) and $instance['download']): ?>
		<form method="post" action="">
			<?php echo $wp_nonce_field; ?>
			<button type="submit" name="download_ebook" class="btn btn-default"><?php _e("Get your eBook!", "publisher"); ?></button>
		</form>
	<?php endif; ?>
	<?php if (isset($instance['external']) and $instance['external'] and ($ibooksUrl || $amazonUrl)): ?>
		<p class="book-links">
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

<?php echo $args['after_widget']; ?>