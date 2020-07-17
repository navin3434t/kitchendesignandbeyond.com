<div class="postbox">
    <h3 class="hndle"><?php _e( 'OpenWeatherMap', 'page-generator-pro' ); ?></h3>
    
    <div class="wpzinc-option">
    	<div class="left">
    		<strong><?php _e( 'API Key', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
    	    <input type="text" name="<?php echo $this->base->plugin->name; ?>-open-weather-map[api_key]" value="<?php echo $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-open-weather-map', 'api_key' ); ?>" class="widefat" />
    	    <p class="description">
                <?php 
                echo sprintf( 
                    __( 'If you reach an API limit when attempting to use the OpenWeatherMap Shortcode, you\'ll need to use your own free API key.  <a href="%s" target="_blank">Click here</a> to read the step by step documentation to do this.', 'page-generator-pro' ),
                    $this->base->plugin->documentation_url . '/open-weather-map-settings/'
                ); ?>
            </p>
        </div>
    </div>
</div>