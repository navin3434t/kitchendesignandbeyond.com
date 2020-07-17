<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content" style="position:relative;">
            <div id="postbox-container-1" class="postbox-container-1">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <!-- Actions -->
                    <div id="page-generator-pro-actions" class="postbox">
                        <button type="button" class="handlediv" aria-expanded="true"></button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Actions', 'page-generator-pro' ); ?></span>
                        </h2>

                        <div class="inside">
                            <?php 
                            // Append to element IDs
                            $bottom = '';
                            require( $this->base->plugin->folder . '/views/admin/generate-meta-box-actions.php' );
                            ?>
                        </div>
                    </div>

                    <!-- Generation -->
                    <div id="page-generator-pro-generation" class="postbox">
                        <button type="button" class="handlediv" aria-expanded="true"></button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Generation', 'page-generator-pro' ); ?></span>
                        </h2>

                        <div class="inside">
                            <?php 
                            require( $this->base->plugin->folder . '/views/admin/generate-meta-box-generation.php' );
                            ?>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div id="page-generator-pro-actions-bottom" class="postbox">
                        <button type="button" class="handlediv" aria-expanded="true"></button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Actions', 'page-generator-pro' ); ?></span>
                        </h2>

                        <div class="inside">
                            <?php 
                            // Append to element IDs
                            $bottom = 'bottom';
                            require( $this->base->plugin->folder . '/views/admin/generate-meta-box-actions.php' );
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="postbox-container-2" class="postbox-container-2">
                <!-- Term -->
                <div id="page-generator-pro-term" class="postbox">
                    <button type="button" class="handlediv" aria-expanded="true"></button>
                    <h2 class="hndle ui-sortable-handle">
                        <span><?php _e( 'Term', $this->base->plugin->name ); ?></span>
                    </h2>

                    <div class="inside">