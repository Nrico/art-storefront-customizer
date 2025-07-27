<?php
if (!defined('ABSPATH')) {
    exit;
}

function asc_add_artwork_details_meta_box() {
    add_meta_box(
        'asc_artwork_details',
        __('Artwork Details', 'art-storefront-customizer'),
        'asc_render_artwork_details_meta_box',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'asc_add_artwork_details_meta_box');

function asc_render_artwork_details_meta_box($post) {
    wp_nonce_field('asc_save_artwork_details', 'asc_artwork_details_nonce');

    $medium     = get_post_meta($post->ID, '_asc_medium', true);
    $year       = get_post_meta($post->ID, '_asc_year_created', true);
    $dimensions = get_post_meta($post->ID, '_asc_dimensions', true);
    $rarity     = get_post_meta($post->ID, '_asc_rarity', true);
    $framed     = get_post_meta($post->ID, '_asc_framed', true);
    $coa        = get_post_meta($post->ID, '_asc_certificate_of_authenticity', true);
    $shipping   = get_post_meta($post->ID, '_asc_shipping_format', true);
    ?>
    <p>
        <label for="asc_medium"><?php _e('Medium', 'art-storefront-customizer'); ?></label><br />
        <input type="text" name="asc_medium" id="asc_medium" value="<?php echo esc_attr($medium); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_year_created"><?php _e('Year Created', 'art-storefront-customizer'); ?></label><br />
        <input type="number" name="asc_year_created" id="asc_year_created" value="<?php echo esc_attr($year); ?>" class="small-text" />
    </p>
    <p>
        <label for="asc_dimensions"><?php _e('Dimensions (W × H × D)', 'art-storefront-customizer'); ?></label><br />
        <input type="text" name="asc_dimensions" id="asc_dimensions" value="<?php echo esc_attr($dimensions); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_rarity"><?php _e('Rarity', 'art-storefront-customizer'); ?></label><br />
        <select name="asc_rarity" id="asc_rarity">
            <option value="one-of-a-kind" <?php selected($rarity, 'one-of-a-kind'); ?>><?php _e('One-of-a-kind', 'art-storefront-customizer'); ?></option>
            <option value="limited-edition" <?php selected($rarity, 'limited-edition'); ?>><?php _e('Limited Edition', 'art-storefront-customizer'); ?></option>
            <option value="open-edition" <?php selected($rarity, 'open-edition'); ?>><?php _e('Open Edition', 'art-storefront-customizer'); ?></option>
        </select>
    </p>
    <p>
        <label for="asc_framed"><?php _e('Framed', 'art-storefront-customizer'); ?></label><br />
        <input type="text" name="asc_framed" id="asc_framed" value="<?php echo esc_attr($framed); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_certificate_of_authenticity">
            <input type="checkbox" name="asc_certificate_of_authenticity" id="asc_certificate_of_authenticity" value="1" <?php checked($coa, '1'); ?> />
            <?php _e('Certificate of Authenticity', 'art-storefront-customizer'); ?>
        </label>
    </p>
    <p>
        <label for="asc_shipping_format"><?php _e('Shipping Format', 'art-storefront-customizer'); ?></label><br />
        <select name="asc_shipping_format" id="asc_shipping_format">
            <option value="rolled" <?php selected($shipping, 'rolled'); ?>><?php _e('Rolled', 'art-storefront-customizer'); ?></option>
            <option value="crated" <?php selected($shipping, 'crated'); ?>><?php _e('Crated', 'art-storefront-customizer'); ?></option>
            <option value="flat" <?php selected($shipping, 'flat'); ?>><?php _e('Flat', 'art-storefront-customizer'); ?></option>
            <option value="other" <?php selected($shipping, 'other'); ?>><?php _e('Other', 'art-storefront-customizer'); ?></option>
        </select>
    </p>
    <?php
}

function asc_save_artwork_details_meta_box($post_id) {
    if (!isset($_POST['asc_artwork_details_nonce']) || !wp_verify_nonce($_POST['asc_artwork_details_nonce'], 'asc_save_artwork_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if ('product' !== ($_POST['post_type'] ?? '')) {
        return;
    }

    if (!current_user_can('edit_product', $post_id)) {
        return;
    }

    $fields = array(
        'asc_medium'                      => sanitize_text_field($_POST['asc_medium'] ?? ''),
        'asc_year_created'                => intval($_POST['asc_year_created'] ?? 0),
        'asc_dimensions'                  => sanitize_text_field($_POST['asc_dimensions'] ?? ''),
        'asc_rarity'                      => sanitize_text_field($_POST['asc_rarity'] ?? ''),
        'asc_framed'                      => sanitize_text_field($_POST['asc_framed'] ?? ''),
        'asc_certificate_of_authenticity' => isset($_POST['asc_certificate_of_authenticity']) ? '1' : '',
        'asc_shipping_format'             => sanitize_text_field($_POST['asc_shipping_format'] ?? ''),
    );

    foreach ($fields as $key => $value) {
        update_post_meta($post_id, '_' . $key, $value);
    }
}
add_action('save_post_product', 'asc_save_artwork_details_meta_box');
