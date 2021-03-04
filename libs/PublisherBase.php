<?php

namespace MPL\Publisher;

use WP_Query;
use Exception;
use Illuminate\View\View;
use Illuminate\View\Factory;
use Illuminate\Events\Dispatcher;
use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\EngineResolver;

class PublisherBase {

    public $data;

    public $filter;

    public function __construct()
    {
        $this->filter = $_GET;
        $this->data   = $this->getBookDefaults();

        $status = $this->getStatus();

        if ($status and isset($status['data']))
        {
            $this->data = array_merge($this->data, $status['data']);

            $format = get_option('date_format') . ' ' . get_option('time_format');

            $this->data['message'] = sprintf(__('Submitted on %s' , "publisher"), date($format, $status['time']));

            $coverSrc = wp_get_attachment_image_src($this->data['cover'], 'full');

            if (is_array($coverSrc) and count($coverSrc) == 4 and array_key_exists(0, $coverSrc))
            {
                $this->data['coverSrc'] = $coverSrc[0];
            }

            if (!empty($this->data['cat_selected']))    $this->filter['cat']         = implode(',', $this->data['cat_selected']);
            if (!empty($this->data['author_selected'])) $this->filter['author']      = implode(',', $this->data['author_selected']);
            if (!empty($this->data['tag_selected']))    $this->filter['tag']         = implode(',', $this->data['tag_selected']);
            if (!empty($this->data['status_selected'])) $this->filter['post_status'] = implode(',', $this->data['status_selected']);
            if (!empty($this->data['year_selected']))   $this->filter['year']        = implode(',', $this->data['year_selected']);
            if (!empty($this->data['month_selected']))  $this->filter['month']       = implode(',', $this->data['month_selected']);
        }

        $this->filter['post_type'] = !empty($this->data['post_type']) ? $this->data['post_type'] : array('post', 'page', 'mpl_chapter');
    }

    public function view($file, $data = array())
    {
        $viewFinder = new FileViewFinder(new Filesystem, array(MPL_BASEPATH));
        $factory    = new Factory(new EngineResolver, $viewFinder, new Dispatcher);
        $files      = new Filesystem;

        return new View($factory, new PhpEngine($files), $file, MPL_BASEPATH . "/views/" . $file, $data);
    }

    public function getBookDefaults()
    {
        return array(
            'identifier'  => '',
            'title'       => get_bloginfo('site_name'),
            'description' => get_bloginfo('site_description'),
            'authors'     => wp_get_current_user()->display_name,
            'language'    => get_locale(),
            'date'        => '',
            'cover'       => false,
            'coverSrc'    => false,
            'editor'      => '',
            'copyright'   => '',
            'landingUrl'  => false,
            'amazonUrl'   => false,
            'ibooksUrl'   => false,
            'customCss'   => '',
            'theme_id'    => 0,
            'cat_selected'    => array(),
            'author_selected' => array(),
            'tag_selected'    => array(),
            'status_selected' => array(),
            'post_type'       => array(),
            'year_selected'   => array(),
            'month_selected'  => array(),
            'selected_posts'  => false,
            'format'          => 'epub2',
            'license'         => ''
        );
    }

    public function getDefaultThemes()
    {
        return array(
            array(
                'id'    => 'default',
                'name'  => __('Lato & Merriweather', 'publisher'),
                'image' => MPL_BASEURL . 'assets/imgs/theme-default.png',
                'style' => MPL_BASEPATH . '/assets/css/theme-default.css',
                'fonts' => array(
                    'merriweather-regular' => MPL_BASEPATH . '/assets/fonts/merriweather-regular.ttf',
                    'merriweather-bold'    => MPL_BASEPATH . '/assets/fonts/merriweather-bold.ttf',
                    'merriweather-italic'  => MPL_BASEPATH . '/assets/fonts/merriweather-italic.ttf',
                    'lato-bold'            => MPL_BASEPATH . '/assets/fonts/lato-bold.ttf'
                )
            ),
            array(
                'id'    => 'crimson',
                'name'  => __('Montserrat & Crimson', 'publisher'),
                'image' => MPL_BASEURL . 'assets/imgs/theme-crimson.png',
                'style' => MPL_BASEPATH . '/assets/css/theme-crimson.css',
                'fonts' => array(
                    'crimson-regular' => MPL_BASEPATH . '/assets/fonts/crimson-regular.ttf',
                    'crimson-bold'    => MPL_BASEPATH . '/assets/fonts/crimson-bold.ttf',
                    'crimson-italic'  => MPL_BASEPATH . '/assets/fonts/crimson-italic.ttf',
                    'montserrat-bold' => MPL_BASEPATH . '/assets/fonts/montserrat-bold.ttf'
                )
            ),

        );
    }

    public function getCategories()
    {
        return get_categories('orderby=post_count&order=DESC');
    }

    public function getAuthors()
    {
        return get_users('orderby=post_count&order=DESC&who=authors');
    }

    public function getTags()
    {
        return get_tags();
    }

    public function getStatuses()
    {
        return get_post_stati();
    }

    public function getMonths()
    {
        return [
            1 => __('January', 'publisher'),
            2 => __('February', 'publisher'),
            3 => __('March', 'publisher'),
            4 => __('April', 'publisher'),
            5 => __('May', 'publisher'),
            6 => __('June', 'publisher'),
            7 => __('July', 'publisher'),
            8 => __('August', 'publisher'),
            9 => __('September', 'publisher'),
            10 => __('October', 'publisher'),
            11 => __('November', 'publisher'),
            12 => __('December', 'publisher')
        ];
    }

