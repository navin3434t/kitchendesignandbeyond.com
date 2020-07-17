<div id="post-body" class="metabox-holder columns-2">
	<!-- Content -->
	<div id="post-body-content">
	
		<!-- Form Start -->
        <form name="post" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'License Key', $this->base->licensing->plugin->name ); ?></h3>

                    <?php
                    // If the license key is defined in wp-config as a constant, just display it here and don't offer the option to edit
                    if ( $this->base->licensing->is_license_key_a_constant() ) {
                        ?>
                        <div class="wpzinc-option">
                            <div class="full">
                                <input type="password" name="ignored" value="****************************************" class="widefat" disabled="disabled" />
                            </div>
                        </div>
                        <?php
                    } else {
                        // Get from options table
                        $license_key = get_option( $this->base->licensing->plugin->name . '_licenseKey' );
                        $input_type = ( $this->base->licensing->check_license_key_valid( false ) ? 'password' : 'text' );
                        ?>
                        <div class="wpzinc-option">
                            <div class="full">
                                <input type="<?php echo $input_type; ?>" name="<?php echo $this->base->licensing->plugin->name; ?>[licenseKey]" value="<?php echo $license_key; ?>" class="widefat" />
                            </div>
                        </div>
                        <div class="wpzinc-option">
                            <input type="submit" name="submit" value="<?php _e( 'Save', $this->base->licensing->plugin->name ); ?>" class="button button-primary" /> 
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!-- /postbox -->
			</div>
			<!-- /normal-sortables -->
	    </form>
	    <!-- /form end -->
		
	</div>
	<!-- /post-body-content -->
	
	<!-- Sidebar -->
	<div id="postbox-container-1" class="postbox-container">
		<!-- About -->
        <div class="postbox">
            <h3 class="hndle"><?php _e( 'About', $this->base->licensing->plugin->name ); ?></h3>
            
            <div class="wpzinc-option">
                <div class="left">
                    <strong><?php _e('Version', $this->base->licensing->plugin->name); ?></strong>
                </div>
                <div class="right">
                    <?php echo $this->base->licensing->plugin->version; ?>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="postbox">
            <div class="handlediv" title="Click to toggle"><br /></div>
            <h3 class="hndle"><span><?php _e('Support', $this->base->licensing->plugin->name); ?></span></h3>
            
            <div class="wpzinc-option">
                <a href="<?php echo ( isset( $this->base->licensing->plugin->documentation_url ) ? $this->base->licensing->plugin->documentation_url : '#' ); ?>" class="button" rel="noopener" target="_blank">
                    <?php _e( 'Documentation', $this->base->licensing->plugin->name ); ?>
                </a>
                <a href="<?php echo ( isset( $this->base->licensing->plugin->support_url ) ? $this->base->licensing->plugin->support_url : '#' ); ?>" class="button button-secondary" rel="noopener" target="_blank">
                    <?php _e( 'Support', $this->base->licensing->plugin->name ); ?>
                </a>
            </div>
        </div>
	</div>
	<!-- /postbox-container -->
</div>