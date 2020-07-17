<?php
/**
 * WP-CLI Command: Generate Terms
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 1.6.1
 */
class Page_Generator_Pro_CLI_Generate_Terms {

    /**
     * Generates Terms based on the given group's settings
     *
     * @since   1.6.1
     *
     * @param   array   $args           Group ID (123) or Group IDs (123,456)
     * @param   array   $arguments      Array of associative arguments
     */
    public function __invoke( $args, $arguments ) {

        WP_CLI::log( 'Generate: Terms: Started' );

        // Add the group ID(s) to the associative arguments
        if ( strpos( $args[0], ',' ) !== false ) {
            $arguments['group_id'] = explode( ',', $args[0] );
        } else {
            $arguments['group_id'] = absint( $args[0] );
        }

        // If the group_id argument is an array, we're generating multiple groups
        if ( is_array( $arguments['group_id'] ) ) {
            foreach ( $arguments['group_id'] as $group_id ) {
                WP_CLI::do_hook( 'page_generator_pro_generate_terms_before', $group_id, false );
                $this->generate_terms( $group_id, $arguments );
                WP_CLI::do_hook( 'page_generator_pro_generate_terms_after', $group_id, false );
            }
        } else {
            WP_CLI::do_hook( 'page_generator_pro_generate_terms_before', $arguments['group_id'], false );
            $this->generate_terms( $arguments['group_id'], $arguments );
            WP_CLI::do_hook( 'page_generator_pro_generate_terms_after', $arguments['group_id'], false );
        }

        WP_CLI::log( 'Generated: Terms: Finished' );
    
    }

