<?php
/**
 * Logging class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 2.6.1
 */
class Page_Generator_Pro_Log {

    /**
     * Holds the base class object.
     *
     * @since   2.6.1
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the DB table name
     *
     * @since   2.6.1
     *
     * @var     string
     */
    private $table = 'page_generator_log';

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

        // Actions
        add_action( 'current_screen', array( $this, 'run_log_table_bulk_actions' ) );
        add_action( 'current_screen', array( $this, 'run_log_table_filters' ) );

    }

    /**
     * Activation routines for this Model
     *
     * @since   2.6.1
     *
     * @global  $wpdb   WordPress DB Object
     */
    public function activate() {

        global $wpdb;

        // Enable error output if WP_DEBUG is enabled.
        $wpdb->show_errors = true;

        // Create database tables
        $wpdb->query( " CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . $this->table . " (
                            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                            `group_id` int(11) NOT NULL,
                            `post_id` int(11) NOT NULL,
                            `url` varchar(191) NOT NULL,
                            `type` enum('content','term','comment') DEFAULT NULL,
                            `system` enum('browser','cron','cli') DEFAULT NULL,
                            `test_mode` tinyint(1) NOT NULL,
                            `generated` tinyint(1) NOT NULL,
                            `keywords_terms` text,
                            `message` text,
                            `start` int(11) NOT NULL,
                            `end` int(11) NOT NULL,
                            `duration` decimal(5,2) NOT NULL,
                            `memory_usage` int(11) NOT NULL,
                            `memory_peak_usage` int(11) NOT NULL,
                            `generated_at` datetime DEFAULT NULL,
                            PRIMARY KEY (`id`),
                            KEY `group_id` (`group_id`),
                            KEY `post_id` (`post_id`),
                            KEY `type` (`type`),
                            KEY `system` (`system`),
                            KEY `test_mode` (`test_mode`),
                            KEY `generated` (`generated`)
                        ) " . $wpdb->get_charset_collate() . " AUTO_INCREMENT=1" ); 

    }

    /**
     * Run any bulk actions on the Log WP_List_Table
     *
     * @since   2.6.1
     */
    public function run_log_table_bulk_actions() {

        // Get screen
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // Bail if we're not on the Logs screen
        if ( $screen['screen'] != 'logs' ) {
            return;
        }

        // Get bulk action from the fields that might contain it
        $bulk_action = array_values( array_filter( array(
            ( isset( $_REQUEST['bulk_action'] ) && $_REQUEST['bulk_action'] != -1 ? sanitize_text_field( $_REQUEST['bulk_action'] ) : '' ),
            ( isset( $_REQUEST['bulk_action2'] ) && $_REQUEST['bulk_action2'] != -1 ? sanitize_text_field( $_REQUEST['bulk_action2'] ) : '' ),
            ( isset( $_REQUEST['bulk_action3'] ) && ! empty( $_REQUEST['bulk_action3'] ) ? sanitize_text_field( $_REQUEST['bulk_action3'] ) : '' ),
        ) ) );

        // Bail if no bulk action
        if ( ! is_array( $bulk_action ) ) {
            return;
        }
        if ( ! count( $bulk_action ) ) {
            return;
        }

        // Setup notices class, enabling persistent storage
        $this->base->get_class( 'notices' )->enable_store();
        $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );

        // Perform Bulk Action
        switch ( $bulk_action[0] ) {
            /**
             * Export
             */
            case 'export':
                $result = $this->export();
                if ( is_wp_error( $result ) ) {
                    $this->base->get_class( 'notices' )->add_error_notice( $result->get_error_message() );
                }
                break;

            /**
             * Delete Logs
             */
            case 'delete':
                // Get Post IDs
                if ( ! isset( $_REQUEST['ids'] ) ) {
                    $this->base->get_class( 'notices' )->add_error_notice(
                        __( 'No logs were selected for deletion.', 'page-generator-pro' )
                    );
                    break;
                }

                // Delete Logs by IDs
                $this->delete_by_ids( array_values( $_REQUEST['ids'] ) );

                // Add success notice
                $this->base->get_class( 'notices' )->add_success_notice(
                    sprintf(
                        __( '%s Logs deleted.', 'page-generator-pro' ),
                        count( $_REQUEST['ids'] )
                    )
                );
                break;

            /**
             * Delete All Logs
             */
            case 'delete_all':
                // Delete Logs
                $this->delete_all();

                // Add success notice
                $this->base->get_class( 'notices' )->add_success_notice(
                    __( 'All Logs deleted.', 'page-generator-pro' )
                );
                break;

        }

        // Redirect
        wp_redirect( 'admin.php?page=' . $this->base->plugin->name . '-' . $screen['screen'] );
        die();

    }

    /**
     * Redirect POST filters to a GET URL
     *
     * @since   2.6.1
     */
    public function run_log_table_filters() {

        // Get screen
        $screen = $this->base->get_class( 'screen' )->get_current_screen();

        // Bail if we're not on the Log screen
        if ( $screen['screen'] != 'logs' ) {
            return;
        }

        $params = array();
        foreach ( $this->get_filters() as $filter ) {
            if ( ! isset( $_POST[ $filter ] ) ) {
                continue;
            }
            if ( empty( $_POST[ $filter ] ) ) {
                continue;
            }

            $params[ $filter ] = esc_html( $_POST[ $filter ] );
        }


        // If params don't exist, exit
        if ( ! count( $params ) ) {
            return;
        }

        // Redirect
        wp_redirect( 'admin.php?page=' . $this->base->plugin->name . '-' . $screen['screen'] . '&' . http_build_query( $params ) );
        die();

    }

    /**
     * Defines the registered filters that can be used on the Log WP_List_Table
     *
     * @since   2.6.1
     *
     * @return  array   Filters
     */
    public function get_filters() {

        // Define filters
        $filters = array( 
            'group_id',
            'system',
            'generated_at_start_date',
            'generated_at_end_date',
            'orderby',
            'order',
        );

        /**
         * Defines the registered filters that can be used on the Log WP_List_Tables.
         *
         * @since   2.6.1
         *
         * @param   array   $filters    Filters
         */
        $filters = apply_filters( 'page_generator_pro_log_get_filters', $filters );

        // Return filtered results
        return $filters;

    }

