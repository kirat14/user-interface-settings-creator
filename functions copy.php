<?php
$setting_name = 'yso_admin_name';

add_action('admin_init', 'yso_setting_setup');
function yso_setting_setup()
{
    // Create Setting
    $settings_group = 'yso';
    register_setting($settings_group, $GLOBALS['setting_name']);

    // Create section of Page
    $settings_section = 'yso_main_settings_section';
    $page = $settings_group;
    add_settings_section(
        $settings_section,
        'Admins names',
        'yso_main_section_text_output',
        $page
    );

    // Add fields to that section
    add_settings_field(
        $GLOBALS['setting_name'],
        'Write the admin name',
        'yso_admin_name_input_renderer',
        $page,
        $settings_section
    );

}

function yso_admin_name_input_renderer()
{
    echo '<input type="text" name="' . $GLOBALS['setting_name'] . '" value="' . get_option($GLOBALS['setting_name'], '') . '">';
}

add_action('admin_menu', 'yso_admin_add_page');
function yso_admin_add_page()
{
    add_menu_page(
        'Admin names Page',
        'Admin names Page',
        'manage_options',
        'yso',
        'yso_settings_page_render'
    );
}

function yso_settings_page_render() {
    ?>
    <div class="wrap">
        <h2>Heading two</h2>
        <form action="options.php" method="post">
            <?php settings_fields( 'yso' ); ?>
            <?php do_settings_sections( 'yso' ); ?>
             
            <input name="Submit" type="submit" value="Save Changes" class="button button-primary" />
        </form>
    </div>
    <?php
    }