<?php
/**
 * Template Name: Single Product Artwork
 *
 * Custom WooCommerce product template for artwork items.
 * Displays product information in a two-column layout with
 * title, price and badges first.
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

global $product;
?>

<section class="product-summary">
    <h1 class="product_title entry-title"><?php the_title(); ?></h1>
    <?php do_action( 'art_storefront_product_badges' ); ?>
    <div class="product-price">
        <?php wc_get_template( 'single-product/price.php' ); ?>
    </div>
</section>

<section class="product-main columns-2">
    <div class="column column-image">
        <?php
        /**
         * Show product images
         */
        woocommerce_show_product_images();
        ?>
    </div>
    <div class="column column-details">
        <?php
        /**
         * Product details on the right
         */
        woocommerce_template_single_excerpt();
        woocommerce_template_single_add_to_cart();
        woocommerce_template_single_meta();
        ?>
    </div>
</section>

<section class="product-about">
    <h2>About the Artwork</h2>
    <?php the_content(); ?>
</section>

<section class="product-details-dimensions">
    <h2>Details &amp; Dimensions</h2>
    <ul class="artwork-attributes">
        <?php if ( $medium = get_post_meta( get_the_ID(), '_asc_medium', true ) ) : ?>
            <li><strong>Medium:</strong> <?php echo esc_html( $medium ); ?></li>
        <?php endif; ?>
        <?php if ( $year = get_post_meta( get_the_ID(), '_asc_year_created', true ) ) : ?>
            <li><strong>Year:</strong> <?php echo esc_html( $year ); ?></li>
        <?php endif; ?>
        <?php if ( $dimensions = get_post_meta( get_the_ID(), '_asc_dimensions', true ) ) : ?>
            <li><strong>Dimensions:</strong> <?php echo esc_html( $dimensions ); ?></li>
        <?php endif; ?>
        <?php if ( $rarity = get_post_meta( get_the_ID(), '_asc_rarity', true ) ) : ?>
            <li><strong>Rarity:</strong> <?php echo esc_html( $rarity ); ?></li>
        <?php endif; ?>
        <?php
        $edition_no   = get_post_meta( get_the_ID(), '_asc_edition_number', true );
        $edition_size = get_post_meta( get_the_ID(), '_asc_edition_size', true );
        $show_edition = $edition_no || $edition_size || $rarity === 'open-edition' || $rarity === 'limited-edition';
        if ( $show_edition ) :
            $parts = array();
            if ( $edition_no && $edition_size ) {
                $parts[] = $edition_no . ' / ' . $edition_size;
            } elseif ( $edition_no ) {
                $parts[] = $edition_no;
            } elseif ( $edition_size ) {
                $parts[] = __( 'of', 'art-storefront-customizer' ) . ' ' . $edition_size;
            }
            if ( $rarity === 'open-edition' ) {
                $parts[] = __( 'Open Edition', 'art-storefront-customizer' );
            } elseif ( $rarity === 'limited-edition' && empty( $edition_no ) && empty( $edition_size ) ) {
                $parts[] = __( 'Closed Edition', 'art-storefront-customizer' );
            } elseif ( $rarity === 'limited-edition' ) {
                $parts[] = __( 'Closed Edition', 'art-storefront-customizer' );
            }
            ?>
            <li><strong><?php esc_html_e( 'Edition', 'art-storefront-customizer' ); ?>:</strong> <?php echo esc_html( implode( ' ', $parts ) ); ?></li>
        <?php endif; ?>
        <?php if ( $framed = get_post_meta( get_the_ID(), '_asc_framed', true ) ) : ?>
            <li><strong>Framed:</strong> <?php echo esc_html( $framed ); ?></li>
        <?php endif; ?>
        <?php if ( $certificate = get_post_meta( get_the_ID(), '_asc_certificate_of_authenticity', true ) ) : ?>
            <li><strong>Certificate:</strong> <?php echo esc_html( $certificate ); ?></li>
        <?php endif; ?>
    </ul>
</section>

<?php
$artist_id = get_post_meta( get_the_ID(), '_asc_artist_id', true );
if ( $artist_id ) {
    $artist = get_post( $artist_id );
    if ( $artist && 'artist' === $artist->post_type ) {
        $bio     = get_post_meta( $artist_id, '_asc_artist_bio', true );
        if ( ! $bio ) {
            $bio = $artist->post_content;
        }
        ?>
        <section class="artist-bio">
            <h2><?php echo esc_html( get_the_title( $artist ) ); ?> - Artist Bio</h2>
            <?php
            $image = get_the_post_thumbnail( $artist_id, 'medium', array( 'class' => 'artist-profile-image' ) );
            if ( $image ) {
                echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            echo wp_kses_post( wpautop( $bio ) );
            $website = get_post_meta( $artist_id, '_asc_artist_website', true );
            if ( $website ) {
                echo '<p><a href="' . esc_url( $website ) . '" target="_blank" rel="noopener">' . esc_html( $website ) . '</a></p>';
            }
            ?>
        </section>
        <?php
    }
} else {
    $artists = get_the_terms( get_the_ID(), 'associated_artist' );
    if ( $artists && ! is_wp_error( $artists ) ) :
        foreach ( $artists as $artist ) :
            $bio = term_description( $artist, 'associated_artist' );
            if ( $bio ) :
                ?>
                <section class="artist-bio">
                    <h2><?php echo esc_html( $artist->name ); ?> - Artist Bio</h2>
                    <?php echo wp_kses_post( wpautop( $bio ) ); ?>
                </section>
                <?php
            endif;
        endforeach;
    endif;
}
?>

<?php get_footer( 'shop' ); ?>
