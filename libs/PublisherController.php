<?php

namespace MPL;

use Twig_Loader_Filesystem;
use Twig_Environment;

class PublisherController {

    private $view;

    public $data = array();

    public function __construct($basePath)
    {
        $loader = new Twig_Loader_Filesystem($basePath . DIRECTORY_SEPARATOR . "views");

        $this->view = new Twig_Environment($loader);

        $this->data['site_name'] = get_bloginfo('site_name');
        $this->data['posts'] = get_posts();
        $this->data['current_user'] = wp_get_current_user();
    }

    public function getIndex()
    {
    	echo $this->view->render('index.php', $this->data);
    }

    public function postIndex()
    {
        $publisher = new EpubPublisher();

        $publisher->setIdentifier($_POST['identifier']);
        $publisher->setTitle($_POST['title']);
        $publisher->setAuthor($_POST['authors']);
        $publisher->setPublisher($_POST['editor']);

        foreach ($this->data['posts'] as $id => $post)
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