    /**
     * Generates Terms based on the given group's settings
     *
     * @since   1.6.1
     *
     * @param   int     $group_id       Group ID
     * @param   array   $arguments      Array of associative arguments
     */
    private function generate_terms( $group_id, $arguments ) {

        // If this Group has a request to cancel generation, silently clear the status, system and cancel
        // flags before performing further checks on whether we should generate
        if ( Page_Generator_Pro()->get_class( 'groups_terms' )->cancel_generation_requested( $group_id ) ) {
            Page_Generator_Pro()->get_class( 'groups_terms' )->stop_generation( $group_id );
        }

        // If the group is already generating, bail
        if ( Page_Generator_Pro()->get_class( 'groups_terms' )->is_generating( $group_id ) ) {
            WP_CLI::error( 
                sprintf(
                    __( 'Group ID #%s: Generation is already running via %s', 'page-generator-pro' ),
                    $group_id,
                    Page_Generator_Pro()->get_class( 'groups_terms' )->get_system( $group_id )
                ),
                false
            );
            return;
        }

        // Get group
        $group = Page_Generator_Pro()->get_class( 'groups_terms' )->get_settings( $group_id );
        if ( ! $group ) {
            WP_CLI::error( 
                sprintf(
                    __( 'Group ID #%s: Could not get settings', 'page-generator-pro' ),
                    $group_id
                ),
                false
            );
            return;
        }

        // Replace the Group's settings with any arguments specified in the command
        if ( isset( $arguments['number_of_terms'] ) && $arguments['number_of_terms'] > 0 ) {
            $group['numberOfPosts'] = absint( $arguments['number_of_terms'] );
        }
        if ( isset( $arguments['resume_index'] ) && $arguments['resume_index'] > 0 ) {
            $group['resumeIndex'] = absint( $arguments['resume_index'] );
        }

        // Calculate how many pages could be generated
        $number_of_pages_to_generate = Page_Generator_Pro()->get_class( 'generate' )->get_max_number_of_pages( $group );
        if ( is_wp_error( $number_of_pages_to_generate ) ) {
            WP_CLI::error( 
                sprintf(
                    __( 'Group ID #%s: %s', 'page-generator-pro' ),
                    $group_id,
                    $result->get_error_message()
                ),
                false
            );
            return;
        }

        // If no limit specified, set one now
        if ( empty( $group['numberOfPosts'] ) ) {
            if ( $group['method'] == 'random' ) {
                $group['numberOfPosts'] = 10;
            } else {
                $group['numberOfPosts'] = $number_of_pages_to_generate;
            }
        }

        // If the requested Number of Posts exceeds the Number of Pages that could be generated,
        // set Number of Posts to match the Number of Pages that could be generated
        if ( $group['numberOfPosts'] > $number_of_pages_to_generate ) {
            $group['numberOfPosts'] = $number_of_pages_to_generate;
        }

        // Add Plugin Settings
        $group['stop_on_error'] = (int) Page_Generator_Pro()->get_class( 'settings' )->get_setting( Page_Generator_Pro()->plugin->name . '-generate', 'stop_on_error', '1' );

        // Set a flag to denote that this Group is generating content via the CLI
        Page_Generator_Pro()->get_class( 'groups_terms' )->start_generation( $group_id, 'generating', 'cli' );
        
        // Run a loop to generate each Term
        for ( $i = $group['resumeIndex']; $i < ( $group['numberOfPosts'] + $group['resumeIndex'] ); $i++ ) {
            // If cancel generation was requested, exit now
            if ( Page_Generator_Pro()->get_class( 'groups_terms' )->cancel_generation_requested( $group_id ) ) {
                Page_Generator_Pro()->get_class( 'groups_terms' )->stop_generation( $group_id );
                WP_CLI::error( 'Group ID #' . $group_id . ': Generation cancelled by User' );
                return;
            }

            // Run
            $result = Page_Generator_Pro()->get_class( 'generate' )->generate_term( $group_id, $i, false, 'cli' );
            
            // Bail if something went wrong
            if ( is_wp_error( $result ) ) {
                // If we're stopping on error, remove generating flag and exit
                if ( $group['stop_on_error'] ) {
                    Page_Generator_Pro()->get_class( 'groups_terms' )->stop_generation( $group_id );  
                }
                
                // Show error, stopping on error if required in the Plugin Settings
                WP_CLI::error( 'Group ID #' . $group_id . ': ' . ( $i + 1 ) . '/' . ( $group['numberOfPosts'] + $group['resumeIndex'] ) . ': ' . $result->get_error_message(), $group['stop_on_error'] );
                
                // If here, we're not stopping on error, so continue the loop
                continue;
            }

            // Build message and output
            $message = array(
                sprintf( 
                    __( 'Group #%s: %s/%s: %s. Permalink: %s. Time: %s seconds. Memory Usage / Peak: %s/%sMB', 'page-generator-pro' ),
                    $group_id,
                    ( $i + 1 ),
                    ( $group['numberOfPosts'] + $group['resumeIndex'] ),
                    $result['message'],
                    $result['url'],
                    $result['duration'],
                    $result['memory_usage'],
                    $result['memory_peak_usage']
                ),
            );
            foreach ( $result['keywords_terms'] as $keyword => $term ) {
                $message[] = '{' . $keyword . '}: ' . ( strlen( $term ) > 50 ? substr( $term, 0, 50 ) . '...' : $term );
            }
            $message[] = '--';
            WP_CLI::success( implode( "\n", $message ) );
        }

        // Stop generation
        Page_Generator_Pro()->get_class( 'groups_terms' )->stop_generation( $group_id ); 

    } 

}

/**
 * WP-CLI Command: Terms Test
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.6.1
 */
class Page_Generator_Pro_CLI_Test_Terms {

