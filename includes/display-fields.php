<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output artwork detail meta fields on the product page.
 *
 * Retrieves custom meta values and displays them in the WooCommerce
 * "Additional Information" section. Only values that exist will be
 * shown.
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

    $rows = array();

    if ($medium) {
        $rows[] = array(
            'label' => __('Medium', 'art-storefront-customizer'),
            'value' => $medium,
        );
    }
    if ($year) {
        $rows[] = array(
            'label' => __('Year Created', 'art-storefront-customizer'),
            'value' => $year,
        );
    }
    if ($dimensions) {
        $rows[] = array(
            'label' => __('Dimensions', 'art-storefront-customizer'),
            'value' => $dimensions,
        );
    }
    if ($rarity) {
        $rows[] = array(
            'label' => __('Rarity', 'art-storefront-customizer'),
            'value' => $rarity,
        );
    }
    if ($framed) {
        $rows[] = array(
            'label' => __('Framed', 'art-storefront-customizer'),
            'value' => $framed,
        );
    }
    if (!empty($settings['enable_framing_options']) && $frame) {
        $rows[] = array(
            'label' => __('Frame Option', 'art-storefront-customizer'),
            'value' => $frame,
        );
    }
    if ($coa) {
        $rows[] = array(
            'label' => __('Certificate of Authenticity', 'art-storefront-customizer'),
            'value' => __('Included', 'art-storefront-customizer'),
        );
    }
    if ($shipping) {
        $rows[] = array(
            'label' => __('Shipping Format', 'art-storefront-customizer'),
            'value' => $shipping,
        );
    }
    if (!empty($settings['enable_edition_print_fields']) && ($edition_no || $edition_sz)) {
        $value   = trim($edition_no . ($edition_sz ? ' / ' . $edition_sz : ''));
        $rows[] = array(
            'label' => __('Edition', 'art-storefront-customizer'),
            'value' => $value,
        );
    }

    if (!empty($rows)) {
        echo '<table class="shop_attributes asc-artwork-details">';
        foreach ($rows as $row) {
            echo '<tr>'
                . '<th class="woocommerce-product-attributes-item__label">' . esc_html($row['label']) . '</th>'
                . '<td class="woocommerce-product-attributes-item__value">' . esc_html($row['value']) . '</td>'
                . '</tr>';
        }
        echo '</table>';
    }
}
add_action('woocommerce_product_additional_information', 'asc_output_artwork_details', 20);
