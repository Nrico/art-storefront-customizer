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