    /**
     * Generates one Term based on the given group's settings
     *
     * @since   1.6.1
     *
     * @param   array   $args   Array of arguments (group ID, current index)
     */
    public function __invoke( $args ) {

        // Sanitize inputs
        $arguments['group_id'] = absint( $args[0] );

        // Run
        $start  = microtime( true );
        $result = Page_Generator_Pro()->get_class( 'generate' )->generate_term( $arguments['group_id'], 0, true );
        $end    = microtime( true );

        WP_CLI::do_hook( 'page_generator_pro_generate_terms_after', $arguments['group_id'], true );

        // Output success or error
        if ( is_wp_error( $result ) ) {
            WP_CLI::error( $result->get_error_message() );
        } else {
            // Output success result and performance
            $data = array(
                'url'               => $result['url'],
                'keywords_terms'    => $result['keywords_terms'],
                'generated'         => $result['generated'],
                'message'           => $result['message'],

                // Performance
                'start'             => $start,
                'end'               => $end,
                'duration'          => round ( ( $end - $start ), 2 ),
                'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
                'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
            );

            // Build message and output
            $message = array(
                sprintf( 
                    __( 'Group #%s: %s/%s: %s. Permalink: %s. Time: %s seconds. Memory Usage / Peak: %s/%sMB', 'page-generator-pro' ),
                    $group_id,
                    ( $i + 1 ),
                    ( $group['numberOfPosts'] + $group['resumeIndex'] ),
                    $data['message'],
                    $data['url'],
                    $data['duration'],
                    $data['memory_usage'],
                    $data['memory_peak_usage']
                ),
            );
            foreach ( $data['keywords_terms'] as $keyword => $term ) {
                $message[] = '{' . $keyword . '}: ' . ( strlen( $term ) > 50 ? substr( $term, 0, 50 ) . '...' : $term );
            }
            WP_CLI::success( implode( "\n", $message ) );
        }

    } 

}

/**
 * WP-CLI Command: Delete Generated Terms
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.7.6
 */
class Page_Generator_Pro_CLI_Delete_Generated_Terms {

    /**
     * Deletes all generated content for the given Group ID
     *
     * @since   1.7.6
     *
     * @param   array   $args   Array of arguments (group ID)
     */
    public function __invoke( $args ) {

        // Sanitize inputs
        $arguments['group_id']       = absint( $args[0] );

        // Run
        $start  = microtime( true );
        $result = Page_Generator_Pro()->get_class( 'generate' )->delete_terms( $arguments['group_id'] );
        $end    = microtime( true );

        // Output success or error
        if ( is_wp_error( $result ) ) {
            WP_CLI::error( $result->get_error_message() );
        } else {
            $data = array(
                'start'             => $start,
                'end'               => $end,
                'duration'          => round ( ( $end - $start ), 2 ),
                'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
                'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
            );

            // Build message and output
            $message = array(
                'Group ID #' . $arguments['group_id'] . ': Deleted Generated Terms in ' . $data['duration'] . ' seconds.  Memory Usage / Peak: ' . $data['memory_usage'] . '/' . $data['memory_peak_usage'] . 'MB',
            );
            WP_CLI::success( implode( "\n", $message ) );
        }

    } 

}

/**
 * WP-CLI Command: List Term Groups
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  2.6.5
 */
class Page_Generator_Pro_CLI_List_Term_Groups {

    /**
     * Lists all Page Generator Pro Groups in table format within the CLI
     *
     * @since   2.6.5
     */
    public function __invoke() {

        // Get all Term Groups
        $groups = Page_Generator_Pro()->get_class( 'groups_terms' )->get_all();

        // Build array for WP-CLI Table
        $groups_table = array();
        foreach ( $groups as $group_id => $group ) {
            $groups_table[] = array(
                'ID'                        => $group_id,
                'title'                     => $group['title'],
                'generated_term_count'      => $group['generated_pages_count'],
            );
        }

        // Output
        \WP_CLI\Utils\format_items( 'table', $groups_table, array_keys( $groups_table[0] ) );

    } 

}

/**
 * WP-CLI Command: Generate Content
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 1.2.1
 */
class Page_Generator_Pro_CLI_Generate_Content {

