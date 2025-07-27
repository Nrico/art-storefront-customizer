<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output artwork detail meta fields on the single product page.
 *
 * Retrieves custom meta values and displays them as a list after the
 * product price. Only values that exist will be shown.
 */
function asc_output_artwork_details() {
    global $product;

    if (!$product instanceof WC_Product) {
        return;
    }

    $post_id = $product->get_id();

    $medium     = get_post_meta($post_id, '_asc_medium', true);
    $year       = get_post_meta($post_id, '_asc_year_created', true);
    $dimensions = get_post_meta($post_id, '_asc_dimensions', true);
    $rarity     = get_post_meta($post_id, '_asc_rarity', true);
    $framed     = get_post_meta($post_id, '_asc_framed', true);
    $coa        = get_post_meta($post_id, '_asc_certificate_of_authenticity', true);
    $shipping   = get_post_meta($post_id, '_asc_shipping_format', true);

    $items = array();

    if ($medium) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Medium', 'art-storefront-customizer'), esc_html($medium));
    }
    if ($year) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Year Created', 'art-storefront-customizer'), esc_html($year));
    }
    if ($dimensions) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Dimensions', 'art-storefront-customizer'), esc_html($dimensions));
    }
    if ($rarity) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Rarity', 'art-storefront-customizer'), esc_html($rarity));
    }
    if ($framed) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Framed', 'art-storefront-customizer'), esc_html($framed));
    }
    if ($coa) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Certificate of Authenticity', 'art-storefront-customizer'), esc_html__('Included', 'art-storefront-customizer'));
    }
    if ($shipping) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Shipping Format', 'art-storefront-customizer'), esc_html($shipping));
    }

    if (!empty($items)) {
        echo '<ul class="asc-artwork-details">' . implode("\n", $items) . '</ul>';
    }
}
add_action('woocommerce_single_product_summary', 'asc_output_artwork_details', 20);
