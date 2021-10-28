<p>
	<label for="<?php echo esc_attr($titleId); ?>">
		<?php _e("Title", "publisher"); ?>:
		<input class="widefat" id="<?php echo esc_attr($titleId); ?>" name="<?php echo esc_attr($titleName); ?>" type="text" value="<?php echo esc_attr((isset($instance['title'])) ? $instance['title'] : ''); ?>">
	</label>
</p>
<p>
	<input class="checkbox" type="checkbox" id="<?php echo esc_attr($externalId); ?>" name="<?php echo esc_attr($externalName); ?>" <?php echo (isset($instance['external']) and $instance['external']) ? "checked=checked" : ''; ?>  />
	<label for="<?php echo esc_attr($externalId); ?>"><?php _e("Include external links", "publisher"); ?>. <a href="<?php echo admin_url('tools.php?page=publisher'); ?>" target="_blank"><?php _e("Edit links", "publisher"); ?></a></label>
	<br>
	<input class="checkbox" type="checkbox" id="<?php echo esc_attr($downloadId); ?>" name="<?php echo esc_attr($downloadName); ?>" <?php echo (isset($instance['download']) and $instance['download']) ? "checked=checked" : ''; ?> />
	<label for="<?php echo esc_attr($downloadId); ?>"><?php _e("Include download button", "publisher"); ?></label>
</p>