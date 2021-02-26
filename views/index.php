<div class="wrap mpl" id="mpl-wrapper">

    <h1 id="mpl-logo">
        <img src="<?php echo MPL_BASEURL; ?>assets/imgs/mpl-logo-60x60.png" alt="MPL - Publisher" style="width:30px;height:30px"> MPL - Publisher <?php if ($mpl_is_premium): ?>Premium ⭐<?php endif; ?><span class="release-notes"></span>
    </h1>

    <?php if ($admin_notice): ?>
        <div class="notice notice-info is-dismissible">
            <p><?php echo $admin_notice; ?></p>
        </div>
    <?php endif; ?>

    <?php if ( ! $mpl_is_premium): ?>
        <hr />
        <p>📚 <?php _e("To get all the available formats and more cool features, download our", "publisher"); ?> <a href="https://mpl-publisher.ferranfigueredo.com?utm_source=plugin&utm_campaign=premium" target="_blank">MPL-Publisher Premium</a> ⭐</p>
        <hr />
    <?php endif; ?>

    <?php do_action('mpl_publisher_after_navbar'); ?>

    <ul class="nav-tab-wrapper nav-tabs hidden-xs">
        <?php do_action('mpl_publisher_after_tabs'); ?>
        <li class="nav-tab active"><a href="#book-details" data-toggle="tab">📖 <?php _e("General details", "publisher"); ?></a></li>
        <li class="nav-tab"><a href="#book-settings" data-toggle="tab">⚙️ <?php _e("Settings", "publisher"); ?></a></li>
        <li class="nav-tab"><a href="#book-links" data-toggle="tab">🔗 <?php _e("Links", "publisher"); ?></a></li>
        <li class="nav-tab"><a href="#book-appearance" data-toggle="tab">🎨 <?php _e("Appearance", "publisher"); ?></a></li>
        <?php if ( ! $mpl_is_premium): ?>
            <li class="nav-tab"><a href="#book-license" data-toggle="tab">⭐ <?php _e("Premium", "publisher"); ?></a></li>
        <?php endif; ?>
        <?php do_action('mpl_publisher_before_tabs'); ?>
    </ul>

    <select class="nav-tabs nav-tab-select visible-xs">
        <?php do_action('mpl_publisher_after_tabs_responsive'); ?>
        <option value="0"><?php _e("General details", "publisher"); ?></option>
        <option value="1"><?php _e("Settings", "publisher"); ?></option>
        <option value="2"><?php _e("Links", "publisher"); ?></option>
        <option value="3"><?php _e("Appearance", "publisher"); ?></option>
        <?php if ( ! $mpl_is_premium): ?>
            <option value="4"><?php _e("Premium", "publisher"); ?></option>
        <?php endif; ?>
        <?php do_action('mpl_publisher_before_tabs_responsive'); ?>
    </select>

    <?php do_action('mpl_publisher_before_navbar'); ?>

    <form id="col-container" action="<?php echo $form_action; ?>" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="action" value="publish_ebook">

        <?php echo $wp_nonce_field; ?>

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap tab-content">

                    <?php do_action('mpl_publisher_before_tabs_content'); ?>

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
                            <label for="book-amazon"><?php _e("Amazon URL", "publisher"); ?> <a href="https://kdp.amazon.com/help?topicId=A2GF0UFHIYG9VQ" target="_blank">[?]</a></label>
                            <input name="amazonUrl" id="book-amazon" type="text" value="<?php echo $amazonUrl; ?>" placeholder="<?php _e('Amazon URL', 'publisher'); ?>">
                        </div>

                        <div class="form-field">
                            <label for="book-ibooks"><?php _e("iBooks URL", "publisher"); ?> <a href="http://www.apple.com/itunes/working-itunes/sell-content/books/book-faq.html" target="_blank">[?]</a></label>
                            <input name="ibooksUrl" id="book-ibooks" type="text" value="<?php echo $ibooksUrl; ?>" placeholder="<?php _e('iBooks URL', 'publisher'); ?>">
                        </div>
                    </div>

                    <div class="tab-pane clearfix" id="book-appearance">
                        <h3><?php _e("Themes", "publisher"); ?></h3>

                        <div class="clearfix">
                            <div class="theme-browser">
                                <?php foreach ($book_themes as $id => $theme): ?>
                                    <div class="theme <?php echo $id == $theme_id ? 'active' : ''; ?>" data-toggle="book-theme" data-theme-id="<?php echo $id; ?>">
                                        <div class="theme-screenshot">
                                            <img src="<?php echo $theme['image']; ?>" />
                                        </div>
                                        <h2 class="theme-name"><?php _e($theme['name'], "publisher"); ?></h2>
                                    </div>
                                <?php endforeach; ?>
                                <input type="hidden" name="theme_id" value="<?php echo $theme_id; ?>">
                            </div>
                        </div>

                        <h3><?php _e("Custom Theme", "publisher"); ?></h3>
                        <p><?php _e("You can publish your book with your custom CSS, overriding the default file included with our themes.", "publisher"); ?>

                        <div class="form-field" id="template">
                            <textarea rows="6" name="custom_css" id="newcontent" placeholder="/* Paste your CSS here */"><?php echo $customCss; ?></textarea>
                        </div>
                    </div>

                    <div class="tab-pane clearfix" id="book-license">
                        <h3><?php _e("License key", "publisher"); ?></h3>
                        <p><?php _e("If you already bought our premium version, you received a license key on the confirmation email. Please, paste the value on the following field and click \"� Save\" to activate your premium version.", "publisher"); ?></p>

                        <div class="form-field">
                            <label for="book-license"><?php _e("License key", "publisher"); ?></label>
                            <input name="license" id="book-license" type="text" value="<?php echo $license; ?>" placeholder="<?php _e('License key', 'publisher'); ?>">
                        </div>
                    </div>

                    <?php do_action('mpl_publisher_after_tabs_content'); ?>

                    <hr class="mt-30 mb-20" />

                    <div class="form-field">
                        <label for="format"><?php _e("Output format", "publisher"); ?></label>
                        <select name="format" id="format">
                            <option value="epub2" <?php echo $format == "epub2" ? "selected='selected'" : ''; ?>>EPUB 2.0</option>
                            <option value="epub3" <?php echo $format == "epub3" ? "selected='selected'" : ''; ?>>EPUB 3.0</option>
                            <option value="mobi"  <?php echo $format == "mobi"  ? "selected='selected'" : ''; ?>>Amazon Mobi</option>
                            <option value="wdocx" <?php echo $format == "wdocx" ? "selected='selected'" : ''; ?>>Microsoft Word (docx)</option>
                            <option value="markd" <?php echo $format == "markd" ? "selected='selected'" : ''; ?>>Markdown</option>
                            <option value="print" <?php echo $format == "print" ? "selected='selected'" : ''; ?>>PDF File<?php if ( ! $mpl_is_premium) echo ' - Premium only'; ?></option>
                            <option value="audio" <?php echo $format == "audio" ? "selected='selected'" : ''; ?>>Audiobook (mp3)<?php if ( ! $mpl_is_premium) echo ' - Premium only'; ?></option>
                        </select>
                        <p><?php _e("Output result will be affected by the complexity of your content (ie. \"plain text\" works best). If you encounter any format error, please use the", "publisher"); ?> <a href="https://wordpress.org/support/plugin/mpl-publisher" target="_blank">MPL-Publisher Support Forum</a></p>
                    </div>

                    <p class="submit hidden-xs">
                        <button type="submit" name="generate" class="button button-primary">🖨️ <?php _e('Publish eBook', "publisher"); ?></button>
                        <button type="submit" name="save" class="button">💾 <?php _e('Save', "publisher"); ?></button>
                    </p>
                </div>
            </div>
        </div>

        <div id="col-right">
            <div class="col-wrap">

                <h3><?php _e("Text", "publisher"); ?></h3>

                <div class="form-wrap">
                    <div class="clearfix filter-bar">
                        <select name="post_type[]" id="type" class="chosen" multiple data-placeholder="<?php _e("All types", "publisher"); ?>">
                            <option value="post" <?php echo in_array('post', $post_type) ? "selected='selected'": ""; ?>>
                                <?php _e("Post", "publisher"); ?>
                            </option>
                            <option value="page" <?php echo in_array('page', $post_type) ? "selected='selected'": ""; ?>>
                                <?php _e("Page", "publisher"); ?>
                            </option>
                            <option value="mpl_chapter" <?php echo in_array('mpl_chapter', $post_type) ? "selected='selected'": ""; ?>>
                                <?php _e("Book Chapter", "publisher"); ?>
                            </option>
                        </select>
                        <select name="status_selected[]" id="status" class="chosen" multiple data-placeholder="<?php _e("All statuses", "publisher"); ?>">
                            <?php foreach ($blog_statuses as $status => $statusName): ?>
                                <option value="<?php echo $status; ?>" <?php echo in_array($status, $status_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo get_post_status_object($status)->label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="cat_selected[]" id="cat" class="chosen" multiple data-placeholder="<?php _e("All categories", "publisher"); ?>">
                            <?php foreach ($blog_categories as $category): ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php echo in_array($category->cat_ID, $cat_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo $category->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="month_selected[]" id="month" class="chosen" multiple data-placeholder="<?php _e("All months", "publisher"); ?>">
                            <?php foreach ($blog_months as $key => $month): ?>
                                <option value="<?php echo $key; ?>" <?php echo in_array($key, $month_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo $month; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="year_selected[]" id="year" class="chosen" multiple data-placeholder="<?php _e("All years", "publisher"); ?>">
                            <?php foreach ($blog_years as $year): ?>
                                <option value="<?php echo $year; ?>" <?php echo in_array($year, $year_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo $year; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php /*
                        <select name="author_selected[]" id="author" class="chosen" multiple data-placeholder="<?php _e("All authors", "publisher"); ?>">
                            <?php foreach ($blog_authors as $author): ?>
                                <option value="<?php echo $author->ID; ?>" <?php echo in_array($author->ID, $author_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo $author->data->display_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (count($blog_tags)): ?>
                            <select name="tag_selected[]" id="tag" class="chosen" multiple data-placeholder="<?php _e("All tags", "publisher"); ?>">
                                <?php foreach ($blog_tags as $tag): ?>
                                    <option value="<?php echo $tag->slug; ?>" <?php echo in_array($tag->slug, $tag_selected) ? "selected='selected'" : ""; ?>>
                                        <?php echo $tag->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                        */ ?>
                        <div class="chosen-container text-right">
                            <button type="submit" name="filter" id="post-query-submit" class="button">🔍 <?php _e('Filter content'); ?></button>
                        </div>
                    </div>
                    <p><?php _e("Drag your filtered results to sort your book's chapters", "publisher"); ?></p>
                    <table class="wp-list-table widefat striped posts">
                        <thead>
                            <tr>
                                <th class="manage-column column-cb check-column">
                                    <input id="cb-select-all-1" type="checkbox">
                                </th>
                                <th class="manage-column column-name"><?php _e("Contents", "publisher"); ?></span></th>
                                <th class="text-right"><a href="<?php echo admin_url('post-new.php?post_type=mpl_chapter'); ?>" class="button">📑 <?php echo _e("Add New Book Chapter", "publisher"); ?></a></th>
                            </tr>
                        </thead>
                        <tbody id="chapter-list">
                            <?php if ($query->found_posts > mpl_max_posts()): ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="alert alert-info">
                                            ℹ️ <?php _e("Your current search has too many results and it's not available yet. Please, use our filters to limit your request.", "publisher"); ?>
                                            <?php _e("Current results", "publisher"); ?>: <b><?php echo $query->found_posts; ?></b>.
                                            <?php _e("Max results", "publisher"); ?>: <b><?php echo mpl_max_posts(); ?></b>.
                                        </div>
                                    </th>
                                </tr>
                            <?php else: ?>
                                <?php if ($query->have_posts()): ?>
                                    <?php while ($query->have_posts()): $query->the_post(); ?>
                                        <tr style="cursor: move">
                                            <th scope="row" class="check-column">
                                                <input type="checkbox" name="selected_posts[]" value="<?php the_ID(); ?>" id="cb-select-<?php the_ID(); ?>" <?php echo ($selected_posts and in_array(get_the_ID(), $selected_posts)) ? 'checked="checked"' : ''; ?> >
                                            </th>
                                            <td class="name column-name">
                                                <?php if (get_post_type() == 'mpl_chapter'): ?>
                                                    <span class="dashicons dashicons-book" data-toggle="tooltip" title="<?php _e('Chapter', 'publisher'); ?>"></span>
                                                <?php elseif (get_post_type() == 'page'): ?>
                                                    <span class="dashicons dashicons-admin-page" data-toggle="tooltip" title="<?php _e('Page', 'publisher'); ?>"></span>
                                                <?php else: ?>
                                                    <span class="dashicons dashicons-admin-post" data-toggle="tooltip" title="<?php _e('Post', 'publisher'); ?>"></span>
                                                <?php endif; ?>
                                                <strong>
                                                    <a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a>
                                                    <?php if (get_post_status() != "publish"): ?> — <span class="post-state"><?php echo get_post_status_object(get_post_status())->label; ?></span><?php endif; ?>
                                                </strong>
                                            </td>
                                            <td class="text-right" style="display:table-cell">
                                                <a href="<?php echo get_edit_post_link(); ?>"><?php _e("Edit", "publisher"); ?></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">
                                            <div class="alert alert-warning">
                                                😞 <?php _e("Your search did not match any posts.", "publisher"); ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="manage-column column-cb check-column">
                                    <input id="cb-select-all-2" type="checkbox">
                                </th>
                                <th class="manage-column column-name"><?php _e("Contents", "publisher"); ?></th>
                                <th class="text-right"><a href="<?php echo admin_url('post-new.php?post_type=mpl_chapter'); ?>" class="button">📑 <?php echo _e("Add New Book Chapter", "publisher"); ?></a></th>
                            </tr>
                        </tfoot>
                    </table>

                    <p class="submit visible-xs">
                        <button type="submit" name="generate" class="button button-primary">🖨️ <?php _e('Publish eBook', "publisher"); ?></button>
                        <button type="submit" name="save" class="button">💾 <?php _e('Save', "publisher"); ?></button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>