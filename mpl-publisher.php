<?php
/**
 * Plugin Name: MPL-Publisher
 * Plugin URI: https://wordpress.mpl-publisher.com/
 * Description: MPL-Publisher 📚 creates an ebook, print-ready PDF book, EPUB for KDP, or Audiobook MP3 directly from your WordPress posts.
 * Version: 2.19.1
 * Author: Ferran Figueredo
 * Author URI: https://ferranfigueredo.com
 * License: MIT
 * Text Domain: publisher
 * Domain Path: /languages
 */

define('MPL_BASEPATH', __DIR__);
define('MPL_BASEURL', plugin_dir_url(__FILE__));
define('MPL_ENDPOINT', 'https://mpl-publisher.com');
define('MPL_MARKETPLACE', 'https://marketplace.mpl-publisher.com');
define('MPL_MAX_POSTS', 50);
define('MPL_OPTION_NAME', 'mpl_publisher_status');
define('MPL_OPTION_LICENSE', 'mpl_publisher_license');
define('MPL_OPTION_MAX_POSTS', 'mpl_publisher_max_posts');

require 'vendor/autoload.php';
require 'helpers.php';

use MPL\Publisher\Str;
use MPL\Publisher\DownloadWidget;
use MPL\Publisher\PublisherController;

add_action('init', function ()
{
    load_plugin_textdomain('publisher', false, MPL_BASEPATH . '/languages');

    register_post_type('mpl_chapter', array(
        'labels' => array(
            'name'          => __('Book Chapters', 'publisher'),
            'singular_name' => __('Book Chapter', 'publisher'),
            'add_new'       => __('Add New Book Chapter', 'publisher'),
            'add_new_item'  => __('Add New Book Chapter', 'publisher'),
            'new_item'      => __('Add New Book Chapter', 'publisher'),
            'edit_item'     => __('Edit Book Chapter', 'publisher'),
            'view_item'     => __('View Book Chapter', 'publisher')
        ),
        'show_ui'       => true,
        'exclude_from_search' => false,
        'show_in_menu'  => false,
        'supports'      => array('title', 'editor', 'author', 'revisions'),
        'taxonomies'    => array('category', 'post_tag'),
        'show_in_rest'  => true
    ));
});

add_action('admin_menu', function ()
{
    add_menu_page('MPL-Publisher', 'MPL-Publisher', 'manage_options', 'publisher', function ()
    {
        $controller = new PublisherController();
        $controller->getIndex();

    }, 'dashicons-book', 76);

    add_submenu_page('publisher', 'MPL-Publisher', __('Publish eBook', 'publisher'), 'manage_options', 'publisher');

    add_submenu_page('publisher', 'MPL-Publisher', __('Cover Editor', 'publisher'), 'manage_options', 'mpl-cover', function ()
    {
        $controller = new PublisherController();
        $controller->getCoverEditor();
    });

    add_submenu_page('publisher', 'MPL-Publisher', __('Resources', 'publisher'), 'manage_options', 'mpl-extensions', function ()
    {
        $controller = new PublisherController();
        $controller->getMarketplace();
    });
});

add_action('admin_post_publish_ebook', function ()
{
    $controller = new PublisherController();
    $controller->postIndex();
});

add_action('wp_ajax_mpl_duplicate_post', function ()
{
    $controller = new PublisherController();
    $controller->postAjaxDuplicate();
});

add_action('wp_loaded', function ()
{
    if (array_key_exists('mpl_duplicated_id', $_GET) and $_GET['mpl_duplicated_id'])
    {
        $duplicated_id = sanitize_text_field($_GET['mpl_duplicated_id']);
        $admin_edit_url = get_edit_post_link($duplicated_id, 'else');

        return wp_safe_redirect($admin_edit_url);
    }
});

