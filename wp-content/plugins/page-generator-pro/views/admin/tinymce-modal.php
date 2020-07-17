<!-- .wp-core-ui ensures styles are applied on frontend editors for e.g. buttons.css -->
<form class="wpzinc-tinymce-popup wp-core-ui">
    <?php
    // Output each Field
    foreach ( $shortcode['fields'] as $field_name => $field ) {
        include( 'tinymce-modal-field-row.php' );
    }
    ?>
    
    <div class="wpzinc-option buttons has-wpzinc-vertical-tabbed-ui">
        <div class="left">
            <button type="button" class="close button"><?php _e( 'Cancel', 'page-generator-pro' ); ?></button>
        </div>
        <div class="right">
            <input type="hidden" name="shortcode" value="page-generator-pro-<?php echo $shortcode['name']; ?>" />
            <input type="button" value="<?php _e( 'Insert', 'page-generator-pro' ); ?>" class="button button-primary right" />
        </div>
    </div>
</form>