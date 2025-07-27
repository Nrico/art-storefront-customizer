<?php
/**
 * Plugin Name: Art Storefront Customizer
 * Description: Artist-friendly customizations for WooCommerce.
 * Version: 0.1.0
 * Author: Your Name
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: art-storefront-customizer-main
 * Domain Path: /languages

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
require_once plugin_dir_path(__FILE__) . 'uninstall.php';

register_uninstall_hook(__FILE__, 'asc_customizer_uninstall');


/**
 * Enqueue plugin styles.
 */
function asc_enqueue_styles() {
    wp_enqueue_style(
        'art-storefront-customizer-main',
        plugins_url('assets/style.css', __FILE__),
        array(),
        '0.1.0'
    );
}
add_action('wp_enqueue_scripts', 'asc_enqueue_styles');
