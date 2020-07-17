<?php
/**
 * Handles Group Term Table actions
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  2.0.2
 */
class Page_Generator_Pro_Groups_Terms_Table {

    /**
     * Holds the base class object.
     *
     * @since   2.0.2
     *
     * @var     object
     */
    public $base;

    /**
     * Stores the current Group the settings are defined for.
     *
     * @since   2.0.2
     *
     * @var     int
     */
    public $group_id = 0;

    /**
     * Constructor.
     *
     * @since   2.0.2
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Bulk Actions Dropdown
        add_filter( 'bulk_actions-edit-page-generator-tax', array( $this, 'register_bulk_actions' ) ); 
        add_filter( 'handle_bulk_actions-edit-page-generator-tax', array( $this, 'run_bulk_actions' ), 10, 3 ); 

        // WP_List_Table Columns
        add_filter( 'manage_edit-page-generator-tax_columns', array( $this, 'admin_columns' ) );
        add_filter( 'manage_page-generator-tax_custom_column', array( $this, 'admin_columns_output' ), 10, 3 );

        // WP_List_Table Row Actions
        add_filter( 'page-generator-tax_row_actions', array( $this, 'admin_row_actions' ), 10, 2 );

        // Run any row actions called from the WP_List_Table
        add_action( 'init', array( $this, 'run_row_actions' ) );

    }

    /**
     * Adds Bulk Action options to Groups Terms WP_List_Table
     *
     * @since   2.0.2
     *
     * @param   array   $actions    Registered Bulk Actions
     * @return  array               Registered Bulk Actions
     */
    public function register_bulk_actions( $actions ) {

        // Define Actions
        $bulk_actions = array(
            'duplicate'                 => $this->base->get_class( 'groups_terms_ui' )->get_title( 'duplicate' ),
            'generate_server'           => $this->base->get_class( 'groups_terms_ui' )->get_title( 'generate_server' ),
            'delete_generated_content'  => $this->base->get_class( 'groups_terms_ui' )->get_title( 'delete_generated_content' ),
        );

        // Remove some actions we don't want
        unset( $actions['edit'] );

        /**
         * Defines Bulk Actions to be added to the select dropdown on the Groups Terms WP_List_Table.
         *
         * @since   2.0.2
         *
         * @param   array   $bulk_actions   Plugin Specific Bulk Actions
         * @param   string  $actions        Existing Registered Bulk Actions (excluding Plugin Specific Bulk Actions)
         */
        $bulk_actions = apply_filters( 'page_generator_pro_groups_terms_table_register_bulk_actions', $bulk_actions, $actions );

        // Merge with default Bulk Actions
        $actions = array_merge( $bulk_actions, $actions );

        // Return
        return $actions;

    }

    /**
     * Handles Bulk Actions when one is selected to run
     *
     * @since   2.0.2
     *
     * @param   string  $redirect_to    Redirect URL
     * @param   string  $action         Bulk Action to Run
     * @param   array   $post_ids       Post IDs to apply Action on
     * @return  string                  Redirect URL
     */
    public function run_bulk_actions( $redirect_to, $action, $post_ids ) {

        // Bail if the action isn't specified
        if ( empty( $action ) ) {
            return $redirect_to;
        }

        // Bail if no Post IDs
        if ( empty( $post_ids ) ) {
            return $redirect_to;
        }

        // Setup notices class, enabling persistent storage
        $this->base->get_class( 'notices' )->enable_store();
        $this->base->get_class( 'notices' )->set_key_prefix( 'page_generator_pro_' . wp_get_current_user()->ID );

        // Run Bulk Action
        switch ( $action ) {

            /**
             * Duplicate
             */
            case 'duplicate':
                foreach ( $post_ids as $post_id ) {
                    // Duplicate
                    $result = $this->base->get_class( 'groups_terms' )->duplicate( $post_id );

                    // If an error occured, add it to the notices
                    if ( is_wp_error( $result ) ) {
                        $this->base->get_class( 'notices' )->add_error_notice( sprintf( __( 'Group #%s: %s', 'page-generator-pro' ), $post_id, $result->get_error_message() ) );
                    } else {
                        $this->base->get_class( 'notices' )->add_success_notice( sprintf( __( 'Group #%s: %s', 'page-generator-pro' ), $post_id, $this->base->get_class( 'groups_terms_ui' )->get_message( 'duplicate_success' ) ) );
                    }
                }
                break;

            /**
             * Generate via Server
             */
            case 'generate_server':
                foreach ( $post_ids as $post_id ) {
                    // Schedule
                    $result = $this->base->get_class( 'groups_terms' )->schedule_generation( $post_id );

                    // If an error occured, add it to the notices
                    if ( is_wp_error( $result ) ) {
                        $this->base->get_class( 'notices' )->add_error_notice( sprintf( __( 'Group #%s: %s', 'page-generator-pro' ), $post_id, $result->get_error_message() ) );
                    } else {
                        $this->base->get_class( 'notices' )->add_success_notice( sprintf( __( 'Group #%s: %s', 'page-generator-pro' ), $post_id, $this->base->get_class( 'groups_terms_ui' )->get_title( 'generate_server_success' ) ) );
                    }
                }
                break;

            /**
             * Delete Generated Content
             */
            case 'delete_generated_content':
                foreach ( $post_ids as $post_id ) {
                    // Delete
                    $result = $this->base->get_class( 'groups_terms' )->delete_generated_content( $post_id );

                    // If an error occured, add it to the notices
                    if ( is_wp_error( $result ) ) {
                        $this->base->get_class( 'notices' )->add_error_notice( sprintf( __( 'Group #%s: %s', 'page-generator-pro' ), $post_id, $result->get_error_message() ) );
                    } else {
                        $this->base->get_class( 'notices' )->add_success_notice( sprintf( __( 'Group #%s: %s', 'page-generator-pro' ), $post_id, $this->base->get_class( 'groups_terms_ui' )->get_title( 'generate_delete_generated_content_success' ) ) );
                    }
                }
                break;

            /**
             * Other Bulk Actions
             */
            default:
                /**
                 * Runs the given Bulk Action against the given Content Group IDs.
                 *
                 * @since   1.9.9
                 *
                 * @param   string  $action     Bulk Action Run
                 * @param   array   $post_ids   Group IDs
                 */
                do_action( 'page_generator-pro_groups_terms_table_run_bulk_actions', $action, $post_ids );
                break;

        }

        // Return redirect
        return $redirect_to;

    }

