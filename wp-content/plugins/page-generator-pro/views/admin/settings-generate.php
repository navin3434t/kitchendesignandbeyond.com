<div class="postbox">
    <h3 class="hndle"><?php _e( 'Generate', 'page-generator-pro' ); ?></h3>

    <div class="wpzinc-option">
        <p class="description">
            <?php _e( 'Specifies default behaviour when Generating Content and Terms.', 'page-generator-pro' ); ?>
        </p>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <label for="log_enabled"><?php _e( 'Enable Logging?', 'page-generator-pro' ); ?></label>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_enabled', '0' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-generate[log_enabled]" id="log_enabled" size="1">
                <option value="1"<?php selected( $setting, '1' ); ?>><?php _e( 'Yes', 'page-generator-pro' ); ?></option>
                <option value="0"<?php selected( $setting, '0' ); ?>><?php _e( 'No', 'page-generator-pro' ); ?></option>
            </select>
        
            <p class="description">
                <?php 
                echo sprintf(
                    __( 'If enabled, the <a href="%s">Plugin Logs</a> will detail results of Content and Term Generation.', 'page-generator-pro' ),
                    $this->base->plugin->documentation_url . '/logs'
                );
                ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <label for="log_preserve_days"><?php _e( 'Preserve Logs', 'page-generator-pro' ); ?></label>
        </div>
        <div class="right">
            <input type="number" name="<?php echo $this->base->plugin->name; ?>-generate[log_preserve_days]" id="log_preserve_days" min="0" max="365" step="1" value="<?php echo $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_preserve_days', '7' ); ?>" />
            <?php _e( 'days', 'page-generator-pro' ); ?>
            
            <p class="description">
                <?php _e( 'The number of days to preserve logs for. Zero means logs are kept indefinitely.', 'page-generator-pro' ); ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Stop on Error', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'stop_on_error', '1' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-generate[stop_on_error]" size="1">
                <option value="1"<?php selected( $setting, '1' ); ?>><?php _e( 'Yes', 'page-generator-pro' ); ?></option>
                <option value="0"<?php selected( $setting, '0' ); ?>><?php _e( 'No', 'page-generator-pro' ); ?></option>
            </select>
        
            <p class="description">
                <?php _e( 'Whether to stop Content / Term Generation when an error occurs.', 'page-generator-pro' ); ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Use Performance Addon?', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'use_mu_plugin', '0' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-generate[use_mu_plugin]" size="1">
                <option value="1"<?php selected( $setting, '1' ); ?>><?php _e( 'Yes', 'page-generator-pro' ); ?></option>
                <option value="0"<?php selected( $setting, '0' ); ?>><?php _e( 'No', 'page-generator-pro' ); ?></option>
            </select>
        
            <p class="description">
                <?php _e( 'Experimental: If enabled, uses the Performance Addon Must-Use Plugin.  This can improve generation times and reduce memory usage on sites with several Plugins.', 'page-generator-pro' ); ?>
            </p>
        </div>
    </div>
</div>