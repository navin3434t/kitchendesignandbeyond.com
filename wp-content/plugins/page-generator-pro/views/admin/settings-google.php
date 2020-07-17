<div class="postbox">
    <h3 class="hndle"><?php _e( 'Google Services', 'page-generator-pro' ); ?></h3>
    
    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Google Maps API Key', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <input type="text" name="<?php echo $this->base->plugin->name; ?>-google[google_maps_api_key]" value="<?php echo $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-google', 'google_maps_api_key' ); ?>" class="widefat" />
            <p class="description">
                <?php echo sprintf( __( 'If you want to use Street View or Direction views with the Google Maps Shortcode, you\'ll need to use your own API key.  These requests are billable by Google to you, so refer to <a href="%s" target="_blank">Google\'s Billing </a> to get the latest pricing.', 'page-generator-pro' ), 'https://developers.google.com/maps/documentation/embed/usage-and-billing#pricing-for-the-maps-embed-api' ); ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'YouTube Data API Key', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <input type="text" name="<?php echo $this->base->plugin->name; ?>-google[youtube_data_api_key]" value="<?php echo $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-google', 'youtube_data_api_key' ); ?>" class="widefat" />
            <p class="description">
                <?php echo sprintf( __( 'If you reach an API limit, or your YouTube shortcodes don\'t render, you\'ll need to use your own API key.  <a href="%s" target="_blank">Click here</a> to read the step by step documentation to do this.', 'page-generator-pro' ), $this->base->plugin->documentation_url . '/google-settings/' ); ?>
            </p>
        </div>
    </div>
</div>