add_action('admin_enqueue_scripts', function ()
{
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-selectable');
    wp_enqueue_media();
    add_thickbox();

    wp_enqueue_script('chosen', MPL_BASEURL . 'assets/js/chosen.jquery.min.js');
    wp_enqueue_style('chosen', MPL_BASEURL . 'assets/css/chosen.min.css');

    wp_enqueue_script('bootstrap', MPL_BASEURL . 'assets/js/bootstrap.js');

    wp_enqueue_script('twemoji', 'https://cdn.jsdelivr.net/npm/@twemoji/api@latest/dist/twemoji.min.js');
    wp_enqueue_script('iframe-resizer', MPL_BASEURL . 'assets/js/iframeResizer.min.js');
    wp_enqueue_script('jquery.are-you-sure', MPL_BASEURL . 'assets/js/jquery.are-you-sure.min.js');

    wp_enqueue_script('introjs', MPL_BASEURL . 'assets/js/intro.min.js');
    wp_enqueue_style('introjs', MPL_BASEURL . 'assets/css/introjs.min.css');

    $own = get_plugin_data(__FILE__);

    wp_enqueue_style('mpl-publisher', MPL_BASEURL . 'assets/css/mpl-publisher.css?mpl=' . $own['Version']);
    wp_enqueue_script('mpl-publisher', MPL_BASEURL . 'assets/js/mpl-publisher.js?mpl=' . $own['Version']);

    wp_enqueue_style('tui-editor-color-picker', MPL_BASEURL . 'assets/tui-editor/tui-color-picker.min.css');
    wp_enqueue_style('tui-editor-image-editor', MPL_BASEURL . 'assets/tui-editor/tui-image-editor.min.css');

    wp_enqueue_script('tui-editor-fabric', MPL_BASEURL . 'assets/tui-editor/fabric.min.js');
    wp_enqueue_script('tui-editor-code-snippet', MPL_BASEURL . 'assets/tui-editor/tui-code-snippet.min.js');
    wp_enqueue_script('tui-color-picker', MPL_BASEURL . 'assets/tui-editor/tui-color-picker.min.js');
    wp_enqueue_script('tui-editor-file-saver', MPL_BASEURL . 'assets/tui-editor/FileSaver.min.js');
    wp_enqueue_script('tui-editor-image-editor', MPL_BASEURL . 'assets/tui-editor/tui-image-editor.min.js');
});

add_action('wp_enqueue_scripts', function ()
{
    wp_enqueue_style('mpl-publisher', MPL_BASEURL . 'assets/css/mpl-widget.css');
});

add_action('add_meta_boxes', function ()
{
    add_meta_box('mpl_chapter_help', __("How book chapters works", "publisher"), function ()
    {
        echo '<p>' . __("MPL-Publisher allows authors to write custom content specific for your book without the needed to be accessible from the public. You just have to publish your chapter and it will be visible only from the Book Settings page.", "publisher") . '</p>';
        echo '<p class="mpl"><a href="' . admin_url('admin.php?page=publisher') . '"><span class="dashicons dashicons-arrow-left-alt2"></span>' . __("Back to Book Settings", "publisher") . '</a></p>';
    },
    'mpl_chapter', 'side', 'high');

    remove_meta_box('slugdiv', 'mpl_chapter', 'normal');
});

