<?php

namespace MPL\Publisher;

use WP_Query;
use Exception;

class PublisherController extends PublisherBase {

    public function getIndex()
    {
        $this->data['query'] = $this->getQuery();

        $this->data['blog_categories'] = $this->getCategories();
        $this->data['blog_authors']    = $this->getAuthors();
        $this->data['blog_tags']       = $this->getTags();
        $this->data['blog_statuses']   = $this->getStatuses();
        $this->data['blog_years']      = $this->getYears();
        $this->data['blog_months']     = $this->getMonths();

        $this->data['book_themes']     = $this->getThemes();
        
        $this->data['form_action']     = admin_url('admin-post.php');
        $this->data['wp_nonce_field']  = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

        $this->data['mpl_is_premium']  = mpl_is_premium();
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

        if (isset($_POST['save'])) $params['msg'] = '✅ ' . __('Changes successfully saved.', 'publisher');

        try
        {
            if (isset($_POST['generate'])) return $this->generateBook();
        }
        catch (Exception $e)
        {
            $params['msg'] = $e->getMessage();
        }

        return wp_safe_redirect(admin_url('admin.php?' . http_build_query($params)));
    }
}