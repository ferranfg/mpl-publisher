<div class="wrap mpl">

	<h2 id="mpl-logo"><img src="<?php echo MPL_BASEURL; ?>assets/imgs/mpl-logo-30x30.png"> MPL - Publisher</h2>

	<ul class="nav-tab-wrapper nav-tabs hidden-xs">
		<li class="nav-tab active"><a href="#book-details" data-toggle="tab"><?php _e("General details", "publisher"); ?></a></li>
		<li class="nav-tab"><a href="#book-settings" data-toggle="tab"><?php _e("Settings", "publisher"); ?></a></li>
		<li class="nav-tab"><a href="#book-links" data-toggle="tab"><?php _e("Links", "publisher"); ?></a></li>
		<li class="nav-tab"><a href="#book-appearance" data-toggle="tab"><?php _e("Appearance", "publisher"); ?></a></li>
		<?php if (isset($message)): ?>
			<li><em><?php echo $message; ?></em></li>
		<?php endif; ?>
	</ul>

	<select class="nav-tabs nav-tab-select visible-xs">
		<option value="0"><?php _e("General details", "publisher"); ?></option>
		<option value="1"><?php _e("Settings", "publisher"); ?></option>
		<option value="2"><?php _e("Links", "publisher"); ?></option>
		<option value="3"><?php _e("Appearance", "publisher"); ?></option>
	</select>

	<form id="col-container" action="<?php echo $form_action; ?>" method="POST" enctype="multipart/form-data">

		<input type="hidden" name="action" value="publish_ebook">

		<?php echo $wp_nonce_field; ?>

		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap tab-content">
					<div class="tab-pane clearfix active" id="book-details">
						<h3><?php _e("Book details", "publisher"); ?></h3>
						<p><?php _e("Enter your book details, including title, description, and authors. We encourage you to complete as many fields as possible, as richer data could help readers discover your books.", "publisher"); ?></p>

						<div class="form-field">
							<label for="book-identifier">
								<?php _e("Identifier (ISBN)", "publisher"); ?>
								<span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("If your book doesn't have an ISBN, use a unique identifier", "publisher"); ?>"></span>
							</label>
							<input name="identifier" id="book-identifier" type="text" value="<?php echo $identifier; ?>" placeholder="ej: 9788494138805 E">
						</div>

						<div class="form-field">
							<label for="book-title"><?php _e("Book Title", "publisher"); ?></label>
							<input name="title" id="book-title" type="text" value="<?php echo $title; ?>" placeholder="<?php _e('Book Title'); ?>">
						</div>

						<div class="form-field">
							<label for="book-description"><?php _e("Book Description", "publisher"); ?></label>
							<textarea name="description" id="book-description" rows="8"><?php echo $description; ?></textarea>
						</div>

						<div class="form-field">
							<label for="book-authors">
								<?php _e("Book authors", "publisher"); ?>
								<span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("Separate multiple authors with commas", "publisher"); ?>"></span>
							</label>
							<input name="authors" id="book-authors" type="text" value="<?php echo $authors; ?>" placeholder="<?php _e('Book authors'); ?>">
						</div>
					</div>

					<div class="tab-pane clearfix" id="book-settings">
						<h3><?php _e("Metadata settings", "publisher"); ?></h3>
						<p><?php _e("The more metadata you provide, the easier it will be for readers to discover your book.", "publisher"); ?></p>

						<div class="form-field">
							<label for="book-language">
								<?php _e("Language", "publisher"); ?>
								<span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php echo _e("Use the RFC3066 Language codes, such as en, es, fr…", "publisher"); ?>"></span>
							</label>
							<input name="language" id="book-language" type="text" value="<?php echo $language; ?>" placeholder="<?php _e('Language', 'publisher'); ?>">
						</div>

						<div class="form-field">
							<label for="book-date">
								<?php _e("Publication date", "publisher"); ?>
								<span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php echo _e("This information won't affect the book's availability", "publisher"); ?>"></span>
							</label>
							<input name="date" id="book-date" type="text" value="<?php echo $date; ?>" placeholder="<?php echo _e('YYYY-MM-DD', 'publisher'); ?>" style="width:95%">
						</div>

						<div class="form-field">
							<label><?php _e("Cover image", "publisher"); ?></label>
							<?php if ($coverSrc):  ?>
								<img src="<?php echo $coverSrc; ?>" id="book-cover-placeholder" width="115" height="184" alt="<?php _e("Cover image", "publisher"); ?>" />
							<?php else: ?>
								<img src="https://placehold.it/115x184&amp;text=625x1000" id="book-cover-placeholder" width="115" height="184" alt="<?php _e("Cover image", "publisher"); ?>" />
							<?php endif; ?>
							<input type="hidden" name="cover" id="book-cover" value="<?php echo $cover; ?>">
							<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?php _e('Upload Image', 'publisher'); ?>">
							<p><?php _e("Recommended size is 625x1000", "publisher"); ?> <a href="https://kdp.amazon.com/help?topicId=A2J0TRG6OPX0VM" target="_blank">[?]</a></p>
						</div>

						<div class="form-field">
							<label for="book-editor"><?php _e("Publisher Name", "publisher"); ?></label>
							<input name="editor" id="book-editor" type="text" value="<?php echo $editor; ?>" placeholder="<?php _e('Publisher Name', 'publisher'); ?>">
						</div>

						<div class="form-field">
							<label for="book-copyright">
								<?php _e("Copyright Information", "publisher"); ?>
								<span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php echo _e("Copyright information includes a statement about various property rights associated with the resource, including intellectual property rights", "publisher"); ?>"></span>
							</label>
							<textarea rows="3" name="copyright" id="book-copyright" placeholder="<?php _e('Copyright Information', 'publisher'); ?>"><?php echo $copyright; ?></textarea>
						</div>
					</div>

					<div class="tab-pane clearfix" id="book-links">
						<h3><?php _e("External links", "publisher"); ?></h3>
						<p><?php _e("Links will appear on your MPL-Download eBook widget extending your book's informations.", "publisher"); ?> <a href="<?php echo admin_url('widgets.php'); ?>" target="_blank"><?php _e("Edit Widgets", "publisher"); ?></a>.</p>

						<div class="form-field">
							<label for="book-landing"><?php _e("Landing Page URL", "publisher"); ?></label>
							<input name="landingUrl" id="book-landing" type="text" value="<?php echo $landingUrl; ?>" placeholder="<?php _e('Landing Page URL', 'publisher'); ?>">
						</div>

						<div class="form-field">
							<label for="book-amazon"><?php _e("Amazon URL", "publisher"); ?></label>
							<input name="amazonUrl" id="book-amazon" type="text" value="<?php echo $amazonUrl; ?>" placeholder="<?php _e('Amazon URL', 'publisher'); ?>">
						</div>

						<div class="form-field">
							<label for="book-ibooks"><?php _e("iBooks URL", "publisher"); ?></label>
							<input name="ibooksUrl" id="book-ibooks" type="text" value="<?php echo $ibooksUrl; ?>" placeholder="<?php _e('iBooks URL', 'publisher'); ?>">
						</div>

						<div class="form-field" id="affiliate-form-field" style="display: <?php echo ($amazonUrl || $ibooksUrl) ? "block" : "none"; ?>">
							<label for="book-affiliate">
								<input type="checkbox" name="affiliate" id="book-affiliate" <?php echo $affiliate ? "checked='checked'" : ""; ?> />
								<?php _e("Remove affiliate tracking from external links", "publisher"); ?> <span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("Affiliate tracking won't affect your book prices and will help to track your sales from MPL-Publisher", "publisher"); ?>"></span>
							</label>
						</div>
					</div>

					<div class="tab-pane clearfix" id="book-appearance">
						<h3><?php _e("Themes", "publisher"); ?></h3>
						<div class="theme-browser">
							<div class="theme active">
								<div class="theme-screenshot">
									<img src="<?php echo MPL_BASEURL . 'assets/imgs/default.png'; ?>" />
								</div>
								<h3 class="theme-name"><?php _e("Default", "publisher"); ?></h3>
							</div>
						</div>
					</div>

					<hr />

					<div class="form-field">
						<label for="format"><?php _e("Output format", "publisher"); ?></label>
						<select name="format" id="format">
							<option value="epub2" <?php echo $format == "epub2" ? "selected='selected'" : ''; ?>>EPUB 2.0</option>
							<option value="epub3" <?php echo $format == "epub3" ? "selected='selected'" : ''; ?>>EPUB 3.0</option>
							<option value="kndle" <?php echo $format == "kndle" ? "selected='selected'" : ''; ?>>Kindle/Mobi</option>
						</select>
						<p><?php _e("Currently, only EPUB2.0 and EPUB3.0 are available. Future versions will include PDF as output format.", "publisher"); ?></p>
					</div>

					<p class="submit hidden-xs">
						<input type="submit" name="generate" class="button button-primary" value="<?php _e('Publish', "publisher"); ?>">
						<input type="submit" name="save" class="button" value="<?php _e('Save Information', "publisher"); ?>">
					</p>
				</div>
			</div>
		</div>

		<div id="col-right">
			<div class="col-wrap">

				<h3><?php _e("Text", "publisher"); ?></h3>

				<div class="form-wrap">
					<div class="clearfix filter-bar">
						<select name="cat_selected[]" id="cat" class="chosen" multiple data-placeholder="<?php _e("All categories", "publisher"); ?>">
							<?php foreach ($blog_categories as $category): ?>
								<option value="<?php echo $category->cat_ID; ?>" <?php echo in_array($category->cat_ID, $cat_selected) ? "selected='selected'" : ""; ?>>
									<?php echo $category->name; ?>
								</option>
							<?php endforeach; ?>
						</select>
						<select name="author_selected[]" id="author" class="chosen" multiple data-placeholder="<?php _e("All authors", "publisher"); ?>">
							<?php foreach ($blog_authors as $author): ?>
								<option value="<?php echo $author->ID; ?>" <?php echo in_array($author->ID, $author_selected) ? "selected='selected'" : ""; ?>>
									<?php echo $author->data->display_name; ?>
								</option>
							<?php endforeach; ?>
						</select>
						<input type="submit" name="filter" id="post-query-submit" class="button" value="<?php _e('Filter'); ?>" />
					</div>
					<div class="clearfix filter-bar">
						<?php if (count($blog_tags)): ?>
							<select name="tag_selected[]" id="tag" class="chosen" multiple data-placeholder="<?php _e("All tags", "publisher"); ?>">
								<?php foreach ($blog_tags as $tag): ?>
									<option value="<?php echo $tag->slug; ?>" <?php echo in_array($tag->slug, $tag_selected) ? "selected='selected'" : ""; ?>>
										<?php echo $tag->name; ?>
									</option>
								<?php endforeach; ?>
							</select>
						<?php endif; ?>
						<select name="post_type[]" id="type" class="chosen" multiple data-placeholder="<?php _e("All types", "publisher"); ?>">
							<option value="post" <?php echo in_array('post', $post_type) ? "selected='selected'": ""; ?>>
								<?php _e("Post", "publisher"); ?>
							</option>
							<option value="mpl_chapter" <?php echo in_array('mpl_chapter', $post_type) ? "selected='selected'": ""; ?>>
								<?php _e("Book Chapter", "publisher"); ?>
							</option>
						</select>
					</div>
					<p><?php _e("Drag your filtered results to sort your book's chapters", "publisher"); ?></p>
					<table class="wp-list-table widefat fixed striped posts">
						<thead>
							<tr>
								<th class="manage-column column-cb check-column">
									<input id="cb-select-all-1" type="checkbox">
								</th>
								<th class="manage-column column-name"><?php _e("Contents", "publisher"); ?></th>
								<th class="text-right"><a href="<?php echo admin_url('post-new.php?post_type=mpl_chapter'); ?>" class="button"><?php echo _e("Add New Book Chapter", "publisher"); ?></a></th>
							</tr>
						</thead>
						<tbody id="chapter-list">
							<?php if ($query->have_posts()): ?>
								<?php while ($query->have_posts()): $query->the_post(); ?>
									<tr style="cursor: move">
										<th scope="row" class="check-column">
											<input type="checkbox" name="selected_posts[]" value="<?php the_ID(); ?>" id="cb-select-<?php the_ID(); ?>" <?php echo ($selected_posts and in_array(get_the_ID(), $selected_posts)) ? 'checked="checked"' : ''; ?> >
										</th>
										<td class="name column-name">
											<?php if (get_post_type() === "mpl_chapter"): ?>
												<span class="dashicons dashicons-book" data-toggle="tooltip" title="<?php _e('Chapter', 'publisher'); ?>"></span>
											<?php else: ?>
												<span class="dashicons dashicons-admin-post" data-toggle="tooltip" title="<?php _e('Post', 'publisher'); ?>"></span>
											<?php endif; ?>
											<strong>
												<a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a>
												<?php if (get_post_status() === "private"): ?> - <?php echo _e("Private", "publisher"); ?><?php endif; ?>
											</strong>
										</td>
										<td class="text-right">
											<a href="<?php echo get_edit_post_link(); ?>"><?php _e("Edit", "publisher"); ?></a>
										</td>
									</tr>
								<?php endwhile; ?>
							<?php else: ?>
								<tr>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
								</tr>
							<?php endif; ?>
						</tbody>
						<tfoot>
							<tr>
								<th class="manage-column column-cb check-column">
									<input id="cb-select-all-2" type="checkbox">
								</th>
								<th class="manage-column column-name"><?php _e("Contents", "publisher"); ?></th>
								<th class="text-right"><a href="<?php echo admin_url('post-new.php?post_type=mpl_chapter'); ?>" class="button"><?php echo _e("Add New Book Chapter", "publisher"); ?></a></th>
							</tr>
						</tfoot>
					</table>

					<p class="submit visible-xs">
						<input type="submit" name="generate" class="button button-primary" value="<?php _e('Publish', "publisher"); ?>">
						<input type="submit" name="save" class="button" value="<?php _e('Save Information', "publisher"); ?>">
					</p>
				</div>
			</div>
		</div>
	</form>
</div>

<script src="//load.sumome.com/" data-sumo-site-id="98842527d5789072b38573b7bbe0b78a2ba328e085ba2b695dedf5204ea0a29c" async="async"></script>