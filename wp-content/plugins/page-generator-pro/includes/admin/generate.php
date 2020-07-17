<?php
/**
 * Generate class. Handles generating Pages, Posts, Custom Post Types
 * and Taxonomy Terms.
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Generate {

    /**
     * Holds the base object.
     *
     * @since   1.9.8
     *
     * @var     object
     */
    public $base;

    /**
     * Holds an array comprising of every keyword detected in the Group.
     * Each Keyword holds an array comprising of every single Term for that Keyword.
     *
     * @since   1.9.8
     *
     * @var     array
     */
    public $keywords = array();

    /**
     * Holds an array comprising of every keyword detected in the Group.
     * Each Keyword holds the nth Term that will be used to replace the Keyword.
     *
     * @since   1.9.8
     *
     * @var     array
     */
    public $keywords_terms = array();

    /**
     * Holds the array of all unique found keywords across all settings.
     *
     * @since   1.2.0
     *
     * @var     array
     */
    public $required_keywords = array();

    /**
     * Holds the array of all unique found keywords, including column name, nth modified and transformation(s) across all settings.
     *
     * @since   1.2.0
     *
     * @var     array
     */
    public $required_keywords_full = array();

    /**
     * Holds the array of keywords to replace e.g. {city}
     *
     * @since   1.3.1
     *
     * @var     array
     */
    public $searches = array();

    /**
     * Holds the array of keyword values to replace e.g. Birmingham
     *
     * @since   1.3.1
     *
     * @var     array
     */
    public $replacements = array();

    /**
     * Holds a flag to denote if one or more $replacements are an array
     * If they're an array, it's because the :random_different transformation
     * is used, and so we have to perform a slower search/replace method.
     *
     * @since   2.7.2
     *
     * @var     bool
     */
    public $replacements_contain_array = false;

    /**
     * Holds a flag to denote whether Page Generator Pro shortcodes
     * should be processed on the main Post Content
     *
     * @since   1.9.5
     *
     * @var     bool
     */
    public $process_shortcodes_on_post_content = false;

    /**
     * Constructor.
     *
     * @since   1.9.3
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Register Action Hooks
        add_action( 'page_generator_pro_generate_content_after', array( $this, 'generate_content_finished' ), 10, 2 );
        add_action( 'page_generator_pro_generate_terms_after', array( $this, 'generate_terms_finished' ), 10, 2 );

        // Delete Geodata on Post Deletion
        add_action( 'delete_post', array( $this, 'delete_latitude_longitude_by_post_id' ) );

        // Bail if WP-CLI is not available
        if ( ! class_exists( 'WP_CLI' ) ) {
            return;
        }
         
        // Register WP-CLI Hooks
        WP_CLI::add_hook( 'page_generator_pro_generate_content_after', array( $this, 'generate_content_finished' ), 10, 2 );
        WP_CLI::add_hook( 'page_generator_pro_generate_terms_after', array( $this, 'generate_terms_finished' ), 10, 2 );

    }

    /**
     * Calculates the maximum number of items that will be generated based
     * on the settings.
     *
     * @since   1.1.5
     *
     * @param   array   $settings   Group Settings (either a Content or Term Group)
     * @return  mixed               WP_Error | integer
     */
    public function get_max_number_of_pages( $settings ) {

        // Build a class array of required keywords that need replacing with data
        $this->find_keywords_in_settings( $settings );

        // Bail if no keywords were found
        if ( count( $this->required_keywords ) == 0 ) {
            return 0;
        }

        // Get the terms for each required keyword
        $this->keywords = $this->get_keywords_terms_columns_delimiters( $this->required_keywords );

        // Bail if no keywords were found
        if ( empty( $this->keywords['terms'] ) ) {
            return 0;
        }

        // Depending on the generation method chosen, for each keyword, define the term
        // that will replace it.
        switch ( $settings['method'] ) {

            /**
             * All
             * Random
             * - Generates all possible term combinations across keywords
             */
            case 'all':
            case 'random':
                $total = 1;
                foreach ( $this->keywords['terms'] as $keyword => $terms ) {
                    $total = ( $total * count( $terms ) );
                }

                return $total;
                break;

            /**
             * Sequential
             * - Generates term combinations across keywords matched by index
             */
            case 'sequential':
                $total = 0;
                foreach ( $this->keywords['terms'] as $keyword => $terms ) {
                    if ( count( $terms ) > 0 && ( count( $terms ) < $total || $total == 0 ) ) {
                        $total = count( $terms );
                    }
                }

                return $total;
                break;

        }

    }
    
    /**
     * Generates a Page, Post or Custom Post Type for the given Group and Index
     *
     * @since   1.6.1
     *
     * @param   int     $group_id   Group ID
     * @param   int     $index      Keyword Index
     * @param   bool    $test_mode  Test Mode
     * @param   string  $system     System (browser|cron|cli)
     * @return  mixed               WP_Error | array
     */
    public function generate_content( $group_id, $index = 0, $test_mode = false, $system = 'browser' ) {

        // Performance debugging
        $start  = microtime( true );

        // Define the Group ID and Index as globals, so it can be picked up by our shortcodes when they're processed
        global $page_generator_pro_group_id, $page_generator_pro_index;
        $page_generator_pro_group_id = $group_id;
        $page_generator_pro_index = $index;

        // If test mode is enabled, set the debug constant
        if ( $test_mode && ! defined( 'PAGE_GENERATOR_PRO_DEBUG' ) ) {
            define( 'PAGE_GENERATOR_PRO_DEBUG', true );
        }

        // If this Group has a request to cancel generation, exit
        if ( ! $test_mode ) {
            if ( $this->base->get_class( 'groups' )->cancel_generation_requested( $group_id ) ) {
                $this->base->get_class( 'groups' )->stop_generation( $group_id );
                return new WP_Error( 'generation_error', __( 'A request to cancel generation was made by the User. Exiting...', 'page-generator-pro' ) );
            }
        }
        
        // Get group settings
        $settings = $this->base->get_class( 'groups' )->get_settings( $group_id );

        // If the Group is not published, generation might fail in Gutenberg stating that no keywords could be found
        // in the Content. Change its status to published
        if ( ! in_array( get_post_status( $group_id ), array_keys( $this->base->get_class( 'groups' )->get_group_statuses() ) ) ) {
            $result = wp_update_post( array(
                'ID'            => $group_id,
                'post_status'   => 'publish',
            ), true );

            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }

        // Validate group
        $validated = $this->base->get_class( 'groups' )->validate( $group_id );
        if ( is_wp_error( $validated ) ) {
            return $validated;
        }

        /**
         * Run any actions before an individual Page, Post or Custom Post Type is generated
         * successfully.
         *
         * @since   2.4.1
         *
         * @param   int     $group_id       Group ID
         * @param   array   $settings       Group Settings
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        do_action( 'page_generator_pro_generate_content_started', $group_id, $settings, $index, $test_mode );

        // Build a class array of required keywords that need replacing with data
        $this->find_keywords_in_settings( $settings );
        if ( count( $this->required_keywords ) == 0 ) {
            return new WP_Error( 'keyword_error', __( 'No keywords were specified in the Group.', 'page-generator-pro' ) );
        }

        // Build a keywords array comprising of terms, columns and delimiters for each of the required keywords
        $this->keywords = $this->get_keywords_terms_columns_delimiters( $this->required_keywords );
        if ( count( $this->keywords['terms'] ) == 0 ) {
            return new WP_Error( 'keyword_error', __( 'Keywords were specified in the Group, but no keywords exist in either the Keywords section of the Plugin or as a Taxonomy.', 'page-generator-pro' ) );
        }

        // Build array of keyword --> term key/value pairs to use for this generation
        $this->keywords_terms = $this->get_keywords_terms( $settings['method'], $index );
        if ( is_wp_error( $this->keywords_terms ) ) {
            return $this->keywords_terms;
        }

        // Rotate Author
        if ( isset( $settings['rotateAuthors'] ) ) {
            $authors = $this->base->get_class( 'common' )->get_authors();
            $user_index = ( $index % count( $authors ) );
        }

        // If 'Apply Synonyms' has been enabled, add spintax to the content now
        if ( isset( $settings['apply_synonyms'] ) && $settings['apply_synonyms'] ) {
            $settings['content'] = $this->base->get_class( 'spintax' )->add_spintax( $settings['content'] );
        }

        // Define whether we'll process shortcodes on the Post Content
        // Some Page Builders will mean we won't do this, such as Elementor, which don't use
        // the Post Content for output.
        $this->process_shortcodes_on_post_content = $this->should_process_shortcodes_on_post_content( $settings );

        // Remove all shortcode processors, so we don't process any shortcodes. This ensures page builders, galleries etc
        // will work as their shortcodes will be processed when the generated page is viewed.
        remove_all_shortcodes();

        // Add Page Generator Pro's shortcodes, so they're processed now (true = we want to register shortcodes that need processing into HTML)
        $this->base->get_class( 'shortcode' )->add_shortcodes( true );

        // Reset search and replacement arrays
        $this->searches = array();
        $this->replacements = array();

        // Iterate through each detected Keyword to build a full $this->searches and $this->replacements arrays
        $this->build_search_replace_arrays();
        
        // Iterate through each keyword and term key/value pair
        $settings = $this->replace_keywords( $settings );

        // Define Post Name / Slug
        // If no Permalink exists, use the Post Title
        if ( ! empty( $settings['permalink'] ) ) {
            $post_name = sanitize_title( $settings['permalink'] );
        } else {
            $post_name = sanitize_title( $settings['title'] );
        }

        // Determine the Post Parent
        $post_parent = ( ( isset( $settings['pageParent'] ) && isset( $settings['pageParent'][ $settings['type'] ] ) && ! empty( $settings['pageParent'][ $settings['type'] ] ) ) ? $settings['pageParent'][ $settings['type'] ] : 0 );
        if ( ! is_numeric( $post_parent ) ) {
            // Convert Post Parent to slug, retaining forwardslashes
            // This also converts special accented characters to non-accented versions
            $post_parent = $this->sanitize_slug( $post_parent );

            // Find the Post ID based on the given name
            $parent = get_page_by_path( $post_parent, OBJECT, $settings['type'] );

            if ( ! $parent ) {
                $post_parent = 0;
            } else {
                $post_parent = $parent->ID;
            }
        }

        // Depending on the Ovewrite setting, check if an existing Post exists
        switch ( $settings['overwrite'] ) {
            /**
             * No, skip if existing Page generated by this Group
             */
            case 'skip_if_exists':
                // Find existing Post by Permalink generated by this Group
                $existing_post_id = $this->post_exists( $group_id, $settings['type'], $post_parent, $post_name );

                // Bail if a Post is found, as we're skipping Generation
                if ( $existing_post_id > 0 ) {
                    return $this->generate_return(
                        $group_id, 
                        $existing_post_id, 
                        $settings['type'],
                        false,
                        sprintf(
                            __( 'Skipped, as %s with Permalink already generated by this Group', 'page-generator-pro' ),
                            $settings['type']
                        ),
                        $start,
                        $test_mode,
                        $system
                    );
                }
                break;

            /**
             * No, skip if existing Page exists
             */
            case 'skip_if_exists_any':
                // Find existing Post by Permalink, regardless of Group
                $existing_post_id = $this->post_exists( 0, $settings['type'], $post_parent, $post_name );
                
                // Bail if a Post is found, as we're skipping Generation
                if ( $existing_post_id > 0 ) {
                    return $this->generate_return( 
                        $group_id,
                        $existing_post_id, 
                        $settings['type'],
                        false,
                        sprintf(
                            __( 'Skipped, as %s with Permalink already exists in WordPress', 'page-generator-pro' ),
                            $settings['type']
                        ),
                        $start,
                        $test_mode,
                        $system
                    );
                }
                break;

            /**
             * Yes, if existing Page generated by this Group
             * Yes, if existing Page generated by this Group, preserving original Publish date
             */
            case 'overwrite':
            case 'overwrite_preseve_date':
                // Try to find existing post
                $existing_post_id = $this->post_exists( $group_id, $settings['type'], $post_parent, $post_name );
                break;

            /**
             * Yes, if existing Page exists
             * Yes, if existing Page exists, preserving original Publish date
             */
            case 'overwrite_any':
            case 'overwrite_any_preseve_date':
                // Try to find existing post
                $existing_post_id = $this->post_exists( 0, $settings['type'], $post_parent, $post_name );
                break;
        }

        /**
         * Modify the Group's settings prior to parsing shortcodes and building the Post Arguments
         * to use for generating a single Page, Post or Custom Post Type.
         *
         * Changes made only affect this item in the generation set, and are not persistent or saved.
         *
         * For Gutenberg and Page Builders with Blocks / Elements registered by this Plugin, this
         * is a good time to convert them to a Shortcode Block / Element / Text
         *
         * @since   2.6.0
         *
         * @param   array   $settings       Group Settings
         * @param   int     $group_id       Group ID
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        $settings = apply_filters( 'page_generator_pro_generate_content_settings', $settings, $group_id, $index, $test_mode );

        // Process Shortcodes
        // Blocks above that have been converted into Shortcodes will now be processed
        array_walk_recursive( $settings, array( $this, 'process_shortcodes_in_array' ) );

        // Build Post args
        $post_args = array(
            'post_type'     => $settings['type'],
            'post_title'    => $settings['title'],
            'post_content'  => $settings['content'],
            'post_status'   => ( $test_mode ? 'draft' : $settings['status'] ),
            'post_author'   => ( ( isset( $settings['rotateAuthors'] ) && $settings['rotateAuthors'] == 1 ) ? $authors[ $user_index ]->ID : $settings['author'] ), // ID
            'comment_status'=> ( ( isset( $settings['comments'] ) && $settings['comments'] == 1 ) ? 'open' : 'closed' ),
            'ping_status'   => ( ( isset( $settings['trackbacks'] ) && $settings['trackbacks'] == 1 ) ? 'open' : 'closed' ),
            'post_parent'   => $post_parent,
            'post_name'     => $post_name,
        );

        // Define Post Excerpt, if the Post Type supports it
        if ( post_type_supports( $settings['type'], 'excerpt' ) ) {
            $post_args['post_excerpt'] = $settings['excerpt'];
        }

        // Define the Post Date
        switch ( $settings['date_option'] ) {

            /**
             * Now
             */
            case 'now':
                if ( $settings['status'] == 'future' ) {
                    // Increment the current date by the schedule hours and unit
                    $post_args['post_date'] = date_i18n( 'Y-m-d H:i:s', strtotime( '+' . ( $settings['schedule'] * ( $index + 1 ) ) . ' ' . $settings['scheduleUnit'] ) );
                } else {
                    $post_args['post_date'] = date_i18n( 'Y-m-d H:i:s' );
                }
                break;

            /**
             * Specific Date
             */
            case 'specific':
                if ( $settings['status'] == 'future' ) {
                    // Increment the specific date by the schedule hours and unit
                    $post_args['post_date'] = date_i18n( 'Y-m-d H:i:s', strtotime( $settings['date_specific'] . ' +' . ( $settings['schedule'] * ( $index + 1 ) ) . ' ' . $settings['scheduleUnit'] ) );
                } else {
                    $post_args['post_date'] = $settings['date_specific'];
                }
                break;

            /**
             * Random
             */
            case 'random':
                $min = strtotime( $settings['date_min'] );
                $max = strtotime( $settings['date_max'] );
                $post_args['post_date'] = date_i18n( 'Y-m-d H:i:s', rand( $min, $max ) );
                break;

        }

        /**
         * Filters arguments used for creating or updating a Post when running
         * content generation.
         *
         * @since   1.6.1
         *
         * @param   array   $post_args  wp_insert_post() / wp_update_post() compatible arguments
         * @param   array   $settings   Content Group Settings
         */
        $post_args = apply_filters( 'page_generator_pro_generate_post_args', $post_args, $settings );

        /**
         * Run any actions immediately before an individual Page, Post or Custom Post Type is generated.
         *
         * @since   2.4.1
         *
         * @param   int     $group_id       Group ID
         * @param   array   $settings       Group Settings
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        do_action( 'page_generator_pro_generate_content_before_insert_update_post', $group_id, $settings, $index, $test_mode );

        // Create or Update a Post
        switch ( $settings['overwrite'] ) {

            /**
             * Overwrite
             */
            case 'overwrite':
                // If a Post was found, update it
                if ( $existing_post_id > 0 ) {
                    // Define the Post ID to update
                    $post_args['ID'] = $existing_post_id;

                    // Remove Post Args that we're not overwriting
                    $post_args = $this->restrict_post_args_by_overwrite_sections( array_keys( $settings['overwrite_sections'] ), $post_args );

                    // Delete Attachments assigned to the existing Post ID created by this Group
                    $this->delete_attachments_by_post_ids( array( $existing_post_id ), $group_id );

                    // Update Page, Post or CPT
                    $post_id = wp_update_post( $post_args, true );

                    // Define return message
                    $log = sprintf( 
                        __( 'Updated, as %s with Permalink already generated by this Group', 'page-generator-pro' ), 
                        $settings['type']
                    );
                } else {
                    // Create Page, Post or CPT
                    $post_id = wp_insert_post( $post_args, true );

                    // Define return message
                    $log = sprintf( 
                        __( 'Created, as %s with Permalink has not yet been generated by this Group', 'page-generator-pro' ), 
                        $settings['type']
                    );
                }
                break;

            /**
             * Overwrite Any
             */
            case 'overwrite_any':
                // If a Post was found, update it
                if ( $existing_post_id > 0 ) {
                    // Define the Post ID to update
                    $post_args['ID'] = $existing_post_id;

                    // Remove Post Args that we're not overwriting
                    $post_args = $this->restrict_post_args_by_overwrite_sections( array_keys( $settings['overwrite_sections'] ), $post_args );

                    // Delete Attachments assigned to the existing Post ID created by this Group
                    $this->delete_attachments_by_post_ids( array( $existing_post_id ), $group_id );

                    // Update Page, Post or CPT
                    $post_id = wp_update_post( $post_args, true );

                    // Define return message
                    $log = sprintf( 
                        __( 'Updated, as %s with Permalink already exists in WordPress', 'page-generator-pro' ), 
                        $settings['type']
                    );
                } else {
                    // Create Page, Post or CPT
                    $post_id = wp_insert_post( $post_args, true );

                    // Define return message
                    $log = sprintf( 
                        __( 'Created, as %s with Permalink does not exist in WordPress', 'page-generator-pro' ), 
                        $settings['type']
                    );
                }
                break;

            /**
             * Don't Overwrite
             */
            default:
                // Create Page, Post or CPT
                $post_id = wp_insert_post( $post_args, true );

                // Define return message
                $log = sprintf( 
                    __( 'Created', 'page-generator-pro' ), 
                    $settings['type']
                );
                break;

        }

        // Check Post creation / update worked
        if ( is_wp_error( $post_id ) ) {
            // Fetch error codes when trying to insert / update the Post
            $error_codes = $post_id->get_error_codes();

            // Ignore invalid_page_template errors.  wp_update_post() adds the existing page_template
            // parameter to $post_args before passing onto wp_insert_post(); however the template
            // might belong to a Page Builder Template that has / will not register the template with
            // the active Theme.
            // We manually assign _wp_page_template later on in this process, so we can safely ignore
            // this error.
            if ( count( $error_codes ) == 1 && $error_codes[0] == 'invalid_page_template' ) {
                // The Post ID will be the existing Post ID we just updated
                $post_id = $existing_post_id;
            } else {
                // UTF-8 encode the Title, Excerpt and Content
                $post_args['post_title'] = utf8_encode( $post_args['post_title'] );
                $post_args['post_content'] = utf8_encode( $post_args['post_content'] );
                if ( post_type_supports( $settings['type'], 'excerpt' ) ) {
                    $post_args['post_excerpt'] = utf8_encode( $post_args['post_excerpt'] );
                }
                
                // Try again
                if ( isset( $post_args['ID'] ) ) {
                    // Remove Post Args that we're not overwriting
                    $post_args = $this->restrict_post_args_by_overwrite_sections( array_keys( $settings['overwrite_sections'] ), $post_args );

                    // Update Page, Post or CPT
                    $post_id = wp_update_post( $post_args, true ); 
                } else {
                    // Create Page, Post or CPT
                    $post_id = wp_insert_post( $post_args, true ); 
                }

                // If Post creation / update still didn't work, bail
                if ( is_wp_error( $post_id ) ) {
                    $post_id->add_data( $post_args, $post_id->get_error_code() );
                    return $post_id;
                }
            }
        }

        /**
         * Run any actions immediately after an individual Page, Post or Custom Post Type is generated, but before
         * its Page Template, Featured Image, Custom Fields, Post Meta, Geodata or Taxonomy Terms have been assigned.
         *
         * @since   2.4.1
         *
         * @param   int     $post_id        Post ID
         * @param   int     $group_id       Group ID
         * @param   array   $settings       Group Settings
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        do_action( 'page_generator_pro_generate_content_after_insert_update_post', $post_id, $group_id, $settings, $index, $test_mode );

        // Store this Group ID and Index in the Post's meta, so we can edit/delete the generated Post(s) in the future
        update_post_meta( $post_id, '_page_generator_pro_group', $group_id );
        update_post_meta( $post_id, '_page_generator_pro_index', $index );

        // Assign Attachments that may have been created by shortcode processing to the generated Post
        // We do this here as shortcodes are processed before a Post is generated, therefore any Attachments
        // created won't have a Post ID
        $result = $this->assign_attachments_to_post_id( $post_id, $group_id, $index );
        if ( is_wp_error( $result ) && defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
            return $result;
        }

        // Post / Page Template
        // Backward compat for Free
        if ( ! empty( $settings['pageTemplate'] ) && ! is_array( $settings['pageTemplate'] ) ) {
            update_post_meta( $post_id, '_wp_page_template', $settings['pageTemplate'] );
        }
        if ( ! empty( $settings['pageTemplate'][ $settings['type'] ] ) ) {
            update_post_meta( $post_id, '_wp_page_template', $settings['pageTemplate'][ $settings['type'] ] );
        }

        // Store Custom Fields as Post Meta on the Generated Post
        $this->set_custom_fields( $post_id, $settings, $post_args );
        
        // Store Post Meta (ACF, Yoast, Page Builder data etc) on the Generated Post
        $this->set_post_meta( $post_id, $settings, $post_args );
      
        // Store Latitude and Longitude
        $result = $this->latitude_longitude( $post_id, $group_id, $settings );
        if ( is_wp_error( $result ) && defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
            return $result;
        }

        // Assign Generated Post to Menu, if required and we're not in Test Modes
        if ( ! $test_mode ) {
            $result = $this->set_menu( $post_id, $settings, $post_args );
            if ( is_wp_error( $result ) && defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return $result;
            }
        }

        // Assign Taxonomy Terms to the Generated Post
        $result = $this->assign_taxonomy_terms_to_post( $settings['tax'], $settings['type'], $post_id );
        if ( is_wp_error( $result ) && defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
            return $result;
        }

        // Featured Image
        // We do this last so that Page Builders e.g. Divi don't overwrite the _thumbnail_id after Custom Field / Post Meta copying
        $image_id = $this->featured_image( $post_id, $group_id, $index, $settings, $post_args );
        if ( is_wp_error( $image_id ) && defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
            return $image_id;
        }

        // Request that the user review the Plugin, if we're not in Test Mode. Notification displayed later,
        // can be called multiple times and won't re-display the notification if dismissed.
        if ( ! $test_mode && ! $this->base->licensing->has_feature( 'whitelabelling' ) ) {
            $this->base->dashboard->request_review();
        
            // Store current index as the last index generated for this Group, if we're not in test mode
            $this->base->get_class( 'groups' )->update_last_index_generated( $group_id, $index );
        }

        /**
         * Run any actions after an individual Page, Post or Custom Post Type is generated
         * successfully.
         *
         * @since   2.4.1
         *
         * @param   int     $post_id        Generated Post ID
         * @param   int     $group_id       Group ID
         * @param   array   $settings       Group Settings
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        do_action( 'page_generator_pro_generate_content_finished', $post_id, $group_id, $settings, $index, $test_mode );

        // Return success data
        return $this->generate_return( $group_id, $post_id, $settings['type'], true, $log, $start, $test_mode, $system );

    }

    /**
     * Generates a Taxonomy Term for the given Group and Index
     *
     * @since   1.0.0
     *
     * @param   int     $group_id   Group ID
     * @param   int     $index      Keyword Index
     * @param   bool    $test_mode  Test Mode
     * @param   string  $system     System (browser|cron|cli)
     * @return  mixed               WP_Error | array
     */
    public function generate_term( $group_id, $index, $test_mode = false, $system = 'browser' ) {

        // Performance debugging
        $start = microtime( true );

        // If test mode is enabled, set the debug constant
        if ( $test_mode && ! defined( 'PAGE_GENERATOR_PRO_DEBUG' ) ) {
            define( 'PAGE_GENERATOR_PRO_DEBUG', true );
        }

        // If this Group has a request to cancel generation, exit
        if ( ! $test_mode ) {
            if ( $this->base->get_class( 'groups_terms' )->cancel_generation_requested( $group_id ) ) {
                $this->base->get_class( 'groups_terms' )->stop_generation( $group_id );
                return new WP_Error( 'generation_error', __( 'A request to cancel generation was made by the User. Exiting...', 'page-generator-pro' ) );
            }
        }

        // Get group settings
        $settings = $this->base->get_class( 'groups_terms' )->get_settings( $group_id );

        /**
         * Run any actions before an individual Term is generated successfully.
         *
         * @since   2.4.1
         *
         * @param   int     $group_id       Group ID
         * @param   array   $settings       Group Settings
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        do_action( 'page_generator_pro_generate_term_started', $group_id, $settings, $index, $test_mode );

        // Validate group
        $validated = $this->base->get_class( 'groups_terms' )->validate( $group_id );
        if ( is_wp_error( $validated ) ) {
            return $validated;
        }

        // Build a class array of required keywords that need replacing with data
        $this->find_keywords_in_settings( $settings );
        if ( count( $this->required_keywords ) == 0 ) {
            return new WP_Error( 'page_generator_pro_generate_generate_term_keyword_error', __( 'No keywords were specified in the Group.', 'page-generator-pro' ) );
        }

        // Build a keywords array comprising of terms, columns and delimiters for each of the required keywords
        $this->keywords = $this->get_keywords_terms_columns_delimiters( $this->required_keywords );
        if ( count( $this->keywords['terms'] ) == 0 ) {
            return new WP_Error( 'page_generator_pro_generate_generate_term_keyword_error', __( 'Keywords were specified in the Group, but no keywords exist in either the Keywords section of the Plugin or as a Taxonomy.', 'page-generator-pro' ) );
        }

        // Build array of keyword --> term key/value pairs to use for this generation
        $this->keywords_terms = $this->get_keywords_terms( $settings['method'], $index );
        if ( is_wp_error( $this->keywords_terms ) ) {
            return $this->keywords_terms;
        }

        // Reset search and replacement arrays
        $this->searches = array();
        $this->replacements = array();

        // Iterate through each detected Keyword to build a full $this->searches and $this->replacements arrays
        $this->build_search_replace_arrays();
        
        // Iterate through each keyword and term key/value pair
        $settings = $this->replace_keywords( $settings );

        // If 'Apply Synonyms' has been enabled, add spintax to the excerpt now
        if ( isset( $settings['apply_synonyms'] ) && $settings['apply_synonyms'] ) {
            $settings['excerpt'] = $this->base->get_class( 'spintax' )->add_spintax( $settings['excerpt'] );
        }

        // Build Term args
        $term_args = array(
            'description'   => $settings['excerpt'],
        );

        // Define Slug
        // If no Permalink exists, use the Title
        if ( ! empty( $settings['permalink'] ) ) {
            $term_args['slug'] = sanitize_title( $settings['permalink'] );
        }

        // If the taxonomy is hierarhical, and a parent term has been specified, attempt to find it now
        $parent_term_id = 0;
        if ( is_taxonomy_hierarchical( $settings['taxonomy'] ) && ! empty( $settings['parent_term'] ) ) {
            $existing_parent_terms = new WP_Term_Query( array(
                'taxonomy'      => array( $settings['taxonomy'] ),
                'name'          => array( $settings['parent_term'] ),
                'hide_empty'    => false,
                
                // For performance, just return the Post ID and don't update meta or term caches
                'fields'                => 'ids',
                'update_term_meta_cache'=> false,
            ) );

            // If an existing Parent Term was found, use that
            if ( ! is_null( $existing_parent_terms->terms ) && count( $existing_parent_terms->terms ) > 0 ) {
                $parent_term_id = $existing_parent_terms->terms[0];
            } else {
                $parent_term = wp_insert_term( $settings['parent_term'], $settings['taxonomy'] ); 
                $parent_term_id = $parent_term['term_id'];
            }

            // Assign the parent term ID now
            $term_args['parent'] = $parent_term_id;
        }

        /**
         * Filters arguments used for creating or updating a Term when running
         * content generation.
         *
         * @since   1.6.1
         *
         * @param   array   $term_args  wp_insert_term() / wp_update_term() compatible arguments
         * @param   array   $settings   Content Group Settings
         */
        $term_args = apply_filters( 'page_generator_pro_generate_term_args', $term_args, $settings );

        // Depending on the Overwrite setting, check if an existing Term exists
        switch ( $settings['overwrite'] ) {
            /**
             * No, skip if existing Term generated by this Group
             */
            case 'skip_if_exists':
                // Find existing Term by Permalink generated by this Group
                $existing_term_id = $this->term_exists( $group_id, $settings['taxonomy'], $parent_term_id, $settings['title'] );

                // Bail if a Term is found, as we're skipping Generation
                if ( $existing_term_id > 0 ) {
                    return $this->generate_return( 
                        $group_id,
                        $existing_term_id, 
                        $settings['taxonomy'],
                        false,
                        sprintf(
                            __( 'Skipped, as %s with Permalink already generated by this Group', 'page-generator-pro' ),
                            $settings['taxonomy']
                        ),
                        $start,
                        $test_mode,
                        $system
                    );
                }
                break;

            /**
             * No, skip if existing Term exists
             */
            case 'skip_if_exists_any':
                // Find existing Post by Permalink, regardless of Group
                $existing_term_id = $this->term_exists( 0, $settings['taxonomy'], $parent_term_id, $settings['title'] );
                
                // Bail if a Post is found, as we're skipping Generation
                if ( $existing_term_id > 0 ) {
                    return $this->generate_return( 
                        $group_id,
                        $existing_term_id, 
                        $settings['taxonomy'],
                        false,
                        sprintf(
                            __( 'Skipped, as %s with Permalink already exists in WordPress', 'page-generator-pro' ),
                            $settings['taxonomy']
                        ),
                        $start,
                        $test_mode,
                        $system
                    );
                }
                break;

            /**
             * Yes, if existing Term generated by this Group
             */
            case 'overwrite':
                // Try to find existing term
                $existing_term_id = $this->term_exists( $group_id, $settings['taxonomy'], $parent_term_id, $settings['title'] );
                break;

            /**
             * Yes, if existing Term exists
             */
            case 'overwrite_any':
                // Try to find existing post
                $existing_term_id = $this->term_exists( 0, $settings['taxonomy'], $parent_term_id, $settings['title'] );
                break;
        }

        /**
         * Run any actions immediately before an individual Term is generated.
         *
         * @since   2.4.1
         *
         * @param   int     $group_id       Group ID
         * @param   array   $settings       Group Settings
         * @param   int     $index          Keyword Index
         * @param   bool    $test_mode      Test Mode
         */
        do_action( 'page_generator_pro_generate_term_before_insert_update_term', $group_id, $settings, $index, $test_mode );

        // Create or Update a Term
        switch ( $settings['overwrite'] ) {

            /**
             * Overwrite
             */
            case 'overwrite':
                // If a Term was found, update it
                if ( $existing_term_id > 0 ) {
                    // Update Term
                    $term = wp_update_term( $existing_term_id, $settings['taxonomy'], $term_args );

                    // Define return message
                    $log = __( 'Updated, as Term with Permalink already generated by this Group', 'page-generator-pro' );
                } else {
                    // Create Term
                    $term = wp_insert_term( $settings['title'], $settings['taxonomy'], $term_args ); 

                    // Define return message
                    $log = __( 'Created, as Term with Permalink has not yet been generated by this Group', 'page-generator-pro' );
                }
                break;

            /**
             * Overwrite Any
             */
            case 'overwrite_any':
                // If a Term was found, update it
                if ( $existing_term_id > 0 ) {
                    // Update Term
                    $term = wp_update_term( $existing_term_id, $settings['taxonomy'], $term_args );

                    // Define return message
                    $log = sprintf( 
                        __( 'Updated, as %s with Permalink already exists in WordPress', 'page-generator-pro' ),
                        $settings['taxonomy']
                    );
                } else {
                    // Create Term
                    $term = wp_insert_term( $settings['title'], $settings['taxonomy'], $term_args ); 

                    // Define return message
                    $log = __( 'Created, as %s with Permalink does not exist in WordPress', 'page-generator-pro' );
                }
                break;

            /**
             * Don't Overwrite
             */
            default:
                // If the Term already exists in this Taxonomy, just return it
                // This prevents calling wp_insert_term(), which would WP_Error when the Taxonomy Term already exists
                $existing_term_id = $this->term_exists( 0, $settings['taxonomy'], $parent_term_id, $settings['title'] );
                if ( $existing_term_id > 0 ) {
                    return $this->generate_return( 
                        $group_id,
                        $existing_term_id, 
                        $settings['taxonomy'],
                        false,
                        sprintf(
                            __( 'Skipped222, as %s with Permalink already exists in WordPress', 'page-generator-pro' ),
                            $settings['taxonomy']
                        ),
                        $start,
                        $test_mode,
                        $system
                    );
                } else {
                    // Create Term
                    $term = wp_insert_term( $settings['title'], $settings['taxonomy'], $term_args ); 

                    // Define return message
                    $log = sprintf( 
                        __( 'Created', 'page-generator-pro' ), 
                        $settings['taxonomy']
                    );
                }
                break;

        }

        // Check Term creation / update worked
        if ( is_wp_error( $term ) ) {
            $term->add_data( $term_args, $term->get_error_code() );
            return $term;
        }

        /**
         * Run any actions immediately after an individual Taxonomy Term is generated, but before
         * its Custom Fields or Term Meta have been assigned.
         *
         * @since   2.6.3
         *
         * @param   array   $term       Generated Term
         * @param   int     $group_id   Group ID
         * @param   array   $settings   Group Settings
         * @param   int     $index      Keyword Index
         * @param   bool    $test_mode  Test Mode
         */
        do_action( 'page_generator_pro_generate_term_after_insert_update_term', $term, $group_id, $settings, $index, $test_mode );

        // Store this Group ID and Index in the Term's meta, so we can edit/delete the generated Term(s) in the future
        update_term_meta( $term['term_id'], '_page_generator_pro_group', $group_id );
        update_term_meta( $term['term_id'], '_page_generator_pro_index', $index );

        // Store Term Meta (ACF, Yoast, Page Builder data etc) on the Generated Term
        $this->set_term_meta( $term['term_id'], $settings, $term_args );

        if ( ! $test_mode ) {
            // Request that the user review the plugin. Notification displayed later,
            // can be called multiple times and won't re-display the notification if dismissed.
            $this->base->dashboard->request_review();

            // Store current index as the last index generated for this Group, if we're not in test mode
            $this->base->get_class( 'groups_terms' )->update_last_index_generated( $group_id, $index );
        }

        /**
         * Run any actions after an individual Term is generated successfully.
         *
         * @since   2.4.1
         *
         * @param   array   $term       Generated Term
         * @param   int     $group_id   Group ID
         * @param   array   $settings   Group Settings
         * @param   int     $index      Keyword Index
         * @param   bool    $test_mode  Test Mode
         */
        do_action( 'page_generator_pro_generate_term_finished', $term, $group_id, $settings, $index, $test_mode );

        // Return the URL and keyword / term replacements used
        return $this->generate_return( $group_id, $term['term_id'], $settings['taxonomy'], true, $log, $start, $test_mode, $system );

    }

    /**
     * For all Keyword tags found in the Group, builds search and replacement class arrays for later use
     * when recursively iterating through a Group's settings to replace the Keyword tags with their Term counterparts
     *
     * @since   2.6.1
     */
    private function build_search_replace_arrays() {

        foreach ( $this->required_keywords_full as $keyword => $keywords_with_modifiers ) {
            // Build search and replacement arrays for this Keyword
            foreach ( $keywords_with_modifiers as $keyword_with_modifiers ) {
                // If the Keyword isn't truly a Keyword in the database, don't do anything
                if ( ! isset( $this->keywords_terms[ $keyword ] ) ) {
                    continue;
                }
                
                // Cast keyword as a string so numeric keywords don't break search/replace
                $this->build_search_replace_arrays_for_keyword( $keyword_with_modifiers, (string) $keyword, $this->keywords_terms[ $keyword ] );
            }
        }

    }

    /**
     * Appends the search and replace arrays for the given Keyword (column name, nth term, transformations) and its applicable Term.
     *
     * @since   2.6.1
     *
     * @param   string  $keyword_with_modifiers     Keyword with Modifiers (search, e.g. keyword(column):3:uppercase_all:url
     * @param   string  $keyword                    Keyword without Modifiers (e.g. keyword)
     * @param   string  $term                       Term (replacement)
     */
    private function build_search_replace_arrays_for_keyword( $keyword_with_modifiers, $keyword, $term ) {

        // If the Keyword with Modifiers matches the Keyword, we have no modifiers
        // Just return the term
        if ( $keyword_with_modifiers == $keyword ) {
            $this->searches[] = '{' . $keyword_with_modifiers . '}';
            $this->replacements[] = $term;
            return;
        }

        // Fetch an array of transformations that might exist and need applying to the Term
        $keyword_transformations = false;
        if ( strpos( $keyword_with_modifiers, ':' ) !== false ) {
            $keyword_transformations = explode( ':', $keyword_with_modifiers );

            // @TODO Make array unique to avoid double transformations that do the same thing for performance
        }

        // If Keyword Transformation(s) exist, and one Transformation is numeric, this is an nth term specifier
        // Fetch the Keyword's nth Term now as the Term to use
        if ( $keyword_transformations ) {
            foreach ( $keyword_transformations as $keyword_transformation ) {
                if ( ! is_numeric( $keyword_transformation ) ) {
                    continue;
                }

                // Keyword Transformation is an nth term specifier
                if ( isset( $this->keywords['terms'][ $keyword ][ $keyword_transformation - 1 ] ) ) {
                    $term = $this->keywords['terms'][ $keyword ][ $keyword_transformation - 1 ];    
                }
            }
        }

        // If the Keyword contains a column, fetch the Keyword Term's Column value now
        $column = false;
        $keyword_column_start_bracket_position = strpos( $keyword_with_modifiers, '(' );
        $keyword_column_end_bracket_position = strpos( $keyword_with_modifiers, ')', $keyword_column_start_bracket_position );
        if ( $keyword_column_start_bracket_position !== false && $keyword_column_end_bracket_position !== false ) {
            // Extract Column Name
            $column = substr( $keyword_with_modifiers, ( $keyword_column_start_bracket_position + 1 ), ( $keyword_column_end_bracket_position - $keyword_column_start_bracket_position - 1 ) );
        
            // Split the Term into Columns
            $term_parts = str_getcsv( $term, $this->keywords['delimiters'][ $keyword ] );

            // Fetch the Column Index
            $column_index = array_search( $column, $this->keywords['columns'][ $keyword ] );
            
            // Fetch the Term
            if ( $column_index !== FALSE && isset( $term_parts[ $column_index ] ) ) {
                $term = trim( $term_parts[ $column_index ] );
            }
        }

        // If Keyword Transformation(s) exist, transform the Term using each Transformation in the order
        // they're listed.
        if ( $keyword_transformations ) {
            foreach ( $keyword_transformations as $keyword_transformation ) {
                // Keyword Transformation is an nth term specifier; skip as we dealt with this earlier
                if ( is_numeric( $keyword_transformation ) ) {
                    continue;
                }

                $term = $this->apply_keyword_transformation( $keyword_transformation, $term, $keyword, $column );
            }
        }

        // Add Keyword and Term to Search and Replace arrays
        $this->searches[] = '{' . $keyword_with_modifiers . '}';
        $this->replacements[] = $term;

        // If $term is an array, set a flag
        if ( is_array( $term ) ) {
            $this->replacements_contain_array = true;
        }

    }

    /**
     * Applies the given keyword transformation to the given string (term)
     *
     * @since   2.2.3
     *
     * @param   string  $keyword_transformation     Keyword Transformation
     * @param   string  $term                       Term
     * @param   string  $keyword                    Keyword
     * @param   mixed   $column                     Keyword Column
     * @return  string                              Transformed Term
     */
    private function apply_keyword_transformation( $keyword_transformation, $term, $keyword, $column = false ) {

        switch ( $keyword_transformation ) {
            /**
             * Uppercase
             */
            case 'uppercase_all':
                // Use i18n compatible method if available
                if ( function_exists( 'mb_convert_case' ) ) {
                    return mb_convert_case( $term, MB_CASE_UPPER );    
                }
                
                // Fallback to basic version which doesn't support i18n
                return strtoupper( $term );
                break;

            /**
             * Lowercase
             */
            case 'lowercase_all':
                // Use i18n compatible method if available
                if ( function_exists( 'mb_convert_case' ) ) {
                    return mb_convert_case( $term, MB_CASE_LOWER );    
                }
                
                // Fallback to basic version which doesn't support i18n
                return strtolower( $term );
                break;

            /**
             * Upperchase first character
             */
            case 'uppercase_first_character':
                // Use i18n compatible method if available
                if ( function_exists( 'mb_strtoupper' ) ) {
                    return mb_strtoupper( mb_substr( $term, 0, 1 ) ) . mb_substr( $term, 1 );  
                }
                
                // Fallback to basic version which doesn't support i18n
                return ucfirst( $term );
                break;

            /**
             * Uppercase first character of each word
             */
            case 'uppercase_first_character_words':
                // Use i18n compatible method if available
                if ( function_exists( 'mb_convert_case' ) ) {
                    return mb_convert_case( $term, MB_CASE_TITLE );    
                }
                
                // Fallback to basic version which doesn't support i18n
                return ucwords( $term );
                break;

            /**
             * First Word
             */
            case 'first_word':
                $term_parts = explode( ' ', $term );
                return $term_parts[0];
                break;

            /**
             * Last Word
             */
            case 'last_word':
                $term_parts = explode( ' ', $term );
                return $term_parts[ count( $term_parts ) - 1 ];
                break;

            /**
             * URL
             */
            case 'url':
                return sanitize_title( $term );
                break;

            /**
             * All, comma separated
             */
            case 'all':
                if ( $column ) {
                    $terms = array();
                    foreach ( $this->keywords['terms'][ $keyword ] as $term ) {
                        // Split the term
                        $term_parts = str_getcsv( $term, $this->keywords['delimiters'][ $keyword ] );

                        // Fetch the column index
                        $column_index = array_search( $column, $this->keywords['columns'][ $keyword ] );

                        // Skip if no column index could be found
                        if ( $column_index === FALSE ) {
                            continue;
                        }

                        $terms[] = ( isset( $term_parts[ $column_index ] ) ? trim( $term_parts[ $column_index ] ) : '' );
                    }

                    // Remove duplicates
                    $terms = array_values( array_unique( $terms ) );

                    // Return All Column Terms for the Keyword
                    return implode( ', ', $terms );
                }
                
                // Return all Terms for the Keyword
                return implode( ', ', $this->keywords['terms'][ $keyword ] );
                break;

            /**
             * Random
             */
            case 'random':
                if ( $column ) {
                    $terms = array();
                    foreach ( $this->keywords['terms'][ $keyword ] as $term ) {
                        // Split the term
                        $term_parts = str_getcsv( $term, $this->keywords['delimiters'][ $keyword ] );

                        // Fetch the column index
                        $column_index = array_search( $column, $this->keywords['columns'][ $keyword ] );

                        // Skip if no column index could be found
                        if ( $column_index === FALSE ) {
                            continue;
                        }

                        $terms[] = ( isset( $term_parts[ $column_index ] ) ? trim( $term_parts[ $column_index ] ) : '' );
                    }

                    // Remove duplicates
                    $terms = array_values( array_unique( $terms ) );
                } else {
                    $terms = $this->keywords['terms'][ $keyword ];
                }

                // Pick a term at random
                $term_index = rand( 0, ( count( $terms ) - 1 ) );  

                // Return random term
                return $terms[ $term_index ];
                break;

            /**
             * Random, Different
             * - Returns an array, so generation can pick a Term at random each time
             */
            case 'random_different':
                if ( $column ) {
                    $terms = array();
                    foreach ( $this->keywords['terms'][ $keyword ] as $term ) {
                        // Split the term
                        $term_parts = str_getcsv( $term, $this->keywords['delimiters'][ $keyword ] );

                        // Fetch the column index
                        $column_index = array_search( $column, $this->keywords['columns'][ $keyword ] );

                        // Skip if no column index could be found
                        if ( $column_index === FALSE ) {
                            continue;
                        }

                        $terms[] = ( isset( $term_parts[ $column_index ] ) ? trim( $term_parts[ $column_index ] ) : '' );
                    }

                    // Remove duplicates
                    $terms = array_values( array_unique( $terms ) );

                    // Return All Column Terms for the Keyword
                    return $terms;
                }
                
                // Return all Terms for the Keyword
                return $this->keywords['terms'][ $keyword ];
                break;

            /**
             * Other Transformations
             */
            default:
                /**
                 * Filter to perform non-standard keyword transformation.
                 *
                 * @since   1.7.8
                 *
                 * @param   string  $term               Term
                 * @param   string  $transformation     Keyword Transformation
                 * @param   string  $keyword            Keyword
                 * @param   mixed   $column             Keyword Column
                 */
                $term = apply_filters( 'page_generator_pro_generate_generate_content_apply_keyword_transformation', $term, $keyword_transformation, $keyword, $column );
                
                return $term;
                break;
        } 
 
    }

    /**
     * Helper method to iterate through each keyword's tags, including any modifiers,
     * building search and replacement arrays before recursively iterating through the supplied settings,
     * replacing the keywords and their transformations with the terms.
     *
     * @since   1.9.8
     *
     * @param   array   $settings   Group Settings
     * @return  array               Group Settings
     */
    public function replace_keywords( $settings ) {

        // Iterate through Group Settings, replacing $this->searches (Keywords) with $this->replacements (Terms)
        // as well as performing spintax and shortcode processing
        array_walk_recursive( $settings, array( $this, 'replace_keywords_in_array' ) );

        // Return
        return $settings;

    }

    /**
     * Returns an array comprising of all keywords and their term replacements,
     * including keywords with column names in the format keyword_column.
     *
     * Does not include transformations or nth terms
     *
     * Used to store basic keyword/term data in the generated Page's Post Meta
     * if Store Keywords is enabled
     *
     * @since   2.2.8
     *
     * @return  array   Keyword / Term Key/Value Pairs
     */
    private function get_keywords_terms_array_with_columns() {

        $store_keywords = array();

        foreach ( $this->keywords_terms as $keyword => $term ) {
            // Add keyword/term pair
            $store_keywords[ $keyword ] = $term;

            // If no columns exist for this Keyword, continue
            if ( ! isset( $this->keywords['columns'] ) ) {
                continue;
            }
            if ( ! isset( $this->keywords['columns'][ $keyword ] ) ) {
                continue;
            }

            foreach ( $this->keywords['columns'][ $keyword ] as $column ) {
                // Split the term
                $term_parts = str_getcsv( $term, $this->keywords['delimiters'][ $keyword ] );

                // Fetch the column index
                $column_index = array_search( $column, $this->keywords['columns'][ $keyword ] );

                // Skip if no column index could be found
                if ( $column_index === FALSE ) {
                    continue;
                }

                // Add to the search and replace arrays
                $store_keywords[ $keyword . '_' . $column ] = ( isset( $term_parts[ $column_index ] ) ? trim( $term_parts[ $column_index ] ) : '' );
            }
        }

        // Bail if no keywords
        if ( count( $store_keywords ) == 0 ) {
            return false;
        }

        return $store_keywords;
    }

    /**
     * Returns an array comprising of keywords, with each keyword having a replacement value, based
     * on the index requested.
     *
     * Returns an array comprising of all possible value combinations, for the given keywords and terms
     *
     * @since   1.5.1
     *
     * @param   array   $input  Multidimensional array of Keyword Names (keys) => Terms (values)
     * @return  array           Single dimensional array, zero indexed, of keyword names (keys) => term (value)
     */
    private function generate_all_array_combinations( $input ) {

        // Setup vars
        $input  = array_filter( $input );
        $result = array( array() );

        // Iterate through each keyword
        foreach ( $input as $keyword => $terms ) {
            $append = array();

            // Iterate through master array of results
            foreach ( $result as $product ) {
                // Iterate through this keyword's terms
                foreach( $terms as $term ) {
                    $product[ $keyword ] = $term;
                    $append[] = $product;
                }
            }

            // Append the list of Terms to the master array of results
            $result = $append;
        }

        return $result;

    }

    /**
     * A faster method for fetching all keyword combinations for PHP 5.5+
     *
     * @since   1.5.1
     *
     * @param   array   $input  Multidimensional array of Keyword Names (keys) => Terms (values)
     * @return  \Generator      Generator
     */
    private function generate_all_combinations( $input ) {

        // Load class
        require_once( $this->base->plugin->folder . '/includes/admin/cartesian-product.php' );

        // Return
        return new Page_Generator_Pro_Cartesian_Product( $input );

    }

    /**
     * Recursively goes through the settings array, finding any {keywords}
     * specified, to build up an array of keywords we need to fetch.
     *
     * @since   1.0.0
     *
     * @param   array   $settings   Settings
     */
    private function find_keywords_in_settings( $settings ) {

        // Recursively walk through all settings to find all keywords
        array_walk_recursive( $settings, array( $this, 'find_keywords_in_string' ) );

    }

    /**
     * For the given array of keywords, only returns keywords with terms, column names and delimiters
     * where each keywords have terms.
     *
     * @since   1.6.5
     *
     * @param   array   $required_keywords  Required Keywords
     * @return  array                       Keywords with Terms, Columns and Delimiters
     */
    private function get_keywords_terms_columns_delimiters( $required_keywords ) {

        // Define blank array for keywords with terms and keywords with columns
        $results = array(
            'terms'     => array(),
            'columns'   => array(),
            'delimiters'=> array(),
        );

        foreach ( $required_keywords as $key => $keyword ) {

            // Get terms for this keyword
            // If this keyword starts with 'taxonomy_', try to fetch the terms for the Taxonomy
            if ( strpos( $keyword, 'taxonomy_') !== false && strpos( $keyword, 'taxonomy_' ) == 0 ) {
                $result = get_terms( array(
                    'taxonomy'              => str_replace( 'taxonomy_', '', $keyword ),
                    'hide_empty'            => false,
                    'fields'                => 'names',
                    'update_term_meta_cache'=> false,
                ) );

                // Skip if no results
                if ( ! is_array( $result ) ) {
                    continue;
                }
                if ( count( $result ) == 0 ) {
                    continue;
                }

                $results['terms'][ $keyword ] = $result;
            } else {
                $result = $this->base->get_class( 'keywords' )->get_by( 'keyword', $keyword );

                // Skip if no results
                if ( ! is_array( $result ) ) {
                    continue;
                }
                if ( count( $result ) == 0 ) {
                    continue;
                }

                $results['terms'][ $keyword ]       = $result['dataArr'];
                
                // Ensure column names are lowercase so array_search() works against our lowercase keyword + column
                $results['columns'][ $keyword ]     = array();
                foreach ( $result['columnsArr'] as $column ) {
                    $results['columns'][ $keyword ][] = strtolower( $column );
                }
                $results['delimiters'][ $keyword ]  = $result['delimiter'];
            }
        }

        // Return results
        return $results;

    }

    /**
     * Returns an array of keyword and term key / value pairs.
     * 
     * @since   1.0.0
     *
     * @param   string  $method     Generation Method
     * @param   int     $index      Generation Index
     * @return  mixed               WP_Error | array
     * 
     */
    private function get_keywords_terms( $method, $index ) {

         switch ( $method ) {

            /**
             * All
             * - Generates all possible term combinations across keywords
             */
            case 'all':
                // If we're on PHP 5.5+, use our Cartesian Product class, which implements a Generator
                // to allow iteration of data without needing to build an array in memory.
                // See: http://php.net/manual/en/language.generators.overview.php
                if ( version_compare( phpversion(), '5.5.0', '>=' ) ) {
                    // Use PHP 5.5+ Generator
                    $combinations = $this->generate_all_combinations( $this->keywords['terms'] );

                    // If the current index exceeds the total number of combinations, we've exhausted all
                    // options and don't want to generate any more Pages (otherwise we end up with duplicates)
                    if ( $index > ( $combinations->count() - 1 ) ) {
                        // If the combinations count is a negative number, we exceeded the floating point for an integer
                        // Tell the user to upgrade PHP and/or reduce the number of keyword terms
                        if ( $combinations->count() < 0 ) {
                            $message = __( 'The total possible number of unique keyword term combinations exceeds the maximum number value that can be stored by your version of PHP.  Please consider upgrading to a 64 bit PHP 7.0+ build and/or reducing the number of keyword terms that you are using.', 'page-generator-pro' );
                        } else {
                            $message = __( 'All possible keyword term combinations have been generated. Generating more Pages/Posts would result in duplicate content.', 'page-generator-pro' );
                        }

                        return new WP_Error( 'page_generator_pro_generate_content_keywords_exhausted', $message );
                    }

                    // Iterate through the combinations until we reach the one matching the index
                    // @TODO Can we optimize this?
                    foreach ( $combinations as $c_index => $combination ) {
                        // Skip if not the index we want
                        if ( $c_index != $index ) {
                            continue;
                        }

                        // Define the keyword => term key/value pairs to use based on the current index
                        $keywords_terms = $combination;
                        break;
                    }
                } else {
                    // Use older method, which will hit memory errors
                    $combinations = $this->generate_all_array_combinations( $this->keywords['terms'] );  

                    // If the current index exceeds the total number of combinations, we've exhausted all
                    // options and don't want to generate any more Pages (otherwise we end up with duplicates)
                    if ( $index > ( count( $combinations ) - 1 ) ) {
                        return new WP_Error( 'keywords_exhausted', __( 'All possible keyword term combinations have been generated. Generating more Pages/Posts would result in duplicate content.', 'page-generator-pro' ) );
                    }

                    // Define the keyword => term key/value pairs to use based on the current index
                    $keywords_terms = $combinations[ $index ];
                    break;
                }
                break;

            /**
             * Sequential
             * - Generates term combinations across keywords matched by index
             */
            case 'sequential':
                $keywords_terms = array();
                foreach ( $this->keywords['terms'] as $keyword => $terms ) {
                    // Use modulo to get the term index for this keyword
                    $term_index = ( $index % count( $terms ) );   

                    // Build the keyword => term key/value pairs
                    $keywords_terms[ $keyword ] = $terms[ $term_index ];
                }
                break;

            /**
             * Random
             * - Gets a random term for each keyword
             */
            case 'random':
                $keywords_terms = array();
                foreach ( $this->keywords['terms'] as $keyword => $terms ) {
                    $term_index = rand( 0, ( count( $terms ) - 1 ) );  

                    // Build the keyword => term key/value pairs
                    $keywords_terms[ $keyword ] = $terms[ $term_index ];
                }
                break;

            /**
             * Invalid method
             */
            default:    
                return new WP_Error( 'page_generator_pro_generate_get_keywords_terms_invalid_method', __( 'The method given is invalid.', 'page-generator-pro' ) );
                break;
        }

        // Cleanup the terms
        foreach ( $keywords_terms as $key => $term ) {
            $keywords_terms[ $key ] = trim( html_entity_decode( $term ) );
        }

        return $keywords_terms;

    }

    /**
     * Performs a search on the given string to find any {keywords}
     *
     * @since 1.2.0
     *
     * @param   string  $content    Array Value (string to search)
     * @param   string  $key        Array Key
     */
    private function find_keywords_in_string( $content, $key ) {

        // If $content is an object, iterate this call
        if ( is_object( $content ) ) {
            return array_walk_recursive( $content, array( $this, 'find_keywords_in_string' ) );
        }

        // Get keywords and spins in this string
        preg_match_all( "|{(.+?)}|", $content, $matches );

        // Bail if no matches are found
        if ( ! is_array( $matches ) ) {
            return;
        }
        if ( count( $matches[1] ) == 0 ) {
            return;
        }

        // Iterate through matches
        foreach ( $matches[1] as $m_key => $keyword ) {
            // If this is a spin, ignore it
            if ( strpos( $keyword, "|" ) !== false ) {
                continue;
            }

            // If a keyword is within spintax at the start of the string (e.g. {{service}|{service2}} ),
            // we get an additional leading curly brace for some reason.  Remove it
            $keyword = str_replace( '{', '', $keyword );
            $keyword = str_replace( '}', '', $keyword );

            // Lowercase keyword, to avoid duplicates e.g. {City} and {city}
            $keyword = strtolower( $keyword );

            // Fetch just the Keyword Name
            $keyword_name = $this->extract_keyword_name_from_keyword( $keyword );

            // If the Keyword Name is not in our required_keywords array, add it now
            if ( ! in_array( $keyword_name, $this->required_keywords ) ) {
                $this->required_keywords[ $keyword_name ] = $keyword_name;
            }

            // If the Keyword (Full) is not in our required_keywords_full array, add it now
            if ( ! isset( $this->required_keywords_full[ $keyword_name ] ) ) {
                $this->required_keywords_full[ $keyword_name ] = array();
            }
            if ( ! in_array( $keyword, $this->required_keywords_full[ $keyword_name ] ) ) {
                $this->required_keywords_full[ $keyword_name ][] = $keyword;
            }
        }

    }

    /**
     * Returns just the keyword name, excluding any columns, nth terms and transformations
     *
     * @since   2.6.1
     *
     * @param   string  $keyword    Keyword
     * @return  string              Keyword Name excluding any columns, nth terms and transformations
     */
    private function extract_keyword_name_from_keyword( $keyword ) {

        if ( strpos( $keyword, ':' ) !== false ) {
            $keyword_parts = explode( ':', $keyword );
            $keyword = trim( $keyword_parts[0] );
        }

        $keyword = preg_replace( '/\(.*?\)/', '', $keyword );

        return $keyword;

    }

    /**
     * array_walk_recursive callback, which finds $this->searches, replacing with
     * $this->replacements in $item.
     *
     * Also performs spintax.
     *
     * @since   1.3.1
     *
     * @param   mixed   $item   Item (array, object, string)
     * @param   string  $key    Key
     */
    private function replace_keywords_in_array( &$item, $key ) {

        // If the settings key's value is an array, walk through it recursively to search/replace
        // Otherwise do a standard search/replace on the string
        if ( is_array( $item ) ) {
            // Array
            array_walk_recursive( $item, array( $this, 'replace_keywords_in_array' ) );
        } elseif( is_object( $item ) ) {
            // Object
            array_walk_recursive( $item, array( $this, 'replace_keywords_in_array' ) );
        } elseif ( is_string( $item ) ) {
            // If here, we have a string
            // Perform keyword replacement, spintax and shortcode processing now

            // If replacements contain an array, we're using the :random_different Keyword Transformation
            // and therefore need to perform a slower search/replace to iterate through every occurance of
            // the same transformation
            if ( $this->replacements_contain_array ) {
                foreach ( $this->searches as $index => $search ) {
                    // Standard search/replace
                    if ( ! is_array( $this->replacements[ $index ] ) ) {
                        $item = str_ireplace( $search, $this->replacements[ $index ], $item );
                        continue;
                    }

                    // Pluck a value at random from the array of replacement Terms for the given search, doing this
                    // every time we find the Keyword, so we get truly random Terms each time in a single string
                    $pos = strpos( $item, $search );
                    while ( $pos !== false ) {
                        $item = substr_replace( $item, $this->replacements[ $index ][ rand( 0, ( count( $this->replacements[ $index ] ) - 1 ) ) ], $pos, strlen( $search ) );

                        // Search for next occurrence of this Keyword   
                        $pos = strpos( $item, $search, $pos + 1 );
                    }
                }
            } else {
                // Replace all searches with all replacements
                $item = str_ireplace( $this->searches, $this->replacements, $item );
            }

            // Process Spintax
            $result = $this->base->get_class( 'spintax' )->process( $item );
            if ( is_wp_error( $result ) ) {
                if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG ) {
                    // Store the error in the item
                    $item = $result->get_error_message();
                    return;
                }
            }

            // Spintax OK - assign to item
            $item = $result;

            // Process Block Spinning
            $result = $this->base->get_class( 'block_spin' )->process( $item );
            if ( is_wp_error( $result ) ) {
                if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG ) {
                    // Store the error in the item
                    $item = $result->get_error_message();
                    return;
                }
            }

            // Block Spinning OK - assign to item
            $item = $result;

            /**
             * Perform any other keyword replacements or string processing.
             *
             * @since   1.9.8
             *
             * @param   string  $item   Group Setting String (this can be Post Meta, Custom Fields, Permalink, Title, Content etc)
             * @param   string  $key    Group Setting Key
             */
            $item = apply_filters( 'page_generator_pro_generate_replace_keywords_in_array', $item, $key );
        }

    }

    /**
     * Determines if a Post already exists that was generated by the given Group ID for the
     * given Post Type, Parent and Slug
     *
     * @since   2.1.8
     *
     * @param   int     $group_id       Group ID (0 = any Group)
     * @param   string  $post_type      Generated Post Type
     * @param   int     $post_parent    Post Parent (0 = none)
     * @param   string  $post_name      Post Name
     * @return  mixed                   false | int
     */
    private function post_exists( $group_id, $post_type, $post_parent, $post_name ) {

        // Fetch valid Post Statuses that can be used when generating content
        $statuses = array_keys( $this->base->get_class( 'common' )->get_post_statuses() );

        // Build query arguments
        $args = array(
            'post_type'         => $post_type,
            'post_status'       => $statuses,
            'post_parent'       => $post_parent, 
            'post_name__in'     => array( $post_name ),
            
            // For performance, just return the Post ID and don't update meta or term caches
            'fields'                => 'ids',
            'cache_results'         => false,
            'update_post_meta_cache'=> false,
            'update_post_term_cache'=> false,
        );

        // If the Group ID isn't zero, add the Group clause to the query
        if ( $group_id > 0 ) {
            $args['meta_query'] = array(
                array(
                    'key'   => '_page_generator_pro_group',
                    'value' => absint( $group_id ),
                ),
            );
        }

        // Try to find existing post
        $existing_post = new WP_Query( $args );

        if ( count( $existing_post->posts ) == 0 ) {
            return false;
        }

        // Return existing Post's ID
        return $existing_post->posts[0];

    }

    /**
     * Determines if a Term already exists that was generated by the given Group ID for the
     * given Taxonomy, Parent and Title
     *
     * @since   2.4.8
     *
     * @param   int     $group_id       Group ID (0 = any Group)
     * @param   string  $taxonomy       Generated Term's Taxonomy
     * @param   int     $term_parent    Term Parent (0 = none)
     * @param   string  $term_name      Term Name
     * @return  mixed                   false | int
     */
    private function term_exists( $group_id, $taxonomy, $term_parent, $term_name ) {

        // Build query arguments
        $args = array(
            'taxonomy'      => array( $taxonomy ),
            'name'          => array( $term_name ),
            'hide_empty'    => false,
            
            // For performance, just return the Post ID and don't update meta or term caches
            'fields'                => 'ids',
            'update_term_meta_cache'=> false,
        );

        // If a Parent Term ID exists, restrict the above query to only check for existing Terms generated
        // that belong to the Parent
        if ( isset( $term_parent ) ) {
            $args['child_of'] = absint( $term_parent );
        }

        // If the Group ID isn't zero, add the Group clause to the query
        if ( $group_id > 0 ) {
            $args['meta_query'] = array(
                array(
                    'key'   => '_page_generator_pro_group',
                    'value' => absint( $group_id ),
                ),
            );
        }

        // Try to find existing term
        $existing_terms = new WP_Term_Query( $args );
        
        if ( is_null( $existing_terms->terms ) ) {
            return false;
        }
        if ( empty( $existing_terms->terms  ) ) {
            return false;
        }
        if ( count( $existing_terms->terms ) == 0 ) {
            return false;
        }

        // Return existing Term's ID
        return $existing_terms->terms[0];

    }

    /**
     * Sanitizes the given string to a slug, retaining forwardslashes.
     *
     * Special accented characters are converted to non-accented versions.
     *
     * @since   2.2.6
     *
     * @param   string  $slug   Slug to sanitize
     * @return  string          Sanitized Slug
     */
    private function sanitize_slug( $slug ) {
        
        // Split by forwardslash
        $slug_parts = explode( '/', $slug );

        // Sanitize each part
        foreach ( $slug_parts as $index => $slug ) {
            $slug_parts[ $index ] = sanitize_title( $slug );
        }

        // Convert array back to string
        $slug = implode( '/', $slug_parts );

        // Return
        return $slug;

    }

    /**
     * array_walk_recursive callback, which processes shortcodes.
     *
     * @since   1.9.7
     *
     * @param   mixed   $item   Item (array, object, string)
     * @param   string  $key    Key
     */
    private function process_shortcodes_in_array( &$item, $key ) {

        // If the settings key's value is an array, walk through it recursively to search/replace
        // Otherwise do a standard search/replace on the string
        if ( is_array( $item ) ) {
            // Array
            array_walk_recursive( $item, array( $this, 'process_shortcodes_in_array' ) );
        } elseif( is_object( $item ) ) {
            // Object
            array_walk_recursive( $item, array( $this, 'process_shortcodes_in_array' ) );
        } elseif ( is_string( $item ) ) {
            // If here, we have a string
            // Perform shortcode processing
            // Some Page Builders don't use the main Post Content for output, and instead use their own post meta to build the output
            // Therefore, processing shortcodes on the Post Content would duplicate effort
            switch ( $key ) {
                case 'content':
                    if ( $this->process_shortcodes_on_post_content ) {
                        $item = do_shortcode( $item );
                    }
                    break;

                default:
                    $item = do_shortcode( $item );
                    break;
            }

            /**
             * Filter to allow registering and processing shortcodes on a string.
             *
             * @since   1.9.8
             *
             * @param   string  $item   Group Setting String (this can be Post Meta, Custom Fields, Permalink, Title, Content etc)
             * @param   string  $key    Group Setting Key
             */
            $item = apply_filters( 'page_generator_pro_generate_process_shortcodes_in_array', $item, $key );
        }

    }

    /**
     * Assigns any Attachments to the given Post ID that have the specified Group ID and Index
     *
     * @since   2.4.1
     *
     * @param   int     $post_id    Generated Post ID
     * @param   int     $group_id   Group ID
     * @param   int     $index      Generation Index
     * @return  mixed               WP_Error | bool
     */
    private function assign_attachments_to_post_id( $post_id, $group_id, $index ) {

        // Build query
        $args = array(
            'post_type'         => 'attachment',
            'post_status'       => 'any',
            'posts_per_page'    => -1,
            'meta_query'        => array(
                array(
                    'key'   => '_page_generator_pro_group',
                    'value' => absint( $group_id ),
                ),
                array(
                    'key'   => '_page_generator_pro_index',
                    'value' => absint( $index ),
                ),
            ),
            'fields'            => 'ids',
        );

        // Get all Attachments belonging to the given Group ID and Index
        $attachments = new WP_Query( $args );

        // If no Attachments found, return false, as there's nothing to assign
        if ( count( $attachments->posts ) == 0 ) {
            return false;
        }

        // For each Attachment, assign it to the Post
        foreach ( $attachments->posts as $attachment_id ) {
            $result = wp_update_post( array(
                'ID'            => $attachment_id,
                'post_parent'   => $post_id,
            ), true );

            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }

        // Done
        return true;

    }

    /**
     * Defines the Featured Image for the given generated Post ID, if
     * the Group Settings specify a Featured Image and (if overwriting)
     * the Featured Image should be overwritten
     *
     * @since   2.3.5
     *
     * @param   int     $post_id    Generated Post ID
     * @param   int     $group_id   Group ID
     * @param   int     $index      Generation Index
     * @param   array   $settings   Group Settings
     * @param   array   $post_args  wp_insert_post() / wp_update_post() arguments
     * @return  mixed               WP_Error | Image ID
     */
    private function featured_image( $post_id, $group_id, $index, $settings, $post_args ) {

        // Bail if no Featured Image source defined
        if ( empty( $settings['featured_image_source'] ) ) {
            return false;
        }

        // Bail if we're overwriting an existing Post and don't want to overwrite the Featured Image
        if ( isset( $post_args['ID'] ) && ! array_key_exists( 'featured_image', $settings['overwrite_sections'] ) ) {
            return false;
        }

        switch ( $settings['featured_image_source'] ) {
            /**
             * Media Library ID
             */
            case 'id':
                // Build Featured Image Search Arguments
                $search_args = array(
                    'title'         => ( ! empty( $settings['featured_image_media_library_title'] ) ? $settings['featured_image_media_library_title'] : false ),
                    'caption'       => ( ! empty( $settings['featured_image_media_library_caption'] ) ? $settings['featured_image_media_library_caption'] : false ),
                    'alt'           => ( ! empty( $settings['featured_image_media_library_alt'] ) ? $settings['featured_image_media_library_alt'] : false ),
                    'description'   => ( ! empty( $settings['featured_image_media_library_description'] ) ? $settings['featured_image_media_library_description'] : false ),
                    'operator'      => ( ! empty( $settings['featured_image_media_library_operator'] ) ? $settings['featured_image_media_library_operator'] : 'AND' ),       
                    'ids'           => ( ! empty( $settings['featured_image_media_library_ids'] ) ? $settings['featured_image_media_library_ids'] : false ),
                    'min_id'        => ( ! empty( $settings['featured_image_media_library_min_id'] ) ? $settings['featured_image_media_library_min_id'] : false ),
                    'max_id'        => ( ! empty( $settings['featured_image_media_library_max_id'] ) ? $settings['featured_image_media_library_max_id'] : false ),
                );

                // Get Image ID
                $image_id = $this->base->get_class( 'media_library' )->get_random_image_id( $search_args );

                // Bail if no Image ID was defined
                if ( ! isset( $image_id ) ) {
                    return false;
                }
                if ( ! $image_id ) {
                    return false;
                }

                // Return the error if a WP_Error
                if ( is_wp_error( $image_id ) ) {
                    return $image_id;
                }

                // If we're copying the image to a new Media Library attachment, do this now
                if ( $settings['featured_image_copy'] ) {
                    // Get image
                    $image = wp_get_attachment_image_src( $image_id, 'full' );
                    if ( ! $image ) {
                        return new WP_Error( 'page_generator_pro_generate_featured_image', __( 'Featured Image: Could not get Image ID\'s source', 'page-generator-pro' ) );
                    }

                    // Copy to new image
                    $image_id = $this->base->get_class( 'import' )->import_remote_image( 
                        $image[0], 
                        $post_id,
                        $group_id,
                        $index,
                        $settings['featured_image_filename'],
                        $settings['featured_image_title'],
                        $settings['featured_image_caption'],
                        $settings['featured_image_alt'],
                        $settings['featured_image_description']
                    );
                }
                break;

            /**
             * Image URL
             */
            case 'url':
                // Bail if no Featured Image URL specified
                if ( empty( $settings['featured_image'] ) ) {
                    return new WP_Error( 'page_generator_pro_generate', __( 'No Featured Image URL was specified.', 'page-generator-pro' ) );
                }

                // Import Image into the Media Library
                $image_id = $this->base->get_class( 'import' )->import_remote_image( 
                    $settings['featured_image'],
                    $post_id,
                    $group_id,
                    $index,
                    $settings['featured_image_filename'],
                    $settings['featured_image_title'],
                    $settings['featured_image_caption'],
                    $settings['featured_image_alt'],
                    $settings['featured_image_description']
                );
                break;

            /**
             * Pexels
             */
            case 'pexels':
                // Bail if no Featured Image Term specified
                if ( empty( $settings['featured_image'] ) ) {
                    return new WP_Error( 'page_generator_pro_generate', __( 'No Featured Image Term was specified.', 'page-generator-pro' ) );
                }
                
                // If a Pexels API Key has been specified, use it instead of the class default.
                $api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-pexels', 'api_key' );
                if ( ! empty( $api_key ) ) {
                    $this->base->get_class( 'pexels' )->set_api_key( $api_key );
                }

                // Run images query
                $images = $this->base->get_class( 'pexels' )->photos_search( $settings['featured_image'], 'original', $settings['featured_image_orientation'] );

                // Bail if an error occured
                if ( is_wp_error( $images ) ) {
                    return $images;
                }

                // Pick an image at random from the resultset
                if ( count( $images ) == 1 ) {
                    $image_index = 0;
                } else {
                    $image_index = rand( 0, ( count( $images ) - 1 ) );
                }

                // Import Image into the Media Library
                $image_id = $this->base->get_class( 'import' )->import_remote_image( 
                    $images[ $image_index ]['url'],
                    $post_id,
                    $group_id,
                    $index,
                    $settings['featured_image_filename'],
                    $settings['featured_image_title'],
                    $settings['featured_image_caption'],
                    $settings['featured_image_alt'],
                    $settings['featured_image_description']
                );
                break;

            /**
             * Pixabay
             */
            case 'pixabay':
                // Bail if no Featured Image Term specified
                if ( empty( $settings['featured_image'] ) ) {
                    return new WP_Error( 'page_generator_pro_generate', __( 'No Featured Image Term was specified.', 'page-generator-pro' ) );
                }

                // If a Pexels API Key has been specified, use it instead of the class default.
                $api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-pixabay', 'api_key' );
                if ( ! empty( $api_key ) ) {
                    $this->base->get_class( 'pixabay' )->set_api_key( $api_key );
                }

                // Run images query
                $images = $this->base->get_class( 'pixabay' )->photos_search( 
                    $settings['featured_image'],
                    $settings['featured_image_pixabay_language'],
                    $settings['featured_image_pixabay_image_type'],
                    $settings['featured_image_orientation'],
                    $settings['featured_image_pixabay_image_category'],
                    0,
                    0,
                    $settings['featured_image_pixabay_image_color']
                );
                
                // Bail if an error occured
                if ( is_wp_error( $images ) ) {
                    return $images;
                }

                // Pick an image at random from the resultset
                if ( count( $images ) == 1 ) {
                    $image_index = 0;
                } else {
                    $image_index = rand( 0, ( count( $images ) - 1 ) );
                }

                // Import Image into the Media Library
                $image_id = $this->base->get_class( 'import' )->import_remote_image( 
                    $images[ $image_index ]['url'],
                    $post_id,
                    $group_id,
                    $index,
                    $settings['featured_image_filename'],
                    $settings['featured_image_title'],
                    $settings['featured_image_caption'],
                    $settings['featured_image_alt'],
                    $settings['featured_image_description']
                );
                break;

        }

        // Bail if no Image ID was defined
        if ( ! isset( $image_id ) ) {
            return false;
        }
        if ( ! $image_id ) {
            return false;
        }

        // Return the error if a WP_Error
        if ( is_wp_error( $image_id ) ) {
            return $image_id;
        }

        // Update Featured Image
        update_post_meta( $post_id, '_thumbnail_id', $image_id );

        // EXIF Data for Featured Image
        $exif = $this->base->get_class( 'exif' )->write(
            $image_id,
            $settings['featured_image_exif_description'],
            $settings['featured_image_exif_comments'],
            $settings['featured_image_exif_latitude'],
            $settings['featured_image_exif_longitude']
        );

        if ( is_wp_error( $exif ) ) {
            return $exif;
        }
       
        // Return Featured Image ID
        return $image_id;

    }

    /**
     * Copies Custom Fields to the Generated Post ID, if
     * the Group Settings specify Custom Field data and (if overwriting)
     * whether the Custom Fields data should be overwritten.
     *
     * @since   2.3.5
     *
     * @param   int     $post_id    Generated Post ID
     * @param   array   $settings   Group Settings
     * @param   array   $post_args  wp_insert_post() / wp_update_post() arguments
     * @return  bool                Updated Custom Fields on Generated Post ID
     */
    private function set_custom_fields( $post_id, $settings, $post_args ) {

        // Bail if we're overwriting an existing Post and don't want to overwrite the Custom Fields
        if ( isset( $post_args['ID'] ) && ! array_key_exists( 'custom_fields', $settings['overwrite_sections'] ) ) {
            return false;
        }

        // Custom Fields
        if ( isset( $settings['meta'] )  && ! empty( $settings['meta'] ) ) {
            foreach ( $settings['meta']['key'] as $meta_index => $meta_key ) {
                update_post_meta( $post_id, $meta_key, $settings['meta']['value'][ $meta_index ] );
            }
        }

        // Store Keywords
        if ( $settings['store_keywords'] ) {
            $store_keywords = $this->get_keywords_terms_array_with_columns();

            if ( $store_keywords ) {
                foreach ( $store_keywords as $meta_key => $meta_value ) {
                    update_post_meta( $post_id, $meta_key, $meta_value );
                }
            }
        }

        return true;

    }

    /**
     * Copies the Content Group's Post Meta to the Generated Post ID,
     * including Page Builder / ACF data.
     *
     * @since   2.3.5
     *
     * @param   int     $post_id    Generated Post ID
     * @param   array   $settings   Group Settings
     * @param   array   $post_args  wp_insert_post() / wp_update_post() arguments
     * @return  bool                Updated Post Meta on Generated Post ID
     */
    private function set_post_meta( $post_id, $settings, $post_args ) {

        // Determine if we want to create/replace Page Builder Post Meta
        $copy_content_post_meta = ( isset( $post_args['ID'] ) && ! array_key_exists( 'post_content', $settings['overwrite_sections'] ) ? false : true ); 

        // Bail if no Post Meta to copy to the generated Post
        if ( ! isset( $settings['post_meta'] ) ) {
            return false;
        }

        // Define the metadata to ignore
        $ignored_keys = array(
            '_wp_page_template',
        );

        /**
         * Defines Post Meta Keys in a Content Group to ignore and not copy to generated Posts / Groups.
         *
         * @since   2.6.1
         *
         * @param   array   $ignored_keys   Ignored Keys
         */
        $ignored_keys = apply_filters( 'page_generator_pro_generate_set_post_meta_ignored_keys', $ignored_keys );

        // Iterate through Post Meta
        foreach ( $settings['post_meta'] as $meta_key => $meta_value ) {

            // Skip ignored keys
            if ( in_array( $meta_key, $ignored_keys ) ) {
                continue;
            }

            // If the meta key is for a Page Builder and we're not overwriting content, skip it
            // @TODO Filter this so each Page Builder defines the key that stores its content.
            if ( ! $copy_content_post_meta ) {
                if ( in_array( $meta_key, array( '_elementor_data', '_cornerstone_data', '_themify_builder_settings_json' ) ) ) {
                    continue;
                }
            }

            /**
             * Filters the Group Metadata for the given Key and Value, immediately before it's
             * saved to the Generated Page.
             *
             * @since   2.6.1
             *
             * @param   mixed   $value  Meta Value
             * @return  mixed           Meta Value
             */
            $meta_value = apply_filters( 'page_generator_pro_generate_set_post_meta_' . $meta_key, $meta_value );

            // Update Generated Page's Meta Value
            update_post_meta( $post_id, $meta_key, $meta_value );

        }

        return true;
        
    }

    /**
     * Copies the Term Group's Term Meta to the Generated Term ID,
     * including Yoast / ACF data.
     *
     * @since   2.6.3
     *
     * @param   int     $term_id    Generated Term ID
     * @param   array   $settings   Group Settings
     * @param   array   $term_args  wp_insert_term() / wp_update_term() arguments
     * @return  bool                Updated Term Meta on Generated Term ID
     */
    private function set_term_meta( $term_id, $settings, $post_args ) {

        // Bail if no Term Meta to copy to the generated Term
        if ( ! isset( $settings['term_meta'] ) ) {
            return false;
        }

        // Define the metadata to ignore
        $ignored_keys = array();

        /**
         * Defines Term Meta Keys in a Term Group to ignore and not copy to generated Term
         *
         * @since   2.6.3
         *
         * @param   array   $ignored_keys   Ignored Keys
         */
        $ignored_keys = apply_filters( 'page_generator_pro_generate_set_term_meta_ignored_keys', $ignored_keys );

        // Iterate through Term Meta
        foreach ( $settings['term_meta'] as $meta_key => $meta_value ) {

            // Skip ignored keys
            if ( in_array( $meta_key, $ignored_keys ) ) {
                continue;
            }

            /**
             * Filters the Group Metadata for the given Key and Value, immediately before it's
             * saved to the Generated Term.
             *
             * @since   2.6.3
             *
             * @param   mixed   $value  Meta Value
             * @return  mixed           Meta Value
             */
            $meta_value = apply_filters( 'page_generator_pro_generate_set_term_meta_' . $meta_key, $meta_value );

            // Update Generated Term's Meta Value
            update_term_meta( $term_id, $meta_key, $meta_value );

        }

        return true;
        
    }

    /**
     * Assigns the Generated Post ID to a Menu, if defined in the Group Settings.
     *
     * If the Generated Post ID is already an item in the Menu, replaces it.
     *
     * @since   2.7.1
     *
     * @param   int     $post_id    Generated Post ID
     * @param   array   $settings   Group Settings
     * @param   array   $post_args  wp_insert_post() / wp_update_post() arguments
     * @return  bool                Menu Item ID
     */
    private function set_menu( $post_id, $settings, $post_args ) {

        // Bail if no Menu is specified
        if ( ! isset( $settings['menu'] ) || ! $settings['menu'] ) {
            return;
        }

        // Build menu arguments
        $args = array(
            'menu-item-object-id'   => $post_id,
            'menu-item-object'      => $settings['type'],
            'menu-item-type'        => 'post_type',
            'menu-item-status'      => 'publish',
        );

        // If a title exists, use it instead
        if ( isset( $settings['menu_title'] ) && ! empty( $settings['menu_title'] ) ) {
            $args['menu-item-title'] = $settings['menu_title'];
        }

        // Update (or create) Menu Item
        return wp_update_nav_menu_item(
            $settings['menu'],
            $this->get_menu_item( $post_id, $settings['menu'] ),
            $args
        );

    }

    /**
     * Gets the given Generated Post ID's Menu Item ID if it exists in the given Menu
     *
     * @since   2.7.1
     *
     * @param   int     $post_id    Generated Post ID
     * @param   int     $menu_id    Menu ID
     * @return  int                 Existing Menu Item ID | false
     */
    private function get_menu_item( $post_id, $menu_id ) {

        $menu_items = wp_get_nav_menu_items( $menu_id );
        if ( empty( $menu_items ) ) {
            return false;
        }

        foreach ( $menu_items as $menu_item ) {
            if ( $menu_item->object_id == $post_id ) {
                return $menu_item->db_id;
            }
        }

        // If here, this Post doesn't exist in the Menu
        return false;

    }

    /**
     * Stores the Latitude and Longitude in the Geo table against the generated Post ID and Group ID
     * if the Latitude and Longitude exist and are valid values
     *
     * @since   2.3.6
     *
     * @param   int     $post_id    Generated Post ID
     * @param   int     $group_id   Group ID
     * @param   array   $settings   Group Settings
     * @return  mixed               WP_Error | bool
     */
    private function latitude_longitude( $post_id, $group_id, $settings ) {

        // Bail if we don't have a latitude or longitude
        if ( empty( $settings['latitude'] ) || empty( $settings['longitude'] ) ) {
            return false;
        }
        if ( ! $this->base->get_class( 'geo' )->is_latitude( $settings['latitude'] ) ) {
            return false;
        }
        if ( ! $this->base->get_class( 'geo' )->is_longitude( $settings['longitude'] ) ) {
            return false;
        }

        // Insert / Update Latitude and Longitude against this Post ID and Group ID
        return $this->base->get_class( 'geo' )->update(
            $post_id,
            $group_id,
            $settings['latitude'],
            $settings['longitude']
        );

    }

    /**
     * Returns an array of Taxonomies and Terms to assign to the Post
     *
     * @since   1.9.5
     *
     * @param   array   $taxonomy_terms     Taxonomies and Terms to assign to the Post
     * @param   string  $post_type          Post Type
     * @param   int     $post_id            Post ID
     * @return  mixed                       WP_Error | bool
     */
    private function assign_taxonomy_terms_to_post( $taxonomy_terms, $post_type, $post_id ) {

        // Get Post Type Taxonomies
        $taxonomies = $this->base->get_class( 'common' )->get_post_type_taxonomies( $post_type );

        // Bail if no Taxonomies exist
        if ( count( $taxonomies ) == 0 ) {
            return true;
        }

        // Iterate through Taxonomies
        foreach ( $taxonomies as $taxonomy ) {
            // Cleanup from last iteration
            unset( $terms );

            // Bail if no Terms exist for this Taxonomy
            if ( ! isset( $taxonomy_terms[ $taxonomy->name ] ) ) {
                continue;
            }
            if ( empty( $taxonomy_terms[ $taxonomy->name ] ) ) {
                continue;
            }
            if ( is_array( $taxonomy_terms[ $taxonomy->name ] ) && count( $taxonomy_terms[ $taxonomy->name ] ) == 0 ) {
                continue;
            }

            // Build Terms, depending on whether the Taxonomy is hierarchical or not
            switch ( $taxonomy->hierarchical ) { 
                case true:
                    foreach ( $taxonomy_terms[ $taxonomy->name ] as $tax_id => $terms_string ) {
                        // If Tax ID is not zero, the Term already exists in the Taxonomy
                        // Just add it to the Terms array
                        if ( $tax_id != 0 ) {
                            $terms[] = (int) $tax_id;
                            continue;
                        }

                        // Convert string to array
                        $terms_arr = str_getcsv( $terms_string, ',' );
                        foreach ( $terms_arr as $new_term ) {
                            // Check if this named term already exists in the taxonomy
                            $result = term_exists( $new_term, $taxonomy->name );
                            if ( $result !== 0 && $result !== null ) {
                                $terms[] = (int) $result['term_id'];
                                continue;
                            }

                            // Skip if the term is empty
                            if ( empty( $new_term ) ) {
                                continue; 
                            }

                            // Term does not exist in the taxonomy - create it
                            $result = wp_insert_term( $new_term, $taxonomy->name );
                            
                            // Skip if something went wrong
                            if ( is_wp_error( $result ) ) {
                                return $result;
                            }
                            
                            // Add to term IDs
                            $terms[] = (int) $result['term_id'];
                        }
                    }
                    break;

                case false:
                default:
                    $terms = $taxonomy_terms[ $taxonomy->name ];
                    break;
            }

            // If terms are not set or empty for this Taxonomy, continue
            if ( ! isset( $terms ) || empty( $terms ) ) {
                continue;
            }

            // Assign Terms to Post
            $result = wp_set_post_terms( $post_id, $terms, $taxonomy->name, false );

            // Bail if an error occured
            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }

        // Terms assigned to Post successfully
        return true;

    }

    /**
     * Checks the given Group settings to see whether we need to process shortcodes on
     * the main Post Content.
     *
     * @since   1.9.5
     * 
     * @param   array   $settings   Group Settings
     * @return  bool                Process Shortcodes
     */
    private function should_process_shortcodes_on_post_content( $settings ) {

        // Assume that we will process shortcodes on the Post Content
        $process = true;

        /**
         * Flag whether the given Group should process shortcodes on the main Post Content
         * (i.e. $post->post_content).
         *
         * @since   2.6.1
         *
         * @param   bool    $process    Process Shortcodes on Post Content
         * @param   array   $settings   Group Settings
         * @return  bool                Process Shortcodes on Post Content
         */
        $process = apply_filters( 'page_generator_pro_generate_should_process_shortcodes_on_post_content', $process, $settings );
        
        // Return result
        return $process;

    }

    /**
     * Runs any actions once content generation has completed.
     *
     * @since   1.9.3
     *
     * @param   int     $group_id   Group ID
     * @param   bool    $test_mode  Test Mode
     */
    public function generate_content_finished( $group_id, $test_mode ) {

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // Elementor: Clear Cache
        if ( is_plugin_active( 'elementor/elementor.php' ) ) {
            Elementor\Plugin::$instance->files_manager->clear_cache();
        }

    }

    /**
     * Runs any actions once content generation has completed.
     *
     * @since   1.9.3
     *
     * @param   int     $group_id   Group ID
     * @param   bool    $test_mode  Test Mode
     */
    public function generate_terms_finished( $group_id, $test_mode ) {


    }

    /**
     * Main function to trash previously generated Contents
     * for the given Group ID
     *
     * @since   1.2.3
     *
     * @param   int     $group_id   Group ID
     * @return  mixed               WP_Error | Success
     */
    public function trash_content( $group_id ) {

        // Get all Post IDs generated by this Group
        $post_ids = $this->get_generated_content_post_ids( $group_id );

        // Bail if an error occured
        if ( is_wp_error( $post_ids ) ) {
            return $post_ids;
        }

        // Delete Posts by their IDs
        foreach ( $post_ids as $post_id ) {
            $result = wp_trash_post( $post_id );
            if ( ! $result ) {
                return new WP_Error( 'page_generator_pro_generate_trash_content', __( 'Unable to trash generated content with ID = ' . $post_id, 'page-generator-pro' ) );
            }
        }

        // Done
        return true;

    }

    /**
     * Main function to delete previously generated Contents
     * for the given Group ID
     *
     * @since   1.2.3
     *
     * @param   int     $group_id   Group ID
     * @return  mixed               WP_Error | Success
     */
    public function delete_content( $group_id ) {

        // Get all Post IDs generated by this Group
        $post_ids = $this->get_generated_content_post_ids( $group_id );

        // Bail if an error occured
        if ( is_wp_error( $post_ids ) ) {
            return $post_ids;
        }

        // Delete Attachments
        $this->delete_attachments_by_post_ids( $post_ids, $group_id );

        // Delete Posts
        foreach ( $post_ids as $post_id ) {
            $result = wp_delete_post( $post_id, true );
            if ( ! $result ) {
                return new WP_Error( 'page_generator_pro_generate_delete_content', __( 'Unable to delete generated content with ID = ' . $post_id, 'page-generator-pro' ) );
            }
        }

        return true;

    }

    /**
     * Deletes latitude and longitude from the Geo table when a Post is deleted.
     * 
     * Trashed Posts are unaffected.
     *
     * @since   2.3.6
     *
     * @param   int     $post_id    Post ID
     */
    public function delete_latitude_longitude_by_post_id( $post_id ) {

        $this->base->get_class( 'geo' )->delete( $post_id );

    }

    /**
     * Returns all Post IDs generated by the given Group ID
     *
     * @since   1.9.1
     *
     * @param   int     $group_id   Group ID
     * @return  mixed               WP_Error | array
     */
    private function get_generated_content_post_ids( $group_id ) {

        // Fetch valid Post Statuses that can be used when generating content
        $statuses = array_keys( $this->base->get_class( 'common' )->get_post_statuses() );

        // Get all Posts
        $posts = new WP_Query( array (
            'post_type'     => 'any',
            'post_status'   => $statuses,
            'posts_per_page'=> -1,
            'meta_query'    => array(
                array(
                    'key'   => '_page_generator_pro_group',
                    'value' => absint( $group_id ),
                ),
            ),
            'fields'        => 'ids',
        ) );

        // If no Posts found, return an error
        if ( count( $posts->posts ) == 0 ) {
            return new WP_Error( 'page_generator_pro_generate_get_generated_content_post_ids', __( 'No content has been generated by this group.', 'page-generator-pro' ) );
        }

        // Return Post IDs
        return $posts->posts;

    }

    /**
     * Deletes Attachments assigned to the given Post ID and Group ID
     *
     * @since   2.4.1
     *
     * @param   array   $post_ids   Post IDs
     * @param   int     $group_id   Group ID
     * @param   int     $index      Generation Index
     * @return  mixed               WP_Error | bool
     */
    private function delete_attachments_by_post_ids( $post_ids, $group_id ) {

        // Build query
        $args = array(
            'post_type'         => 'attachment',
            'post_status'       => 'any',
            'posts_per_page'    => -1,
            'post_parent__in'   => $post_ids,
            'meta_query'        => array(
                array(
                    'key'   => '_page_generator_pro_group',
                    'value' => absint( $group_id ),
                ),
            ),
            'fields'            => 'ids',
        );

        // Get all Attachments belonging to the given Post IDs
        $attachments = new WP_Query( $args );

        // If no Attachments found, return false, as there's nothing to delete
        if ( count( $attachments->posts ) == 0 ) {
            return false;
        }

        // Delete attachments
        foreach ( $attachments->posts as $attachment_id ) {
            wp_delete_attachment( $attachment_id );
        }

        return true;
        
    }

    /**
     * Main function to delete previously generated Terms
     * for the given Group ID
     *
     * @since   1.6.1
     *
     * @param   int     $group_id   Group ID
     * @return  mixed               WP_Error | Success
     */
    public function delete_terms( $group_id ) {

        // Get Settings
        $settings = $this->base->get_class( 'groups_terms' )->get_settings( $group_id );

        // Get all Terms
        $terms = new WP_Term_Query( array(
            'taxonomy'      => $settings['taxonomy'],
            'meta_query'    => array(
                array(
                    'key'   => '_page_generator_pro_group',
                    'value' => absint( $group_id ),
                ),
            ),
            'hide_empty'    => false,
            
            // For performance, just return the Post ID and don't update meta or term caches
            'fields'                => 'ids',
            'update_term_meta_cache'=> false,
        ) );

        // If no Terms found, return false, as there's nothing to delete
        if ( count( $terms->terms ) == 0 ) {
            return new WP_Error( 'page_generator_pro_generate_delete_terms', __( 'No Terms have been generated by this group, so there are no Terms to delete.', 'page-generator-pro' ) );
        }

        // Delete Terms by their IDs
        foreach ( $terms->terms as $term_id ) {
            $result = wp_delete_term( $term_id, $settings['taxonomy'] );
            if ( ! $result ) {
                return new WP_Error( 'page_generator_pro_generate_delete_terms', __( 'Unable to delete generated Term with ID = ' . $term_id, 'page-generator-pro' ) );
            }
        }

        // Done
        return true;

    }

    /**
     * Removes wp_update_post() $post_args that are not selected for overwriting
     *
     * @since   2.3.5
     *
     * @param   array   $overwrite_sections     Sections to Overwrite
     * @param   array   $post_args              wp_update_post() compatible Post Arguments
     * @return  array                           wp_update_post() compatible Post Arguments
     */
    private function restrict_post_args_by_overwrite_sections( $overwrite_sections, $post_args ) {

        // Fetch all available overwrite sections
        $all_possible_overwrite_sections = array_keys( $this->base->get_class( 'common' )->get_content_overwrite_sections() );
        $overwrite_sections_to_ignore = array_diff( $all_possible_overwrite_sections, $overwrite_sections );

        // If all overwrite sections are selected (i.e. no overwrite sections to ignore / skip), just return the post args
        if ( empty( $overwrite_sections_to_ignore ) ) {
            return $post_args;
        }

        // For each overwrite section to ignore / skip, remove it from the Post Args
        foreach ( $overwrite_sections_to_ignore as $overwrite_section_to_ignore ) {
            unset( $post_args[ $overwrite_section_to_ignore ] );
        }

        return $post_args;

    }

    /**
     * Returns an array of data relating to the successfully generated Post or Term,
     * logging the result if logging is enabled
     *
     * @since   2.1.8
     *
     * @param   int     $group_id               Group ID
     * @param   int     $post_or_term_id        Post or Term ID
     * @param   string  $post_type_or_taxonomy  Post Type or Taxonomy
     * @param   bool    $generated              Post Generated (false = skipped)
     * @param   string  $message                Message to return (created, updated, skipped etc.)
     * @param   int     $start                  Start Time
     * @param   bool    $test_mode              Test Mode
     * @param   string  $system                 System (browser|cron|cli)
     * @return                                  Success Data
     */
    private function generate_return( $group_id, $post_or_term_id, $post_type_or_taxonomy, $generated, $message, $start, $test_mode, $system ) {

        // Determine if we're returning data for a generated Post or Term
        // We check if it's a Taxonomy first as post_type_exists() fails for e.g. WooCommerce Products
        if ( taxonomy_exists( $post_type_or_taxonomy ) ) {
            // Term
            $url = get_term_link( $post_or_term_id, $post_type_or_taxonomy );
        } else {
            // Post, Page or CPT
            if ( $test_mode ) {
                $url = get_bloginfo( 'url' ) . '?page_id=' . $post_or_term_id . '&preview=true';
            } else {
                $url = get_permalink( $post_or_term_id );
            }
        }

        // Performance debugging
        $end = microtime( true );

        // Build result array
        $result = array(
            // Item
            'post_id'           => $post_or_term_id,
            'url'               => $url,
            'type'              => ( taxonomy_exists( $post_type_or_taxonomy ) ? 'term' : 'content' ),
            'system'            => $system,
            'test_mode'         => $test_mode,
            'generated'         => $generated,
            'keywords_terms'    => $this->keywords_terms,
            'message'           => $message,

            // Performance data
            'start'             => $start,
            'end'               => $end,
            'duration'          => round ( ( $end - $start ), 2 ),
            'memory_usage'      => round( memory_get_usage() / 1024 / 1024 ),
            'memory_peak_usage' => round( memory_get_peak_usage() / 1024 / 1024 ),
        );

        // Maybe add to log
        if ( $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-generate', 'log_enabled', '0' ) ) {
            $this->base->get_class( 'log' )->add( $group_id, $result );
        }

        // Return
        return $result;

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since       1.1.6
     * @deprecated  1.9.8
     *
     * @return      object Class.
     */
    public static function get_instance() {

        // Define class name
        $name = 'generate';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}