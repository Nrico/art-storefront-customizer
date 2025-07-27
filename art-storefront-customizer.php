<?php
/**
 * Plugin Name: Art Storefront Customizer
 * Description: Customizes WooCommerce product availability for fine art sales. Replaces "Out of stock" with "Collected Work" and adds a red dot.
 * Version: 1.1
 * Author: El Trujillo
 * Author URI: https://eltrujillo.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: art-storefront-customizer
 * Domain Path: /languages
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/artist-profile.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'uninstall.php';

/**
 * Load plugin textdomain for translations.
 */
function asc_load_textdomain() {
    load_plugin_textdomain(
        'art-storefront-customizer',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'init', 'asc_load_textdomain' );

/**
 * Include WooCommerce dependent functionality when WooCommerce is active.
 */
function asc_include_woocommerce_features() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/display-fields.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/badges.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/admin-tools.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/language-overrides.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/template-overrides.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/availability.php';
}
add_action( 'plugins_loaded', 'asc_include_woocommerce_features' );

register_uninstall_hook( __FILE__, 'asc_customizer_uninstall' );

/**
 * Replace "Out of stock" text with "Collected Work" and add a red dot in WooCommerce
 */
function art_storefront_customizer_change_availability_text( $availability, $product ) {
    if ( isset( $availability['availability'] ) && strtolower( $availability['availability'] ) === 'out of stock' ) {
        // Red dot SVG (small, accessible, inline)
        $red_dot_svg = '<span class="artstorefront-red-dot" aria-label="Collected Work" title="Collected Work" style="display:inline-block;vertical-align:middle;margin-right:6px;"><svg width="14" height="14" viewBox="0 0 14 14" style="display:inline;" aria-hidden="true"><circle cx="7" cy="7" r="6" fill="#d40000"/></svg></span>';
        $availability['availability'] = $red_dot_svg . __('Collected Work', 'art-storefront-customizer');
    }
    return $availability;
}
add_filter( 'woocommerce_get_availability', 'art_storefront_customizer_change_availability_text', 10, 2 );

/**
 * Optional: Change CSS class for collected items (for styling)
 */
function art_storefront_customizer_out_of_stock_class( $class, $product ) {
    if ( ! $product->is_in_stock() ) {
        $class = 'collected-work';
    }
    return $class;
}
add_filter( 'woocommerce_stock_html_class', 'art_storefront_customizer_out_of_stock_class', 10, 2 );

/**
 * Optional: Translate the "Out of stock" string in other places (such as product loops)
 */
function art_storefront_customizer_translate_strings( $translated, $text, $domain ) {
    if ( $domain === 'woocommerce' && strtolower( $text ) === 'out of stock' ) {
        return __( 'Collected Work', 'art-storefront-customizer' );
    }
    return $translated;
}
add_filter( 'gettext', 'art_storefront_customizer_translate_strings', 10, 3 );

/**
 * Custom CSS for Collected Work (red dot and text style)
 */
function art_storefront_customizer_custom_css() {
    ?>
    <style>
    .collected-work,
    .artstorefront-red-dot + .collected-work {
        color: #d40000 !important;
        font-weight: bold;
        vertical-align: middle;
    }
    .artstorefront-red-dot {
        margin-right: 6px;
    }
    </style>
    <?php
}
add_action( 'wp_head', 'art_storefront_customizer_custom_css' );
