<?php
/**
 * Uninstall routines for the Art Storefront Customizer plugin.
 */


// This file is included by the main plugin during normal execution.
// We can't bail out early if `WP_UNINSTALL_PLUGIN` is undefined,
// otherwise the plugin would stop loading. Instead, the uninstall
// routine itself checks for the constant before running.

/**
 * Delete all post meta for each custom key.
 */
function asc_customizer_uninstall() {
    // Only run when WordPress is actually uninstalling the plugin.
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        return;
    }

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


