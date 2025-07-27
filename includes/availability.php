<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Hide default out-of-stock notices for unique artworks.
 *
 * @param string     $html    Availability HTML.
 * @param WC_Product $product WooCommerce product.
 * @return string Modified HTML.
 */
function asc_hide_unique_out_of_stock_html($html, $product) {
    $rarity = get_post_meta($product->get_id(), '_asc_rarity', true);
    if (!$product->is_in_stock() && $rarity === 'one-of-a-kind') {
        return '';
    }
    return $html;
}
add_filter('woocommerce_get_stock_html', 'asc_hide_unique_out_of_stock_html', 10, 2);

/**
 * Hide out-of-stock text string for unique artworks.
 *
 * @param string     $text    Availability text.
 * @param WC_Product $product WooCommerce product.
 * @return string Modified text.
 */
function asc_hide_unique_availability_text($text, $product) {
    $rarity = get_post_meta($product->get_id(), '_asc_rarity', true);
    if (!$product->is_in_stock() && $rarity === 'one-of-a-kind') {
        return '';
    }
    return $text;
}
add_filter('woocommerce_get_availability_text', 'asc_hide_unique_availability_text', 10, 2);

/**
 * Replace the price HTML with a collected label when the product is out of stock.
 *
 * @param string     $price_html Price HTML.
 * @param WC_Product $product    WooCommerce product.
 * @return string Modified HTML.
 */
function asc_replace_out_of_stock_price_html($price_html, $product) {
    if (!$product->is_in_stock()) {
        $settings = asc_get_settings();
        $label = !empty($settings['out_of_stock_label']) ? $settings['out_of_stock_label'] : __('Collected', 'art-storefront-customizer');
        return '<span class="asc-collected">' . esc_html($label) . '</span>';
    }
    return $price_html;
}
add_filter('woocommerce_get_price_html', 'asc_replace_out_of_stock_price_html', 10, 2);
