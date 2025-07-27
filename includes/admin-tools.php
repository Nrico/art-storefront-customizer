<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register "Art Storefront Utilities" under the Tools menu.
 */
function asc_admin_tools_menu() {
    add_management_page(
        __('Art Storefront Utilities', 'art-storefront-customizer'),
        __('Art Storefront Utilities', 'art-storefront-customizer'),
        'manage_options',
        'asc_admin_tools',
        'asc_render_admin_tools_page'
    );
}
add_action('admin_menu', 'asc_admin_tools_menu');

/**
 * Render utilities page for bulk editing artwork fields.
 */
function asc_render_admin_tools_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['asc_bulk_edit_nonce']) && wp_verify_nonce($_POST['asc_bulk_edit_nonce'], 'asc_bulk_edit_artwork')) {
        $product_ids = array_map('intval', $_POST['product_ids'] ?? array());
        $medium      = sanitize_text_field($_POST['medium'] ?? '');
        $year        = intval($_POST['year_created'] ?? 0);

        foreach ($product_ids as $product_id) {
            if ('product' !== get_post_type($product_id)) {
                continue;
            }

            if ($medium !== '') {
                update_post_meta($product_id, '_asc_medium', $medium);
            }

            if ($year) {
                update_post_meta($product_id, '_asc_year_created', $year);
            }
        }

        echo '<div class="updated"><p>' . esc_html__('Artwork updated.', 'art-storefront-customizer') . '</p></div>';
    }

    $products = get_posts(array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Art Storefront Utilities', 'art-storefront-customizer'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('asc_bulk_edit_artwork', 'asc_bulk_edit_nonce'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e('Products', 'art-storefront-customizer'); ?></th>
                    <td>
                        <select name="product_ids[]" multiple size="5" style="min-width: 300px;">
                            <?php foreach ($products as $product) : ?>
                                <option value="<?php echo esc_attr($product->ID); ?>"><?php echo esc_html($product->post_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php esc_html_e('Hold Ctrl/Command to select multiple products.', 'art-storefront-customizer'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="asc_medium"><?php esc_html_e('Medium', 'art-storefront-customizer'); ?></label></th>
                    <td><input type="text" name="medium" id="asc_medium" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="asc_year_created"><?php esc_html_e('Year Created', 'art-storefront-customizer'); ?></label></th>
                    <td><input type="number" name="year_created" id="asc_year_created" class="small-text" /></td>
                </tr>
            </table>
            <?php submit_button(__('Update Artworks', 'art-storefront-customizer')); ?>
        </form>
    </div>
    <?php
}

/**
 * Add "Convert to Artwork" row action in the product list table.
 *
 * @param array   $actions Existing row actions.
 * @param WP_Post $post    Current post object.
 * @return array Modified actions array.
 */
function asc_add_convert_row_action($actions, $post) {
    if ($post->post_type !== 'product') {
        return $actions;
    }

    $url = wp_nonce_url(
        admin_url('admin-post.php?action=asc_convert_to_artwork&post_id=' . $post->ID),
        'asc_convert_' . $post->ID
    );

    $actions['asc_convert_to_artwork'] = '<a href="' . esc_url($url) . '">' . esc_html__('Convert to Artwork', 'art-storefront-customizer') . '</a>';

    return $actions;
}
add_filter('post_row_actions', 'asc_add_convert_row_action', 10, 2);

/**
 * Handle convert to artwork action.
 */
function asc_handle_convert_to_artwork() {
    $post_id = intval($_GET['post_id'] ?? 0);

    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_die(__('Invalid request.', 'art-storefront-customizer'));
    }

    check_admin_referer('asc_convert_' . $post_id);

    $defaults = array(
        '_asc_medium'                      => '',
        '_asc_year_created'                => '',
        '_asc_dimensions'                  => '',
        '_asc_rarity'                      => '',
        '_asc_framed'                      => '',
        '_asc_certificate_of_authenticity' => '',
        '_asc_shipping_format'             => '',
    );

    foreach ($defaults as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

    wp_safe_redirect(admin_url('edit.php?post_type=product'));
    exit;
}
add_action('admin_post_asc_convert_to_artwork', 'asc_handle_convert_to_artwork');
