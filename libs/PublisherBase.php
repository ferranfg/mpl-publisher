<?php

namespace MPL\Publisher;

use Illuminate\View\View;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Engines\PhpEngine;

class PublisherBase {

    public $statusOptionName = 'mpl_publisher_status';

    public $data;

    public $filter;

    public function __construct()
    {
		$this->data = $this->getBookDefaults();
        $status 	= $this->getStatus();

        $this->filter = $_GET;

        if ($status and isset($status['data']))
        {
            $this->data = array_merge($this->data, $status['data']);

            $format = get_option('date_format') . ' ' . get_option('time_format');
            $this->data['message'] = sprintf(__('Last modification: %s' , "publisher"), date($format, $status['time']));

			$coverSrc = wp_get_attachment_image_src($this->data['cover'], 'full');
			if (count($coverSrc) == 4 and isset($coverSrc[0])) $this->data['coverSrc'] = $coverSrc[0];

            if (!empty($this->data['cat_selected']))    $this->filter['cat']    = implode(',', $this->data['cat_selected']);
            if (!empty($this->data['author_selected'])) $this->filter['author'] = implode(',', $this->data['author_selected']);
            if (!empty($this->data['tag_selected']))    $this->filter['tag']    = implode(',', $this->data['tag_selected']);
        }

        $this->filter['post_type'] = !empty($this->data['post_type']) ? $this->data['post_type'] : array('post', 'mpl_chapter');
    }

    public function view($file, $data = array())
    {
        $viewFinder = new FileViewFinder(new Filesystem, array(MPL_BASEPATH));
        $factory    = new Factory(new EngineResolver, $viewFinder, new Dispatcher);

        return new View($factory, new PhpEngine, $file, MPL_BASEPATH . "/views/" . $file, $data);
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
            'coverSrc'	  => false,
            'editor'      => '',
            'copyright'   => '',
            'landingUrl'  => false,
            'amazonUrl'	  => false,
            'ibooksUrl'	  => false,            
            'cat_selected'    => array(),
            'author_selected' => array(),
            'tag_selected'    => array(),
            'post_type'       => array(),
            'selected_posts'  => false,
            'format'          => 'epub2'
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

    public function generateBook()
    {
        $publisher = false;

        switch ($_POST['format'])
        {
            case 'epub2':
            case 'epub3':
                $publisher = new EpubPublisher($_POST['format'], MPL_BASEPATH);
                break;
            case 'markd':
                $publisher = new MarkdownPublisher();
                break;
        }

        if (!$publisher) return;

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

        $query = new \WP_Query(array(
            'post__in'       => $_POST['selected_posts'],
            'orderby'        => 'post__in',
            'posts_per_page' => '-1',
            'post_status'    => 'any',
            'post_type'      => 'any'
        ));

        if ($query->have_posts())
        {
            while ($query->have_posts())
            {
                $query->the_post();

                $publisher->addChapter(get_the_ID(), get_the_title(), wpautop(get_the_content()));
            }
        }

        return $publisher->send(get_bloginfo('name') . ' - ' . wp_get_current_user()->display_name);
    }

    public function saveStatus($data)
    {
        return update_option($this->statusOptionName, array(
            'time' => current_time('timestamp'),
            'data' => $data
        ));
    }

    public function getStatus()
    {
        return get_option($this->statusOptionName);
    }
}