<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register taxonomy for relating products to artists.
 */
function asc_register_associated_artist_taxonomy() {
    $labels = array(
        'name'          => __('Associated Artists', 'art-storefront-customizer'),
        'singular_name' => __('Associated Artist', 'art-storefront-customizer'),
        'search_items'  => __('Search Artists', 'art-storefront-customizer'),
        'all_items'     => __('All Artists', 'art-storefront-customizer'),
        'edit_item'     => __('Edit Artist', 'art-storefront-customizer'),
        'update_item'   => __('Update Artist', 'art-storefront-customizer'),
        'add_new_item'  => __('Add New Artist', 'art-storefront-customizer'),
        'new_item_name' => __('New Artist Name', 'art-storefront-customizer'),
        'menu_name'     => __('Associated Artists', 'art-storefront-customizer'),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => false,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    );

    // Attach to products and artist profiles so they can share terms.
    register_taxonomy('associated_artist', array('product', 'asc_artist_profile'), $args);
}
add_action('init', 'asc_register_associated_artist_taxonomy');
