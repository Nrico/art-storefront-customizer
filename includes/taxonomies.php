<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom taxonomies for WooCommerce products.
 */
function asc_register_product_taxonomies() {
    $taxonomies = array(
        'associated_artist' => array(
            'singular' => __('Associated Artist', 'art-storefront-customizer'),
            'plural'   => __('Associated Artists', 'art-storefront-customizer'),
        ),
        'art_style' => array(
            'singular' => __('Art Style', 'art-storefront-customizer'),
            'plural'   => __('Art Styles', 'art-storefront-customizer'),
        ),
        'subject_matter' => array(
            'singular' => __('Subject', 'art-storefront-customizer'),
            'plural'   => __('Subjects', 'art-storefront-customizer'),
        ),
    );

    foreach ($taxonomies as $slug => $labels) {
        register_taxonomy(
            $slug,
            array('product'),
            array(
                'hierarchical'      => true,
                'public'            => true,
                'show_admin_column' => true,
                'labels'            => array(
                    'name'              => $labels['plural'],
                    'singular_name'     => $labels['singular'],
                    'search_items'      => sprintf(__('Search %s', 'art-storefront-customizer'), $labels['plural']),
                    'all_items'         => sprintf(__('All %s', 'art-storefront-customizer'), $labels['plural']),
                    'edit_item'         => sprintf(__('Edit %s', 'art-storefront-customizer'), $labels['singular']),
                    'view_item'         => sprintf(__('View %s', 'art-storefront-customizer'), $labels['singular']),
                    'update_item'       => sprintf(__('Update %s', 'art-storefront-customizer'), $labels['singular']),
                    'add_new_item'      => sprintf(__('Add New %s', 'art-storefront-customizer'), $labels['singular']),
                    'new_item_name'     => sprintf(__('New %s Name', 'art-storefront-customizer'), $labels['singular']),
                    'parent_item'       => sprintf(__('Parent %s', 'art-storefront-customizer'), $labels['singular']),
                    'parent_item_colon' => sprintf(__('Parent %s:', 'art-storefront-customizer'), $labels['singular']),
                    'menu_name'         => $labels['plural'],
                ),
            )
        );
    }
}
add_action('init', 'asc_register_product_taxonomies');
