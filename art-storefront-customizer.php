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
require_once plugin_dir_path(__FILE__) . 'uninstall.php';

register_uninstall_hook(__FILE__, 'asc_customizer_uninstall');

/**
 * Load plugin textdomain for translations.
 */
function asc_load_textdomain() {
    load_plugin_textdomain(
        'art-storefront-customizer',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}
add_action('init', 'asc_load_textdomain');