    /**
     * Adds columns to the Groups Terms within the WordPress Administration List Table
     * 
     * @since   2.0.2
     *
     * @param   array   $columns    Columns
     * @return  array               New Columns
     */
    public function admin_columns( $columns ) {

        // Remove columsn we don't want
        unset( $columns['posts'], $columns['description'] );

        // Inject columns
        $columns['id']              = __( 'Group ID', 'page-generator-pro' );
        $columns['taxonomy']        = __( 'Taxonomy', 'page-generator-pro' );
        $columns['generated_count'] = __( 'No. Generated Items', 'page-generator-pro' );
        $columns['status']          = __( 'Status', 'page-generator-pro' );

        /**
         * Filters the columns to display on the Groups: Terms WP_List_Table.
         *
         * @since   2.0.2
         *
         * @param   array   $columns    Columns
         */
        $columns = apply_filters( 'page_generator_pro_groups_terms_table_admin_columns', $columns );

        // Return
        return $columns;

    }

    /**
     * Manages the data to be displayed within a column on the Groups Taxonomy within 
     * the WordPress Administration List Table
     * 
     * @since   2.0.2
     *
     * @param   string  $content        Content
     * @param   string  $column_name    Column Name
     * @param   int     $term_id        Group ID
     */
    public function admin_columns_output( $content, $column_name, $term_id ) {

        // Array to hold the item(s) to output in this column for this Group
        $items = array();

        // Get group settings, if we don't have them
        if ( $term_id != $this->group_id ) {
            $this->group_id = $term_id;
            $this->settings = $this->base->get_class( 'groups_terms' )->get_settings( $this->group_id );
        }

        switch ( $column_name ) {
            /**
             * ID
             */
            case 'id':
                echo $this->group_id;
                break;

            /**
             * Taxonomy
             */
            case 'taxonomy':
                $items = array(
                    'taxonomy' => $this->settings['taxonomy'],
                );
                break;

            /**
             * Number of Generated Pages
             */
            case 'generated_count':
                if ( $this->base->get_class( 'groups_terms' )->has_generated_content( $term_id ) ) {
                    $items = array(
                        'generated_count'           => sprintf( __( 'Generated Items: %s', 'page-generator-pro' ), $this->settings['generated_pages_count'] ),
                        'last_index_generated'      => sprintf( __( 'Last Index Generated: %s', 'page-generator-pro' ), $this->settings['last_index_generated'] ),
                        'delete_generated_content'  => '<a href="edit-tags.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name . '&' . $this->base->plugin->name . '-action=delete_generated_content&id=' . $term_id . '&type=term">' . $this->base->get_class( 'groups_terms_ui' )->get_title( 'delete_generated_content' ) . '</a>',
                    );
                } else {
                    $items = array(
                        'generated_count'           => sprintf( __( 'Generated Items: %s', 'page-generator-pro' ), 0 ),
                        'last_index_generated'      => sprintf( __( 'Last Index Generated: %s', 'page-generator-pro' ), $this->settings['last_index_generated'] ),
                    );
                }
                break;

            /**
             * Status
             */
            case 'status':
                if ( $this->base->get_class( 'groups_terms' )->is_idle( $term_id ) ) {
                    $items = array(
                        'test'              => '<a href="edit-tags.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name . '&' . $this->base->plugin->name . '-action=test&id=' . $term_id . '&type=term">' . $this->base->get_class( 'groups_terms_ui' )->get_title( 'test' ) . '</a>',
                        'generate'          => '<a href="admin.php?page=' . $this->base->plugin->name . '-generate&id=' . $term_id . '&type=term">' . $this->base->get_class( 'groups_terms_ui' )->get_title( 'generate' ) . '</a>',
                        'generate_server'   => '<a href="edit-tags.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name . '&' . $this->base->plugin->name . '-action=generate_server&id=' . $term_id . '&type=term">' . $this->base->get_class( 'groups_terms_ui' )->get_title( 'generate_server' ) . '</a>',
                    );
                } else {
                    $items = array(
                        'status'        => '<div class="page-generator-pro-generating-spinner">
                                                <span class="spinner"></span>' . 
                                                sprintf( 
                                                    __( '%s (%s)', 'page-generator-pro' ), 
                                                    ucfirst( $this->base->get_class( 'groups_terms' )->get_status( $term_id ) ),
                                                    $this->base->get_class( 'groups_terms' )->get_system( $term_id )
                                                ) . '
                                            </div>',
                        'cancel'        => '<a href="edit-tags.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name . '&' . $this->base->plugin->name . '-action=cancel_generation&id=' . $term_id . '&type=term">' . $this->base->get_class( 'groups_terms_ui' )->get_title( 'cancel_generation' ) . '</a>',
                    );
                }
                break;

            default:
                /**
                 * Filters the output for a non-standard column on the Groups: Terms WP_List_Table.
                 *
                 * @since   2.0.2
                 *
                 * @param   array   $columns    Columns
                 * @param   int     $term_id    Group ID
                 * @param   array   $settings   Group Settings
                 */
                $content = apply_filters( 'page_generator_pro_groups_terms_table_admin_columns_output', $column_name, $term_id, $this->settings );
                break;
        }

        // If no items are defined for output, bail
        if ( empty( $items ) ) {
            return $content;
        }

        // Iterate through items, outputting
        foreach ( $items as $class => $item ) {
            $content .= '<span class="' . $class . '">' . $item . '</span><br />';
        }

        // Return
        return $content;

    }

