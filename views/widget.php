<?php echo $args['before_widget']; ?>

<?php if (!empty($instance['title'])): ?>
	<?php echo $args['before_title'] . apply_filters('widget_title', $instance['title']). $args['after_title']; ?>
<?php endif; ?>

<?php include MPL_BASEPATH . '/views/alert.php'; ?>

<section itemscope="" itemtype="http://schema.org/Book">
	<?php if ($cover_src): ?>
		<?php if ($landing_url): ?>
			<a href="<?php echo esc_url($landing_url); ?>" title="<?php echo esc_attr($title); ?>">
				<img class="book-cover" src="<?php echo esc_url($cover_src); ?>" width="95" height="152" alt="<?php echo esc_attr($title); ?>" itemprop="image" />
			</a>
		<?php else: ?>
			<img class="book-cover" src="<?php echo esc_url($cover_src); ?>" width="95" height="152" alt="<?php echo esc_attr($title); ?>" itemprop="image" />
		<?php endif; ?>
	<?php endif; ?>
	<h5 itemprop="name">
		<?php if ($landing_url): ?>
			<a href="<?php echo esc_url($landing_url); ?>" title="<?php echo esc_attr($title); ?>"><?php echo esc_html($title); ?></a>
		<?php else: ?>
			<?php echo esc_html($title); ?>
		<?php endif; ?>
	</h5>
	<?php if ($description): ?>
		<p itemprop="description"><?php echo esc_html($description); ?></p>
	<?php endif; ?>
	<?php if ($authors): ?>
		<p itemprop="author" itemscope itemtype="http://schema.org/Person"><?php _e("By", "publisher"); ?> <span itemprop="name"><?php echo esc_html($authors); ?></span></p>
	<?php endif; ?>
	<?php if (isset($instance['download']) and $instance['download']): ?>
		<form method="post" action="">
			<?php echo $wp_nonce_field; ?>
			<input type="hidden" name="book_id" value="<?php echo esc_attr($book_id); ?>" />
			<button type="submit" name="download_ebook" class="btn btn-default"><?php _e("Get your eBook!", "publisher"); ?></button>
		</form>
	<?php endif; ?>
	<?php if (isset($instance['external']) and $instance['external'] and ($ibooks_url || $amazon_url)): ?>
		<p class="book-links">
			<?php if ($amazon_url): ?>
				<a href="<?php echo esc_url($amazon_url); ?>">
					<img src="<?php echo esc_url(MPL_BASEURL . "assets/imgs/amazon-apps-store-us-black.png"); ?>" />
				</a>
			<?php endif; ?>
			<?php if ($ibooks_url): ?>
				<a href="<?php echo esc_url($ibooks_url); ?>">
					<img src="<?php echo esc_url(MPL_BASEURL . "assets/imgs/iBooks_icon_large.png"); ?>" />
				</a>
			<?php endif; ?>
		</p>
	<?php endif; ?>
</section>

<?php echo $args['after_widget']; ?>