<?php

namespace MPL\Publisher;

use Illuminate\View\View;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Engines\PhpEngine;

class PublisherController {

    private $basePath;

    private $viewPath;

    public function __construct($basePath)
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->basePath = $basePath;
        $this->viewPath = $basePath . $DS . 'views' . $DS;
    }

    private function view($file, $data = array())
    {
        $viewFinder = new FileViewFinder(new Filesystem, array($this->basePath));
        $factory = new Factory(new EngineResolver, $viewFinder, new Dispatcher);

        return new View($factory, new PhpEngine, $file, $this->viewPath . $file, $data);
    }

    public function getIndex()
    {
        $this->data['site_name'] = get_bloginfo('site_name');
        $this->data['query'] = new \WP_Query('');
        $this->data['categories'] = get_categories();
        $this->data['tags'] = get_tags();
        $this->data['current_user'] = wp_get_current_user();
        $this->data['action'] = admin_url('admin-post.php');
        $this->data['wp_nonce_field'] = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

    	echo $this->view('index.php', $this->data);
    }

    public function postIndex()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || !check_admin_referer('publish_ebook', '_wpnonce')) return;

        $publisher = new EpubPublisher($_POST['format'], $this->basePath);

        $publisher->setIdentifier(sanitize_text_field($_POST['identifier']));
        $publisher->setTitle(sanitize_text_field($_POST['title']));
        $publisher->setAuthor(sanitize_text_field($_POST['authors']));
        $publisher->setPublisher(sanitize_text_field($_POST['editor']));

        if (!empty($_POST['cover']) and $imageId = intval($_POST['cover']))
        {
            $publisher->setCoverImage("cover.jpg", file_get_contents(get_attached_file($imageId)));
        }

        $query = new \WP_Query(array('post__in' => $_POST['selected_posts'], 'orderby' => 'post__in'));

        if ($query->have_posts())
        {
            while ($query->have_posts())
            {
                $query->the_post();

                $publisher->addChapter(get_the_ID(), get_the_title(), get_the_content());
            }
        }

        return $publisher->send(get_bloginfo('name') . ' - ' . wp_get_current_user()->display_name);
    }
}