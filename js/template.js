// template.js
export const fieldTemplate = `
    <div class="field-pair" data-field-name="{{fieldName}}">
        <label for="{{fieldName}}">Field Name:</label>
        <input type="text" id="{{fieldName}}" name="{{fieldName}}" />
        
        <label for="{{defaultValue}}">Default Value:</label>
        <input type="text" id="{{defaultValue}}" name="{{defaultValue}}" />
    </div>
`;
