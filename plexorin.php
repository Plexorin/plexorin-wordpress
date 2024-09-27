<?php
/*
Plugin Name: Blog Yazılarını Sosyal Medyada Otomatik Paylaşın - Plexorin
Description: WordPress blog içerikleriniz otomatik olarak anında sosyal medya hesaplarınızda paylaşılsın! WordPress to Social.
Version: 1.0.3
Author: Plexorin
Author URI: https://plexorin.com/tr/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to run when the plugin is activated
function bts_plexorin_activate() {
    // Get all users and categories
    $users = get_users(array('fields' => array('ID')));
    $user_ids = array_map(function($user) { return $user->ID; }, $users);
    
    $categories = get_categories(array('fields' => 'ids'));
    
    // Save default settings
    add_option('bts_plexorin_settings', array(
        'users' => $user_ids,
        'categories' => $categories,
        'default_title' => '',
        'default_description' => '',
        'default_image' => ''
    ));
}
register_activation_hook(__FILE__, 'bts_plexorin_activate');

// Function to run when the plugin is deactivated
/*function plexorin_deactivate() {
    // Delete settings
    delete_option('plexorin_settings');
}
register_deactivation_hook(__FILE__, 'plexorin_deactivate');*/

function bts_plexorin_admin_enqueue_scripts($hook) {
    if ($hook != 'toplevel_page_bts-plexorin-settings') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'bts_plexorin_admin_enqueue_scripts');

// Include admin settings page
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';

// Include meta box and post scheduler
require_once plugin_dir_path(__FILE__) . 'includes/meta-box.php';
require_once plugin_dir_path(__FILE__) . 'includes/post-scheduler.php';
