jQuery(document).ready(function ($) {
    
    // When to enable or disable the remove button depending on row count
    function updateRemoveButton(){
        // Get the row count
        var rowCount = $('#custom-array-tbody tr').length;
        $('#remove-row').prop('disabled', rowCount === 1);
    }
    
    // Add new row
    $('#add-row').on('click', function () {
       var lastRow = $('#custom-array-tbody tr:last');
       // We check if the input has something in it
       var hasValue = lastRow.find('input[type=text]').filter( function () {
            return $(this).val().trim() !== '';
       }).length > 0;
       
       // ADD a new row if the last one is not empty
       if(hasValue){
            var rowCount = $('#custom-array-tbody tr').length;
            // Create a row
            var newRow = '<tr>'+
                            '<td><input type="text" class="td-name" name="mha_staff['+rowCount+'][key1]" value="" placeholder="Name"></td>'+
                            '<td><input type="text" class="td-role" name="mha_staff['+rowCount+'][key2]" value="" placeholder="Role"></td>'+
                            '<td><button type="button" id="remove-row" class="button button-secondary td-delete"><span class="dashicons dashicons-dismiss"></span></button></td>'+
                        '</tr>';
            $('#custom-array-tbody').append(newRow);
            updateRemoveButton();
       }
       
    });
    
    // Delete row
    $('.custom-array-metabox').on('click', '#remove-row', function () {
        $(this).closest('tr').remove();
        updateRemoveButton();
    });
    
    // Initialization
    updateRemoveButton();
});
