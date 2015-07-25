<p>
	<label for="<?php echo $titleId; ?>">
		<?php _e("Title", "publisher"); ?>:
		<input class="widefat" id="<?php echo $titleId; ?>" name="<?php echo $titleName; ?>" type="text" value="<?php echo (isset($instance['title'])) ? $instance['title'] : ''; ?>">
	</label>
</p>
<p>
	<input class="checkbox" type="checkbox" id="<?php echo $externalId; ?>" name="<?php echo $externalName; ?>" <?php echo (isset($instance['external']) and $instance['external']) ? "checked=checked" : ''; ?>  />
	<label for="<?php echo $externalId; ?>"><?php _e("Include external links", "publisher"); ?>. <a href="<?php echo admin_url('tools.php?page=publisher'); ?>" target="_blank"><?php _e("Edit links", "publisher"); ?></a></label>
	<br>
	<input class="checkbox" type="checkbox" id="<?php echo $downloadId; ?>" name="<?php echo $downloadName; ?>" <?php echo (isset($instance['download']) and $instance['download']) ? "checked=checked" : ''; ?> />
	<label for="<?php echo $downloadId; ?>"><?php _e("Include download button", "publisher"); ?></label>
</p>