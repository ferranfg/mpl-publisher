<?php

namespace MPL\Publisher;

use Exception;

class PublisherController extends PublisherBase {

    public function getIndex()
    {
        $this->data['query'] = $this->getQuery(
            array_key_exists('order_asc', $this->data) ? $this->data['order_asc'] : true,
            array_key_exists('selected_posts', $this->data) ? (array) $this->data['selected_posts'] : array()
        );

        $this->data['blog_categories'] = $this->getCategories();
        $this->data['blog_tags']       = $this->getTags();
        $this->data['blog_statuses']   = $this->getStatuses();
        $this->data['blog_years']      = $this->getYears();
        $this->data['blog_months']     = $this->getMonths();

        $this->data['book_themes']     = $this->getThemes();

        $this->data['form_action']     = admin_url('admin-post.php');
        $this->data['wp_nonce_field']  = wp_nonce_field('publish_ebook', '_wpnonce', true, false);

        wp_reset_postdata();

        set_transient('mpl_msg', null);

        echo $this->view('index.php', stripslashes_deep($this->data));
    }

    public function postIndex()
    {
        // http://codex.wordpress.org/Function_Reference/check_admin_referer
        if (empty($_POST) || ! check_admin_referer('publish_ebook', '_wpnonce')) return;

        $params = ['page' => 'publisher'];

        if (array_key_exists('license', $_POST))
        {
            $this->saveLicense(sanitize_text_field($_POST['license']));

            unset($_POST['license']);
        }

        if (array_key_exists('max_posts', $_POST))
        {
            $this->saveMaxPosts(sanitize_text_field($_POST['max_posts']));

            unset($_POST['max_posts']);
        }

        // Remove a book
        if (array_key_exists('clear', $_POST))
        {
            $this->removeStatus(sanitize_text_field($_POST['book_id']));

            set_transient('mpl_msg', 'ℹ️ ' . __('Book successfully removed.', 'publisher'));
        }

        // Create a new book
        if (array_key_exists('create', $_POST))
        {
            $clean_data = $this->getBookDefaults();
            $book_id = uniqid();

            $clean_data['book_id'] = $book_id;
            $params['book_id'] = $book_id;

            $this->saveStatus($clean_data, $book_id);

            set_transient('mpl_msg', '✅ ' . __('Book successfully created.', 'publisher'));
        }

        // Clone a book
        if (array_key_exists('clone', $_POST))
        {
            $clean_data = mpl_sanitize_array($_POST);
            $book_id = uniqid();

            $clean_data['book_id'] = $book_id;
            $params['book_id'] = $book_id;

            $this->saveStatus($clean_data, $book_id);

            set_transient('mpl_msg', '✅ ' . __('Book successfully cloned.', 'publisher'));
        }

        // Save the book
        if (array_key_exists('save', $_POST) or array_key_exists('filter', $_POST) or array_key_exists('order', $_POST))
        {
            // Toggle current order value
            if (array_key_exists('order', $_POST)) $_POST['order_asc'] = ! $_POST['order_asc'];

            $clean_data = mpl_sanitize_array($_POST);
            $book_id = sanitize_text_field($_POST['book_id']);

            $clean_data['book_id'] = $book_id;
            $params['book_id'] = $book_id;

            $this->saveStatus($clean_data, $book_id);

            if (array_key_exists('save', $_POST))
            {
                set_transient('mpl_msg', '✅ ' . __('Changes successfully saved.', 'publisher'));
            }
        }

        try
        {
            if ( ! wp_is_writable(get_temp_dir()))
            {
                throw new Exception(__('Missing a temporary folder.'));
            }

            if (array_key_exists('generate', $_POST))
            {
                $clean_data = mpl_sanitize_array($_POST);
                $book_id = sanitize_text_field($_POST['book_id']);

                $this->saveStatus($clean_data, $book_id);

                return $this->generateBook();
            }
        }
        catch (Exception $e)
        {
            set_transient('mpl_msg', $e->getMessage());
        }

        return wp_safe_redirect(mpl_admin_url($params));
    }

    public function getMarketplace()
    {
        echo $this->view('marketplace.php', stripslashes_deep($this->data));
    }

    public function getCoverEditor()
    {
        echo $this->view('cover-editor.php', stripslashes_deep($this->data));
    }

    public function postAjaxDuplicate()
    {
        // Check the nonce
        check_ajax_referer('mpl_ajax_file_nonce', 'security');

        // Get variables
        $original_id = sanitize_text_field($_POST['original_id']);

        // Get the post as an array
        $duplicate = get_post($original_id, 'ARRAY_A');

        $duplicate['post_type'] = 'mpl_chapter';

        // Remove some of the keys
        unset($duplicate['ID']);
        unset($duplicate['guid']);
        unset($duplicate['comment_count']);

        // Handles guttenburg escaping in returns for blocks
        $duplicate['post_content'] = str_replace(array('\r\n', '\r', '\n'), '<br />', $duplicate['post_content']);

        // Insert the post into the database
        $duplicate_id = wp_insert_post( $duplicate );

        echo $duplicate_id;

        die(); // this is required to return a proper result
    }
}