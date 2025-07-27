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

/**
 * Load all plugin files after verifying WooCommerce is active.
 */
function asc_init_plugin() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'asc_wc_inactive_notice');
        return;
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

    add_action('init', 'asc_load_textdomain');
}

/**
 * Display admin notice when WooCommerce is inactive.
 */
function asc_wc_inactive_notice() {
    echo '<div class="notice notice-error"><p>' .
         esc_html__('Art Storefront Customizer requires WooCommerce to be active.', 'art-storefront-customizer') .
         '</p></div>';
}

add_action('plugins_loaded', 'asc_init_plugin');

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