/**
     * Wrapper for PHP's error_log() function, which will only write
     * to the error log if:
     * - WP_DEBUG = true
     * - WP_DEBUG_DISPLAY = false
     * - WP_DEBUG_LOG = true
     *
     * This will ensure that the output goes to wp-content/debug.log
     *
     * @since   3.6.8
     *
     * @param   mixed   $data          Data to log
     * @param   array   $backtrace     Backtrace data from debug_backtrace()
     */
    public function add_to_debug_log( $data = '', $backtrace = false ) {

        // Bail if no WP_DEBUG, or it's false
        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            return;
        }

        // Bail if no WP_DEBUG_DISPLAY, or it's true
        if ( ! defined( 'WP_DEBUG_DISPLAY' ) || WP_DEBUG_DISPLAY ) {
            return;
        }

        // Bail if no WP_DEBUG_LOG, or it's false
        if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
            return;
        }

        // If we need to fetch the class and function name to prefix to the log entry, do so now
        $prefix_data = '';
        if ( $backtrace != false ) {
            if ( isset( $backtrace[0] ) ) {
                if ( isset( $backtrace[0]['class'] ) ) {
                    $prefix_data .= $backtrace[0]['class'];
                }
                if ( isset( $backtrace[0]['function'] ) ) {
                    $prefix_data .= '::' . $backtrace[0]['function'] . '()';
                }
            }
        }

        // If the data is empty, change it to 'called'
        if ( empty( $data ) ) {
            $data = 'Called';
        }

        // If the data is an array or object, convert it to a string
        if ( is_array( $data ) || is_object( $data ) ) {
            $data = print_r( $data, true );
        }

        // If we're prefixing the log entry, do so now
        if ( ! empty( $prefix_data ) ) {
            $data = $prefix_data . ': ' . $data;
        }

        // Add the data to the error log, which will appear in wp-content/debug.log
        error_log( $data );
        
    }

    /**
     * Adds a log entry
     *
     * @since   2.6.1
     *
     * @param   int     $group_id   Group ID
     * @param   array   $log        Log
     *      int             $post_id            Generated Post ID (or, if skipped, the existing matching Post ID)
     *      string          $url                Generated Post URL (or, if skipped, the existing matching Post ID)
     *      string          $type               Generation Type (content|term|comment)
     *      string          $system             Generation System (browser,cron,cli)
     *      bool            $test_mode          Test Mode (false = no)
     *      bool            $generated          Item Generated (false = skipped e.g. because Group Settings skip existing Items)
     *      array           $keywords_terms     Keyword/Term Key/Value pairs used for this Generated Item
     *      string          $message            Response from Generation Routine
     *      int             $start              microtime() when Generation Started
     *      int             $end                microtime() when Generation Ended
     *      int             $duration           $end - $start, rounded to 2dp
     *      int             $memory_usage       memory_get_usage(), rounded to 2dp
     *      int             $memory_peak_usage  memory_get_peak_usage(), rounded to 2pm
     */
    public function add( $group_id, $log ) {

        global $wpdb;

        // Enable error output if WP_DEBUG is enabled.
        $wpdb->show_errors();

        // Add Group ID and generated_at to log
        $log['group_id'] = absint( $group_id );
        $log['generated_at'] = date_i18n( 'Y-m-d H:i:s' ); // @TODO Check this matches WP timezone

        // If Keywords Terms is an array...
        if ( isset( $log['keywords_terms'] ) && is_array( $log['keywords_terms'] ) ) {
            $log['keywords_terms'] = json_encode( $log['keywords_terms'] );
        }

        // Insert Log
        $result = $wpdb->insert( 
            $wpdb->prefix . $this->table,
            $log
        );

    }

    /**
     * Searches logs by the given key/value pairs
     *
     * @since   2.6.1
     *
     * @param   string      $order_by   Order Results By
     * @param   string      $order      Order (asc|desc)
     * @param   int         $page       Pagination Offset (default: 0)
     * @param   int         $per_page   Number of Results to Return (default: 20)
     * @param   mixed       $params     Query Parameters (false = all records)
     */
    public function search( $order_by, $order, $page = 0, $per_page = 20, $params = false ) {
   
        global $wpdb;

        // Build where clauses
        $where = $this->build_where_clause( $params );

        // Prepare query
        $query = "  SELECT " . $wpdb->prefix . $this->table . ".*, " . $wpdb->posts . ".post_title AS group_name
                    FROM " . $wpdb->prefix . $this->table . "
                    LEFT JOIN " . $wpdb->posts . "
                    ON " . $wpdb->prefix . $this->table . ".group_id = " . $wpdb->posts . ".ID";

        // Add where clauses
        if ( $where != false ) {
            $query .= " WHERE " . $where;
        }

        // Order
        $query .= " ORDER BY " . $order_by . " " . $order;

        // Limit
        if ( $page > 0 && $per_page > 0 ) {
            $query .= $wpdb->prepare( " LIMIT %d, %d", ( ( $page - 1 ) * $per_page ), $per_page );
        }

        // Run and return query results
        return $wpdb->get_results( $query, ARRAY_A );

    }

    /**
     * Gets the number of log records found for the given query parameters
     *
     * @since   2.6.1
     *
     * @param   mixed   $params     Query Parameters (false = all records)
     * @return  int                 Total Records
     */
    public function total( $params = false ) {

        global $wpdb;

        // Build where clauses
        $where = $this->build_where_clause( $params );

        // Prepare query
        $query = "  SELECT COUNT(" . $wpdb->prefix . $this->table . ".id) FROM " . $wpdb->prefix . $this->table . "
                    LEFT JOIN " . $wpdb->posts . "
                    ON " . $wpdb->prefix . $this->table . ".group_id = " . $wpdb->posts . ".ID";

        // Add where clauses
        if ( $where != false ) {
            $query .= " WHERE " . $where;
        }

        // Run and return total records found
        return $wpdb->get_var( $query );

    }

    /**
     * Builds a WHERE SQL clause based on the given column key/values
     *
     * @since   2.6.1
     *
     * @param   array   $params     Query Parameters (false = all records)
     */
    private function build_where_clause( $params ) {

        // Bail if no params
        if ( ! $params ) {
            return false;
        }

        // Build where clauses
        $where = array();
        if ( $params != false && is_array( $params ) && count( $params ) > 0 ) {
            foreach ( $params as $key => $value ) {
                // Skip blank params
                if ( empty( $value ) ) {
                    continue;
                }

                // Build condition based on the key
                switch ( $key ) {
                    case 'generated_at_start_date':
                        if ( ! empty( $params['generated_at_end_date'] ) && $params['generated_at_start_date'] > $params['generated_at_end_date'] ) {
                            $where[] = "generated_at <= '" . $value . "'";
                        } else {
                            $where[] = "generated_at >= '" . $value . "'";
                        }
                        break;

                    case 'generated_at_end_date':
                        if ( ! empty( $params['generated_at_start_date'] ) && $params['generated_at_start_date'] > $params['generated_at_end_date'] ) {
                            $where[] = "generated_at >= '" . $value . "'";
                        } else {
                            $where[] = "generated_at <= '" . $value . "'";
                        }
                        break;

                        // @TODO Group Title (Post Title)

                    default:
                        $where[] = $key . " = '" . $value . "'";
                        break;      
                }
            }
        }

        if ( ! count( $where ) ) {
            return false;
        }

        return implode( ' AND ', $where );

    }

    /**
     * Exports the Log Table data to a CSV file
     *
     * @since   2.6.1
     */
    public function export() {

        // Get params
        $params     = $this->get_search_params();
        $order_by   = $this->get_order_by();
        $order      = $this->get_order();
        $page       = 0;
        $per_page   = 0;

        // Run query
        $rows = $this->search( $order_by, $order, $page, $per_page, $params );

        // Bail if no data to export
        if ( ! count( $rows ) ) {
            return new WP_Error( 'page_generator_pro_log_export', __( 'No log entries found based on the given search and filter criteria.', 'page-generator-pro' ) );
        }

        // Build log file
        $headers = array_keys( $rows[0] );
        $csv = array(
            '"' . implode( '","', $headers ) . '"',
        );
        foreach ( $rows as $row ) {
            // Convert Keyword Terms to string
            $keywords_terms = json_decode( $row['keywords_terms'] );
            $row['keywords_terms'] = array();
            foreach ( $keywords_terms as $keyword => $term ) {
                $row['keywords_terms'][] = $keyword . ': ' . $term;
            }
            $row['keywords_terms'] = implode( "\n", $row['keywords_terms'] );

            $csv[] = '"' . implode( '","', $row ) . '"';
        }

        // Force download with output
        header( "Content-type: application/x-msdownload" );
        header( "Content-Disposition: attachment; filename=log.csv" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        echo implode( "\n", $csv );
        exit();

    }

    /**
     * Deletes a single Log entry for the given Log ID
     *
     * @since   2.6.1
     *
     * @param   array   $id     Log ID
     * @return  bool            Success
     */
    public function delete_by_id( $id ) {

        global $wpdb;

        return $wpdb->delete(
            $wpdb->prefix . $this->table,
            array(
                'id' => absint( $id ),
            )
        );

    }

    /**
     * Deletes multiple Log entries for the given Log IDs
     *
     * @since   2.6.1
     *
     * @param   array   $ids    Log IDs
     * @return  bool            Success
     */
    public function delete_by_ids( $ids ) {

        global $wpdb;

        return $wpdb->query( "  DELETE FROM " . $wpdb->prefix . $this->table . "
                                WHERE id IN (" . implode( ',', array_map( 'absint', $ids ) ) . ")" );

    }

    /**
     * Deletes Log entries for the given Group ID
     *
     * @since   2.6.1
     *
     * @param   int     $group_id    Group ID
     */
    public function delete_by_group_id( $group_id ) {

        global $wpdb;

        return $wpdb->delete(
            $wpdb->prefix . $this->table,
            array(
                'group_id' => absint( $group_id ),
            )
        );

    }

    /**
     * Deletes all Log entries older than the given date
     *
     * @since   2.6.1
     *
     * @param   datetime    $date   Date and Time
     * @return  bool                Success
     */
    public function delete_by_generated_at_cutoff( $date_time ) {

        global $wpdb;

        // Build query
        $query = $wpdb->prepare( "  DELETE FROM " . $wpdb->prefix . $this->table . " 
                                    WHERE generated_at < %s",
                                    $date_time );

        // Run query
        return $wpdb->query( $query );

    }

    /**
     * Deletes all Log entries
     *
     * @since   2.6.1
     *
     * @return  bool    Success
     */
    public function delete_all() {

        global $wpdb;

        return $wpdb->query( "TRUNCATE TABLE " . $wpdb->prefix . $this->table );

    }

/**
     * Returns an array of search parameters and their values
     *
     * @since   2.6.1
     *
     * @param   array   Search Parameters
     */
    public function get_search_params() {

        // Build search params
        $params = array(
            'group_id'                  => $this->get_group_id(),
            'system'                    => $this->get_system(),
            'generated_at_start_date'   => $this->get_generated_at_start_date(),
            'generated_at_end_date'     => $this->get_generated_at_end_date(),
        );

        // Return params if freeform search isn't supplied
        if ( ! isset( $_REQUEST['s'] ) ) {
            return $params;
        }
        if ( empty( $_REQUEST['s'] ) ) {
            return $params;
        }

        // If search is a number, add it as the Group ID and return
        $search = esc_html( $_REQUEST['s'] );
        if ( is_numeric( $search ) ) {
            $params['group_id'] = absint( $search );
            return $params;
        }

        // Add it as the Post Title and return
        $params['post_title'] = $search;

        return $params;

    }

    /**
     * Get the Group ID Filter requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_group_id() {

        return ( isset( $_REQUEST['group_id'] ) ? esc_html( $_REQUEST['group_id'] ) : '' );

    }

    /**
     * Get the System Filter requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_system() {

        return ( isset( $_REQUEST['system'] ) ? esc_html( $_REQUEST['system'] ) : '' );

    }

    /**
     * Get the Generated At Start Date Filter requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_generated_at_start_date() {

        return ( isset( $_REQUEST['generated_at_start_date'] ) ? esc_html( $_REQUEST['generated_at_start_date'] ) : '' );

    }

    /**
     * Get the Generated At End Date Filter requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_generated_at_end_date() {

        return ( isset( $_REQUEST['generated_at_end_date'] ) ? esc_html( $_REQUEST['generated_at_end_date'] ) : '' );

    }

    /**
     * Get the Order By requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_order_by() {

        return ( isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'generated_at' );

    }

    /**
     * Get the Order requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_order() {

        return ( isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC' );

    }

    /**
     * Get the Pagination Page requested by the user
     *
     * @since   2.6.1
     *
     * @return  string
     */
    public function get_page() {

        return ( ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) ? absint( $_GET['paged'] ) : 1 );

    }

}