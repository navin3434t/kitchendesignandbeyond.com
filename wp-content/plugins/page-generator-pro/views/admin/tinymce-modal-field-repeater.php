<div class="wpzinc-option">
    <div class="full">
        <table class="widefat">
            <thead>
                <tr>
                    <?php
                    foreach ( $field['sub_fields'] as $sub_field_name => $sub_field ) {
                        ?>
                        <th><?php echo $sub_field['label']; ?></th>
                        <?php
                    }
                    ?>
                    <th><?php _e( 'Actions', 'page-generator-pro' ); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <td colspan="3">
                        <button class="button add-row" 
                            data-container="#<?php echo $shortcode['name']; ?>-<?php echo $field_name; ?>"
                            data-row="#<?php echo $shortcode['name']; ?>-<?php echo $field_name; ?>-row">
                            <?php _e( 'Add', 'page-generator-pro' ); ?>
                        </button>
                    </td>
                </tr>
            </tfoot>

            <tbody id="<?php echo $shortcode['name']; ?>-<?php echo $field_name; ?>">
                <tr id="<?php echo $shortcode['name']; ?>-<?php echo $field_name; ?>-row" class="hidden">
                    <?php
                    $sub_fields = $field['sub_fields'];
                    foreach ( $sub_fields as $field_name => $field ) {
                        ?>
                        <td>
                            <?php include( 'tinymce-modal-field.php' ); ?>
                        </td>
                        <?php
                    }
                    ?> 
                    <td>
                        <a href="#" class="delete-row" data-row="tr">
                            <?php _e( 'Delete', 'page-generator-pro' ); ?>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>