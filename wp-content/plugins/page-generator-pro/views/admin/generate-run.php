<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $this->base->plugin->displayName; ?>

        <span>
            <?php echo sprintf( __( 'Generating &quot;%s&quot;', 'page-generator-pro' ), $settings['title'] ); ?>
        </span>
    </h1>

    <hr class="wp-header-end" />

    <div class="wrap-inner">
        <p>
        	<?php 
            echo sprintf( 
                __( 'Please be patient while content is generated. This can take a while if you have a lot of Pages to generate, and/or you are using Page Generator Shortcodes.
                <a href="%s/generate/#page-generation" target="_blank">Read the Documentation</a> to understand why.', 'page-generator-pro' ),
                $this->base->plugin->documentation_url
            );
            ?>
            <br />

        	<?php _e( 'Do not navigate away from this page until this script is done or all items will not be generated.
        	You will be notified via this page when the process is completed.', 'page-generator-pro' ); ?>
        </p>

        <!-- Progress Bar -->
        <div id="progress-bar"></div>
        <div id="progress">
            <span id="progress-number">0</span>
            <span> / <?php echo $settings['numberOfPosts']; ?></span>
        </div>

        <!-- Status Updates -->
        <div id="log">
            <ul></ul>
        </div>

        <p>
            <!-- Cancel Button -->
            <a href="post.php?post=<?php echo $id; ?>&amp;action=edit" class="button wpzinc-button-red page-generator-pro-generate-cancel-button">
                <?php _e( 'Stop Generation', 'page-generator-pro' ); ?>
            </a>

            <!-- Return Button (display when generation routine finishes -->
            <a href="<?php echo $return_url; ?>" class="button button-primary page-generator-pro-generate-return-button">
                <?php _e( 'Return to Group', 'page-generator-pro' ); ?>
            </a>
        </p>
    </div>

    <!-- Triggers AJAX request to run numberOfPosts -->
    <script type="text/javascript">
        jQuery( document ).ready( function( $ ) {

            var page_generator_pro_cancelled = false,
                page_generator_pro_generate_stop_on_error = <?php echo $settings['stop_on_error']; ?>;
            
            $('#progress-bar').synchronous_request( {
                url:                ajaxurl,
                number_requests:    <?php echo $settings['numberOfPosts'] + $settings['resumeIndex']; ?>,
                offset:             <?php echo $settings['resumeIndex']; ?>,
                data: {
                    id:     <?php echo $id; ?>,
                    action: 'page_generator_pro_generate_<?php echo $type; ?>'   
                },
                onRequestSuccess: function( response, currentIndex ) {

                    // Update counter
                    $( '#progress-number' ).text( ( currentIndex + 1 ) );

                    if ( response.success ) {
                        // Define message and CSS class
                        var message = response.data.message + ' <a href="' + response.data.url + '" target="_blank">' + response.data.url + '</a><br />Time: ' + response.data.duration + ' seconds. Memory Usage / Peak: ' + response.data.memory_usage + '/' + response.data.memory_peak_usage + 'MB',
                            css_class = ( response.data.generated ? 'success' : 'warning' );

                        for ( var keyword in response.data.keywords_terms ) {
                            message += '<br />{' + keyword + '}: ' + response.data.keywords_terms[ keyword ];
                        }

                        $( '#log ul' ).append( '<li class="' + css_class + '">' + message + '</li>' );

                        // Run the next request, unless the user clicked the 'Stop Generation' button
                        if ( page_generator_pro_cancelled == true ) {
                            this.onFinished();
                            return false;
                        }

                        // Run the next request
                        return true;
                    } else {
                        // Something went wrong
                        $( '#log ul' ).append( '<li class="error">' + response.data + '</a></li>' ); 

                        // Run the next request, unless the user clicked the 'Stop Generation' button
                        if ( page_generator_pro_cancelled == true ) {
                            this.onFinished();
                            return false;
                        }

                        // Depending on the global Plugin settings, either continue generation or exit
                        if ( ! page_generator_pro_generate_stop_on_error ) {
                            // Run the next request
                            return true;
                        }

                        // Don't run any more requests
                        this.onFinished();
                        return false;
                    }

                },

                onRequestError: function( xhr, textStatus, e, currentIndex ) {

                    // Update counter
                    $( '#progress-number' ).text( ( currentIndex + 1 ) );

                    $( '#log ul' ).append( '<li class="error">' + xhr.status + ' ' + xhr.statusText + '</li>' );

                    // Run the next request, unless the user clicked the 'Stop Generation' button
                    if ( page_generator_pro_cancelled == true ) {
                        this.onFinished();
                        return false;
                    }

                    // Depending on the global Plugin settings, either continue generation or exit
                    if ( ! page_generator_pro_generate_stop_on_error ) {
                        // Run the next request
                        return true;
                    }

                    // Don't run any more requests
                    this.onFinished();
                    return false;

                },

                onFinished: function() {

                    // If the user clicked the 'Stop Generation' button, show that in the log.
                    if ( page_generator_pro_cancelled == true ) {
                        $( '#log ul' ).append( '<li class="success">Process cancelled by user</li>' );
                    } else {
                        $( '#log ul' ).append( '<li class="success">Finished</li>' );
                    }

                    // Hide the 'Stop Generation' button
                    $( 'a.page-generator-pro-generate-cancel-button' ).hide();

                    // Show the 'Return to Group' button
                    $( 'a.page-generator-pro-generate-return-button' ).removeClass( 'page-generator-pro-generate-return-button' );

                    // Send an AJAX request to remove the generating flag on the Group
                    $.ajax( {
                        url:        ajaxurl,
                        type:       'POST',
                        async:      true,
                        data:      {
                            id:     <?php echo $id; ?>,
                            action: 'page_generator_pro_generate_<?php echo $type; ?>_finished'   
                        },
                        error: function( a, b, c ) {
                        },
                        success: function( result ) {
                        }
                    } );

                }
            } );

            // Sets the page_generator_pro_cancelled flag to true when the user clicks the 'Stop Generation' button
            $( 'a.page-generator-pro-generate-cancel-button' ).on( 'click', function( e ) {
                e.preventDefault();
                page_generator_pro_cancelled = true;
            } );
        } );  
    </script>
</div>