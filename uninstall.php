<?php
/**
 * Uninstall routines for the Art Storefront Customizer plugin.
 */

// Exit if accessed directly or uninstall not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Delete all post meta for each custom key.
 */
function asc_customizer_uninstall() {
    // Delete plugin options.
    delete_option( 'asc_settings' );

    $meta_keys = array(
        '_asc_medium',
        '_asc_year_created',
        '_asc_dimensions',
        '_asc_rarity',
        '_asc_framed',
        '_asc_certificate_of_authenticity',
        '_asc_shipping_format',
    );

    foreach ( $meta_keys as $meta_key ) {
        delete_post_meta_by_key( $meta_key );
    }
}


