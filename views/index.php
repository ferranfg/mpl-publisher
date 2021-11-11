<form class="wrap mpl" id="mpl-wrapper" action="<?php echo esc_url($form_action); ?>" method="POST" enctype="multipart/form-data"
    data-thickbox-url="<?php echo esc_url($marketplace_url); ?>&utm_campaign=publish"
    data-is-premium="<?php echo $mpl_is_premium ? 'true' : 'false'; ?>"
    data-alert-premium="<?php _e('This is a premium feature and it is not available on the free version.', 'publisher'); ?> <?php _e('Please, visit our homepage and get access to this and more features.', 'publisher'); ?>">

    <input type="hidden" name="action" value="publish_ebook">
    <input type="hidden" name="book_id" value="<?php echo esc_attr($book_id); ?>">
    <input type="hidden" name="order_asc" value="<?php echo esc_attr($order_asc); ?>" />

    <?php echo $wp_nonce_field; ?>

    <h1 id="mpl-logo" class="clearfix">
        <?php include MPL_BASEPATH . '/views/logo.php'; ?>
        <div id="book-selector" class="float-right">
            <select class="nav-book-select">
                <?php foreach ($all_books as $index => $book): ?>
                    <option value="<?php echo mpl_admin_url(['book_id' => $index]); ?>" <?php echo $book_id == $index ? "selected='selected'" : ''; ?>>
                        <?php echo esc_html($book['data']['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (count($all_books) > 1): ?>
                <button type="submit" name="clear" class="button">🧹 <?php _e('Remove Book', "publisher"); ?></button>
            <?php endif; ?>
            <?php if ($mpl_is_premium): ?>
                <button type="submit" name="create" class="button" title="<?php _e("Add New Book", "publisher"); ?>">📚 <span class="hidden-inline-xs"><?php _e("Add New Book", "publisher"); ?></span></button>
            <?php else: ?>
                <span disabled="disabled" class="button" data-toggle="tooltip" data-placement="bottom" title="<?php _e('Premium only', 'publisher'); ?>">📚 <?php _e("Add New Book", "publisher"); ?></span>
            <?php endif; ?>
            <button type="button" id="mpl-introjs" class="button" data-step="1" data-intro="<?php _e('Welcome to <b>MPL-Publisher</b>! Before you start, we will quickly guide you through the main features. Let\'s get started!', 'publisher'); ?>">❓</button>
        </div>
    </h1>

    <?php include MPL_BASEPATH . '/views/alert.php'; ?>

    <?php if ($admin_notice): ?>
        <div class="notice notice-info is-dismissible">
            <p><?php echo esc_html($admin_notice); ?></p>
        </div>
    <?php endif; ?>

    <div data-step="2" data-intro="<?php _e('This is the main navbar. You can navigate across the tabs to configure your ebook settings. You can edit your book details, adding a cover image, or changing your book design.', 'publisher'); ?>">
        <?php do_action('mpl_publisher_after_navbar'); ?>

        <ul class="nav-tab-wrapper nav-tabs hidden-xs">
            <?php do_action('mpl_publisher_after_tabs'); ?>
            <li class="nav-tab active"><a href="#book-details" data-toggle="tab">📖 <?php _e("Details", "publisher"); ?></a></li>
            <li class="nav-tab"><a href="#book-cover" data-toggle="tab">📔 <?php _e("Cover", "publisher"); ?></a></li>
            <li class="nav-tab"><a href="#book-appearance" data-toggle="tab">🎨 <?php _e("Design", "publisher"); ?></a></li>
            <li class="nav-tab"><a href="#book-settings" data-toggle="tab">⚙️ <?php _e("Meta", "publisher"); ?></a></li>
            <?php if (is_null(mpl_premium_token())): ?>
                <li class="nav-tab"><a href="#book-license" data-toggle="tab">⭐ <?php _e("Premium", "publisher"); ?></a></li>
            <?php endif; ?>
            <li class="nav-tab"><a href="<?php echo admin_url('admin.php?page=mpl-extensions'); ?>">🚀 <?php _e("Resources", "publisher"); ?></a></li>
            <?php do_action('mpl_publisher_before_tabs'); ?>
        </ul>

        <select class="nav-tabs nav-tab-select visible-xs">
            <?php do_action('mpl_publisher_after_tabs_responsive'); ?>
            <option value="0"><?php _e("Details", "publisher"); ?></option>
            <option value="1"><?php _e("Cover", "publisher"); ?></option>
            <option value="2"><?php _e("Design", "publisher"); ?></option>
            <option value="3"><?php _e("Meta", "publisher"); ?></option>
            <?php if (is_null(mpl_premium_token())): ?>
                <option value="4"><?php _e("Premium", "publisher"); ?></option>
            <?php endif; ?>
            <?php do_action('mpl_publisher_before_tabs_responsive'); ?>
        </select>

        <?php do_action('mpl_publisher_before_navbar'); ?>
    </div>

    <div id="col-container">
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
                            <input name="identifier" id="book-identifier" type="text" value="<?php echo esc_attr($identifier); ?>" placeholder="ej: 9788494138805 E">
                        </div>

                        <div class="form-field" data-step="3" data-intro="<?php _e('For example, you can change your book title in this field. The title is the first thing the reader sees or hears about your book. Getting it right is the single most important book marketing decision you\'ll make!', 'publisher'); ?>">
                            <label for="book-title"><?php _e("Book Title", "publisher"); ?></label>
                            <input name="title" id="book-title" type="text" value="<?php echo esc_attr($title); ?>" placeholder="<?php _e('Book Title'); ?>">
                        </div>

                        <div class="form-field">
                            <label for="book-description"><?php _e("Book Description", "publisher"); ?></label>
                            <textarea name="description" id="book-description" rows="8"><?php echo esc_textarea($description); ?></textarea>
                        </div>

                        <div class="form-field">
                            <label for="book-authors">
                                <?php _e("Book authors", "publisher"); ?>
                                <span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("Separate multiple authors with commas", "publisher"); ?>"></span>
                            </label>
                            <input name="authors" id="book-authors" type="text" value="<?php echo esc_attr($authors); ?>" placeholder="<?php _e('Book authors'); ?>">
                        </div>
                    </div>

                    <div class="tab-pane clearfix" id="book-cover">
                        <h3><?php _e("Cover image", "publisher"); ?></h3>
                        <p><?php _e("Strong cover design will catch a reader's eye, capture their interest and communicate what the book is about. These things inspire someone to buy your book.", "publisher"); ?></p>

                        <div class="form-field mb-20">
                            <label><?php _e("Cover image", "publisher"); ?></label>
                            <?php if ($cover_src): ?>
                                <img src="<?php echo esc_url($cover_src); ?>" id="book-cover-placeholder" width="115" height="184" alt="<?php _e("Cover image", "publisher"); ?>" />
                            <?php else: ?>
                                <img src="https://via.placeholder.com/115x184&text=625x1000" id="book-cover-placeholder" width="115" height="184" alt="<?php _e("Cover image", "publisher"); ?>" />
                            <?php endif; ?>
                            <input type="hidden" name="cover" id="book-cover-id" value="<?php echo esc_attr($cover); ?>">
                            <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?php _e('Select Image', 'publisher'); ?>">
                            <p><?php _e("Recommended size is 625x1000", "publisher"); ?> <a href="https://kdp.amazon.com/help?topicId=A2J0TRG6OPX0VM" target="_blank">[?]</a></p>
                        </div>
                        <a href="<?php echo admin_url('admin.php?page=mpl-cover'); ?>" class="button button-primary">🎨 <?php _e('Try our Cover Editor', 'publisher'); ?></a>
                    </div>

                    <div class="tab-pane clearfix" id="book-appearance">
                        <h3><?php _e("Themes", "publisher"); ?></h3>
                        <div class="clearfix">
                            <div class="theme-browser">
                                <?php foreach ($book_themes as $id => $theme): ?>
                                    <div class="theme <?php echo $id == $theme_id ? 'active' : ''; ?>" data-toggle="book-theme" data-theme-id="<?php echo esc_attr($id); ?>" data-theme-slug="<?php echo esc_attr($theme['id']); ?>">
                                        <div class="theme-screenshot">
                                            <img src="<?php echo esc_url($theme['image']); ?>" alt="<?php _e($theme['name'], "publisher"); ?>" />
                                        </div>
                                        <h2 class="theme-name"><?php _e($theme['name'], "publisher"); ?></h2>
                                    </div>
                                <?php endforeach; ?>
                                <input type="hidden" name="theme_id" value="<?php echo esc_attr($theme_id); ?>">
                            </div>
                        </div>

                        <h3><?php _e("Content images", "publisher"); ?></h3>
                        <p>
                            <?php _e("Configure how the plugin will manage your book images", "publisher"); ?>
                            <span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("Load images from original URL will result in lighter files but will require an internet connection from the device.", "publisher"); ?>"></span>
                        </p>
                        <div class="form-field">
                            <select name="images_load" style="width: 100%">
                                <option value="default" <?php echo $images_load == "default" ? "selected='selected'" : ''; ?>><?php _e("Load images from original URL", "publisher"); ?></option>
                                <option value="embed"   <?php echo $images_load == "embed"   ? "selected='selected'" : ''; ?>><?php _e("Embed images (Experimental)", "publisher"); ?></option>
                            </select>
                        </div>

                        <h3><?php _e("Custom Theme", "publisher"); ?></h3>
                        <p><?php _e("You can publish your book with your custom CSS, overriding the default file included with our themes.", "publisher"); ?>

                        <div class="form-field" id="template">
                            <textarea name="custom_css" id="newcontent" placeholder="/* Paste your CSS here */"><?php echo esc_textarea($custom_css); ?></textarea>
                        </div>
                    </div>

                    <div class="tab-pane clearfix" id="book-settings">
                        <h3><?php _e("Metadata settings", "publisher"); ?></h3>
                        <p><?php _e("Metadata is the information about your book. It is what allows your ebooks reading app to organize or filter the ebooks. The more information you provide, the easier it will be for readers to discover your book.", "publisher"); ?></p>

                        <div class="form-field">
                            <label for="book-language">
                                <?php _e("Language", "publisher"); ?>
                                <span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("Use the RFC3066 Language codes, such as en, es, fr…", "publisher"); ?>"></span>
                            </label>
                            <input name="language" id="book-language" type="text" value="<?php echo esc_attr($language); ?>" placeholder="<?php _e('Language', 'publisher'); ?>">
                        </div>

                        <div class="form-field">
                            <label for="book-date">
                                <?php _e("Publication date", "publisher"); ?>
                                <span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("This information won't affect the book's availability", "publisher"); ?>"></span>
                            </label>
                            <input name="date" id="book-date" type="text" value="<?php echo esc_attr($date); ?>" placeholder="<?php _e('YYYY-MM-DD', 'publisher'); ?>" style="width:95%">
                        </div>

                        <div class="form-field">
                            <label for="book-editor"><?php _e("Publisher Name", "publisher"); ?></label>
                            <input name="editor" id="book-editor" type="text" value="<?php echo esc_attr($editor); ?>" placeholder="<?php _e('Publisher Name', 'publisher'); ?>">
                        </div>

                        <div class="form-field">
                            <label for="book-copyright">
                                <?php _e("Copyright Information", "publisher"); ?>
                                <span class="dashicons dashicons-info" data-toggle="tooltip" title="<?php _e("Copyright information includes a statement about various property rights associated with the resource, including intellectual property rights", "publisher"); ?>"></span>
                            </label>
                            <textarea rows="3" name="copyright" id="book-copyright" placeholder="<?php _e('Copyright Information', 'publisher'); ?>"><?php echo esc_textarea($copyright); ?></textarea>
                        </div>

                        <h3><?php _e("External links", "publisher"); ?></h3>
                        <p><?php _e("Links will appear on your MPL-Download eBook widget extending your book's informations.", "publisher"); ?> <a href="<?php echo admin_url('widgets.php'); ?>" target="_blank"><?php _e("Edit Widgets", "publisher"); ?></a>.</p>

                        <div class="form-field">
                            <label for="book-landing"><?php _e("Landing Page URL", "publisher"); ?></label>
                            <input name="landing_url" id="book-landing" type="text" value="<?php echo esc_attr($landing_url); ?>" placeholder="<?php _e('Landing Page URL', 'publisher'); ?>">
                        </div>

                        <div class="form-field">
                            <label for="book-amazon"><?php _e("Amazon URL", "publisher"); ?> <a href="https://kdp.amazon.com/help?topicId=A2GF0UFHIYG9VQ" target="_blank">[?]</a></label>
                            <input name="amazon_url" id="book-amazon" type="text" value="<?php echo esc_attr($amazon_url); ?>" placeholder="<?php _e('Amazon URL', 'publisher'); ?>">
                        </div>

                        <div class="form-field">
                            <label for="book-ibooks"><?php _e("iBooks URL", "publisher"); ?> <a href="http://www.apple.com/itunes/working-itunes/sell-content/books/book-faq.html" target="_blank">[?]</a></label>
                            <input name="ibooks_url" id="book-ibooks" type="text" value="<?php echo esc_attr($ibooks_url); ?>" placeholder="<?php _e('iBooks URL', 'publisher'); ?>">
                        </div>
                    </div>

                    <div class="tab-pane clearfix" id="book-license">
                        <h3><?php _e("License key", "publisher"); ?></h3>
                        <?php if ($mpl_is_premium): ?>
                            <p><?php _e("Your <a href='https://wordpress.mpl-publisher.com?utm_medium=plugin&utm_campaign=license'>MPL-Publisher Premium</a> ⭐ has been successfully activated 🎉. You now have access to all the available formats and more cool features.", "publisher"); ?></p>
                            <p><?php _e("To make any change to your license, edit the following field and click \"💾 Save\" to confirm the changes.", "publisher"); ?></p>
                        <?php else: ?>
                            <p><?php _e("If you already bought <a href='https://wordpress.mpl-publisher.com?utm_medium=plugin&utm_campaign=license'>MPL-Publisher Premium</a> ⭐, you received a license key on the confirmation email.", "publisher"); ?></p>
                            <p><?php _e("Please, paste the value on the following field and click \"💾 Save\" to activate your premium version.", "publisher"); ?></p>
                        <?php endif; ?>

                        <div class="form-field">
                            <label for="license"><?php _e("License key", "publisher"); ?></label>
                            <input name="license" id="license" type="text" value="<?php echo esc_attr($license); ?>" placeholder="<?php _e('License key', 'publisher'); ?>">
                        </div>
                    </div>

                    <?php do_action('mpl_publisher_after_tabs_content'); ?>

                    <hr class="mt-30 mb-20" />

                    <div class="form-field mb-20" data-step="7" data-intro="<?php _e('We are almost there. We offer a good number of formats to download your book. We support the main extensions depending on your distribution platform.', 'publisher'); ?>">
                        <label for="format"><?php _e("Output format", "publisher"); ?>¹</label>
                        <select name="format" id="format">
                            <option value="epub2" <?php echo $format == "epub2" ? "selected='selected'" : ''; ?>>EPUB 2.0</option>
                            <option value="epub3" <?php echo $format == "epub3" ? "selected='selected'" : ''; ?>>EPUB 3.0</option>
                            <option value="wdocx" <?php echo $format == "wdocx" ? "selected='selected'" : ''; ?>>Microsoft Word (DOCX)</option>
                            <option value="mobi"  <?php echo $format == "mobi"  ? "selected='selected'" : ''; ?>>Amazon MOBI</option>
                            <option value="plain" <?php echo $format == "plain" ? "selected='selected'" : ''; ?>>Plain Text (TXT)</option>
                            <optgroup label="<?php _e('Premium only', 'publisher'); ?>">
                                <option value="json"  <?php echo $format == "json" ? "selected='selected'" : ''; ?>>MPL-Publisher (JSON)²</option>
                                <option value="print" <?php echo $format == "print" ? "selected='selected'" : ''; ?>>Adobe PDF File</option>
                                <option value="audio" <?php echo $format == "audio" ? "selected='selected'" : ''; ?>>Audiobook (MP3)</option>
                            </optgroup>
                        </select>
                        <p>¹ <?php _e("Output result will be affected by the complexity of your content (ie. \"plain text\" works best).", "publisher"); ?></p>
                        <p>² <?php _e("<b>MPL-Publisher (JSON)</b> format will allow you compatibility with our brand new <a href='https://mpl-publisher.com?utm_medium=plugin&utm_campaign=tools'>self-publishing tools</a>.", "publisher"); ?></p>
                    </div>

                    <p class="submit hidden-xs">
                        <button type="submit" name="generate" class="generate-button button button-primary" data-step="8" data-intro="<?php _e('Finally, if everything looks good, you can click Download, and we will generate a file for you. You are ready to go. Enjoy!', 'publisher'); ?>">🖨️ <?php _e('Download eBook', "publisher"); ?></button>
                        <button type="submit" name="save" class="button">💾 <?php _e('Save', "publisher"); ?></button>
                    </p>
                </div>
            </div>
        </div>

        <div id="col-right">
            <div class="col-wrap">

                <h3><?php _e("Text", "publisher"); ?></h3>

                <div class="form-wrap">
                    <div class="clearfix filter-bar" data-step="5" data-intro="<?php _e('Remember that you can use the filters for a more refined search. It will be helpful if you want to publish a book from a specific category or date.', 'publisher'); ?>">
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
                                <option value="<?php echo esc_attr($status); ?>" <?php echo in_array($status, $status_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo esc_html(get_post_status_object($status)->label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="cat_selected[]" id="cat" class="chosen" multiple data-placeholder="<?php _e("All categories", "publisher"); ?>">
                            <?php foreach ($blog_categories as $category): ?>
                                <option value="<?php echo esc_attr($category->cat_ID); ?>" <?php echo in_array($category->cat_ID, $cat_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo esc_html($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="month_selected[]" id="month" class="chosen" multiple data-placeholder="<?php _e("All months", "publisher"); ?>">
                            <?php foreach ($blog_months as $key => $month): ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php echo in_array($key, $month_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo esc_html($month); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="year_selected[]" id="year" class="chosen" multiple data-placeholder="<?php _e("All years", "publisher"); ?>">
                            <?php foreach ($blog_years as $year): ?>
                                <option value="<?php echo esc_attr($year); ?>" <?php echo in_array($year, $year_selected) ? "selected='selected'" : ""; ?>>
                                    <?php echo esc_html($year); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="chosen-container text-right">
                            <button type="submit" name="filter" id="post-query-submit" class="button">🔍 <span class="hidden-inline-xs"><?php _e('Filter content'); ?></span></button>
                        </div>
                    </div>
                    <p><?php _e("Drag your filtered results to sort your book's chapters", "publisher"); ?></p>
                    <table class="wp-list-table widefat striped posts">
                        <thead>
                            <tr>
                                <th class="check-column"></th>
                                <th class="manage-column column-cb check-column" data-step="4" data-intro="<?php _e('Use these checkboxes to select the content you want to include in your book (all by once or individually). Also, you can use drag & drop to sort them according to your preferences.', 'publisher'); ?>">
                                    <input id="cb-select-all-1" type="checkbox">
                                </th>
                                <th class="manage-column column-name">
                                    <?php _e("Contents", "publisher"); ?>
                                    (<?php _e('Order', 'publisher'); ?>: <button type="submit" name="order" class="button-link"><?php echo $order_asc ? "🔼" : "🔽"; ?></button>)
                                </th>
                                <th class="text-right"><a href="<?php echo admin_url('post-new.php?post_type=mpl_chapter'); ?>" class="button" data-step="6" data-intro="<?php _e('If you want to add unique content for your book, you can use Book Chapters. They will be private posts only available to your books, so it\'s a way to reward your readers with exclusive content.', 'publisher'); ?>">📑 <span class="hidden-inline-xs"><?php _e("Add New Book Chapter", "publisher"); ?></span></a></th>
                            </tr>
                        </thead>
                        <?php if ($query->found_posts > mpl_max_posts()): ?>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-info">
                                            ℹ️ <?php _e("Your current search has too many results and it's not available yet. Please, use our filters to limit your request.", "publisher"); ?>
                                            <?php _e("Current results", "publisher"); ?>: <b><?php echo $query->found_posts; ?></b>.
                                            <?php _e("Max results", "publisher"); ?>: <b><?php echo mpl_max_posts(); ?></b>.
                                        </div>
                                    </th>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                        <?php if ($query->have_posts()): ?>
                            <tbody id="chapter-list">
                                <?php while ($query->have_posts()): $query->the_post(); ?>
                                    <tr style="cursor: move">
                                        <th scope="row" class="check-column" style="padding:9px 0 0 10px;opacity:0.5">
                                            <span class="dashicons dashicons-menu" style="margin-right:0"></span>
                                        </th>
                                        <th scope="row" class="check-column">
                                            <input type="checkbox" name="selected_posts[]" value="<?php the_ID(); ?>" id="cb-select-<?php the_ID(); ?>" <?php echo ($selected_posts and in_array(get_the_ID(), $selected_posts)) ? 'checked="checked"' : ''; ?> >
                                        </th>
                                        <td class="name column-name">
                                            <div style="margin-bottom:4px">
                                                <?php if (get_post_type() == 'mpl_chapter'): ?>
                                                    <span class="dashicons dashicons-book" data-toggle="tooltip" title="<?php _e('Chapter', 'publisher'); ?>"></span>
                                                <?php elseif (get_post_type() == 'page'): ?>
                                                    <span class="dashicons dashicons-admin-page" data-toggle="tooltip" title="<?php _e('Page', 'publisher'); ?>"></span>
                                                <?php else: ?>
                                                    <span class="dashicons dashicons-admin-post" data-toggle="tooltip" title="<?php _e('Post', 'publisher'); ?>"></span>
                                                <?php endif; ?>
                                                <strong style="line-height:20px">
                                                    <a href="<?php echo get_edit_post_link(); ?>" target="_blank">
                                                        <?php the_title(); ?>
                                                    </a>
                                                    <?php if (get_post_status() != "publish"): ?> — <span class="post-state"><?php echo get_post_status_object(get_post_status())->label; ?></span><?php endif; ?>
                                                </strong>
                                            </div>
                                            <small><a href="<?php echo get_permalink(); ?>" target="_blank">View</a> | <a href="<?php echo get_edit_post_link(); ?>" target="_blank">Edit</a></small>
                                        </td>
                                        <td class="text-right" style="display:table-cell">
                                            <?php echo MPL\Publisher\PublisherBase::getContentStats(get_the_content()); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-warning">
                                            😞 <?php _e("Your search did not match any posts.", "publisher"); ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                        <tfoot>
                            <tr>
                                <th class="check-column"></th>
                                <th class="manage-column column-cb check-column">
                                    <input id="cb-select-all-2" type="checkbox">
                                </th>
                                <th class="manage-column column-name">
                                    <?php _e("Contents", "publisher"); ?>
                                    (<?php _e('Order', 'publisher'); ?>: <button type="submit" name="order" class="button-link"><?php echo $order_asc ? "🔼" : "🔽"; ?></button>)
                                </th>
                                <th class="text-right"><a href="<?php echo admin_url('post-new.php?post_type=mpl_chapter'); ?>" class="button">📑 <span class="hidden-inline-xs"><?php _e("Add New Book Chapter", "publisher"); ?></span></a></th>
                            </tr>
                        </tfoot>
                    </table>

                    <p class="submit visible-xs">
                        <button type="submit" name="generate" class="generate-button button button-primary">🖨️ <?php _e('Download eBook', "publisher"); ?></button>
                        <button type="submit" name="save" class="button">💾 <?php _e('Save', "publisher"); ?></button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>