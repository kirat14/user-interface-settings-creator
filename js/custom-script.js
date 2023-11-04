import { fieldTemplate } from './template.js';
import { prepare_ajax_request } from './helper-functions.js';

var fieldCount = 0;
var field_name = 'field-name';
var default_value = 'default-value';

function render_saved_settings() {
    // Getting Fields using AJAX
    var data = {
        action: 'get_custom_settings',
        security: custom_script_vars.ajax_nonce,
    };

    jQuery.post(custom_script_vars.ajax_url, data, function (response) {
        /* response = {
            key1: 'value1',
            key2: 'value2',
            key3: 'value3'
        }; */

        if (response != null) {
            const keys = Object.keys(response);
            keys.forEach(key => {
                appendFields(key, response[key]);
            });
        }
    });
}

function initializeFieldCount() {
    var existingPairs = jQuery('#custom-fields-container .field-pair');
    fieldCount = existingPairs.length;
}

function appendFields(field_name, default_value) {
    var filledTemplate = fieldTemplate.replace(/{{fieldName}}/g, field_name);
    filledTemplate = filledTemplate.replace(/{{defaultValue}}/g, default_value);

    jQuery('#custom-fields-container').append(filledTemplate);
}

function save_custom_field() {
    // Save Fields using AJAX
    var fieldPairsData = [];

    jQuery('#custom-fields-container .field-pair').each(function (index) {
        var field_name = jQuery(this).data('field-name');
        // Use jQuery find to get the default value input within the current pair
        var defaultValueInput = jQuery(this).find('[id^="default-value"]');
        var field_name_input_val = jQuery(this).find('#' + field_name).val();
        // Now you can get the value
        var defaultValue = defaultValueInput.val();

        // Add the field pair data to the array
        fieldPairsData.push({
            field_name: field_name_input_val,
            default_value: defaultValue,
        });
    });
    console.log(fieldPairsData);

    // Include the field pairs data in the AJAX request
    prepare_ajax_request('save_custom_fields', {field_pairs: fieldPairsData});

}


jQuery(document).ready(function () {
    console.log('document has been loaded');
    render_saved_settings()
    initializeFieldCount();
    jQuery('#add-custom-field').on('click', function () {
        fieldCount++;
        appendFields(field_name + fieldCount, default_value + fieldCount);
    });

    jQuery('.remove-field').on('click', function (event) {
        event.preventDefault();
        var $this = jQuery(this); // Store a reference to 'this'
        let option_key = jQuery(this).data('field-name');
        let data = {
            'option_group_key': 'custom_fields',
            'option_key': option_key
        }
        prepare_ajax_request('remove_setting', data, function (response) {
            if(response === true)
            $this.parent(".custom-field").remove();
        });

    });

    jQuery('form#my-custom-form').on('submit', function (event) {
        // Prevent the default form submission
        event.preventDefault();
        save_custom_field();
    });

});