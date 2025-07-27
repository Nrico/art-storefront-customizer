<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Override WooCommerce labels based on plugin settings
 */
add_filter('gettext', 'asc_override_woocommerce_labels', 20, 3);

function asc_override_woocommerce_labels($translated_text, $text, $domain) {
    // Only override WooCommerce strings
    if ($domain === 'woocommerce') {
        $settings = asc_get_settings();

        // Only modify labels when collector mode is enabled
        if (empty($settings['enable_collector_mode'])) {
            return $translated_text;
        }

        // Custom "Add to cart" replacement
        if ($text === 'Add to cart' && !empty($settings['add_to_cart_label'])) {
            return esc_html($settings['add_to_cart_label']);
        }

        // Custom "Out of stock" replacement
        if ($text === 'Out of stock' && !empty($settings['out_of_stock_label'])) {
            return esc_html($settings['out_of_stock_label']);
        }
    }

    return $translated_text;
}
