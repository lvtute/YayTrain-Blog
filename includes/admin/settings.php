<?php

function yayblog_settings_init()
{
    // Register a new setting for "yayblog" page.
    register_setting('yayblog', 'yayblog_options', [
        'default' => array('yayblog_point_ladder' => 'out_of_10', 'review_ui' => 'icon')
    ]);

    add_settings_section(
        'yayblog_section_developers',
        __('Review settings', 'yayblog'),
        null,
        'yayblog'
    );

    add_settings_field(
        'yayblog_point_ladder',
        __('Review', 'yayblog'),
        'yayblog_point_ladder_cb',
        'yayblog',
        'yayblog_section_developers',
        array(
            'label_for'         => 'yayblog_point_ladder',
            'class'             => 'review_max',
            'yayblog_custom_data' => 'custom',
        )
    );

    add_settings_field(
        'review_ui',
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

add_action('admin_init', 'yayblog_settings_init');


function yayblog_point_ladder_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('yayblog_options');
    // var_dump($options);
    // die();
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['yayblog_custom_data']); ?>" name="yayblog_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="out_of_5" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'out_of_5', false)) : (''); ?>>
            <?php esc_html_e('out of 5', 'yayblog'); ?>
        </option>
        <option value="out_of_10" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'out_of_10', false)) : (''); ?>>
            <?php esc_html_e('out of 10', 'yayblog'); ?>
        </option>
    </select>

<?php
}

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


add_action('admin_menu', 'yayblog_options_page');


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
    // settings_errors('yayblog_messages');


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
