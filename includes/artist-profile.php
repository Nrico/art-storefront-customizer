<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register custom post type for artists.
 */
function asc_register_artist_post_type() {
    $labels = array(
        'name'               => __('Artists', 'art-storefront-customizer-main'),
        'singular_name'      => __('Artist', 'art-storefront-customizer-main'),
        'add_new_item'       => __('Add New Artist', 'art-storefront-customizer-main'),
        'edit_item'          => __('Edit Artist', 'art-storefront-customizer-main'),
        'new_item'           => __('New Artist', 'art-storefront-customizer-main'),
        'view_item'          => __('View Artist', 'art-storefront-customizer-main'),
        'search_items'       => __('Search Artists', 'art-storefront-customizer-main'),
        'not_found'          => __('No artists found', 'art-storefront-customizer-main'),
        'all_items'          => __('All Artists', 'art-storefront-customizer-main'),
    );

    register_post_type(
        'artist',
        array(
            'labels'       => $labels,
            'public'       => true,
            'has_archive'  => false,
            'show_in_rest' => true,
            'supports'     => array('title', 'editor', 'thumbnail'),
            'rewrite'      => array('slug' => 'artist'),
        )
    );
}
add_action('init', 'asc_register_artist_post_type');

/**
 * Add meta box for artist details.
 */
function asc_add_artist_meta_box() {
    add_meta_box(
        'asc_artist_details',
        __('Artist Details', 'art-storefront-customizer-main'),
        'asc_render_artist_meta_box',
        'artist',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes_artist', 'asc_add_artist_meta_box');

/**
 * Render artist details meta box.
 *
 * @param WP_Post $post Artist post object.
 */
function asc_render_artist_meta_box($post) {
    wp_nonce_field('asc_save_artist_details', 'asc_artist_details_nonce');

    $bio     = get_post_meta($post->ID, '_asc_artist_bio', true);
    $website = get_post_meta($post->ID, '_asc_artist_website', true);
    ?>
    <p>
        <label for="asc_artist_website"><?php esc_html_e('Website', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_artist_website" id="asc_artist_website" value="<?php echo esc_attr($website); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_artist_bio"><?php esc_html_e('Biography', 'art-storefront-customizer-main'); ?></label>
    </p>
    <?php
    wp_editor(
        $bio,
        'asc_artist_bio',
        array(
            'textarea_name' => 'asc_artist_bio',
            'textarea_rows' => 5,
            'media_buttons' => false,
        )
    );
}

/**
 * Save artist meta fields.
 *
 * @param int $post_id Post ID.
 */
function asc_save_artist_meta_box($post_id) {
    if (!isset($_POST['asc_artist_details_nonce']) || !wp_verify_nonce($_POST['asc_artist_details_nonce'], 'asc_save_artist_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ('artist' !== ($_POST['post_type'] ?? '')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $bio     = wp_kses_post($_POST['asc_artist_bio'] ?? '');
    $website = esc_url_raw($_POST['asc_artist_website'] ?? '');

    update_post_meta($post_id, '_asc_artist_bio', $bio);
    update_post_meta($post_id, '_asc_artist_website', $website);
}
add_action('save_post_artist', 'asc_save_artist_meta_box');

/**
 * Add meta box on products to select an associated artist.
 */
function asc_add_product_artist_meta_box() {
    add_meta_box(
        'asc_product_artist',
        __('Associated Artist', 'art-storefront-customizer-main'),
        'asc_render_product_artist_meta_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes_product', 'asc_add_product_artist_meta_box');

/**
 * Render product artist select box.
 *
 * @param WP_Post $post Product post.
 */
function asc_render_product_artist_meta_box($post) {
    wp_nonce_field('asc_save_product_artist', 'asc_product_artist_nonce');

    $current = get_post_meta($post->ID, '_asc_artist_id', true);
    $artists = get_posts(
        array(
            'post_type'      => 'artist',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );

    echo '<select name="asc_artist_id" id="asc_artist_id" style="width:100%;">';
    echo '<option value="">' . esc_html__('— None —', 'art-storefront-customizer-main') . '</option>';
    foreach ($artists as $artist) {
        echo '<option value="' . esc_attr($artist->ID) . '" ' . selected($current, $artist->ID, false) . '>' . esc_html($artist->post_title) . '</option>';
    }
    echo '</select>';
}

/**
 * Save selected artist for a product.
 *
 * @param int $post_id Product ID.
 */
function asc_save_product_artist_meta_box($post_id) {
    if (!isset($_POST['asc_product_artist_nonce']) || !wp_verify_nonce($_POST['asc_product_artist_nonce'], 'asc_save_product_artist')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ('product' !== ($_POST['post_type'] ?? '')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $artist_id = intval($_POST['asc_artist_id'] ?? 0);
    if ($artist_id) {
        update_post_meta($post_id, '_asc_artist_id', $artist_id);
    } else {
        delete_post_meta($post_id, '_asc_artist_id');
    }
}
add_action('save_post_product', 'asc_save_product_artist_meta_box');

