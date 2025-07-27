<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Artist Profile custom post type.
 */
function asc_register_artist_profile_post_type() {
    $labels = array(
        'name'               => __('Artist Profiles', 'art-storefront-customizer'),
        'singular_name'      => __('Artist Profile', 'art-storefront-customizer'),
        'add_new'            => __('Add New', 'art-storefront-customizer'),
        'add_new_item'       => __('Add New Artist Profile', 'art-storefront-customizer'),
        'edit_item'          => __('Edit Artist Profile', 'art-storefront-customizer'),
        'new_item'           => __('New Artist Profile', 'art-storefront-customizer'),
        'view_item'          => __('View Artist Profile', 'art-storefront-customizer'),
        'search_items'       => __('Search Artist Profiles', 'art-storefront-customizer'),
        'not_found'          => __('No artist profiles found', 'art-storefront-customizer'),
        'not_found_in_trash' => __('No artist profiles found in Trash', 'art-storefront-customizer'),
        'menu_name'          => __('Artist Profiles', 'art-storefront-customizer'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_rest'       => true,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'rewrite'            => array('slug' => 'artist'),
    );

    register_post_type('asc_artist_profile', $args);
}
add_action('init', 'asc_register_artist_profile_post_type');

/**
 * Add meta box for artist profile fields.
 */
function asc_add_artist_profile_meta_box() {
    add_meta_box(
        'asc_artist_profile_details',
        __('Artist Details', 'art-storefront-customizer'),
        'asc_render_artist_profile_meta_box',
        'asc_artist_profile',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'asc_add_artist_profile_meta_box');

/**
 * Render the artist profile meta box.
 *
 * @param WP_Post $post Current post object.
 */
function asc_render_artist_profile_meta_box($post) {
    wp_nonce_field('asc_save_artist_profile', 'asc_artist_profile_nonce');

    $short_bio  = get_post_meta($post->ID, '_asc_short_bio', true);
    $location   = get_post_meta($post->ID, '_asc_location', true);
    $headshot_id = get_post_meta($post->ID, '_asc_headshot_id', true);

    ?>
    <p>
        <label for="asc_location"><?php _e('Location', 'art-storefront-customizer'); ?></label><br />
        <input type="text" name="asc_location" id="asc_location" value="<?php echo esc_attr($location); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_short_bio"><?php _e('Short Bio', 'art-storefront-customizer'); ?></label><br />
        <textarea name="asc_short_bio" id="asc_short_bio" rows="4" class="widefat"><?php echo esc_textarea($short_bio); ?></textarea>
    </p>
    <p>
        <label for="asc_headshot_id"><?php _e('Headshot Image ID', 'art-storefront-customizer'); ?></label><br />
        <input type="number" name="asc_headshot_id" id="asc_headshot_id" value="<?php echo esc_attr($headshot_id); ?>" class="small-text" />
        <?php if ($headshot_id) : ?>
            <div><?php echo wp_get_attachment_image($headshot_id, 'thumbnail'); ?></div>
        <?php endif; ?>
    </p>
    <?php
}

/**
 * Save meta box data.
 *
 * @param int $post_id Post being saved.
 */
function asc_save_artist_profile_meta($post_id) {
    if (!isset($_POST['asc_artist_profile_nonce']) || !wp_verify_nonce($_POST['asc_artist_profile_nonce'], 'asc_save_artist_profile')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ('asc_artist_profile' !== ($_POST['post_type'] ?? '')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        '_asc_short_bio'  => sanitize_textarea_field($_POST['asc_short_bio'] ?? ''),
        '_asc_location'   => sanitize_text_field($_POST['asc_location'] ?? ''),
        '_asc_headshot_id'=> intval($_POST['asc_headshot_id'] ?? 0),
    );

    foreach ($fields as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}
add_action('save_post_asc_artist_profile', 'asc_save_artist_profile_meta');

/**
 * Display associated artist details on the product page.
 */
function asc_display_associated_artist_details() {
    global $post;

    if (!is_singular('product')) {
        return;
    }

    $terms = get_the_terms($post->ID, 'associated_artist');
    if (!$terms || is_wp_error($terms)) {
        return;
    }

    foreach ($terms as $term) {
        $artist = get_posts(array(
            'post_type'  => 'asc_artist_profile',
            'tax_query'  => array(
                array(
                    'taxonomy' => 'associated_artist',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'posts_per_page' => 1,
        ));

        if (empty($artist)) {
            continue;
        }

        $artist    = $artist[0];
        $bio       = get_post_meta($artist->ID, '_asc_short_bio', true);
        $location  = get_post_meta($artist->ID, '_asc_location', true);
        $headshot  = get_post_meta($artist->ID, '_asc_headshot_id', true);
        ?>
        <div class="asc-artist-details">
            <?php if ($headshot) : ?>
                <div class="artist-headshot"><?php echo wp_get_attachment_image($headshot, 'thumbnail'); ?></div>
            <?php endif; ?>
            <div class="artist-info">
                <h3 class="artist-name"><?php echo esc_html(get_the_title($artist)); ?></h3>
                <?php if ($location) : ?>
                    <p class="artist-location"><?php echo esc_html($location); ?></p>
                <?php endif; ?>
                <?php if ($bio) : ?>
                    <div class="artist-bio"><?php echo wp_kses_post(wpautop($bio)); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'asc_display_associated_artist_details', 12);