    /**
     * Generates Pages, Posts or CPTs based on the given group's settings
     *
     * @since   1.2.1
     *
     * @param   array   $args           Group ID (123) or Group IDs (123,456)
     * @param   array   $arguments      Array of associative arguments
     */
    public function __invoke( $args, $arguments ) {

        WP_CLI::log( 'Generate: Content: Started' );

        // Add the group ID(s) to the associative arguments
        if ( strpos( $args[0], ',' ) !== false ) {
            $arguments['group_id'] = explode( ',', $args[0] );
        } else {
            $arguments['group_id'] = absint( $args[0] );
        }

        // If the group_id argument is an array, we're generating multiple groups
        if ( is_array( $arguments['group_id'] ) ) {
            foreach ( $arguments['group_id'] as $group_id ) {
                WP_CLI::do_hook( 'page_generator_pro_generate_content_before', $group_id, false );
                $this->generate( $group_id, $arguments );
                WP_CLI::do_hook( 'page_generator_pro_generate_content_after', $group_id, false );
            }
        } else {
            WP_CLI::do_hook( 'page_generator_pro_generate_content_before', $arguments['group_id'], false );
            $this->generate( $arguments['group_id'], $arguments );
            WP_CLI::do_hook( 'page_generator_pro_generate_content_after', $arguments['group_id'], false );
        }

        WP_CLI::log( 'Generated: Content: Finished' );
    
    }

    /**
     * Generates Pages, Posts or CPTs based on the given group's settings
     *
     * @since   1.5.3
     *
     * @param   int     $group_id       Group ID
     * @param   array   $arguments      Array of associative arguments
     */
    private function generate( $group_id, $arguments ) {

        // If this Group has a request to cancel generation, silently clear the status, system and cancel
        // flags before performing further checks on whether we should generate
        if ( Page_Generator_Pro()->get_class( 'groups' )->cancel_generation_requested( $group_id ) ) {
            Page_Generator_Pro()->get_class( 'groups' )->stop_generation( $group_id );
        }

        // If the group is already generating, bail
        if ( Page_Generator_Pro()->get_class( 'groups' )->is_generating( $group_id ) ) {
            WP_CLI::error( 
                sprintf(
                    __( 'Group ID #%s: Generation is already running via %s', 'page-generator-pro' ),
                    $group_id,
                    Page_Generator_Pro()->get_class( 'groups' )->get_system( $group_id )
                ),
                false
            );
            return;
        }

        // Get group
        $group = Page_Generator_Pro()->get_class( 'groups' )->get_settings( $group_id );
        if ( ! $group ) {
            WP_CLI::error( 
                sprintf(
                    __( 'Group ID #%s: Could not get settings', 'page-generator-pro' ),
                    $group_id
                ),
                false
            );
            return;
        }

        // Replace the Group's settings with any arguments specified in the command
        if ( isset( $arguments['number_of_posts'] ) && $arguments['number_of_posts'] > 0 ) {
            $group['numberOfPosts'] = absint( $arguments['number_of_posts'] );
        }
        if ( isset( $arguments['resume_index'] ) && $arguments['resume_index'] > 0 ) {
            $group['resumeIndex'] = absint( $arguments['resume_index'] );
        }

        // Calculate how many pages could be generated
        $number_of_pages_to_generate = Page_Generator_Pro()->get_class( 'generate' )->get_max_number_of_pages( $group );
        if ( is_wp_error( $number_of_pages_to_generate ) ) {
            WP_CLI::error( 
                sprintf(
                    __( 'Group ID #%s: %s', 'page-generator-pro' ),
                    $group_id,
                    $result->get_error_message()
                ),
                false
            );
            return;
        }

        // If no limit specified, set one now
        if ( empty( $group['numberOfPosts'] ) ) {
            if ( $group['method'] == 'random' ) {
                $group['numberOfPosts'] = 10;
            } else {
                $group['numberOfPosts'] = $number_of_pages_to_generate;
            }
        }

        // If the requested Number of Posts exceeds the Number of Pages that could be generated,
        // set Number of Posts to match the Number of Pages that could be generated
        if ( $group['numberOfPosts'] > $number_of_pages_to_generate ) {
            $group['numberOfPosts'] = $number_of_pages_to_generate;
        }

        // Add Plugin Settings
        $group['stop_on_error'] = (int) Page_Generator_Pro()->get_class( 'settings' )->get_setting( Page_Generator_Pro()->plugin->name . '-generate', 'stop_on_error', '1' );

        // Set a flag to denote that this Group is generating content via the CLI
        Page_Generator_Pro()->get_class( 'groups' )->start_generation( $group_id, 'generating', 'cli' );
        
        // Run a loop to generate each page
        for ( $i = $group['resumeIndex']; $i < ( $group['numberOfPosts'] + $group['resumeIndex'] ); $i++ ) {
            // If cancel generation was requested, exit now
            if ( Page_Generator_Pro()->get_class( 'groups' )->cancel_generation_requested( $group_id ) ) {
                Page_Generator_Pro()->get_class( 'groups' )->stop_generation( $group_id );
                WP_CLI::error( 'Group ID #' . $group_id . ': Generation cancelled by User' );
                return;
            }

            // Run
            $start  = microtime( true );
            $result = Page_Generator_Pro()->get_class( 'generate' )->generate_content( $group_id, $i, false, 'cli' );
            $end    = microtime( true );

            // Bail if something went wrong
            if ( is_wp_error( $result ) ) {
                // If we're stopping on error, remove generating flag and exit
                if ( $group['stop_on_error'] ) {
                    Page_Generator_Pro()->get_class( 'groups' )->stop_generation( $group_id );  
                }
                
                // Show error, stopping on error if required in the Plugin Settings
                WP_CLI::error( 'Group ID #' . $group_id . ': ' . ( $i + 1 ) . '/' . ( $group['numberOfPosts'] + $group['resumeIndex'] ) . ': ' . $result->get_error_message(), $group['stop_on_error'] );
                
                // If here, we're not stopping on error, so continue the loop
                continue;
            }

            // Output success result and performance
            $data = array(
                'url'               => $result['url'],
                'keywords_terms'    => $result['keywords_terms'],
                'generated'         => $result['generated'],
                'message'           => $result['message'],

                // Performance
                'start'             => $start,
                'end'               => $end,
                'duration'          => round ( ( $end - $start ), 2 ),
                'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
                'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
            );
        
            // Build message and output
            $message = array(
                sprintf( 
                    __( 'Group #%s: %s/%s: %s. Permalink: %s. Time: %s seconds. Memory Usage / Peak: %s/%sMB', 'page-generator-pro' ),
                    $group_id,
                    ( $i + 1 ),
                    ( $group['numberOfPosts'] + $group['resumeIndex'] ),
                    $data['message'],
                    $data['url'],
                    $data['duration'],
                    $data['memory_usage'],
                    $data['memory_peak_usage']
                ),
            );
            foreach ( $data['keywords_terms'] as $keyword => $term ) {
                $message[] = '{' . $keyword . '}: '  . ( strlen( $term ) > 50 ? substr( $term, 0, 50 ) . '...' : $term );
            }
            $message[] = '--';
            WP_CLI::success( implode( "\n", $message ) );
        }

        // Stop generation
        Page_Generator_Pro()->get_class( 'groups' )->stop_generation( $group_id );

    } 

}

