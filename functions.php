<?php

require_once get_template_directory() . '/enable.js.module.php';
require_once get_template_directory() . '/includes/helper-functions.php';
require_once get_template_directory() . '/setup/enqueue.php';

function my_custom_menu_page()
{
    add_menu_page(
        'Custom Settings',
        'Custom Settings',
        'manage_options',
        'custom-settings',
        'custom_settings_page'
    );
}
function custom_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Custom Settings</h1>

        <form id="my-custom-form" method="post" action="options.php">
            <?php settings_fields('custom_settings_group'); ?>
            <?php do_settings_sections('custom_settings_group'); ?>

            <div id="custom-fields-container">
                <?php
                $options = get_option('custom_fields');
                error_log(''. print_r($options, true));
                if ($options) {
                    foreach ($options as $field_name => $field_value) {
                        echo '<div class="field-pair" data-field-name="' . esc_attr($field_name) . '">';
                        echo '<label for="' . esc_attr($field_name) . '">Field Name:</label>';
                        echo '<input type="text" id="' . esc_attr($field_name) . '" name="custom_fields[' . esc_attr($field_name) . ']" value="' . esc_attr($field_name) . '" />';

                        echo '<label for="' . esc_attr($field_value) . '">Default Value:</label>';
                        echo '<input type="text" id="' . esc_attr(sanitize_key($field_value)) . '" name="' . esc_attr(sanitize_key($field_value)) . '" value="' . esc_attr($field_value) . '" />';
                        echo '<button type="button" class="remove-field" data-field-name="' . esc_attr($field_name) . '">Remove</button>';
                        echo '</div>';
                    }
                }

                ?>
            </div>

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>

        <button id="add-custom-field" class="button">Add Custom Field</button>
    </div>
    <?php
}
add_action('admin_menu', 'my_custom_menu_page');

function my_custom_settings()
{
    // Register settings and fields dynamically
    register_setting('custom_settings_group', 'custom_fields');

    // Add a custom section
    add_settings_section('my_section', 'My Section', 'my_section_callback', 'general');

    $options = get_option('custom_fields');
    if ($options) {
        foreach ($options as $field_name => $field_value) {
            // Add field to the custom section
            add_settings_field(
                $field_name,
                'Custom Field Label',
                'custom_field_callback',
                'general',
                'my_section',
                array('field_name' => $field_name, 'field_value' => $field_value)
            );
        }
    }
}
add_action('admin_init', 'my_custom_settings');


function my_section_callback()
{
    // Output any section-specific content
    echo '<p>This is my custom section.</p>';
}


function custom_field_callback($args)
{
    // Output the field markup dynamically
    $field_name = $args['field_name'];
    $field_value = $args['field_value'];

    echo '<input type="text" id="' . esc_attr($field_name) . '" name="custom_fields[' . esc_attr($field_name) . ']" value="' . esc_attr($field_value) . '" />';
}



function save_custom_fields()
{
    check_ajax_referer('custom_nonce', 'security');

    error_log(''. print_r($_POST['fields_pairs'], true));

    if (isset($_POST['fields_pairs'])) {
        // Apply the callback function to each sub-array
        $sanitizedFieldPairs = array_map(
            fn($fieldPair) =>
            array(
                'field_name' => sanitize_key($fieldPair['field_name']),
                'default_value' => sanitize_text_field($fieldPair['default_value']),
            ),
            $_POST['fields_pairs']
        );

        error_log("Sanitized Field Pairs: " . print_r($sanitizedFieldPairs, true));

        $options = get_option('custom_fields');
        // Check if $options is an array, initialize it if it's not
        if (!is_array($options)) {
            $options = array();
        }

        error_log("Options before update: " . print_r($options, true));

        foreach ($sanitizedFieldPairs as $fieldPair) {
            $options[$fieldPair['field_name']] = $fieldPair['default_value'];
        }

        error_log("Options after update: " . print_r($options, true));

        $result = update_option('custom_fields', $options);

        if ($result) {
            error_log("Options Updated Successfully");
        } else {
            error_log("Failed to Update Options");
        }
    }

    wp_die();
}
add_action('wp_ajax_save_custom_fields', 'save_custom_fields');


function get_option_json()
{
    $options = get_option($_POST['group_option_key']);

    check_ajax_referer('custom_nonce', 'security');
    // Send the response as JSON
    wp_send_json($options);
    // Always exit to avoid extra output
    wp_die();
}
add_action('wp_ajax_get_option_json', 'get_option_json');


function remove_setting()
{
    check_ajax_referer('custom_nonce', 'security');

    if (isset($_POST['option_key'])) {
        $options = get_option($_POST['group_option_key']);
        // Check if $options is an array, initialize it if it's not
        if (!is_array($options)) {
            $options = array();
        }

        // Remove the option_key(score) from the settings
        /* Ex: Array (Settings)
        (
            [score] => 10
            [median] => 5
        ) */
        unset($options[$_POST['option_key']]);

        $result = update_option($_POST['group_option_key'], $options);
        // Send the response as JSON
        wp_send_json($result);
        // Always exit to avoid extra output
        wp_die();
    }
}
add_action('wp_ajax_remove_setting', 'remove_setting');
