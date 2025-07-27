<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register settings and add menu page.
 */
function asc_settings_init() {
    $defaults = array(
        'enable_collector_mode'       => 0,
        'add_to_cart_label'           => 'Collect Now',
        'out_of_stock_label'          => 'Collected 🟥',
        'enable_framing_options'      => 0,
        'enable_edition_print_fields' => 0,
        'display_shipping_badge'      => 1,
        'display_guarantee_badge'     => 1,
    );

    register_setting('asc_settings_group', 'asc_settings', 'asc_sanitize_settings');

    add_settings_section('asc_main_section', '', '__return_false', 'asc_settings');

    add_settings_field(
        'enable_collector_mode',
        __('Enable Collector Mode', 'art-storefront-customizer'),
        'asc_render_checkbox_enable_collector_mode',
        'asc_settings',
        'asc_main_section'
    );

    add_settings_field(
        'add_to_cart_label',
        __('Add to Cart Label', 'art-storefront-customizer'),
        'asc_render_add_to_cart_label',
        'asc_settings',
        'asc_main_section'
    );

    add_settings_field(
        'out_of_stock_label',
        __('Out of Stock Label', 'art-storefront-customizer'),
        'asc_render_out_of_stock_label',
        'asc_settings',
        'asc_main_section'
    );

    add_settings_field(
        'enable_framing_options',
        __('Enable Framing Options', 'art-storefront-customizer'),
        'asc_render_checkbox_enable_framing_options',
        'asc_settings',
        'asc_main_section'
    );

    add_settings_field(
        'enable_edition_print_fields',
        __('Enable Edition Print Fields', 'art-storefront-customizer'),
        'asc_render_checkbox_enable_edition_print_fields',
        'asc_settings',
        'asc_main_section'
    );

    add_settings_field(
        'display_shipping_badge',
        __('Display Shipping Badge', 'art-storefront-customizer'),
        'asc_render_checkbox_display_shipping_badge',
        'asc_settings',
        'asc_main_section'
    );

    add_settings_field(
        'display_guarantee_badge',
        __('Display Guarantee Badge', 'art-storefront-customizer'),
        'asc_render_checkbox_display_guarantee_badge',
        'asc_settings',
        'asc_main_section'
    );
}
add_action('admin_init', 'asc_settings_init');

/**
 * Add submenu page under Settings.
 */
function asc_add_settings_page() {
    add_options_page(
        __('Art Storefront Settings', 'art-storefront-customizer'),
        __('Art Storefront', 'art-storefront-customizer'),
        'manage_options',
        'asc_settings',
        'asc_render_settings_page'
    );
}
add_action('admin_menu', 'asc_add_settings_page');

/**
 * Render settings page.
 */
function asc_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Art Storefront Settings', 'art-storefront-customizer'); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('asc_settings_group'); // Adds nonce and option_page fields
            do_settings_sections('asc_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Sanitize settings before saving.
 *
 * @param array $input Raw input values.
 * @return array Sanitized values.
 */
function asc_sanitize_settings($input) {
    $output = array();

    $output['enable_collector_mode'] = isset($input['enable_collector_mode']) ? 1 : 0;
    $output['add_to_cart_label'] = isset($input['add_to_cart_label']) ? sanitize_text_field($input['add_to_cart_label']) : 'Collect Now';
    $output['out_of_stock_label'] = isset($input['out_of_stock_label']) ? sanitize_text_field($input['out_of_stock_label']) : 'Collected 🟥';
    $output['enable_framing_options'] = isset($input['enable_framing_options']) ? 1 : 0;
    $output['enable_edition_print_fields'] = isset($input['enable_edition_print_fields']) ? 1 : 0;
    $output['display_shipping_badge'] = isset($input['display_shipping_badge']) ? 1 : 0;
    $output['display_guarantee_badge'] = isset($input['display_guarantee_badge']) ? 1 : 0;

    return $output;
}

/**
 * Helper to get options with defaults.
 *
 * @return array
 */
function asc_get_settings() {
    $defaults = array(
        'enable_collector_mode'      => 0,
        'add_to_cart_label'          => 'Collect Now',
        'out_of_stock_label'         => 'Collected 🟥',
        'enable_framing_options'     => 0,
        'enable_edition_print_fields' => 0,
        'display_shipping_badge'      => 1,
        'display_guarantee_badge'     => 1,
    );
    $options = get_option('asc_settings', array());
    return wp_parse_args($options, $defaults);
}

function asc_render_checkbox_enable_collector_mode() {
    $options = asc_get_settings();
    ?>
    <input type="checkbox" id="enable_collector_mode" name="asc_settings[enable_collector_mode]" value="1" <?php checked($options['enable_collector_mode'], 1); ?> />
    <?php
}

function asc_render_add_to_cart_label() {
    $options = asc_get_settings();
    ?>
    <input type="text" id="add_to_cart_label" name="asc_settings[add_to_cart_label]" value="<?php echo esc_attr($options['add_to_cart_label']); ?>" class="regular-text" />
    <?php
}

function asc_render_out_of_stock_label() {
    $options = asc_get_settings();
    ?>
    <input type="text" id="out_of_stock_label" name="asc_settings[out_of_stock_label]" value="<?php echo esc_attr($options['out_of_stock_label']); ?>" class="regular-text" />
    <?php
}

function asc_render_checkbox_enable_framing_options() {
    $options = asc_get_settings();
    ?>
    <input type="checkbox" id="enable_framing_options" name="asc_settings[enable_framing_options]" value="1" <?php checked($options['enable_framing_options'], 1); ?> />
    <?php
}

function asc_render_checkbox_enable_edition_print_fields() {
    $options = asc_get_settings();
    ?>
    <input type="checkbox" id="enable_edition_print_fields" name="asc_settings[enable_edition_print_fields]" value="1" <?php checked($options['enable_edition_print_fields'], 1); ?> />
    <?php
}

function asc_render_checkbox_display_shipping_badge() {
    $options = asc_get_settings();
    ?>
    <input type="checkbox" id="display_shipping_badge" name="asc_settings[display_shipping_badge]" value="1" <?php checked($options['display_shipping_badge'], 1); ?> />
    <?php
}

function asc_render_checkbox_display_guarantee_badge() {
    $options = asc_get_settings();
    ?>
    <input type="checkbox" id="display_guarantee_badge" name="asc_settings[display_guarantee_badge]" value="1" <?php checked($options['display_guarantee_badge'], 1); ?> />
    <?php
}
