<?php

namespace MPL\Publisher;

class PublisherController extends PublisherBase {

    public function getIndex()
    {
        $this->data['books'] = $this->getStatus();

        echo $this->view('index.php', $this->data);
    }

    public function getView($index)
    {
        $this->setBookIndex($index);

        $query = http_build_query(array_merge(array(
            'posts_per_page' => '-1',
            'post_status'    => 'publish,private'
        ), $this->filter));

        $this->data['query'] = new \WP_Query($query);

        $this->data['blog_categories'] = $this->getCategories();
        $this->data['blog_authors']    = $this->getAuthors();
        $this->data['blog_tags']       = get_tags();
        
        $this->data['form_action']     = admin_url('admin-post.php');
        $this->data['wp_nonce_field']  = wp_nonce_field('publish_ebook', '_wpnonce', true, false);
        $this->data['index']           = $index;

        wp_reset_postdata();

    	echo $this->view('view.php', $this->data);
    }

    public function postView()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || !check_admin_referer('publish_ebook', '_wpnonce')) return;

        $this->saveStatus($_POST, $_POST['index']);

        if (isset($_POST['generate'])) return $this->generateBook();

        return wp_redirect(admin_url('tools.php?page=publisher'));
    }
}