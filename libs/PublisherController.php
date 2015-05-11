<?php

namespace MPL;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_SimpleFunction;

class PublisherController {

    private $view;

    private $loader;

    private $basePath;

    public $data = array();

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->loader = new Twig_Loader_Filesystem($this->basePath . DIRECTORY_SEPARATOR . "views");

        $this->view = new Twig_Environment($this->loader);

        $__ = new Twig_SimpleFunction('__', function ($value)
        {
            return __($value, 'publisher');
        });

        $this->view->addFunction($__);
    }

    public function getIndex()
    {
        $this->data['site_name'] = get_bloginfo('site_name');
        $this->data['posts'] = get_posts();
        $this->data['categories'] = get_categories();
        $this->data['current_user'] = wp_get_current_user();
        $this->data['action'] = admin_url('admin-post.php');
        $this->data['wp_nonce_field'] = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

    	echo $this->view->render('index.php', $this->data);
    }

    public function postIndex()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || !check_admin_referer('publish_ebook', '_wpnonce')) return;

        $publisher = new EpubPublisher();

        $publisher->setIdentifier(sanitize_text_field($_POST['identifier']));
        $publisher->setTitle(sanitize_text_field($_POST['title']));
        $publisher->setAuthor(sanitize_text_field($_POST['authors']));
        $publisher->setPublisher(sanitize_text_field($_POST['editor']));

        $query = new \WP_Query(array('post__in' => $_POST['selected_posts'], 'orderby' => 'post__in'));

        if ($query->have_posts())
        {
            while ($query->have_posts())
            {
                $query->the_post();

                $publisher->addChapter(get_the_title(), get_the_content());
            }
        }

        return $publisher->send(get_bloginfo('name') . ' - ' . wp_get_current_user()->display_name);
    }
}