    /**
     * Adds Duplicate, Test and Generate Row Actions to each Term Group within
     * the WordPress Administration List Table
     *
     * @since   2.0.2
     *
     * @param   array       $actions    Row Actions
     * @param   WP_Term     $term       Taxonomy Term
     * @return  array                   Row Actions
     */
    public function admin_row_actions( $actions, $term ) {

        // Bail if not a Groups Term
        if ( $term->taxonomy != $this->base->get_class( 'taxonomy' )->taxonomy_name ) {
            return $actions;
        }

        // Add Duplicate Action
        $actions['duplicate'] = '<br /><a href="edit-tags.php?taxonomy=' . $this->base->get_class( 'taxonomy' )->taxonomy_name . '&' . $this->base->plugin->name . '-action=duplicate&id=' . $term->term_id . '&type=term">' . $this->base->get_class( 'groups_terms_ui' )->get_title( 'duplicate' ) . '</a>';

        /**
         * Filters the row actions to output on each Content Group in the Groups: Content WP_List_Table.
         *
         * @since   2.0.2
         *
         * @param   array       $actions                Row Actions
         * @param   WP_Term     $term                   Term
         */
        $actions = apply_filters( 'page_generator_pro_groups_terms_table_admin_row_actions', $actions, $term );

        // Return
        return $actions;

    }

    /**
     * Runs a clicked action for a given Term Group.
     *
     * @since   2.0.2
     */
    public function run_row_actions() {

        // Bail if we're not on a Groups screen
        if ( ! isset( $_REQUEST['taxonomy'] ) ) {
            return;
        }
        if ( sanitize_text_field( $_REQUEST['taxonomy'] ) != $this->base->get_class( 'taxonomy' )->taxonomy_name ) {
            return;
        }

        // If no action specified, return
        if ( ! isset( $_REQUEST[ $this->base->plugin->name . '-action' ] ) ) {
            return;
        }

        // Fetch action and group ID
        $action = sanitize_text_field( $_REQUEST[ $this->base->plugin->name . '-action' ] );
        $id = absint( $_REQUEST['id'] );

        // Run action
        $this->base->get_class( 'groups_terms' )->run_action( $action, $id, true );

    }

}