<?php

namespace MPL\Publisher;

use WP_Query;
use Illuminate\View\View;
use Illuminate\View\Factory;
use Illuminate\Events\Dispatcher;
use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\EngineResolver;

class PublisherBase {

    public $statusOptionName = 'mpl_publisher_status';

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
            if (count($coverSrc) == 4 and isset($coverSrc[0])) $this->data['coverSrc'] = $coverSrc[0];

            if (!empty($this->data['cat_selected']))    $this->filter['cat']         = implode(',', $this->data['cat_selected']);
            if (!empty($this->data['author_selected'])) $this->filter['author']      = implode(',', $this->data['author_selected']);
            if (!empty($this->data['tag_selected']))    $this->filter['tag']         = implode(',', $this->data['tag_selected']);
            if (!empty($this->data['status_selected'])) $this->filter['post_status'] = implode(',', $this->data['status_selected']);
            if (!empty($this->data['year_selected']))   $this->filter['year']        = implode(',', $this->data['year_selected']);
        }

        $this->filter['post_type'] = !empty($this->data['post_type']) ? $this->data['post_type'] : array('post', 'mpl_chapter');
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
            'language'    => '',
            'date'        => '',
            'cover'       => false,
            'coverSrc'    => false,
            'editor'      => '',
            'copyright'   => '',
            'landingUrl'  => false,
            'amazonUrl'   => false,
            'ibooksUrl'   => false,
            'affiliate'   => false,
            'customCss'   => '',
            'theme_id'    => 0,
            'cat_selected'    => array(),
            'author_selected' => array(),
            'tag_selected'    => array(),
            'status_selected' => array(),
            'post_type'       => array(),
            'year_selected'   => array(),
            'selected_posts'  => false,
            'format'          => 'epub2'
        );
    }

    public function getThemeDefaults()
    {
        return array(
            'name'  => __('Default', 'publisher'),
            'image' => MPL_BASEURL . 'assets/imgs/default.png',
            'style' => MPL_BASEPATH . '/assets/css/book.css',
            'fonts' => array(
                'merriweather-regular' => MPL_BASEPATH . '/assets/fonts/merriweather-regular.ttf',
                'merriweather-bold'    => MPL_BASEPATH . '/assets/fonts/merriweather-bold.ttf',
                'merriweather-italic'  => MPL_BASEPATH . '/assets/fonts/merriweather-italic.ttf',
                'lato-bold'            => MPL_BASEPATH . '/assets/fonts/lato-bold.ttf'
            )
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

    public function getYears()
    {
        $archive = wp_get_archives(array(
            'type' => 'yearly',
            'echo' => false,
            'format' => 'custom',
            'before' => '',
            'after' => '%'
        ));

        $stripped = preg_replace('/\s+/', '', strip_tags($archive));

        return array_filter(explode('%', $stripped));
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
                $publisher = new EpubPublisher($_POST['format']);
            break;
            case 'mobi':
                $publisher = new MobiPublisher();
            break;
            case 'wdocx':
                $publisher = new WordPublisher(wp_upload_dir()['path']);
            break;
            case 'markd':
                $publisher = new MarkdownPublisher();
            break;            
        }

        if ( ! $publisher) return;

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
            $this->getTheme(
                sanitize_text_field($_POST['theme_id'])
            ),
            sanitize_text_field($_POST['custom_css'])
        );

        $query = new WP_Query(array(
            'post__in'       => isset($_POST['selected_posts']) ? $_POST['selected_posts'] : [0],
            'orderby'        => 'post__in',
            'posts_per_page' => '-1',
            'post_status'    => 'any',
            'post_type'      => 'any'
        ));

        if ($query->have_posts())
        {
            while ($query->have_posts()): $query->the_post();
                $post = get_post(get_the_ID());
                $publisher->addChapter($post->ID, $post->post_title, wpautop($post->post_content));
            endwhile;
        }

        return $publisher->send(get_bloginfo('name') . ' - ' . wp_get_current_user()->display_name);
    }

    public function saveStatus($data)
    {
        return update_option($this->statusOptionName, array(
            'time' => current_time('timestamp'),
            'data' => apply_filters('mpl_publisher_save_status', $data)
        ));
    }

    public function getStatus()
    {
        return apply_filters('mpl_publisher_get_status', get_option($this->statusOptionName));
    }

    public function getThemes()
    {
        return apply_filters('mpl_publisher_get_themes', array(
            $this->getThemeDefaults()
        ));
    }

    public function getTheme($themeId)
    {
        $themes = $this->getThemes();

        if ( count($themes) and array_key_exists($themeId, $themes)) return $themes[$themeId];

        return $this->getThemeDefaults();
    }

}