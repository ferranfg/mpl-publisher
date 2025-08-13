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
		<?php if ($mpl_is_premium): ?>
			<div class="mpl-lead-magnet-notice">
				<p><small><?php _e('📧 Grow your reader list! Collect emails from your book downloads.', 'publisher'); ?></small></p>
			</div>
		<?php endif; ?>
		<form method="post" action="">
			<?php echo $wp_nonce_field; ?>
			<input type="hidden" name="book_id" value="<?php echo esc_attr($book_id); ?>" />
			
			<?php if ($mpl_is_premium): ?>
				<div class="mpl-email-field">
					<label for="reader_email_<?php echo esc_attr($book_id); ?>" class="mpl-email-label">
						<?php _e('Email Address', 'publisher'); ?> <span class="required">*</span>
					</label>
					<input 
						type="email" 
						id="reader_email_<?php echo esc_attr($book_id); ?>" 
						name="reader_email" 
						required 
						placeholder="<?php esc_attr_e('Enter your email to download', 'publisher'); ?>"
						class="mpl-email-input"
					/>
				</div>
			<?php else: ?>
				<div class="mpl-email-field mpl-email-disabled">
					<label for="reader_email_disabled_<?php echo esc_attr($book_id); ?>" class="mpl-email-label">
						<?php _e('Email Address', 'publisher'); ?> <span class="mpl-premium-badge">Premium</span>
					</label>
					<input 
						type="email" 
						id="reader_email_disabled_<?php echo esc_attr($book_id); ?>" 
						disabled 
						placeholder="<?php esc_attr_e('Upgrade to Premium to collect reader emails', 'publisher'); ?>"
						class="mpl-email-input mpl-disabled"
					/>
					<p class="mpl-upgrade-notice">
						<small>
							<a href="https://wordpress.mpl-publisher.com?utm_medium=plugin&utm_campaign=email_optinwidget" target="_blank">
								<?php _e('Upgrade to Premium', 'publisher'); ?>
							</a> <?php _e('to enable email collection and grow your reader list.', 'publisher'); ?>
						</small>
					</p>
				</div>
			<?php endif; ?>
			
			<button type="submit" name="download_ebook" class="btn btn-default mpl-download-btn"><?php _e("Get your eBook!", "publisher"); ?></button>
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