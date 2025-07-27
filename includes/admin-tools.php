<?php
/**
 * Admin Utilities for Art Storefront Customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the utilities page under Tools.
 */
function asc_register_utilities_page() {
    add_management_page(
        __( 'Art Storefront Utilities', 'art-storefront-customizer' ),
        __( 'Art Storefront Utilities', 'art-storefront-customizer' ),
        'manage_woocommerce',
        'asc-utilities',
        'asc_render_utilities_page'
    );
}
add_action( 'admin_menu', 'asc_register_utilities_page' );

/**
 * Handle form submissions for bulk updates and conversions.
 */
function asc_handle_utilities_actions() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    if ( empty( $_POST['asc_bulk_nonce'] ) || ! wp_verify_nonce( $_POST['asc_bulk_nonce'], 'asc_bulk_update' ) ) {
        return;
    }

    // Update custom fields.
    if ( isset( $_POST['products'] ) && is_array( $_POST['products'] ) ) {
        foreach ( $_POST['products'] as $product_id => $fields ) {
            $product_id = intval( $product_id );
            if ( isset( $fields['medium'] ) ) {
                update_post_meta( $product_id, 'asc_medium', sanitize_text_field( $fields['medium'] ) );
            }
            if ( isset( $fields['year_created'] ) ) {
                update_post_meta( $product_id, 'asc_year_created', sanitize_text_field( $fields['year_created'] ) );
            }
            if ( isset( $fields['rarity'] ) ) {
                update_post_meta( $product_id, 'asc_rarity', sanitize_text_field( $fields['rarity'] ) );
            }
        }
    }

    // Convert to artwork if requested.
    if ( ! empty( $_POST['convert_to_artwork'] ) ) {
        $product_id = intval( $_POST['convert_to_artwork'] );
        update_post_meta( $product_id, 'asc_is_artwork', 'yes' );

        // Ensure default Artwork category exists and assign it.
        $term = term_exists( 'Artwork', 'product_cat' );
        if ( ! $term ) {
            $term = wp_insert_term( 'Artwork', 'product_cat' );
        }
        if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
            wp_set_object_terms( $product_id, intval( $term['term_id'] ), 'product_cat', true );
        }
    }

    add_action( 'admin_notices', 'asc_utilities_saved_notice' );
}
add_action( 'admin_init', 'asc_handle_utilities_actions' );

function asc_utilities_saved_notice() {
    echo '<div class="updated"><p>' . esc_html__( 'Utilities updated.', 'art-storefront-customizer' ) . '</p></div>';
}

/**
 * Render the utilities admin page.
 */
function asc_render_utilities_page() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    $products = get_posts( [
        'post_type'      => 'product',
        'posts_per_page' => -1,
    ] );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Art Storefront Utilities', 'art-storefront-customizer' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'asc_bulk_update', 'asc_bulk_nonce' ); ?>
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Product', 'art-storefront-customizer' ); ?></th>
                        <th><?php esc_html_e( 'Medium', 'art-storefront-customizer' ); ?></th>
                        <th><?php esc_html_e( 'Year Created', 'art-storefront-customizer' ); ?></th>
                        <th><?php esc_html_e( 'Rarity', 'art-storefront-customizer' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'art-storefront-customizer' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $products as $product ) : ?>
                    <tr>
                        <td><?php echo esc_html( $product->post_title ); ?></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->ID ); ?>][medium]" value="<?php echo esc_attr( get_post_meta( $product->ID, 'asc_medium', true ) ); ?>" /></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->ID ); ?>][year_created]" value="<?php echo esc_attr( get_post_meta( $product->ID, 'asc_year_created', true ) ); ?>" /></td>
                        <td><input type="text" name="products[<?php echo esc_attr( $product->ID ); ?>][rarity]" value="<?php echo esc_attr( get_post_meta( $product->ID, 'asc_rarity', true ) ); ?>" /></td>
                        <td>
                            <button type="submit" class="button" name="convert_to_artwork" value="<?php echo esc_attr( $product->ID ); ?>">
                                <?php esc_html_e( 'Convert to Artwork', 'art-storefront-customizer' ); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'art-storefront-customizer' ); ?>" />
            </p>
        </form>
    </div>
    <?php
}
