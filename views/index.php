<div class="wrap">

	<h2><?php _e("Publisher", "publisher"); ?></h2>

	<hr />

	<ul class="nav-tab-wrapper nav-tabs">
		<li class="nav-tab active"><a href="#book-details" data-toggle="tab"><?php _e("General details", "publisher"); ?></a></li>
		<li class="nav-tab"><a href="#book-settings" data-toggle="tab"><?php _e("Settings", "publisher"); ?></a></li>
	</ul>

	<form id="col-container" action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">

		<input type="hidden" name="action" value="publish_ebook">

		<?php echo $wp_nonce_field; ?>

		<div id="col-right">
			<div class="col-wrap">

				<h3><?php _e("Contents", "publisher"); ?></h3>

				<div class="form-wrap">
					<div class="clearfix filter-bar">
						<select name="cat[]" id="cat" class="chosen" multiple data-placeholder="<?php _e("All categories", "publisher"); ?>">
							<?php foreach ($categories as $category): ?>
								<option value="<?php echo $category->cat_ID; ?>" <?php echo ($categories_selected and in_array($category->cat_ID, $categories_selected)) ? "selected='selected'" : ""; ?>>
									<?php echo $category->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
						<select name="author[]" id="author" class="chosen" multiple data-placeholder="<?php _e("All authors", "publisher"); ?>">
							<?php foreach ($authors as $author): ?>
								<option value="<?php echo $author->ID; ?>" <?php echo ($authors_selected and in_array($author->ID, $authors_selected)) ? "selected='selected'" : ""; ?>>
									<?php echo $author->data->display_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
						<input type="submit" name="filter" id="post-query-submit" class="button" value="<?php _e('Filter'); ?>" />
					</div>
					<?php if (count($tags)): ?>
						<div class="clearfix filter-bar">
							<select name="tag[]" id="tag" class="chosen" multiple data-placeholder="<?php _e("All tags", "publisher"); ?>">
								<?php foreach ($tags as $tag): ?>
									<option value="<?php echo $tag->slug; ?>" <?php echo ($tags_selected and in_array($tag->slug, $tags_selected)) ? "selected='selected'" : ""; ?>>
										<?php echo $tag->name; ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>
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
							<?php if ($query->have_posts()): ?>
								<?php while ($query->have_posts()): $query->the_post(); ?>
									<tr style="cursor: move">
										<th scope="row" class="check-column">
											<input type="checkbox" name="selected_posts[]" value="<?php the_ID(); ?>" id="cb-select-<?php the_ID(); ?>"  checked="checked">
										</th>
										<td class="name column-name">
											<strong><a href="<?php echo get_edit_post_link(); ?>" target="_blank"><?php the_title(); ?></a></strong>
										</td>
									</tr>
								<?php endwhile; ?>
							<?php else: ?>
								<tr>
									<th></th>
									<td></td>
								</tr>
							<?php endif; ?>
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
					<p><?php _e("Drag your filtered results to sort your book's chapters", "publisher"); ?></p>
				</div>
			</div>
		</div>

		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap tab-content">
					<div class="tab-pane active" id="book-details">
						<h3><?php _e("Book details", "publisher"); ?></h3>
						<p><?php _e("Enter your book details, including title, description, and authors. We encourage you to complete as many fields as possible, as richer data could help readers discover your books.", "publisher"); ?></p>

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
							<label for="book-descripcion"><?php _e("Book Description", "publisher"); ?></label>
							<textarea name="description" id="book-description" rows="6"><?php echo $site_description; ?></textarea>
						</div>

						<div class="form-field">
							<label for="book-authors"><?php _e("Book authors", "publisher"); ?></label>
							<input name="authors" id="book-authors" type="text" value="<?php echo $current_user->display_name; ?>" placeholder="<?php _e('Book authors'); ?>">
							<p><?php _e("Separate multiple authors with commas", "publisher"); ?></p>
						</div>
					</div>

					<div class="tab-pane" id="book-settings">
						<h3><?php _e("Metadata settings", "publisher"); ?></h3>
						<p><?php _e("The more metadata you provide, the easier it will be for readers to discover your book.", "publisher"); ?></p>

						<div class="form-field">
							<label for="book-language"><?php _e("Language (Optional)", "publisher"); ?></label>
							<input name="language" id="book-language" type="text" value="" placeholder="<?php _e('Language (Optional)', 'publisher'); ?>">
							<p><?php echo _e("Formatted as a three-letter ISO 639-2/B code", "publisher"); ?> <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes" target="_blank">[?]</a></p>
						</div>

						<div class="form-field">
							<label for="book-date"><?php _e("Publication date (Optional)", "publisher"); ?></label>
							<input name="date" id="book-date" type="date" value="" placeholder="<?php echo _e('YYYY-MM-DD', 'publisher'); ?>" style="width:95%">
							<p><?php echo _e("This information won't affect the book's availability", "publisher"); ?>
						</div>

						<div class="form-field">
							<label><?php _e("Cover image (Optional)", "publisher"); ?></label>
							<img src="https://placehold.it/115x184&amp;text=625x1000" id="book-cover-placeholder" width="115" height="184" alt="<?php _e("Cover image (Optional)", "publisher"); ?>" />
							<input type="hidden" name="cover" id="book-cover" value="">
							<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?php _e('Upload Image', 'publisher'); ?>">
							<p><?php _e("Recommended size is 625x1000", "publisher"); ?> <a href="https://kdp.amazon.com/help?topicId=A2J0TRG6OPX0VM" target="_blank">[?]</a></p>
						</div>						

						<div class="form-field">
							<label for="book-editor"><?php _e("Publisher Name (Optional)", "publisher"); ?></label>
							<input name="editor" id="book-editor" type="text" value="" placeholder="<?php _e('Publisher Name (Optional)', 'publisher'); ?>">
						</div>
					</div>

					<hr />

					<div class="form-field">
						<label for="format"><?php _e("Output format", "publisher"); ?></label>
						<select name="format" id="format">
							<option value="epub2">EPUB 2.0</option>
							<option value="epub3">EPUB 3.0</option>
						</select>
						<p><?php _e("Currently, only EPUB2.0 and EPUB3.0 are available. Future versions will include PDF as output format.", "publisher"); ?></p>
					</div>

					<p class="submit">
						<input type="submit" name="generate" id="submit" class="button button-primary" value="<?php _e('Publish'); ?>">
					</p>
				</div>
			</div>
		</div>
	</form>
</div>