<?php
// Override WooCommerce templates with plugin versions

add_filter('woocommerce_locate_template', 'asc_override_product_template', 10, 3);

/**
 * Use custom template for single product pages if available.
 *
 * @param string $template      Default template path.
 * @param string $template_name Template name requested by WooCommerce.
 * @param string $template_path Template path within WooCommerce.
 * @return string Modified template path.
 */
function asc_override_product_template($template, $template_name, $template_path) {
    if ($template_name === 'single-product.php') {
        $custom = plugin_dir_path(__FILE__) . '../templates/single-product-artwork.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }
    return $template;
}
