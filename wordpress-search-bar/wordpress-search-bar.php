<?php
/*
Plugin Name: Admin Post Lookup Bar
Description: Adds a lookup bar to search for posts by title or content in the admin post editor screen.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Admin_Post_Lookup_Bar {

    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('add_meta_boxes', [$this, 'add_lookup_meta_box']);
        add_action('wp_ajax_post_lookup', [$this, 'ajax_post_lookup']);
    }

    // Enqueue JavaScript and CSS
    public function enqueue_scripts($hook) {
        if ($hook === 'post.php' || $hook === 'post-new.php') {
            wp_enqueue_script('post-lookup-bar', plugin_dir_url(__FILE__) . 'post-lookup-bar.js', ['jquery'], null, true);
            wp_enqueue_style('post-lookup-bar-style', plugin_dir_url(__FILE__) . 'post-lookup-bar.css');
            wp_localize_script('post-lookup-bar', 'postLookup', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('post_lookup_nonce')
            ]);
        }
    }

    // Add the lookup bar meta box
    public function add_lookup_meta_box() {
        add_meta_box('post-lookup-bar', 'Post Lookup', [$this, 'lookup_meta_box_html'], null, 'side', 'high');
    }

    // Meta box HTML
    public function lookup_meta_box_html() {
        echo '<input type="text" id="post-lookup-input" placeholder="Search posts by title or content..." />';
        echo '<div id="post-lookup-results"></div>';
    }

    // AJAX handler for searching posts
    public function ajax_post_lookup() {
        check_ajax_referer('post_lookup_nonce', 'nonce');

        $query = sanitize_text_field($_POST['query']);
        $args = [
            's' => $query,
            'post_type' => 'post',
            'post_status' => 'any',
            'posts_per_page' => 5,
        ];
        
        $query = new WP_Query($args);

        $results = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $results[] = [
                    'title' => get_the_title(),
                    'link' => get_edit_post_link(get_the_ID())
                ];
            }
        }
        wp_reset_postdata();

        wp_send_json($results);
    }
}

new Admin_Post_Lookup_Bar();