/**
 * WP-CLI Command: Test Content
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.2.1
 */
class Page_Generator_Pro_CLI_Test_Content {

    /**
     * Generates one Page in Draft mode based on the given group's settings
     *
     * @since   1.2.1
     *
     * @param   array   $args   Array of arguments (group ID, current index)
     */
    public function __invoke( $args ) {

        // Sanitize inputs
        $arguments['group_id']       = absint( $args[0] );

        // Run
        $start  = microtime( true );
        $result = Page_Generator_Pro()->get_class( 'generate' )->generate_content( $arguments['group_id'], 0, true );
        $end    = microtime( true );

        WP_CLI::do_hook( 'page_generator_pro_generate_content_after', $arguments['group_id'], true );

        // Output success or error
        if ( is_wp_error( $result ) ) {
            WP_CLI::error( $result->get_error_message() );
        } else {
            $data = array(
                'url'               => $result['url'],
                'keywords_terms'    => $result['keywords_terms'],
                'start'             => $start,
                'end'               => $end,
                'duration'          => round ( ( $end - $start ), 2 ),
                'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
                'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
            );

            // Build message and output
            $message = array(
                sprintf( 
                    __( 'Group #%s: %s/%s: %s. Permalink: %s. Time: %s seconds. Memory Usage / Peak: %s/%sMB', 'page-generator-pro' ),
                    $group_id,
                    ( $i + 1 ),
                    ( $group['numberOfPosts'] + $group['resumeIndex'] ),
                    $data['message'],
                    $data['url'],
                    $data['duration'],
                    $data['memory_usage'],
                    $data['memory_peak_usage']
                ),
            );
            foreach ( $data['keywords_terms'] as $keyword => $term ) {
                $message[] = '{' . $keyword . '}: '  . ( strlen( $term ) > 50 ? substr( $term, 0, 50 ) . '...' : $term );
            }
            WP_CLI::success( implode( "\n", $message ) );
        }

    } 

}

