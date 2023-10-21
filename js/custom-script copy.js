

jQuery(document).ready(function($) {
    // Function to handle the removal of a custom field
    function removeCustomField() {
        $(this).closest('.custom-field').remove();
    }

    // Add Custom Field button click event
    $('#add-custom-field').on('click', function() {
        var fieldName = prompt('Enter the name for the new field:');
        var defaultValue = prompt('Enter the default value for the new field:');

        if (fieldName && defaultValue) {
            var newField = `
                <div class="custom-field">
                    <label for="${fieldName}">Custom Field Label:</label>
                    <input type="text" id="${fieldName}" name="custom_fields[${fieldName}]" value="${defaultValue}" />
                    <button class="remove-field">Remove</button>
                </div>
            `;

            $('#custom-fields-container').append(newField);
        } else {
            alert('Field creation canceled. Both name and default value are required.');
        }
    });

    // Save Fields using AJAX
    $('#custom-fields-container').on('change', 'input[type="text"]', function() {
        var data = {
            action: 'save_custom_field',
            field_name: $(this).attr('name'),
            field_value: $(this).val(),
            security: custom_script_vars.ajax_nonce,
        };

        $.post(custom_script_vars.ajax_url, data, function(response) {
            console.log('Field Saved:', response);
        });
    });

    // Remove Field button click event
    $('#custom-fields-container').on('click', '.remove-field', removeCustomField);
});