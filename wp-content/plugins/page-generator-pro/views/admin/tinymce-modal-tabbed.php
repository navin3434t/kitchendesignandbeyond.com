<!-- .wp-core-ui ensures styles are applied on frontend editors for e.g. buttons.css -->
<form class="wpzinc-tinymce-popup wp-core-ui">
    <!-- Vertical Tabbed UI -->
    <div class="wpzinc-vertical-tabbed-ui">
        <!-- Tabs -->
        <ul class="wpzinc-nav-tabs wpzinc-js-tabs" 
            data-panels-container="#<?php echo $shortcode['name']; ?>-container"
            data-panel=".<?php echo $shortcode['name']; ?>"
            data-active="wpzinc-nav-tab-vertical-active"
            data-match-height="#page-generator-pro-modal-body-body">

            <?php
            // Output each Tab
            $first_tab = true;
            foreach ( $shortcode['tabs'] as $tab_name => $tab ) {
                ?>
                <li class="wpzinc-nav-tab<?php echo ( isset( $tab['class'] ) ? ' ' . $tab['class'] : '' ); ?>">
                    <a href="#<?php echo $shortcode['name'] . '-' . $tab_name; ?>"<?php echo ( $first_tab ? ' class="wpzinc-nav-tab-vertical-active"' : '' ); ?>>
                        <?php echo $tab['label']; ?>
                    </a>
                </li>
                <?php
                $first_tab = false;
            }
            ?>
        </ul>
        
        <!-- Content -->
        <div id="<?php echo $shortcode['name']; ?>-container" class="wpzinc-nav-tabs-content no-padding">
            <?php
            // Output each Tab Panel
            foreach ( $shortcode['tabs'] as $tab_name => $tab ) {
                ?>
                <div id="<?php echo $shortcode['name'] . '-' . $tab_name; ?>" class="<?php echo $shortcode['name']; ?>">
                    <div class="postbox">
                        <header>
                            <h3><?php echo $tab['label']; ?></h3>
                            <?php
                            if ( isset( $tab['description'] ) && ! empty( $tab['description'] ) ) {
                                ?>
                                <p class="description">
                                    <?php echo $tab['description']; ?>
                                </p>
                                <?php
                            }
                            ?>
                        </header>

                        <?php
                        // Iterate through this tab's field names
                        foreach ( $tab['fields'] as $field_name ) {
                            // Skip if this field doesn't exist
                            if ( ! isset( $shortcode['fields'][ $field_name ] ) ) {
                                continue;
                            }

                            // Fetch the field properties
                            $field = $shortcode['fields'][ $field_name ];

                            // Output Field
                            include( 'tinymce-modal-field-row.php' );
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

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