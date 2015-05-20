<div class="wrap">

	<h2><?php _e("Publisher", "publisher"); ?></h2>

	<hr />

	<form id="col-container" action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">

		<input type="hidden" name="action" value="publish_ebook">

		<?php echo $wp_nonce_field; ?>

		<div id="col-right">
			<div class="col-wrap">

				<h3><?php _e("Contents", "publisher"); ?></h3>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th class="manage-column column-cb check-column">
								<input id="cb-select-all-1" type="checkbox" checked="checked">
							</th>
							<th class="manage-column column-name"><?php _e("Chapter", "publisher"); ?></th>
						</tr>
					</thead>
					<tbody id="chapter-list">
						<?php foreach ($posts as $post): ?>
							<tr style="cursor: move">
								<th scope="row" class="check-column">
									<input type="checkbox" name="selected_posts[]" value="<?php echo $post->ID; ?>" id="cb-select-<?php echo $post->ID; ?>"  checked="checked">
								</th>
								<td class="name column-name">
									<strong><a href="<?php echo $post->guid; ?>" target="_blank"><?php echo $post->post_title; ?></a></strong>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th class="manage-column column-cb check-column">
								<input id="cb-select-all-2" type="checkbox" checked="checked">
							</th>
							<th class="manage-column column-name"><?php _e("Chapter", "publisher"); ?></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap">
					<h3><?php _e("General details", "publisher"); ?></h3>

					<div class="form-field">
						<label for="book-identifier"><?php _e("Identifier (ISBN)", "publisher"); ?></label>
						<input name="identifier" id="book-identifier" type="text" value="" placeholder="ej: 9788494138805 E">
						<p><?php _e("If your book doesn't have an ISBN, use a unique identifier", "publisher"); ?></p>
					</div>

					<div class="form-field">
						<label for="book-title"><?php _e("Book Title", "publisher"); ?></label>
						<input name="title" id="book-title" type="text" value="<?php echo $site_name; ?>" placeholder="<?php _e('Book Title'); ?>">
					</div>					

					<div class="form-field">
						<label for="book-authors"><?php _e("Book authors", "publisher"); ?></label>
						<input name="authors" id="book-authors" type="text" value="<?php echo $current_user->display_name; ?>" placeholder="<?php _e('Book authors'); ?>">
						<p><?php _e("Separate multiple authors with commas", "publisher"); ?></p>
					</div>

					<div class="form-field">
						<label for="book-editor"><?php _e("Publisher Name (Optional)", "publisher"); ?></label>
						<input name="editor" id="book-editor" type="text" value="" placeholder="<?php _e('Publisher Name (Optional)', 'publisher'); ?>">
					</div>

					<div class="form-field">
						<label><?php _e("Cover image (Optional)", "publisher"); ?></label>
						<img src="https://placehold.it/115x184&amp;text=625x1000" id="book-cover-placeholder" width="115" height="184" alt="<?php _e("Cover image (Optional)", "publisher"); ?>" />
						<input type="hidden" name="cover" id="book-cover" value="">
						<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?php _e('Upload Image', 'publisher'); ?>">
						<p><?php _e("Recommended size is 625x1000", "publisher"); ?> <a href="https://kdp.amazon.com/help?topicId=A2J0TRG6OPX0VM" target="_blank">[+ info]</a></p>
					</div>

					<div class="form-field">
						<label for="format"><?php _e("Output format", "publisher"); ?></label>
						<select name="format" id="format">
							<option value="epub2">EPUB 2.0</option>
							<option value="epub3">EPUB 3.0</option>
						</select>
						<p><?php _e("Currently, only EPUB2.0 and EPUB3.0 are available. Future versions will include Mobi, PDF, etc.", "publisher"); ?></p>
					</div>

					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Publish'); ?>">
					</p>
				</div>
			</div>
		</div>
	</form>
</div>