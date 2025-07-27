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
    <div class="product-price">
        <?php wc_get_template( 'single-product/price.php' ); ?>
    </div>
    <?php do_action( 'art_storefront_product_badges' ); ?>
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
        <?php if ( $framed = get_post_meta( get_the_ID(), '_asc_framed', true ) ) : ?>
            <li><strong>Framed:</strong> <?php echo esc_html( $framed ); ?></li>
        <?php endif; ?>
        <?php if ( $certificate = get_post_meta( get_the_ID(), '_asc_certificate_of_authenticity', true ) ) : ?>
            <li><strong>Certificate:</strong> <?php echo esc_html( $certificate ); ?></li>
        <?php endif; ?>
    </ul>
</section>

<?php
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
?>

<?php get_footer( 'shop' ); ?>