    public function getYears()
    {
        $archive = wp_get_archives(array(
            'type'   => 'yearly',
            'echo'   => false,
            'format' => 'custom',
            'before' => '',
            'after'  => '%'
        ));

        $stripped = preg_replace('/\s+/', '', strip_tags($archive));

        return array_filter(explode('%', $stripped));
    }

    public function getQuery()
    {
        if (array_key_exists('month', $this->filter))
        {
            $this->filter['date_query'] = ['relation' => 'OR'];

            array_map(function($key)
            {
                array_push($this->filter['date_query'], ['month' => $key]);

            }, explode(',', $this->filter['month']));
        }

        $query = http_build_query(array_merge(array(
            'posts_per_page' => mpl_max_posts(),
            'order' => 'ASC'
        ), $this->filter));

        return new WP_Query($query);
    }

    public function generateBook($forceGenerate = false)
    {
        if ($forceGenerate) $_POST = $this->data;

        $_POST = apply_filters('mpl_publisher_generate_book', $_POST);

        $publisher = false;

        switch ($_POST['format'])
        {
            case 'epub2':
            case 'epub3':
                $publisher = new EpubPublisher();
                $publisher->setFormat($_POST['format']);
            break;
            case 'mobi':
                $publisher = new MobiPublisher();
            break;
            case 'wdocx':
                $publisher = new WordPublisher();
                $publisher->setTmpPath(get_temp_dir());
            break;
            case 'markd':
                $publisher = new MarkdownPublisher();
            break;
            case 'print':
                $publisher = new PrintPublisher();
                $publisher->setEmail(wp_get_current_user()->user_email);
                $publisher->setTmpPath(get_temp_dir());
            break;
            case 'audio':
                $publisher = new AudiobookPublisher();
                $publisher->setEmail(wp_get_current_user()->user_email);
                $publisher->setTmpPath(get_temp_dir());
            break;
        }

        if ( ! $publisher) throw new Exception('⚠️ ' . __('No valid output format selected.', 'publisher'));

        $publisher->setIdentifier(sanitize_text_field($_POST['identifier']));
        $publisher->setTitle(sanitize_text_field($_POST['title']));
        $publisher->setAuthor(sanitize_text_field($_POST['authors']));
        $publisher->setPublisher(sanitize_text_field($_POST['editor']));
        $publisher->setDescription(sanitize_text_field($_POST['description']));
        $publisher->setDate(sanitize_text_field($_POST['date']));

        $language = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : substr(get_locale(), 0, 2);
        $publisher->setLanguage($language);

        if (!empty($_POST['cover']) and $imageId = intval($_POST['cover']))
        {
            $publisher->setCoverImage('cover.jpg', file_get_contents(get_attached_file($imageId)));
        }

        $publisher->setRights(sanitize_text_field($_POST['copyright']));
        $publisher->setTheme(
            $this->getTheme(sanitize_text_field($_POST['theme_id'])),
            sanitize_text_field($_POST['custom_css'])
        );

        $query = new WP_Query(array(
            'ignore_sticky_posts' => 1,
            'post__in'       => isset($_POST['selected_posts']) ? $_POST['selected_posts'] : [0],
            'orderby'        => 'post__in',
            'posts_per_page' => '-1',
            'post_status'    => 'any',
            'post_type'      => 'any',
            'no_found_rows'  => true
        ));

        if ($query->have_posts())
        {
            $chapter = 1;

            while ($query->have_posts()): $query->the_post();
                $post = get_post(get_the_ID());
                $content = $this->parseText($post->post_content);

                $publisher->addChapter($chapter, $post->post_title, $content);

                $chapter++;
            endwhile;
        }
        else
        {
            throw new Exception('⚠️ ' . __('Please, select at least one chapter to publish your book.', 'publisher'));
        }

        return $publisher->send(sanitize_text_field($_POST['title']));
    }
    
    public function saveStatus($data)
    {
        return update_option(MPL_OPTION_NAME, array(
            'time' => current_time('timestamp'),
            'data' => apply_filters('mpl_publisher_save_status', $data)
        ));
    }

    public function getStatus()
    {
        return apply_filters('mpl_publisher_get_status', get_option(MPL_OPTION_NAME));
    }

    public function getThemes()
    {
        return apply_filters('mpl_publisher_get_themes', $this->getDefaultThemes());
    }

    public function getTheme($themeId)
    {
        $themes = $this->getThemes();

        if (array_key_exists($themeId, $themes)) return $themes[$themeId];

        return reset($themes);
    }

    private function parseText($post_content)
    {
        // Remove shortcodes
        $content = strip_shortcodes($post_content);
        // Remove HTML comments
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        // Remove properties from allowed HTML tags (except <p>)
        $content = wp_kses($content, array(
            // Headings
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            // Global
            'a' => array(
                'href' => array(),
                'title' => array(),
            ),
            'img' => array(
                'src' => array(),
                'alt' => array(),
                'class' => array()
            ),
            'blockquote' => array(),
            'q' => array(),
            'cite' => array(),
            'hr' => array(),
            // Styles
            'u' => array(),
            'i' => array(),
            'b' => array(),
            'em' => array(),
            'small' => array(),
            'strong' => array(),
            'strike' => array(),
            // Lists
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
            // Tables
            'table' => array(),
            'tbody' => array(),
            'thead' => array(),
            'tfooter' => array(),
            'tr' => array(),
            'td' => array(),
            'th' => array()
        ));
        // Use autop to generate p
        $content = wpautop($content);
        // Remove new lines: https://stackoverflow.com/a/3760830
        $content = preg_replace('/\s+/', ' ', $content);
        // Remove unnecesary spaces
        $content = str_replace('&nbsp;', ' ', $content);

        return $content;
    }

}