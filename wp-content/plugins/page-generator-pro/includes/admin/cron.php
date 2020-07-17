<?php
/**
 * Cron class
 * 
 * @package  WP_To_Social_Pro
 * @author   Tim Carr
 * @version  2.6.1
 */
class Page_Generator_Pro_Cron {

    /**
     * Holds the base class object.
     *
     * @since   2.6.1
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     *
     * @since   2.6.1
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

    }

    /**
     * Schedules the log cleanup event in the WordPress CRON on a daily basis
     *
     * @since   2.6.1
     */
    public function schedule_log_cleanup_event() {

        // Bail if logging is disabled
        if ( ! $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_enabled', '0' ) ) {
            return;
        }

        // Bail if the preserve logs settings is indefinite
        if ( ! $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_preserve_days', '7' ) ) {
            return;
        }

        // Bail if the scheduled event already exists
        $scheduled_event = $this->get_log_cleanup_event();
        if ( $scheduled_event != false ) {
            return;
        }

        // Schedule event
        $scheduled_date_time = date( 'Y-m-d', strtotime( '+1 day' ) ) . ' 00:00:00';
        wp_schedule_event( strtotime( $scheduled_date_time ), 'daily', 'page_generator_pro_log_cleanup_cron' );
        
    }

    /**
     * Unschedules the log cleanup event in the WordPress CRON.
     *
     * @since   2.6.1
     */
    public function unschedule_log_cleanup_event() {

        wp_clear_scheduled_hook( 'page_generator_pro_log_cleanup_cron' );

    }

    /**
     * Reschedules the log cleanup event in the WordPress CRON, by unscheduling
     * and scheduling it.
     *
     * @since   2.6.1
     */
    public function reschedule_log_cleanup_event() {

        $this->unschedule_log_cleanup_event();
        $this->schedule_log_cleanup_event();

    }

    /**
     * Returns the scheduled log cleanup event, if it exists
     *
     * @since   2.6.1
     */
    public function get_log_cleanup_event() {

        return wp_get_schedule( 'page_generator_pro_log_cleanup_cron' );

    }

    /**
     * Returns the scheduled log cleanup event's next date and time to run, if it exists
     *
     * @since   2.6.1
     *
     * @param   mixed   $format     Format Timestamp (false | php date() compat. string)
     */
    public function get_log_cleanup_event_next_scheduled( $format = false) {

        // Get timestamp for when the event will next run
        $scheduled = wp_next_scheduled( 'page_generator_pro_log_cleanup_cron' );

        // If no timestamp or we're not formatting the result, return it now
        if ( ! $scheduled || ! $format ) {
            return $scheduled;
        }

        // Return formatted date/time
        return date( $format, $scheduled );

    }

    /**
     * Runs the generate CRON event.
     *
     * @since   2.6.1
     * 
     * @param   int     $group_id   Group ID
     * @param   string  $type       Content Type
     */
    public function generate( $group_id, $type = 'content' ) {

        $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Started' );

        // Get Groups or Groups Term Instance
        $groups = ( ( $type == 'term' ) ? $this->base->get_class( 'groups_terms' ) : $this->base->get_class( 'groups' ) );

        // If this Group has a request to cancel generation, silently clear the status, system and cancel
        // flags before performing further checks on whether we should generate
        if ( $groups->cancel_generation_requested( $group_id ) ) {
            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Error: Generation cancelled by User' );
            $groups->stop_generation( $group_id );
        }

        // If the group is already generating, bail
        if ( $groups->is_generating( $group_id ) ) {
            $error = new WP_Error( 
                'page_generator_pro_generate_cron',
                sprintf(
                    __( 'Group ID #%s: Generation is already running via %s', 'page-generator-pro' ),
                    $group_id,
                    $groups->get_system( $group_id )
                )
            );

            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Error: ' . $error->get_error_message() );
            $groups->stop_generation( $group_id );
            return;
        }

        // Get group
        $group = $groups->get_settings( $group_id );
        if ( ! $group ) {
            $error = new WP_Error(
                'page_generator_pro_generate_cron',
                sprintf(
                    __( 'Group ID #%s: Could not get settings', 'page-generator-pro' ),
                    $group_id
                )
            );

            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Error: ' . $error->get_error_message() );
            $groups->stop_generation( $group_id );
            return;
        }

        // Calculate how many pages could be generated
        $number_of_pages_to_generate = $this->base->get_class( 'generate' )->get_max_number_of_pages( $group );
        if ( is_wp_error( $number_of_pages_to_generate ) ) {
            $error = new WP_Error( 
                'page_generator_pro_generate_cron',
                sprintf(
                    __( 'Group ID #%s: %s', 'page-generator-pro' ),
                    $group_id,
                    $result->get_error_message()
                )
            );

            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Error: ' . $error->get_error_message() );
            $groups->stop_generation( $group_id );
            return;
        }

        // If no limit specified, set one now
        if ( empty( $group['numberOfPosts'] ) ) {
            if ( $group['method'] == 'random' ) {
                $group['numberOfPosts'] = 10;
            } else {
                $group['numberOfPosts'] = $number_of_pages_to_generate;
            }

            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Setting Number of Posts = ' . $group['numberOfPosts'] . ', as no limit specified in Group.' );
        }

        // If the requested Number of Posts exceeds the Number of Pages that could be generated,
        // set Number of Posts to match the Number of Pages that could be generated
        if ( $group['numberOfPosts'] > $number_of_pages_to_generate ) {
            $group['numberOfPosts'] = $number_of_pages_to_generate;
            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Restricting Number of Posts = : ' . $group['numberOfPosts'] . ', as limit specified in Group exceeded the number of possible Pages that could be generated.' );
        }

        // Add Plugin Settings
        $group['stop_on_error'] = (int) $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'stop_on_error', '1' );

