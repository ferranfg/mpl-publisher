<?php
/**
 * Plugin Name: MPL - Publisher
 * Plugin URI: http://miproximolibro.com
 * Description: MPL - Publisher is a WordPress plugin to help you publish your WordPress posts as an elegant eBooks. Choose your format: PDF, ePub, Mobi... etc.
 * Version: 1.0
 * Author: Ferran Figueredo
 * Author URI: http://miproximolibro.com
 * License: MIT
 */

require 'vendor/autoload.php';

add_action('admin_menu', function ()
{
    $controller = new \MPL\PublisherController(__DIR__);

    add_management_page('MPL - Publisher', 'Publisher', 'manage_options', 'publisher', function () use ($controller)
    {
        if (isset($_POST['submit'])) return $controller->postIndex();

        return $controller->getIndex();
    });
});