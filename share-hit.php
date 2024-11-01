<?php
/*
Plugin Name: Share Hit
Description: Displays a share popup when any element with the class `share-blog-hit` is clicked.
Version: 1.0
Author: Hitesh Lendi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue the necessary styles and scripts
function share_hit_enqueue_scripts() {
    $version = '1.0'; // You can update this version number whenever you make changes

    // Use file modification time for versioning (optional, but helps for cache busting)
    $style_version = filemtime(plugin_dir_path(__FILE__) . 'style.css');
    $script_version = filemtime(plugin_dir_path(__FILE__) . 'script.js');

    wp_enqueue_style('share_hit_style', plugins_url('style.css', __FILE__), array(), $style_version);
    wp_enqueue_script('share_hit_script', plugins_url('script.js', __FILE__), array('jquery'), $script_version, true);

    // Localize script to pass AJAX URL and nonce to JavaScript
    wp_localize_script('share_hit_script', 'shareHitPopup', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('share_hit_popup_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'share_hit_enqueue_scripts');

// Add the share popup HTML to the footer
function share_hit_add_html() {
    if (is_single() || is_page()) { // Ensure it is used on single posts/pages
        echo '
        <div class="share-hit-popup">
            <div class="share-hit-popup-content">
                <span class="share-hit-popup-close">&times;</span>
                <div class="share-hit-popup-buttons">
                    <a href="#" class="share-hit-button share-facebook" data-share-url="">Share on Facebook</a>
                    <a href="#" class="share-hit-button share-twitter" data-share-url="">Share on Twitter</a>
                    <a href="#" class="share-hit-button share-email" data-share-url="">Share via Email</a>
                </div>
            </div>
        </div>';
    }
}
add_action('wp_footer', 'share_hit_add_html');

// Handle AJAX requests
function share_hit_handle_share() {
    // Verify nonce
    check_ajax_referer('share_hit_popup_nonce', 'nonce');
    
    // Sanitize input
    $url = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';

    // Example: Handle the sharing logic (e.g., logging, tracking, etc.)
    if ($url) {
        wp_send_json_success('Share URL received: ' . $url);
    } else {
        wp_send_json_error('Invalid URL');
    }
}
add_action('wp_ajax_share_hit_handle_share', 'share_hit_handle_share');
add_action('wp_ajax_nopriv_share_hit_handle_share', 'share_hit_handle_share');
