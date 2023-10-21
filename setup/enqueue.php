<?php
function enqueue_custom_script() {
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom-script.js?type=module', array('jquery'), '1.0', true);

    // Pass the Ajax URL and nonce to the script
    wp_localize_script('custom-script', 'custom_script_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('custom_nonce'),
    ));
}

add_action('admin_enqueue_scripts', 'enqueue_custom_script');