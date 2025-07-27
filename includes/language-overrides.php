<?php
/**
 * Overrides WooCommerce text labels using custom settings.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Replace default WooCommerce labels with custom values from plugin settings.
 *
 * @param string $translated  Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string Modified text.
 */
function asc_override_woocommerce_labels($translated, $text, $domain)
{
    if ($domain !== 'woocommerce') {
        return $translated;
    }

    $settings = get_option('asc_settings', array());

    if ($text === 'Add to cart' && !empty($settings['cart_label'])) {
        return $settings['cart_label'];
    }

    if ($text === 'Out of stock' && !empty($settings['sold_label'])) {
        return $settings['sold_label'];
    }

    return $translated;
}
add_filter('gettext', 'asc_override_woocommerce_labels', 20, 3);
