<?php
/**
 * Plugin Name: Elementor Custom CSS for Elements
 * Description: Allows users to enter custom CSS for specific elements in Elementor.
 * Version: 1.0
 * Author: Tsur Levy 2024
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue custom JS and CSS
function enqueue_custom_css_scripts() {
    wp_enqueue_script( 'elementor-custom-css', plugin_dir_url( __FILE__ ) . 'assets/js/custom-css.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_css_scripts' );

// Add custom control in Elementor
function register_custom_css_control( $element ) {
    // Add the custom control under the advanced tab
    $element->start_controls_section(
        'custom_css_section',
        [
            'label' => __( 'Custom CSS', 'plugin-name' ),
            'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
    );

    $element->add_control(
        'custom_css_input',
        [
            'label'       => __( 'Custom CSS', 'plugin-name' ),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'description' => __( 'Enter custom CSS to apply to this element.', 'plugin-name' ),
        ]
    );

    $element->end_controls_section();
}
add_action( 'elementor/element/common/_section_style/after_section_end', 'register_custom_css_control', 10, 1 );

// Apply custom CSS
function apply_custom_css( $post_css, $element ) {
    $custom_css = $element->get_settings_for_display( 'custom_css_input' );
    
    if ( ! empty( $custom_css ) ) {
        $css = sprintf( '%s { %s }', $element->get_unique_selector(), $custom_css );
        $post_css->get_stylesheet()->add_raw_css( $css );
    }
}
add_action( 'elementor/css-file/post/parse', 'apply_custom_css', 10, 2 );