        // Set a flag to denote that this Group is generating content via CRON
        $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Setting Group Status = generating via cron' );
        $groups->start_generation( $group_id, 'generating', 'cron' );

        // Get first admin user
        $users = get_users( array(
            'role' => 'administrator',
        ) );
        $user_id = $users[0]->ID;
        
        // Run a loop to generate each page
        for ( $i = $group['resumeIndex']; $i < ( $group['numberOfPosts'] + $group['resumeIndex'] ); $i++ ) {
            // If cancel generation was requested, exit now
            if ( $groups->cancel_generation_requested( $group_id ) ) {
                $groups->stop_generation( $group_id );
                $error = new WP_Error( 'page_generator_pro_generate_cron', 'Group ID #' . $group_id . ': Generation cancelled by User' );

                $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Error: ' . $error->get_error_message() );
                return;
            }

            // Set current User to an Admin, so that the unfiltered_html capability is enabled,
            // allowing generation to perform the same way as if run through the browser or CLI
            // (i.e. iframes are permitted)
            wp_set_current_user( $user_id );

            // Run
            switch ( $type ) {
                case 'term':
                    $result = $this->base->get_class( 'generate' )->generate_term( $group_id, $i, false, 'cron' );
                    break;

                default:
                    $result = $this->base->get_class( 'generate' )->generate_content( $group_id, $i, false, 'cron' );
                    break;    
            }
         
            // Set current User to nothing, so that the unfiltered_html capability is disabled
            wp_set_current_user( 0 );

            // Bail if something went wrong
            if ( is_wp_error( $result ) ) {
                $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Group ID #' . $group_id . ': Error: ' . $error->get_error_message() );

                // If we're stopping on error, remove generating flag and exit
                if ( $group['stop_on_error'] ) {
                    $groups->stop_generation( $group_id );
                    return; 
                }

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
                $message[] = '{' . $keyword . '}: '  . ( strlen( $term ) > 50 ? substr( $term, 0, 50 ) . '...' : $term );
            }
            $message[] = '--';

            // Output log
            $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): ' . implode( "\n", $message ) );
        }

        // Stop generation
        $groups->stop_generation( $group_id ); 

        $this->base->get_class( 'log' )->add_to_debug_log( 'page_generator_pro_generate_cron(): Finished' );

    }

    /**
     * Runs the log cleanup CRON event
     *
     * @since   2.6.1
     */
    public function log_cleanup() {

        // Bail if logging is disabled
        if ( ! $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_enabled', '0' ) ) {
            return;
        }

        // Bail if the preserve logs settings is indefinite
        if ( ! $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_preserve_days', '7' ) ) {
            return;
        }

        // Define the date cutoff
        $date_time = date( 'Y-m-d H:i:s', strtotime( '-' . $preserve_days . ' days' ) );

        // Delete log entries older than the date
        $this->base->get_class( 'log' )->delete_by_generated_at_cutoff( $date_time );

    }

}