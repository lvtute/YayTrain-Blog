<?php
/**
 * Plugin Name:     Train Yay Blog
 * Plugin URI:      https://yaycommerce.com/
 * Description:     Starter plugin. Training basic WordPress plugin development.
 * Author:          Yay Commerce
 * Author URI:      https://yaycommerce.com/
 * Text Domain:     yayblog
 * Domain Path:     /languages
 * Version:         1.0.0.3
 *
 * @package Yayblog
 */

if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}

define( 'YAY_BLOG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! wp_installing() ) {
    add_action(
        'plugins_loaded',
        function () {
            include YAY_BLOG_PLUGIN_PATH . 'includes/admin/settings.php';
            include YAY_BLOG_PLUGIN_PATH . 'includes/admin/edit-star.php';
            include YAY_BLOG_PLUGIN_PATH . 'includes/frontend/post.php';
            include YAY_BLOG_PLUGIN_PATH . 'includes/frontend/view-counter.php';
        }
    );
}