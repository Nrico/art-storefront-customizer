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
        $badges[] = __('🔴 Collected', 'art-storefront-customizer-main');
    }

    $certificate = get_post_meta($product->get_id(), '_asc_certificate_of_authenticity', true);
    if ('1' === $certificate) {
        $badges[] = __('✅ Certificate Included', 'art-storefront-customizer-main');
    }

    $settings = asc_get_settings();

    $shipping_format = get_post_meta($product->get_id(), '_asc_shipping_format', true);
    if (!empty($shipping_format) && !empty($settings['display_shipping_badge'])) {
        $badges[] = __('📦 Shipping Included', 'art-storefront-customizer-main');
    }

    if (!empty($settings['display_guarantee_badge'])) {
        $badges[] = __('💯 14-Day Satisfaction Guarantee', 'art-storefront-customizer-main');
    }

    if (empty($badges)) {
        return;
    }

    echo '<div class="asc-badges">';
    foreach ($badges as $badge) {
        echo '<span class="asc-badge">' . esc_html($badge) . '</span>';
    }
    echo '</div>';
}
add_action('art_storefront_product_badges', 'asc_display_product_badges');
