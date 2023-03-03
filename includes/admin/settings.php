<?php

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function yayblog_settings_init()
{
    // Register a new setting for "yayblog" page.
    register_setting('yayblog', 'yayblog_options');

    // Register a new section in the "yayblog" page.
    add_settings_section(
        'yayblog_section_developers',
        __('Review settings', 'yayblog'),
        null,
        'yayblog'
    );

    // Register a new field in the "yayblog_section_developers" section, inside the "yayblog" page.
    add_settings_field(
        'yayblog_field_pill', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('Review', 'yayblog'),
        'yayblog_field_pill_cb',
        'yayblog',
        'yayblog_section_developers',
        array(
            'label_for'         => 'yayblog_field_pill',
            'class'             => 'review_max',
            'yayblog_custom_data' => 'custom',
        )
    );

    add_settings_field(
        'review_ui', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('UI', 'yayblog'),
        'review_ui_cb',
        'yayblog',
        'yayblog_section_developers',
        array(
            'label_for'         => 'review_ui',
            'class'             => 'review_ui',
            'yayblog_custom_data' => 'custom',
        )
    );
}

/**
 * Register our yayblog_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'yayblog_settings_init');


/**
 * Custom option and settings:
 *  - callback functions
 */

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function yayblog_field_pill_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('yayblog_options');
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['yayblog_custom_data']); ?>" name="yayblog_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="out of 5" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'out of 5', false)) : (''); ?>>
            <?php esc_html_e('out of 5', 'yayblog'); ?>
        </option>
        <option value="out of 10" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'out of 10', false)) : (''); ?>>
            <?php esc_html_e('out of 10', 'yayblog'); ?>
        </option>
    </select>

<?php
}

/**
 * Review UI callback function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function review_ui_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('yayblog_options');
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['yayblog_custom_data']); ?>" name="yayblog_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="badge" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'badge', false)) : (''); ?>>
            Badge
        </option>
        <option value="icon" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'icon', false)) : (''); ?>>
            Icon
        </option>
        <option value="tooltip" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'tooltip', false)) : (''); ?>>
            Tooltip
        </option>
    </select>

<?php
}

/**
 * Add the top level menu page.
 */
function yayblog_options_page()
{
    add_options_page(
        'YayBlog',
        'YayBlog Options',
        'manage_options',
        'yay-blog-options',
        'yayblog_options_page_html'
    );
}


/**
 * Register our yayblog_options_page to the admin_menu action hook.
 */
add_action('admin_menu', 'yayblog_options_page');


/**
 * Top level menu callback function
 */
function yayblog_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('yayblog_messages', 'yayblog_message', __('Settings Saved', 'yayblog'), 'updated');
    }

    // show error/update messages
    settings_errors('yayblog_messages');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "yayblog"
            settings_fields('yayblog');
            // output setting sections and their fields
            // (sections are registered for "yayblog", each field is registered to a specific section)
            do_settings_sections('yayblog');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}
