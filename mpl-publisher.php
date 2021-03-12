<?php
/**
 * Plugin Name: MPL - Publisher
 * Plugin URI: https://mpl-publisher.ferranfigueredo.com/
 * Description: MPL - Publisher 📚 helps you self-publishing an ebook or audiobook from your WordPress posts. The plugin is here to help authors ✍️ solving the "how to publish my digital book" problem the simplest possible way 👌, easing the process of convert your ebook to ePub, PDF, mp3, kindle books, Mobi… etc.
 * Version: 1.23.0
 * Author: Ferran Figueredo
 * Author URI: https://ferranfigueredo.com
 * License: MIT
 */

define('MPL_BASEPATH', __DIR__);
define('MPL_BASEURL', plugin_dir_url(__FILE__));
define('MPL_ENDPOINT', 'https://api.ferranfigueredo.com');
define('MPL_MAX_POSTS', 100);
define('MPL_OPTION_NAME', 'mpl_publisher_status');

require 'vendor/autoload.php';

use Illuminate\Support\Str;

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
        'public'        => true,
        'show_in_menu'  => false,
        'supports'      => array('title', 'editor', 'author', 'revisions')
    ));
});

add_action('admin_menu', function ()
{
    add_menu_page('MPL - Publisher', 'MPL - Publisher', 'manage_options', 'publisher', function ()
    {
        $controller = new \MPL\Publisher\PublisherController();

        $controller->getIndex();
    }, 'dashicons-book', 76);
});

add_action('admin_post_publish_ebook', function ()
{
    $controller = new \MPL\Publisher\PublisherController();

    $controller->postIndex();
});

add_action('admin_enqueue_scripts', function ()
{
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_media();

    wp_enqueue_script('chosen', MPL_BASEURL . 'assets/js/chosen.jquery.min.js');
    wp_enqueue_style('chosen', MPL_BASEURL . 'assets/css/chosen.min.css');

    wp_enqueue_script('bootstrap', MPL_BASEURL . 'assets/js/bootstrap.js');

    $own = get_plugin_data(__FILE__);

    wp_enqueue_style('mpl-publisher', MPL_BASEURL . 'assets/css/mpl-publisher.css?mpl=' . $own['Version']);
    wp_enqueue_script('mpl-publisher', MPL_BASEURL . 'assets/js/mpl-publisher.js?mpl=' . $own['Version']);

    wp_enqueue_script('headwayapp', 'https://cdn.headwayapp.co/widget.js');
    wp_enqueue_script('tweemoji', 'https://twemoji.maxcdn.com/v/latest/twemoji.min.js');
});

add_action('wp_enqueue_scripts', function ()
{
    wp_enqueue_style('mpl-publisher', MPL_BASEURL . 'assets/css/mpl-widget.css');
});

add_action('add_meta_boxes', function ()
{
    add_meta_box('mpl_chapter_back', "&nbsp;", function ()
    {
        echo '<p class="mpl"><a href="' . admin_url('admin.php?page=publisher') . '"><span class="dashicons dashicons-arrow-left-alt2"></span>' . __("Back to Book Settings", "publisher") . '</a></p>';
    },
    'mpl_chapter', 'side', 'high');

    add_meta_box('mpl_chapter_help', __("How book chapters works", "publisher"), function ()
    {
        echo '<p>' . __("MPL - Publisher allows authors to write custom content specific for your book without the needed to be accessible from the public. You just have to publish your chapter and it will be visible only from the Book Settings page.", "publisher") . '</p>';
    },
    'mpl_chapter', 'side', 'high');

    remove_meta_box('slugdiv', 'mpl_chapter', 'normal');
});

add_action('widgets_init', function()
{
    register_widget('\MPL\Publisher\DownloadWidget');
});

add_shortcode('mpl', function ($attr, $content)
{
    $download = new \MPL\Publisher\DownloadWidget();

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

if ( ! function_exists('mpl_is_premium'))
{
    /**
     * https://mpl-publisher.ferranfigueredo.com :)
     *
     * We validate the license key on the backend
     */
    function mpl_is_premium()
    {
        return ! is_null(mpl_premium_license()) or ! is_null(mpl_premium_token());
    }
}

if ( ! function_exists('mpl_premium_license'))
{
    function mpl_premium_license()
    {
        $options = get_option(MPL_OPTION_NAME);

        if (is_array($options) and isset($options['data']['license']))
        {
            if (preg_match('/[A-Z0-9]{8}-[A-Z0-9]{8}-[A-Z0-9]{8}-[A-Z0-9]{8}/', $options['data']['license']))
            {
                return $options['data']['license'];
            }
        }

        return null;
    }
}

if ( ! function_exists('mpl_premium_token'))
{
    function mpl_premium_token()
    {
        if (file_exists(MPL_BASEPATH . '/mpl-publisher.json'))
        {
            $content = json_decode(file_get_contents(MPL_BASEPATH . '/mpl-publisher.json'), true);

            if (array_key_exists('token', $content)) return $content['token'];
        }

        return null;
    }
}

if ( ! function_exists('mpl_max_posts'))
{
    function mpl_max_posts()
    {
        return MPL_MAX_POSTS;
    }
}

if ( ! function_exists('mpl_mime_content_type'))
{
    function mpl_mime_content_type($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $parts = explode('.', $filename);
        // Only variables should be passed by reference
        $ext = strtolower(array_pop($parts));

        if (array_key_exists($ext, $mime_types))
        {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open'))
        {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);

            finfo_close($finfo);

            return $mimetype;
        }
        else
        {
            return 'application/octet-stream';
        }
    }
}