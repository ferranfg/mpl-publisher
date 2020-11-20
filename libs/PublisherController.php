<?php

namespace MPL\Publisher;

use WP_Query;
use Exception;

class PublisherController extends PublisherBase {

    public function getIndex()
    {
        $query = http_build_query(array_merge(array(
            'posts_per_page' => '-1'
        ), $this->filter));

        $this->data['query'] = new WP_Query($query);

        $this->data['blog_categories'] = $this->getCategories();
        $this->data['blog_authors']    = $this->getAuthors();
        $this->data['blog_tags']       = $this->getTags();
        $this->data['blog_statuses']   = $this->getStatuses();
        $this->data['blog_years']      = $this->getYears();

        $this->data['book_themes']     = $this->getThemes();
        
        $this->data['form_action']     = admin_url('admin-post.php');
        $this->data['wp_nonce_field']  = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

        $this->data['premium_version'] = file_exists(MPL_BASEPATH . '/mpl-publisher.json');
        $this->data['admin_notice']    = array_key_exists('msg', $_GET) ? $_GET['msg'] : null;

        wp_reset_postdata();

        echo $this->view('index.php', $this->data);
    }

    public function postIndex()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || ! check_admin_referer('publish_ebook', '_wpnonce')) return;

        $this->saveStatus($_POST);

        $params = ['page' => 'publisher'];

        try
        {
            if (isset($_POST['generate'])) return $this->generateBook();
        }
        catch (Exception $e)
        {
            $params['msg'] = $e->getMessage();
        }

        return wp_safe_redirect(admin_url('tools.php?' . http_build_query($params)));
    }
}