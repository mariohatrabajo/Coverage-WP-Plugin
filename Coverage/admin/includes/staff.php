<div class="custom-array-metabox">
    <table class="staff-table">
        <thead>
            <tr>
                <th class="th-name">Name</th>
                <th class="th-role">Role</th>
                <th class="th-delete"></th>
            </tr>
        </thead>
        <tbody id="custom-array-tbody">
            <?php
                if(!empty($custom_array_values)){
                    foreach($custom_array_values as $index => $row){
            ?>
                        <tr>
                            <td><input type="text" class="td-name" name="mha_staff[<?php echo $index; ?>][key1]" value="<?php echo $row['key1']; ?>" placeholder="Name"></td>
                            <td><input type="text" class="td-role" name="mha_staff[<?php echo $index; ?>][key2]" value="<?php echo $row['key2']; ?>" placeholder="Role"></td>
                            <td><button type="button" id="remove-row" class="button button-secondary td-delete"><span class="dashicons dashicons-dismiss"></span></button></td>
                        </tr>
            <?php 
                    } 
                }else { // We show an empty row
                    ?>
                        <tr>
                            <td><input type="text" class="td-name" name="mha_staff[0][key1]" value="" placeholder="Name"></td>
                            <td><input type="text" class="td-role" name="mha_staff[0][key2]" value="" placeholder="Role"></td>
                            <td><button type="button" id="remove-row" class="button button-secondary td-delete"><span class="dashicons dashicons-dismiss"></span></button></td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
    
    <p>
        <button type="button" class="button button-primary" id="add-row"><span class="dashicons dashicons-insert"></span>&nbsp;Add Staff</button>
    </p>
    
</div>