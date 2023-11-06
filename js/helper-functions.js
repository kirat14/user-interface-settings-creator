export const prepare_ajax_request =
    function (action, data_param, callback = function (response) { console.log(response); }) {
        // Include the field pairs data in the AJAX request
        let data = {
            action: action,
            ...data_param,
            security: custom_script_vars.ajax_nonce,
        };

        jQuery.post(custom_script_vars.ajax_url, data, callback);
    }