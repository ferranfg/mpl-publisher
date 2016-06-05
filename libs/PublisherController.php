<?php

namespace MPL\Publisher;

class PublisherController extends PublisherBase {

    public function getIndex()
    {
        $query = http_build_query(array_merge(array(
            'posts_per_page' => '-1'
        ), $this->filter));

        $this->data['query'] = new \WP_Query($query);

        $this->data['blog_categories'] = $this->getCategories();
        $this->data['blog_authors']    = $this->getAuthors();
        $this->data['blog_tags']       = get_tags();
        $this->data['blog_statuses']   = get_post_stati();
        
        $this->data['form_action']     = admin_url('admin-post.php');
        $this->data['wp_nonce_field']  = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

        wp_reset_postdata();

    	echo $this->view('index.php', $this->data);
    }

    public function postIndex()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || !check_admin_referer('publish_ebook', '_wpnonce')) return;

        $this->saveStatus($_POST);

        if (isset($_POST['generate'])) return $this->generateBook();
        
        return wp_redirect(admin_url('tools.php?page=publisher'));
    }
}