/**
 * WP-CLI Command: Trash Generated Content
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.9.9
 */
class Page_Generator_Pro_CLI_Trash_Generated_Content {

    /**
     * Deletes all generated content for the given Group ID
     *
     * @since   1.7.6
     *
     * @param   array   $args   Array of arguments (group ID)
     */
    public function __invoke( $args ) {

        // Sanitize inputs
        $arguments['group_id']       = absint( $args[0] );

        // Run
        $start  = microtime( true );
        $result = Page_Generator_Pro()->get_class( 'generate' )->trash_content( $arguments['group_id'] );
        $end    = microtime( true );

        // Output success or error
        if ( is_wp_error( $result ) ) {
            WP_CLI::error( $result->get_error_message() );
        } else {
            $data = array(
                'start'             => $start,
                'end'               => $end,
                'duration'          => round ( ( $end - $start ), 2 ),
                'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
                'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
            );

            // Build message and output
            $message = array(
                'Group ID #' . $arguments['group_id'] . ': Trashed Generated Content in ' . $data['duration'] . ' seconds.  Memory Usage / Peak: ' . $data['memory_usage'] . '/' . $data['memory_peak_usage'] . 'MB',
            );
            WP_CLI::success( implode( "\n", $message ) );
        }

    } 

}

/**
 * WP-CLI Command: Delete Generated Content
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.7.6
 */
class Page_Generator_Pro_CLI_Delete_Generated_Content {

    /**
     * Deletes all generated content for the given Group ID
     *
     * @since   1.7.6
     *
     * @param   array   $args   Array of arguments (group ID)
     */
    public function __invoke( $args ) {

        // Sanitize inputs
        $arguments['group_id']       = absint( $args[0] );

        // Run
        $start  = microtime( true );
        $result = Page_Generator_Pro()->get_class( 'generate' )->delete_content( $arguments['group_id'] );
        $end    = microtime( true );

        // Output success or error
        if ( is_wp_error( $result ) ) {
            WP_CLI::error( $result->get_error_message() );
        } else {
            $data = array(
                'start'             => $start,
                'end'               => $end,
                'duration'          => round ( ( $end - $start ), 2 ),
                'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
                'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
            );

            // Build message and output
            $message = array(
                'Group ID #' . $arguments['group_id'] . ': Deleted Generated Content in ' . $data['duration'] . ' seconds.  Memory Usage / Peak: ' . $data['memory_usage'] . '/' . $data['memory_peak_usage'] . 'MB',
            );
            WP_CLI::success( implode( "\n", $message ) );
        }

    } 

}

/**
 * WP-CLI Command: Groups List
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  1.2.1
 */
class Page_Generator_Pro_CLI_List_Content_Groups {

    /**
     * Lists all Page Generator Pro Groups in table format within the CLI
     *
     * @since   1.5.3
     */
    public function __invoke() {

        // Get all Groups
        $groups = Page_Generator_Pro()->get_class( 'groups' )->get_all();

        // Build array for WP-CLI Table
        $groups_table = array();
        foreach ( $groups as $group_id => $group ) {
            $groups_table[] = array(
                'ID'                        => $group_id,
                'title'                     => $group['title'],
                'description'               => $group['description'],
                'generated_pages_count'     => $group['generated_pages_count'],
            );
        }

        // Output
        \WP_CLI\Utils\format_items( 'table', $groups_table, array_keys( $groups_table[0] ) );

    } 

}

