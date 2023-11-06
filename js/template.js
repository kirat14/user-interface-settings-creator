// template.js
export const fieldTemplate = `
    <div class="field-pair" data-count="{{fieldCount}}">
        <label for="{{fieldName}}">Field Name:</label>
        <input type="text" id="{{fieldName}}" name="{{fieldName}}" />
        
        <label for="{{defaultValue}}">Default Value:</label>
        <input type="text" id="{{defaultValue}}" name="{{defaultValue}}" />
        <button type="button" id="ui-remove-{{fieldName}}" class="remove-field">Remove</button>
    </div>
`;
