<?php
if (!defined('ABSPATH')) {
    exit;
}

function asc_add_artwork_details_meta_box() {
    add_meta_box(
        'asc_artwork_details',
        __('Artwork Details', 'art-storefront-customizer-main'),
        'asc_render_artwork_details_meta_box',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'asc_add_artwork_details_meta_box');

function asc_add_framing_options_meta_box() {
    $settings = asc_get_settings();
    if (!empty($settings['enable_framing_options'])) {
        add_meta_box(
            'asc_framing_options',
            __('Framing Options', 'art-storefront-customizer-main'),
            'asc_render_framing_options_meta_box',
            'product',
            'side',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'asc_add_framing_options_meta_box');

function asc_add_edition_info_meta_box() {
    $settings = asc_get_settings();
    if (!empty($settings['enable_edition_print_fields'])) {
        add_meta_box(
            'asc_edition_info',
            __('Edition Information', 'art-storefront-customizer-main'),
            'asc_render_edition_info_meta_box',
            'product',
            'side',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'asc_add_edition_info_meta_box');

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
        <label for="asc_medium"><?php esc_html_e('Medium', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_medium" id="asc_medium" value="<?php echo esc_attr($medium); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_year_created"><?php esc_html_e('Year Created', 'art-storefront-customizer-main'); ?></label><br />
        <input type="number" name="asc_year_created" id="asc_year_created" value="<?php echo esc_attr($year); ?>" class="small-text" />
    </p>
    <p>
        <label for="asc_dimensions"><?php esc_html_e('Dimensions (W × H × D)', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_dimensions" id="asc_dimensions" value="<?php echo esc_attr($dimensions); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_rarity"><?php esc_html_e('Rarity', 'art-storefront-customizer-main'); ?></label><br />
        <select name="asc_rarity" id="asc_rarity">
            <option value="one-of-a-kind" <?php selected($rarity, 'one-of-a-kind'); ?>><?php esc_html_e('One-of-a-kind', 'art-storefront-customizer-main'); ?></option>
            <option value="limited-edition" <?php selected($rarity, 'limited-edition'); ?>><?php esc_html_e('Limited Edition', 'art-storefront-customizer-main'); ?></option>
            <option value="open-edition" <?php selected($rarity, 'open-edition'); ?>><?php esc_html_e('Open Edition', 'art-storefront-customizer-main'); ?></option>
        </select>
    </p>
    <p>
        <label for="asc_framed"><?php esc_html_e('Framed', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_framed" id="asc_framed" value="<?php echo esc_attr($framed); ?>" class="widefat" />
    </p>
    <p>
        <label for="asc_certificate_of_authenticity">
            <input type="checkbox" name="asc_certificate_of_authenticity" id="asc_certificate_of_authenticity" value="1" <?php checked($coa, '1'); ?> />
            <?php esc_html_e('Certificate of Authenticity', 'art-storefront-customizer-main'); ?>
        </label>
    </p>
    <p>
        <label for="asc_shipping_format"><?php esc_html_e('Shipping Format', 'art-storefront-customizer-main'); ?></label><br />
        <select name="asc_shipping_format" id="asc_shipping_format">
            <option value="rolled" <?php selected($shipping, 'rolled'); ?>><?php esc_html_e('Rolled', 'art-storefront-customizer-main'); ?></option>
            <option value="crated" <?php selected($shipping, 'crated'); ?>><?php esc_html_e('Crated', 'art-storefront-customizer-main'); ?></option>
            <option value="flat" <?php selected($shipping, 'flat'); ?>><?php esc_html_e('Flat', 'art-storefront-customizer-main'); ?></option>
            <option value="other" <?php selected($shipping, 'other'); ?>><?php esc_html_e('Other', 'art-storefront-customizer-main'); ?></option>
        </select>
    </p>
    <?php
}

function asc_render_framing_options_meta_box($post) {
    wp_nonce_field('asc_save_framing_options', 'asc_framing_options_nonce');
    $option = get_post_meta($post->ID, '_asc_frame_option', true);
    ?>
    <p>
        <label for="asc_frame_option"><?php esc_html_e('Frame Option', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_frame_option" id="asc_frame_option" value="<?php echo esc_attr($option); ?>" class="widefat" />
    </p>
    <?php
}

function asc_render_edition_info_meta_box($post) {
    wp_nonce_field('asc_save_edition_info', 'asc_edition_info_nonce');
    $number = get_post_meta($post->ID, '_asc_edition_number', true);
    $size   = get_post_meta($post->ID, '_asc_edition_size', true);
    ?>
    <p>
        <label for="asc_edition_number"><?php esc_html_e('Edition Number', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_edition_number" id="asc_edition_number" value="<?php echo esc_attr($number); ?>" class="small-text" />
    </p>
    <p>
        <label for="asc_edition_size"><?php esc_html_e('Edition Size', 'art-storefront-customizer-main'); ?></label><br />
        <input type="text" name="asc_edition_size" id="asc_edition_size" value="<?php echo esc_attr($size); ?>" class="small-text" />
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

function asc_save_framing_options_meta_box($post_id) {
    $settings = asc_get_settings();
    if (empty($settings['enable_framing_options'])) {
        return;
    }

    if (!isset($_POST['asc_framing_options_nonce']) || !wp_verify_nonce($_POST['asc_framing_options_nonce'], 'asc_save_framing_options')) {
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

    $value = sanitize_text_field($_POST['asc_frame_option'] ?? '');
    update_post_meta($post_id, '_asc_frame_option', $value);
}
add_action('save_post_product', 'asc_save_framing_options_meta_box');

function asc_save_edition_info_meta_box($post_id) {
    $settings = asc_get_settings();
    if (empty($settings['enable_edition_print_fields'])) {
        return;
    }

    if (!isset($_POST['asc_edition_info_nonce']) || !wp_verify_nonce($_POST['asc_edition_info_nonce'], 'asc_save_edition_info')) {
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

    $number = sanitize_text_field($_POST['asc_edition_number'] ?? '');
    $size   = sanitize_text_field($_POST['asc_edition_size'] ?? '');

    update_post_meta($post_id, '_asc_edition_number', $number);
    update_post_meta($post_id, '_asc_edition_size', $size);
}
add_action('save_post_product', 'asc_save_edition_info_meta_box');
