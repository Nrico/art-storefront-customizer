<?php
/**
 * Plugin Name: Art Storefront Customizer
 * Description: Artist-friendly customizations for WooCommerce.
 * Version: 0.1.0
 * Author: Your Name
 * Text Domain: art-storefront-customizer
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/display-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'includes/badges.php';
require_once plugin_dir_path(__FILE__) . 'includes/artist-profile.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-tools.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/language-overrides.php';
require_once plugin_dir_path(__FILE__) . 'includes/template-overrides.php';

/**
 * Enqueue plugin styles.
 */
function asc_enqueue_styles() {
    wp_enqueue_style(
        'art-storefront-customizer',
        plugins_url('assets/style.css', __FILE__)
    );
}
add_action('wp_enqueue_scripts', 'asc_enqueue_styles');
