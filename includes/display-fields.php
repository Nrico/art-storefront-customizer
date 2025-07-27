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

    $settings = asc_get_settings();

    $medium     = get_post_meta($post_id, '_asc_medium', true);
    $year       = get_post_meta($post_id, '_asc_year_created', true);
    $dimensions = get_post_meta($post_id, '_asc_dimensions', true);
    $rarity     = get_post_meta($post_id, '_asc_rarity', true);
    $framed     = get_post_meta($post_id, '_asc_framed', true);
    $coa        = get_post_meta($post_id, '_asc_certificate_of_authenticity', true);
    $shipping   = get_post_meta($post_id, '_asc_shipping_format', true);
    $frame      = get_post_meta($post_id, '_asc_frame_option', true);
    $edition_no = get_post_meta($post_id, '_asc_edition_number', true);
    $edition_sz = get_post_meta($post_id, '_asc_edition_size', true);

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
    if (!empty($settings['enable_framing_options']) && $frame) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Frame Option', 'art-storefront-customizer'), esc_html($frame));
    }
    if ($coa) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Certificate of Authenticity', 'art-storefront-customizer'), esc_html__('Included', 'art-storefront-customizer'));
    }
    if ($shipping) {
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Shipping Format', 'art-storefront-customizer'), esc_html($shipping));
    }
    if (!empty($settings['enable_edition_print_fields']) && ($edition_no || $edition_sz || $rarity === 'open-edition' || $rarity === 'limited-edition')) {
        $parts = array();
        if ($edition_no && $edition_sz) {
            $parts[] = $edition_no . ' / ' . $edition_sz;
        } elseif ($edition_no) {
            $parts[] = $edition_no;
        } elseif ($edition_sz) {
            $parts[] = __('of', 'art-storefront-customizer') . ' ' . $edition_sz;
        }

        if ($rarity === 'open-edition') {
            $parts[] = __('Open Edition', 'art-storefront-customizer');
        } elseif ($rarity === 'limited-edition' && empty($edition_no) && empty($edition_sz)) {
            $parts[] = __('Closed Edition', 'art-storefront-customizer');
        } elseif ($rarity === 'limited-edition') {
            $parts[] = __('Closed Edition', 'art-storefront-customizer');
        }

        $value = implode(' ', $parts);
        $items[] = sprintf('<li><strong>%s:</strong> %s</li>', esc_html__('Edition', 'art-storefront-customizer'), esc_html($value));
    }

    if (!empty($items)) {
        echo '<ul class="asc-artwork-details">' . implode("\n", $items) . '</ul>';
    }
}
add_action('woocommerce_single_product_summary', 'asc_output_artwork_details', 20);