// Register WP-CLI commands here

// Generate Content
// Backward compat command
WP_CLI::add_command( 'page-generator-pro-generate', 'Page_Generator_Pro_CLI_Generate_Content', array(
    'shortdesc' => __( 'Generates Pages / Posts / Custom Post Types for the given Generate Group ID.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'number_of_posts',
            'optional' => true,
            'multiple' => false,
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'resume_index',
            'optional' => true,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Test Content
// Backward compat command
WP_CLI::add_command( 'page-generator-pro-test', 'Page_Generator_Pro_CLI_Test_Content', array(
    'shortdesc' => __( 'Generates one Page / Post / CPT for the given Generate Group ID, storing it as a Draft. Use this to test your settings.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Generate Content
WP_CLI::add_command( 'page-generator-pro-generate-content', 'Page_Generator_Pro_CLI_Generate_Content', array(
    'shortdesc' => __( 'Generates Pages / Posts / Custom Post Types for the given Generate Group ID.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'number_of_posts',
            'optional' => true,
            'multiple' => false,
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'resume_index',
            'optional' => true,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Test Content
WP_CLI::add_command( 'page-generator-pro-test-content', 'Page_Generator_Pro_CLI_Test_Content', array(
    'shortdesc' => __( 'Generates one Page / Post / CPT for the given Generate Group ID, storing it as a Draft. Use this to test your settings.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Trash Content
WP_CLI::add_command( 'page-generator-pro-trash-generated-content', 'Page_Generator_Pro_CLI_Trash_Generated_Content', array(
    'shortdesc' => __( 'Trashes all generated content for the given Group ID.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Delete Content
WP_CLI::add_command( 'page-generator-pro-delete-generated-content', 'Page_Generator_Pro_CLI_Delete_Generated_Content', array(
    'shortdesc' => __( 'Deletes all generated content for the given Group ID.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// List Content Groups
WP_CLI::add_command( 'page-generator-pro-list-content-groups', 'Page_Generator_Pro_CLI_List_Content_Groups', array(
    'shortdesc' => __( 'Lists all Content Groups in the CLI.', 'page-generator-pro' ),
    'when'      => 'before_wp_load',
) );

// Generate Terms
WP_CLI::add_command( 'page-generator-pro-generate-terms', 'Page_Generator_Pro_CLI_Generate_Terms', array(
    'shortdesc' => __( 'Generates Terms for the given Generate Group ID.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'number_of_terms',
            'optional' => true,
            'multiple' => false,
        ),
        array(
            'type'     => 'assoc',
            'name'     => 'resume_index',
            'optional' => true,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Test Terms
WP_CLI::add_command( 'page-generator-pro-test-terms', 'Page_Generator_Pro_CLI_Test_Terms', array(
    'shortdesc' => __( 'Generates one Term for the given Generate Group ID. Use this to test your settings.', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// Delete Terms
WP_CLI::add_command( 'page-generator-pro-delete-generated-terms', 'Page_Generator_Pro_CLI_Delete_Generated_Terms', array(
    'shortdesc' => __( 'Deletes all generated terms for the given Group ID', 'page-generator-pro' ),
    'synopsis'  => array(
        array(
            'type'     => 'positional',
            'name'     => 'group_id',
            'optional' => false,
            'multiple' => false,
        ),
    ),
    'when' => 'before_wp_load',
) );

// List Term Groups
WP_CLI::add_command( 'page-generator-pro-list-term-groups', 'Page_Generator_Pro_CLI_List_Term_Groups', array(
    'shortdesc' => __( 'Lists all Term Groups in the CLI.', 'page-generator-pro' ),
    'when'      => 'before_wp_load',
) );
