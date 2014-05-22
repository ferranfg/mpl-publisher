<?php

namespace MPL;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_SimpleFunction;

class PublisherController {

    private $view;

    public $data = array();

    public function __construct($basePath)
    {
        $loader = new Twig_Loader_Filesystem($basePath . DIRECTORY_SEPARATOR . "views");

        $this->view = new Twig_Environment($loader);

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
        
        $this->data['epubExists'] = file_exists(wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'mpl-publisher.epub');
        $this->data['epubDownload'] = wp_upload_dir()['baseurl'] . '/mpl-publisher.epub';

    	echo $this->view->render('index.php', $this->data);
    }

    public function postIndex()
    {
        $publisher = new EpubPublisher();

        $publisher->setIdentifier($_POST['identifier']);
        $publisher->setTitle($_POST['title']);
        $publisher->setAuthor($_POST['authors']);
        $publisher->setPublisher($_POST['editor']);

        foreach (get_posts() as $id => $post)
        {
            if (in_array($post->ID, $_POST['selected_posts']))
            {
                $publisher->addChapter($post->post_title, $post->post_content);
            }
        }

        $publisher->save('mpl-publisher');

        return $this->getIndex();
    }
}