add_action('widgets_init', function()
{
    if (class_exists('\MPL\Publisher\DownloadWidget')) register_widget('\MPL\Publisher\DownloadWidget');
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($actions)
{
    $actions[] = '<a href="'. esc_url(get_admin_url(null, 'admin.php?page=publisher')) . '">' . __('Settings', 'publisher') . '</a>';
    $actions[] = '<a href="https://wordpress.mpl-publisher.com?utm_medium=plugin&utm_campaign=settings" target="_blank"><b>Premium</b></a>';

    return $actions;
});

add_shortcode('mpl', function ($attr, $content)
{
    $download = new DownloadWidget();

    return $download->shortcode($attr, $content);
});

add_filter('admin_footer_text', function ($text)
{
    global $current_screen;

    if ( ! empty($current_screen->id) and Str::contains($current_screen->id, 'publisher'))
    {
        $url  = 'https://wordpress.org/support/plugin/mpl-publisher/reviews/?filter=5#new-post';
        $text = sprintf(
            wp_kses(
                /* translators: $1$s - MPL-Publisher plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
                __('Please rate %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer" style="text-decoration:none">⭐⭐⭐⭐⭐</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you!', 'publisher'),
                array(
                    'a' => array(
                        'href'   => array(),
                        'target' => array(),
                        'rel'    => array(),
                        'style'  => array()
                    ),
                )
            ),
            '<strong>MPL-Publisher</strong>',
            $url,
            $url
        );
    }

    return $text;
});

// Enable Divi Builder on MPL-Publisher
add_filter('et_builder_should_load_framework', function ($should_load)
{
    if (array_key_exists('action', $_POST) and $_POST['action'] == 'publish_ebook')
    {
        return true;
    }

    return $should_load;
});

// Show Author, Categories and Tags in the Screen Options
add_filter('screen_settings', 'publisher_screen_options', 10, 2);

function publisher_screen_options($settings, $screen)
{
    if ($screen->id === 'toplevel_page_publisher')
    { // Ensure this matches your actual screen ID
        $user_id = get_current_user_id();
        $show_author = get_user_meta($user_id, 'show_author_column_publisher', true);
        $show_category = get_user_meta($user_id, 'show_category_column_publisher', true);
        $show_tags = get_user_meta($user_id, 'show_tags_column_publisher', true);
        $show_date = get_user_meta($user_id, 'show_date_column_publisher', true);

        // Default unchecked (hidden)
        $show_author = ($show_author === '' || $show_author == 0) ? 0 : 1;
        $show_category = ($show_category === '' || $show_category == 0) ? 0 : 1;
        $show_tags = ($show_tags === '' || $show_tags == 0) ? 0 : 1;
        $show_date = ($show_date === '' || $show_date == 0) ? 0 : 1;

        // Output the settings panel with checkboxes and an Apply button
        $settings .= '
        <form method="post" class="mpl-screen-options" action="">
            ' . wp_nonce_field('publisher_screen_options_save', 'publisher_screen_options_nonce', true, false) . '
            <fieldset class="mpl-fieldset">
                <legend>Columns</legend>
                <label>
                    <input type="checkbox" name="show_author_column_publisher" value="1" ' . checked($show_author, 1, false) . ' />
                    Author
                </label>
                <br>
                <label>
                    <input type="checkbox" name="show_category_column_publisher" value="1" ' . checked($show_category, 1, false) . ' />
                    Categories
                </label>
                <br>
                <label>
                    <input type="checkbox" name="show_tags_column_publisher" value="1" ' . checked($show_tags, 1, false) . ' />
                    Tags
                </label>
                <br>
                <label>
                    <input type="checkbox" name="show_date_column_publisher" value="1" ' . checked($show_date, 1, false) . ' />
                    Date
                </label>
            </fieldset>
            <p class="submit">
                <input type="submit" name="save_publisher_screen_options" class="button button-primary" value="Apply">
            </p>
        </form>';
    }

    return $settings;
}

add_action('admin_init', 'save_publisher_screen_options');

function save_publisher_screen_options() {
    if (isset($_POST['save_publisher_screen_options'])) {
        if (!current_user_can('manage_options') || !isset($_POST['publisher_screen_options_nonce']) || !wp_verify_nonce($_POST['publisher_screen_options_nonce'], 'publisher_screen_options_save')) {
            return;
        }

        $user_id = get_current_user_id();

        // Save Author visibility
        if (isset($_POST['show_author_column_publisher']) && $_POST['show_author_column_publisher'] == 1) {
            update_user_meta($user_id, 'show_author_column_publisher', 1);
        } else {
            delete_user_meta($user_id, 'show_author_column_publisher');
        }

        // Save Categories visibility
        if (isset($_POST['show_category_column_publisher']) && $_POST['show_category_column_publisher'] == 1) {
            update_user_meta($user_id, 'show_category_column_publisher', 1);
        } else {
            delete_user_meta($user_id, 'show_category_column_publisher');
        }

        // Save Tags visibility
        if (isset($_POST['show_tags_column_publisher']) && $_POST['show_tags_column_publisher'] == 1) {
            update_user_meta($user_id, 'show_tags_column_publisher', 1);
        } else {
            delete_user_meta($user_id, 'show_tags_column_publisher');
        }

        // Save Date visibility
        if (isset($_POST['show_date_column_publisher']) && $_POST['show_date_column_publisher'] == 1) {
            update_user_meta($user_id, 'show_date_column_publisher', 1);
        } else {
            delete_user_meta($user_id, 'show_date_column_publisher');
        }

        // Redirect to avoid resubmission on page reload
        wp_redirect(add_query_arg('settings-updated', 'true', $_SERVER['HTTP_REFERER']));

        exit;
    }
}

register_deactivation_hook(__FILE__, 'mpl_uninstall_hook');
register_uninstall_hook(__FILE__, 'mpl_uninstall_hook');