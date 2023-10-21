import { fieldTemplate } from './template.js';

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



jQuery(document).ready(function () {
    console.log('document has been loaded');
    render_saved_settings()
    initializeFieldCount();
    jQuery('#add-custom-field').on('click', function () {
        fieldCount++;
        appendFields(field_name + fieldCount, default_value + fieldCount);
    });

});