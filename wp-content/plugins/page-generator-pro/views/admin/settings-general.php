<div class="postbox">
    <h3 class="hndle"><?php _e( 'General', 'page-generator-pro' ); ?></h3>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Country Code', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'country_code', 'US' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-general[country_code]" size="1">
                <?php
                foreach ( $countries as $country_code => $country_name ) {
                    ?>
                    <option value="<?php echo $country_code; ?>"<?php selected( $setting, $country_code ); ?>>
                        <?php echo $country_name; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        
            <p class="description">
                <?php _e( 'The default country to select for any Country Code dropdowns within the Plugin.', 'page-generator-pro' ); ?>
            </p>
        </div>
    </div>
    
    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'CSS Prefix', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <input type="text" name="<?php echo $this->base->plugin->name; ?>-general[css_prefix]" value="<?php echo $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'css_prefix' ); ?>" class="widefat" />
            <p class="description">
                <?php 
                echo sprintf( 
                    __( 'If defined, CSS and Shortcode elements related to this Plugin will use the above prefix instead of %s.<br />
                        This can help hide %s from viewers and search engines.<br />
                        Leave blank to use the Plugin default.', $this->base->plugin->name ),
                    $this->base->plugin->name,
                    $this->base->plugin->displayName
                );
                ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Enable Revisions on Content Groups', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'revisions', '0' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-general[revisions]" size="1">
                <option value="1"<?php selected( $setting, '1' ); ?>><?php _e( 'Yes', 'page-generator-pro' ); ?></option>
                <option value="0"<?php selected( $setting, '0' ); ?>><?php _e( 'No', 'page-generator-pro' ); ?></option>
            </select>

            <p class="description">
                <?php 
                echo sprintf( 
                    __( 'Enables or disables <a href="%s">WordPress\' revisions</a> on Content Groups. Useful if you want to store a record of each saved draft or published update to a Content Group.', $this->base->plugin->name ),
                    'https://wordpress.org/support/article/revisions/'
                );
                ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Disable Custom Fields Dropdown on Pages', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'disable_custom_fields', '0' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-general[disable_custom_fields]" size="1">
                <option value="1"<?php selected( $setting, '1' ); ?>><?php _e( 'Yes', 'page-generator-pro' ); ?></option>
                <option value="0"<?php selected( $setting, '0' ); ?>><?php _e( 'No', 'page-generator-pro' ); ?></option>
            </select>

            <p class="description">
                <?php 
                _e( 'Enable this option to improve performance of the Page / Post editor.  This does not affect the use of any Custom Field Post Meta data.', $this->base->plugin->name );
                ?>
            </p>
        </div>
    </div>

    <div class="wpzinc-option">
        <div class="left">
            <strong><?php _e( 'Change Page Dropdown Fields', 'page-generator-pro' ); ?></strong>
        </div>
        <div class="right">
            <?php
            $setting = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-general', 'restrict_parent_page_depth', '0' );
            ?>
            <select name="<?php echo $this->base->plugin->name; ?>-general[restrict_parent_page_depth]" size="1">
                <option value="ajax_select"<?php selected( $setting, '1' ); ?>><?php _e( 'Search Dropdown Field', 'page-generator-pro' ); ?></option>
                <option value="1"<?php selected( $setting, '1' ); ?>><?php _e( 'ID Field', 'page-generator-pro' ); ?></option>
                <option value="0"<?php selected( $setting, '0' ); ?>><?php _e( 'No', 'page-generator-pro' ); ?></option>
            </select>

            <p class="description">
                <?php
                _e( 'Enable this option to replace the following dropdown fields with a Search or ID Field for performance:<br />
                    - Page Parent dropdown on hierarchical Post Types, such as Pages<br />
                    - Settings > Reading > Homepage, Posts page<br />
                    - Appearance > Customize', 'page-generator-pro' );
                ?>
                <br />
                <?php _e( 'This improves WordPress performance on sites with a large number of Pages.', 'page-generator-pro' ); ?>
            </p>
        </div>
    </div>
</div>