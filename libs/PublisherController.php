<?php

namespace MPL\Publisher;

use Exception;

class PublisherController extends PublisherBase {

    public function getIndex()
    {
        $this->data['query'] = $this->getQuery(
            array_key_exists('order_asc', $this->data) ? $this->data['order_asc'] : true
        );

        $this->data['blog_categories'] = $this->getCategories();
        $this->data['blog_authors']    = $this->getAuthors();
        $this->data['blog_tags']       = $this->getTags();
        $this->data['blog_statuses']   = $this->getStatuses();
        $this->data['blog_years']      = $this->getYears();
        $this->data['blog_months']     = $this->getMonths();

        $this->data['book_themes']     = $this->getThemes();

        $this->data['form_action']     = admin_url('admin-post.php');
        $this->data['wp_nonce_field']  = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

        wp_reset_postdata();

        echo $this->view('index.php', $this->data);
    }

    public function postIndex()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || ! check_admin_referer('publish_ebook', '_wpnonce')) return;

        $params = ['page' => 'publisher'];

        if (array_key_exists('license', $_POST))
        {
            $this->saveLicense($_POST['license']);

            unset($_POST['license']);
        }

        if (array_key_exists('clear', $_POST))
        {
            $this->removeStatus($_POST['book_id']);

            $params['msg'] = 'ℹ️ ' . __('Book successfully removed.', 'publisher');
        }

        if (array_key_exists('create', $_POST))
        {
            $book_id = uniqid();

            $this->saveStatus($this->getBookDefaults(), $book_id);

            $params['book_id'] = $book_id;
            $params['msg'] = '✅ ' . __('Book successfully created.', 'publisher');
        }

        if (array_key_exists('save', $_POST) or array_key_exists('filter', $_POST) or array_key_exists('order', $_POST))
        {
            $book_id = $_POST['book_id'];

            // Toggle current order value
            if (array_key_exists('order', $_POST)) $_POST['order_asc'] = ! $_POST['order_asc'];

            $this->saveStatus($_POST, $book_id);

            $params['book_id'] = $book_id;

            if (array_key_exists('save', $_POST)) 
            {
                $params['msg'] = '✅ ' . __('Changes successfully saved.', 'publisher');
            }
        }

        try
        {
            if (array_key_exists('generate', $_POST))
            {
                $this->saveStatus($_POST, $_POST['book_id']);

                return $this->generateBook();
            }
        }
        catch (Exception $e)
        {
            $params['msg'] = $e->getMessage();
        }

        return wp_safe_redirect(mpl_admin_url($params));
    }

    public function getMarketplace()
    {
        echo $this->view('marketplace.php', $this->data);
    }

    public function getCoverEditor()
    {
        echo $this->view('cover-editor.php', $this->data);
    }
}