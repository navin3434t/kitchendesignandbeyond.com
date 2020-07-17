<div class="wpzinc-option"> 
    <div class="left">
        <label for="store_keywords"><?php _e( 'Store Keywords?', 'page-generator-pro' ); ?></strong>
    </div>
    <div class="right">
        <input type="checkbox" id="store_keywords" name="<?php echo $this->base->plugin->name; ?>[store_keywords]" value="1"<?php checked( $this->settings['store_keywords'], 1 ); ?> />
    
        <p class="description">
            <?php _e( 'If checked, each generated Page/Post will store keyword and term key/value pairs in the Page/Post\'s Custom Fields. This is useful for subsequently querying Custom Field Metadata in e.g. Related Links.', 'page-generator-pro' ); ?>
        </p>
    </div>
</div>

<!-- Custom Fields -->
<div id="custom-fields" class="is-sortable">
	<?php
	// Existing Custom Fields
	if ( is_array( $this->settings['meta'] ) && count( $this->settings['meta'] ) > 0 ) {
        foreach ( $this->settings['meta']['key'] as $i => $key ) {
            ?>
            <div class="wpzinc-option">	
            	<div class="left">
            		<strong>
            			<?php _e( 'Meta Key', 'page-generator-pro' ); ?>
            		</strong>
                	<input type="text" name="<?php echo $this->base->plugin->name; ?>[meta][key][]" value="<?php echo $key; ?>" placeholder="<?php _e( 'Meta Key', 'page-generator-pro' ); ?>" class="widefat" />
                    
                    <a href="#" class="move-row">
                        <span class="dashicons dashicons-move "></span>
                        <?php _e( 'Move', 'page-generator-pro' ); ?>
                    </a>

                    <a href="#" class="delete-row" data-row=".wpzinc-option">
                        <span class="dashicons dashicons-trash"></span>
                        <?php _e( 'Delete', 'page-generator-pro' ); ?>
                    </a>
                </div>
                <div class="right">
                	<strong><?php _e( 'Meta Value', 'page-generator-pro' ); ?></strong>
            		<textarea name="<?php echo $this->base->plugin->name; ?>[meta][value][]" placeholder="<?php _e( 'Meta Value', 'page-generator-pro' ); ?>" class="widefat wpzinc-autocomplete"><?php echo $this->settings['meta']['value'][ $i ]; ?></textarea>
            	</div>
            </div>
            <?php
        }
    }
    ?>
</div>

<!-- Hidden Option -->
<div id="custom-fields-row" class="wpzinc-option hidden">	
	<div class="left">
		<strong>
			<?php _e( 'Meta Key', 'page-generator-pro' ); ?>
		</strong>
    	<input type="text" name="<?php echo $this->base->plugin->name; ?>[meta][key][]" value="" placeholder="<?php _e( 'Meta Key', 'page-generator-pro' ); ?>" class="widefat" />
       
        <a href="#" class="move-row">
            <span class="dashicons dashicons-move "></span>
            <?php _e( 'Move', 'page-generator-pro' ); ?>
        </a>

        <a href="#" class="delete-row" data-row=".wpzinc-option">
            <span class="dashicons dashicons-trash"></span>
            <?php _e( 'Delete', 'page-generator-pro' ); ?>
        </a>
    </div>
    <div class="right">
    	<strong><?php _e( 'Meta Value', 'page-generator-pro' ); ?></strong>
		<textarea name="<?php echo $this->base->plugin->name; ?>[meta][value][]" placeholder="<?php _e( 'Meta Value', 'page-generator-pro' ); ?>" class="widefat wpzinc-autocomplete"></textarea>
	</div>
</div>

<!-- Add -->
<div class="wpzinc-option">	
    <button class="button add-row" data-container="#custom-fields" data-row="#custom-fields-row" data-class="wpzinc-option">
        <?php _e( 'Add Custom Field', 'page-generator-pro' ); ?>
    </button>
</div>