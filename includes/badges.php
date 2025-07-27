<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display custom badges below the product price on single product pages.
 */
function asc_display_product_badges() {
    global $product;

    if (!$product instanceof WC_Product) {
        return;
    }

    $badges = array();

    if (!$product->is_in_stock()) {
        $badges[] = '🟥 Collected';
    }

    $certificate = get_post_meta($product->get_id(), '_asc_certificate_of_authenticity', true);
    if ('1' === $certificate) {
        $badges[] = '✅ Certificate Included';
    }

    $badges[] = '📦 Shipping Included';
    $badges[] = '💯 14-Day Satisfaction Guarantee';

    if (empty($badges)) {
        return;
    }

    echo '<div class="asc-badges">';
    foreach ($badges as $badge) {
        echo '<span class="asc-badge">' . esc_html($badge) . '</span>';
    }
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'asc_display_product_badges', 11);
