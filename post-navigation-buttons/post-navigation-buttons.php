<?php
/**
 * Plugin Name: Post Navigation Buttons
 * Description: Adds "Next" and "Previous" buttons for post navigation in the admin panel.
 * Version: 1.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'edit_form_after_title', 'add_post_navigation_buttons' );

function add_post_navigation_buttons() {
    global $post;

    // Get the previous and next posts
    $prev_post = get_adjacent_post( false, '', true );
    $next_post = get_adjacent_post( false, '', false );

    // Start outputting buttons
    echo '<div class="post-navigation-buttons" style="margin: 20px 0;">';

    // Previous Post Button
    if ( $prev_post ) {
        echo '<a href="' . get_edit_post_link( $prev_post->ID ) . '" class="button button-secondary">Previous Post: ' . esc_html( get_the_title( $prev_post->ID ) ) . '</a>';
    }

    // Next Post Button
    if ( $next_post ) {
        echo '<a href="' . get_edit_post_link( $next_post->ID ) . '" class="button button-secondary">Next Post: ' . esc_html( get_the_title( $next_post->ID ) ) . '</a>';
    }

    echo '</div>';
}
