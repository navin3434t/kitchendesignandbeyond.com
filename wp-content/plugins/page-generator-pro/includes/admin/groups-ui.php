<?php
/**
 * Handles the Groups Post Type UI
 * 
 * @package  Page_Generator_Pro
 * @author   Tim Carr
 * @version  2.0.2
 */
class Page_Generator_Pro_Groups_UI {

    /**
     * Holds the base class object.
     *
     * @since   2.0.2
     *
     * @var     object
     */
    public $base;

    /**
     * Holds keywords for the Group we're editing
     *
     * @since   2.0.2
     *
     * @var     mixed (bool|array)
     */
    public $keywords = false;

    /**
     * Holds settings for the Group we're editing
     *
     * @since   2.0.2
     *
     * @var     array
     */
    public $settings = array();

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

        // Add filter by Group to Pages, Posts and Custom Post Types
        add_action( 'restrict_manage_posts', array( $this, 'output_posts_filter_by_group_dropdown' ), 10, 2 );
        add_filter( 'parse_query', array( $this, 'posts_filter_by_group' ) );

        // Search
        add_filter( 'posts_join', array( $this, 'search_settings_join' ), 999 );
        add_filter( 'posts_where', array( $this, 'search_settings_where' ), 999 );
        
        // Modify Post Messages
        add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

        // Don't allow Group Editing if a Group is Generating Content
        add_filter( 'user_has_cap', array( $this, 'maybe_prevent_group_edit' ), 10, 3 );

        // Before Title
        add_action( 'edit_form_top', array( $this, 'output_keywords_dropdown_before_title' ) );

        // Meta Boxes
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

        // Save Group
        add_action( 'save_post', array( $this, 'save_post' ) );

        // Deprecated for Yoast SEO 10.0+, which simply inherits the Permalink of the Post
        add_filter( 'wpseo_sanitize_post_meta__yoast_wpseo_canonical', array( $this, 'wpseo_sanitize_post_meta__yoast_wpseo_canonical' ), 10, 4 );
      
        // Page Generator
        if ( class_exists( 'Page_Generator' ) ) {
            add_action( 'init', array( $this, 'limit_admin' ) );
            add_filter( 'wp_insert_post_empty_content', array( $this, 'limit_xml_rpc' ), 10, 2 );
        }

    }

    /**
     * Outputs the Filter by Group Dropdown on Pages, Posts and Custom Post Types
     *
     * @since   2.2.6
     *
     * @param   string  $post_type  Post Type
     * @param   string  $where      Location of Filter (top, bottom, bar)
     */
    public function output_posts_filter_by_group_dropdown( $post_type, $where ) {

        // Bail if the Post Type is a Content Group
        if ( $post_type == $this->base->get_class( 'post_type' )->post_type_name ) {
            return;
        }

        // Get all Groups
        $groups = $this->base->get_class( 'groups' )->get_all_ids_names();

        // Bail if no Groups exist
        if ( ! $groups ) {
            return;
        }

        // Get currently selected Group, if any
        $current_group_id = ( isset( $_REQUEST['page_generator_pro_group_id'] ) ? absint( $_REQUEST['page_generator_pro_group_id'] ) : '' );

        // Load view
        include( $this->base->plugin->folder . 'views/admin/wp-list-table-filter-groups-dropdown.php' ); 

    }

    /**
     * Adds WHERE clause(s) to the WP_Query when the User has requested to filter Pages,
     * Posts or Custom Post Types by a Group ID
     *
     * @since   2.2.6
     *
     * @param   WP_Query    $query  WordPress Query object
     * @return  WP_Query            Modified WordPress Query object
     */
    public function posts_filter_by_group( $query ) {

        // Bail if the filter isn't active
        if ( ! isset( $_REQUEST['page_generator_pro_group_id'] ) ) {
            return $query;
        }
        if ( empty( $_REQUEST['page_generator_pro_group_id'] ) ) {
            return $query;
        }

        // Bail if the filter isn't for the Post Type that we're viewing
        if ( sanitize_text_field( $_REQUEST['post_type'] ) != $query->query_vars['post_type'] ) {
            return $query;
        }

        // Get Group ID
        $group_id = absint( $_REQUEST['page_generator_pro_group_id'] );

        // If no meta query var is defined, define a blank array now
        if ( ! isset( $query->query_vars['meta_query'] ) ) {
            $query->query_vars['meta_query'] = array();
        }

        // Add meta query
        $query->query_vars['meta_query'][] = array(
            'key'   => '_page_generator_pro_group',
            'value' => (string) $group_id,
        );

        // Return query
        return $query;

    }

    /**
     * Adds a join to the WordPress meta table for Content Group searches in the WordPress Administration
     *
     * @since   2.3.5
     *
     * @param   string  $join   SQL JOIN Statement
     * @return  string          SQL JOIN Statement
     */
    public function search_settings_join( $join ) {

        global $wpdb, $wp_query;

        // Bail if no search term specified
        if ( empty( $wp_query->query_vars['s'] ) ) {
            return $join;
        }

        // Bail if we're not searching Content Groups
        if ( $wp_query->query_vars['post_type'] != $this->base->get_class( 'post_type' )->post_type_name ) {
            return $join;
        }

        // Append JOIN and return
        $join .= " LEFT JOIN $wpdb->postmeta AS pgp_postmeta ON $wpdb->posts.ID = pgp_postmeta.post_id ";
        return $join;

    }
    
    /**
     * Adds a where clause to the WordPress meta table for Content Group searches in the WordPress Administration
     *
     * @since   2.3.5
     *
     * @param   string  $where      SQL WHERE
     * @param   return              SQL WHERE
     */
    public function search_settings_where( $where ) {

        global $wpdb, $wp_query;

        // Bail if no search term specified
        if ( empty( $wp_query->query_vars['s'] ) ) {
            return $where;
        }

        // Bail if we're not searching Content Groups
        if ( $wp_query->query_vars['post_type'] != $this->base->get_class( 'post_type' )->post_type_name ) {
            return $where;
        }

        // Build WHERE conditions
        $where_conditions = array(
            "(pgp_postmeta.meta_key = '_page_generator_pro_settings' AND pgp_postmeta.meta_value LIKE '%" . $wp_query->query_vars['s'] . "%')",
        );

        // Find WHERE search clause(s)
        $start = strpos( $where, 'AND (((' );
        $end = strpos( $where, ')))', $start );

        // Bail if we couldn't find the WHERE search clause(s)
        if ( $start === false || $end === false ) {
            return $where;
        }

        // Append just after wp_posts.post_content LIKE ...
        $where = str_replace( '))', ' OR ' . implode( ' OR ', $where_conditions ) . '))', $where );

        // Group
        $where .= ' GROUP BY ' . $wpdb->posts . '.id';

        // Return
        return $where;

    }

    /**
     * Defines admin notices for the Post Type.
     *
     * This also removes the 'View post' link on the message, which would result
     * in an error on the frontend.
     *
     * @since   2.0.2
     *
     * @param   array   $messages   Messages
     * @return  array               Messages
     */
    public function post_updated_messages( $messages ) {

        $messages[ $this->base->get_class( 'post_type' )->post_type_name ] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __( 'Group updated.', 'page-generator-pro' ),
            2 => __( 'Custom field updated.', 'page-generator-pro'  ),
            3 => __( 'Custom field deleted.', 'page-generator-pro'  ),
            4 => __( 'Group updated.', 'page-generator-pro'  ),
            5 => ( isset( $_GET['revision'] ) ? sprintf( __( 'Group restored to revision from %s.', 'page-generator-pro'  ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false ),
            6 => __( 'Group saved.', 'page-generator-pro'  ),
            7 => __( 'Group saved.', 'page-generator-pro'  ),
            8 => __( 'Group submitted.', 'page-generator-pro'  ),
            9 => __( 'Group scheduled.', 'page-generator-pro'  ),
            10 => __( 'Group draft updated.', 'page-generator-pro'  ),
        );

        return $messages;

    }

    /**
     * Prevent loading of editing a Group if that Group is Generating Content.
     *
     * Sets the capability to false when current_user_can() has been called on
     * one of the capabilities we're interested in on a locked or protected post.
     *
     * @since   2.0.2
     *
     * @param   array $allcaps  All capabilities of the user.
     * @param   array $cap      [0] Required capability.
     * @param   array $args     [0] Requested capability.
     *                          [1] User ID.
     *                          [2] Post ID.
     */
    public function maybe_prevent_group_edit( $all_caps, $cap, $args ) {

        // Let the request through if it doesn't contain the required arguments
        if ( ! isset( $args[2] ) ) {
            return $all_caps;
        }

        // Fetch the Capability the User requires, and the Group ID
        $capability = $args[0];
        $group_id = $args[2];

        // If the capability the User requires isn't one that we need to modify, let the request through
        $capabilities_to_disable = $this->base->get_class( 'common' )->get_capabilities_to_disable_on_group_content_generation();
        if ( ! in_array( $capability, $capabilities_to_disable ) ) {
            return $all_caps;
        }

        // If the Group ID doesn't correspond to a Group (i.e. it's a capability for a different Post or Term), let the request through
        if ( get_post_type( $group_id ) != $this->base->get_class( 'post_type' )->post_type_name ) {
            return $all_caps;
        }

        // If the Group isn't generating content, let the request through
        if ( $this->base->get_class( 'groups' )->is_idle( $group_id ) ) {
            return $all_caps;
        }

        // If here, the Group is generating content, and the capability requested needs to be temporarily disabled
        $all_caps[ $cap[0] ] = false;

        // Return
        return $all_caps;

    }

    /**
     * Outputs the Keywords Dropdown before the Title field
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_keywords_dropdown_before_title( $post ) {

        // Don't do anything if we're not on this Plugin's CPT
        if ( get_post_type( $post ) !== $this->base->get_class( 'post_type' )->post_type_name ) {
            return;
        }

        // Get all available keywords
        if ( ! $this->keywords ) {
            $this->keywords = $this->base->get_class( 'keywords' )->get_keywords_and_columns();
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-title-keywords.php' ); 

    }

    /**
     * Registers meta boxes for the Generate Custom Post Type
     *
     * @since   2.0.2
     */
    public function add_meta_boxes() {

        // Remove some metaboxes that we don't need, to improve the UI
        $this->remove_meta_boxes();

        // Determine whether we're using the Gutenberg Editor
        // The use of $current_screen is in cases where is_gutenberg_page() sometimes wrongly returns false
        global $current_screen;
        $is_gutenberg_page = ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ? true : false );
        if ( ! $is_gutenberg_page && method_exists( $current_screen, 'is_block_editor' ) ) {
            $is_gutenberg_page = $current_screen->is_block_editor();
        }

        // Description
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-description', 
            __( 'Description', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_description' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal',
            'high'
        );
        
        // Permalink
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-permalink', 
            __( 'Permalink', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_permalink' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal' 
        );
        
        // Excerpt
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-excerpt', 
            __( 'Excerpt', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_excerpt' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal'  
        );

        // Featured Image
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-featured-image', 
            __( 'Featured Image', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_featured_image' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal'
        );

        // Geo
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-geo', 
            __( 'Geolocation Data', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_geo' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal'  
        );

        // Custom Fields
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-custom-fields', 
            __( 'Custom Fields', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_custom_fields' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal'  
        );

        // Author
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-author', 
            __( 'Author', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_author' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal'  
        );

        // Discussion
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-discussion', 
            __( 'Discussion', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_discussion' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'normal'  
        );

        // Upgrade
        if ( class_exists( 'Page_Generator' ) ) {
            add_meta_box( 
                $this->base->get_class( 'post_type' )->post_type_name . '-upgrade', 
                __( 'Upgrade', 'page-generator-pro' ), 
                array( $this, 'output_meta_box_upgrade' ), 
                $this->base->get_class( 'post_type' )->post_type_name,
                'normal'  
            );
        }

        /**
         * Sidebar
         */

        // Page Builders
        do_action( 'page_generator_pro_groups_ui_add_meta_boxes', $this->base->get_class( 'post_type' ) );
        
        // Actions Top
        if ( ! $is_gutenberg_page ) {
            add_meta_box( 
                $this->base->get_class( 'post_type' )->post_type_name . '-actions', 
                __( 'Actions', 'page-generator-pro' ), 
                array( $this, 'output_meta_box_actions_top' ), 
                $this->base->get_class( 'post_type' )->post_type_name,
                'side',
                'high'
            );
        }

        // Publish
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-publish', 
            __( 'Publish', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_publish' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'side'
        );

        // Generation
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-generation', 
            __( 'Generation', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_generation' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'side'
        );

        // Menu
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-menu', 
            __( 'Menu', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_menu' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'side'
        );

        // Attributes
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-attributes', 
            __( 'Attributes', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_attributes' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'side'
        );

        // Taxonomies
        add_meta_box( 
            $this->base->get_class( 'post_type' )->post_type_name . '-taxonomies', 
            __( 'Taxonomies', 'page-generator-pro' ), 
            array( $this, 'output_meta_box_taxonomies' ), 
            $this->base->get_class( 'post_type' )->post_type_name,
            'side'
        );

        // Actions Bottom
        if ( ! $is_gutenberg_page ) {
            add_meta_box( 
                $this->base->get_class( 'post_type' )->post_type_name . '-actions-bottom', 
                __( 'Actions', 'page-generator-pro' ), 
                array( $this, 'output_meta_box_actions_bottom' ), 
                $this->base->get_class( 'post_type' )->post_type_name,
                'side',
                'low'
            );
        } else {
            add_meta_box( 
                $this->base->get_class( 'post_type' )->post_type_name . '-actions-gutenberg-bottom', 
                __( 'Actions', 'page-generator-pro' ), 
                array( $this, 'output_meta_box_actions_gutenberg' ),
                $this->base->get_class( 'post_type' )->post_type_name,
                'side'
            );
        }

    }

    /**
     * Removes some metaboxes on the Groups Custom Post Type UI
     *
     * @since   2.1.1
     *
     * @global  array   $wp_meta_boxes  Array of registered metaboxes.
     */
    public function remove_meta_boxes() {

        global $wp_meta_boxes;

        // Bail if no meta boxes for this CPT exist
        if ( ! isset( $wp_meta_boxes['page-generator-pro'] ) ) {
            return;
        }

        // Define the metaboxes to remove
        $remove_meta_boxes = array(
            // Main
            'slugdiv',

            // Sidebar
            'submitdiv',
            'tagsdiv-page-generator-tax',

            // Divi
            'pageparentdiv',
            'postcustom',
        );

        /**
         * Filters the metaboxes to remove from the Content Groups Screen.
         *
         * @since   2.1.1
         *
         * @param   array   $remove_meta_boxes   Meta Boxes to Remove
         */
        $remove_meta_boxes = apply_filters( 'page_generator_pro_groups_ui_remove_meta_boxes', $remove_meta_boxes );

        // Bail if no meta boxes are defined for removal
        if ( ! is_array( $remove_meta_boxes ) ) {
            return;
        }
        if ( count( $remove_meta_boxes ) == 0 ) {
            return;
        }

        // Iterate through all registered meta boxes, removing those that aren't permitted
        foreach ( $wp_meta_boxes['page-generator-pro'] as $position => $contexts ) {
            foreach ( $contexts as $context => $meta_boxes ) {
                foreach ( $meta_boxes as $meta_box_id => $meta_box ) {
                    // If this meta box is in the array of meta boxes to remove, remove it now
                    if ( in_array( $meta_box_id, $remove_meta_boxes ) ) {
                        unset( $wp_meta_boxes['page-generator-pro'][ $position ][ $context ][ $meta_box_id ] );
                    }
                }
            }
        }

    }

    /**
     * Outputs the Description Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_description( $post ) {
      
        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-description.php' ); 

    }

    /**
     * Outputs the Permalink Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_permalink( $post ) {
      
        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get all available keywords, post types, taxonomies, authors and other settings that we might use on the admin screen
        if ( ! $this->keywords ) {
            $this->keywords = $this->base->get_class( 'keywords' )->get_keywords_and_columns();
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-permalink.php' ); 

    }

    /**
     * Outputs the Excerpt Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_excerpt( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get options
        $excerpt_post_types = $this->base->get_class( 'common' )->get_excerpt_post_types();

        // Build string of hierarchal post types to use as a selector class
        $excerpt_post_type_class = '';
        if ( is_array( $excerpt_post_types ) && count( $excerpt_post_types ) > 0 ) {
            foreach ( $excerpt_post_types as $type => $post_type ) {
                $excerpt_post_type_class .= $type . ' ';
            }
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-excerpt.php' ); 

    }

    /**
     * Outputs the Geolocation Data Meta Box
     *
     * @since   2.3.6
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_geo( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-geo.php' ); 

    }

    /**
     * Outputs the Custom Fields Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_custom_fields( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-custom-fields.php' ); 

    }

    /**
     * Outputs the Author Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_author( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // If an author is selected, fetch their details now for the select dropdown
        if ( ! empty( $this->settings['author'] ) ) {
            $author = get_user_by( 'ID', $this->settings['author'] );
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-author.php' ); 

    }

    /**
     * Outputs the Discussion Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_discussion( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-discussion.php' ); 

    }

    /**
     * Outputs the Upgrade Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_upgrade( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Load view
        include( $this->base->plugin->folder . '/_modules/dashboard/views/footer-upgrade-embedded.php' );

    }

    /**
     * Outputs the Actions Sidebar Top Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_actions_top( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Append to element IDs
        $bottom = '';

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-actions.php' ); 

    }

    /**
     * Outputs the Actions Sidebar Bottom Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_actions_bottom( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Append to element IDs
        $bottom = 'bottom';

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-actions.php' ); 

    }

    /**
     * Outputs the Actions Sidebar Meta Box for Gutenberg
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_actions_gutenberg( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-actions-gutenberg.php' ); 

    }

    /**
     * Outputs the Publish Sidebar Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_publish( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get options
        $post_types             = $this->base->get_class( 'common' )->get_post_types();
        $statuses               = $this->base->get_class( 'common' )->get_post_statuses();
        $date_options           = $this->base->get_class( 'common' )->get_date_options();
        $schedule_units         = $this->base->get_class( 'common' )->get_schedule_units();

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-publish.php' ); 

    }

    /**
     * Outputs the Generation Sidebar Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_generation( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get options
        $methods = $this->base->get_class( 'common' )->get_methods();
        $overwrite_methods = $this->base->get_class( 'common' )->get_overwrite_methods();
        $overwrite_sections = $this->base->get_class( 'common' )->get_content_overwrite_sections();

        // Define labels
        $labels = array(
            'singular'  => __( 'Page', 'page-generator-pro' ),
            'plural'    => __( 'Pages', 'page-generator-pro' ),
        );

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-generation.php' ); 

    }

    /**
     * Outputs the Menu Sidebar Meta Box
     *
     * @since   2.7.1
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_menu( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get options
        $menus = wp_get_nav_menus();
        
        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-menu.php' ); 

    }

    /**
     * Outputs the Attributes Sidebar Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_attributes( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get options
        $hierarchical_post_types = $this->base->get_class( 'common' )->get_hierarchical_post_types();
        $post_types              = $this->base->get_class( 'common' )->get_post_types();
        $post_types_templates    = $this->base->get_class( 'common' )->get_post_types_templates();

        // Build string of hierarchal post types to use as a selector class
        $hierarchical_post_types_class = '';
        if ( is_array( $hierarchical_post_types ) && count( $hierarchical_post_types ) > 0 ) {
            foreach ( $hierarchical_post_types as $type => $post_type ) {
                $hierarchical_post_types_class .= $type . ' ';
            }
        }

        // Build string of post types to use as a selector class
        $post_types_class = '';
        if ( is_array( $post_types ) && count( $post_types ) > 0 ) {
            foreach ( $post_types as $type => $post_type ) {
                $post_types_class .= $type . ' ';
            }
        }

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-attributes.php' ); 

    }

    /**
     * Outputs the Taxonomies Sidebar Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_taxonomies( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Fetch some taxonomy information for our Taxonomy meta boxes
        $post_types             = $this->base->get_class( 'common' )->get_post_types();
        $taxonomies             = $this->base->get_class( 'common' )->get_taxonomies();

        // Iterate through taxonomies, outputting options for each
        foreach ( $taxonomies as $taxonomy ) {
            // Build list of Post Types this taxonomy is registered for use on
            $post_types_string = '';
            foreach ( $taxonomy->object_type as $post_type ) {
                $post_types_string = $post_types_string . $post_type . ' ';
            }

            // Load view
            include( $this->base->plugin->folder . 'views/admin/generate-meta-box-taxonomies.php' ); 
        }

    }

    /**
     * Outputs the Featured Image Sidebar Meta Box
     *
     * @since   2.0.2
     *
     * @param   WP_Post     $post   Custom Post Type's Post
     */
    public function output_meta_box_featured_image( $post ) {

        // Get settings
        if ( count( $this->settings ) == 0 ) {
            $this->settings = $this->base->get_class( 'groups' )->get_settings( $post->ID );
        }

        // Get options
        $featured_image_sources = $this->base->get_class( 'common' )->get_featured_image_sources();
        $operators = $this->base->get_class( 'common' )->get_operator_options();
        $image_orientations = $this->base->get_class( 'pexels' )->get_image_orientations();
        $pixabay_languages = $this->base->get_class( 'pixabay' )->get_languages();
        $pixabay_image_types = $this->base->get_class( 'pixabay' )->get_image_types();
        $pixabay_image_categories = $this->base->get_class( 'pixabay' )->get_categories();
        $pixabay_image_colors = $this->base->get_class( 'pixabay' )->get_colors();

        // Load view
        include( $this->base->plugin->folder . 'views/admin/generate-meta-box-featured-image.php' ); 

    }

    /**
     * Called when a Group is saved.
     *
     * @since   2.0.2
     *
     * @param   int     $post_id
     */
    public function save_post( $post_id ) {

        // Bail if this isn't a Page Generator Pro Group that's being saved
        if ( get_post_type( $post_id ) != $this->base->get_class( 'post_type' )->post_type_name ) {
            return;
        }

        // Run security checks
        // Missing nonce 
        if ( ! isset( $_POST[ $this->base->plugin->name . '_nonce' ] ) ) { 
            return;
        }

        // Invalid nonce
        if ( ! wp_verify_nonce( $_POST[ $this->base->plugin->name . '_nonce' ], 'save_generate' ) ) {
            return;
        }

        // Save the Group's Settings
        $this->base->get_class( 'groups' )->save( $_POST[ $this->base->plugin->name ], $post_id, $_POST );

        // Check which submit action was given, as we may need to run a test or redirect to the generate screen now.
        $action = $this->get_action();
        if ( ! $action ) {
            return;
        }

        // Maybe run an action on the Group now
        $redirect = ( $action == 'generate_server' ? true : false );
        $this->base->get_class( 'groups' )->run_action( $action, $post_id, $redirect );

    }

    /**
     * Returns the localized title
     *
     * @since   2.0.2
     *
     * @param   string  $key    Key
     * @return  string          Message
     */
    public function get_title( $key ) {

        // Get Titles and Messages
        $titles_messages = $this->get_titles_and_messages();

        // Bail if no Titles exist
        if ( ! isset( $titles_messages['titles'] ) ) {
            return '';
        }

        // Bail if the Title does not exist
        if ( ! isset( $titles_messages['titles'][ $key ] ) ) {
            return '';
        }

        // Return the title
        return $titles_messages['titles'][ $key ];

    }

    /**
     * Returns the localized message
     *
     * @since   2.0.2
     *
     * @param   string  $key    Key
     * @return  string          Message
     */
    public function get_message( $key ) {

        // Get Titles and Messages
        $titles_messages = $this->get_titles_and_messages();

        // Bail if no Messages exist
        if ( ! isset( $titles_messages['messages'] ) ) {
            return '';
        }

        // Bail if the Message does not exist
        if ( ! isset( $titles_messages['messages'][ $key ] ) ) {
            return '';
        }

        // Return the message
        return $titles_messages['messages'][ $key ];

    }

    /**
     * Returns Titles and Messages that are used for Content Generation,
     * which are displayed in various notifications.
     *
     * @since   2.0.2
     *
     * @return  array   Titles and Messages
     */
    public function get_titles_and_messages() {

        // Define localizations
        $localization = array(
            'titles'    => array(
                'duplicate'                                 => __( 'Duplicate', 'page-generator-pro' ),
                'test'                                      => __( 'Test', 'page-generator-pro' ),
                'generate'                                  => __( 'Generate via Browser', 'page-generator-pro' ),
                'generate_server'                           => __( 'Generate via Server', 'page-generator-pro' ),
                'trash_generated_content'                   => __( 'Trash Generated Content', 'page-generator-pro' ),
                'delete_generated_content'                  => __( 'Delete Generated Content', 'page-generator-pro' ),
                'cancel_generation'                         => __( 'Cancel Generation', 'page-generator-pro' ),
            ),

            'messages'  => array(
                // Generate
                'generate_confirm'                  => __( 'This will generate all Pages/Posts. Proceed?', 'page-generator-pro' ),
                
                // Generate via Server
                'generate_server_confirm'           => __( 'This will generate all Content using WordPress\' CRON to offload the process to the server. Proceed?', 'page-generator-pro' ),
                'generate_server_success'           => __( 'Queued for Generation via WordPress\' CRON. This may take a minute or two to begin.', 'page-generator-pro' ),

                // Cancel Generation
                'cancel_generation_confirm'         => __( 'This will cancel Content Generation, allowing the Group to be edited.  Proceed?', 'page-generator-pro' ),
                'cancel_generation_success'         => __( 'Generation Cancelled', 'page-generator-pro' ),
                
                // Duplicate
                'duplicate_success'                 => __( 'Group Duplicated', 'page-generator-pro' ),

                // Test
                'test_confirm'                      => __( 'This will generate a single Page/Post in draft mode. Proceed?', 'page-generator-pro' ),
                'test'                              => __( 'Generating Test Page in Draft Mode...', 'page-generator-pro' ),
                'test_error'                        => __( 'An error occured. Please try again.', 'page-generator-pro' ),
                
                // Trash Generated Content
                'trash_generated_content_confirm'   => __( 'This will trash ALL content generated by this group. Proceed?', 'page-generator-pro' ),  
                'trash_generated_content'           => __( 'Trashing Generated Content...', 'page-generator-pro' ),
                'trash_generated_content_success'   => __( 'Generated Content Trashed', 'page-generator-pro' ),
                'trash_generated_content_error'     => __( 'An error occured. Please try again.', 'page-generator-pro' ),

                // Delete Generated Content
                'delete_generated_content_confirm'  => __( 'This will PERMANENTLY DELETE ALL content generated by this group. Proceed?', 'page-generator-pro' ),  
                'delete_generated_content'          => __( 'Deleting Generated Content...', 'page-generator-pro' ),
                'delete_generated_content_success'  => __( 'Generated Content Deleted', 'page-generator-pro' ),
                'delete_generated_content_error'    => __( 'An error occured. Please try again.', 'page-generator-pro' ),
            ),
        );

        /**
         * Filters the localization title and message strings used for Generation.
         *
         * @since   2.0.2
         *
         * @param   array   $localization   Titles and Messages
         */
        $localization = apply_filters( 'page_generator_pro_groups_ui_get_titles_and_messages', $localization );

        // Return
        return $localization;
        
    }

    /**
     * When saving the Canonical URL field, revert Yoast SEO's sanitization, which strips curly braces
     *
     * @since   2.0.2
     *
     * @param   string  $clean          Sanitized Value
     * @param   string  $meta_value     Unsanitized Value
     * @param   array   $field_def      Meta Field Definition
     * @param   string  $meta_key       Meta Key
     * @return  string                  Value to save
     */
    public function wpseo_sanitize_post_meta__yoast_wpseo_canonical( $clean, $meta_value, $field_def, $meta_key ) {

        // Bail if no Post ID set
        if ( ! isset( $_POST['ID'] ) ) {
            return $clean;
        }

        // Get Post ID
        $group_id = absint( $_POST['ID'] );

        // Bail if not a Group
        if ( get_post_type( $group_id ) != $this->base->get_class( 'post_type' )->post_type_name ) {
            return $clean;
        }

        // Return non-sanitized value
        return $meta_value;

    }

    /**
     * Determines which submit button was pressed on the Groups add/edit screen
     *
     * @since   2.0.2
     *
     * @return  string  Action
     */
    private function get_action() {

        if ( isset( $_POST['test'] ) ) {
            return 'test';
        }

        if ( isset( $_POST['generate'] ) ) {
            return 'generate';
        }

        if ( isset( $_POST['generate_server'] ) ) {
            return 'generate_server';
        }

        if ( isset( $_POST['trash_generated_content'] ) ) {
            return 'trash_generated_content';
        }

        if ( isset( $_POST['delete_generated_content'] ) ) {
            return 'delete_generated_content';
        }

        if ( isset( $_POST['cancel_generation'] ) ) {
            return 'cancel_generation';
        }

        if ( isset( $_POST['save'] ) ) {
            return 'save';
        }

        // No action given
        return false;
  
    }

    /**
     * Limit creating more than one Group via the WordPress Administration, by preventing
     * the 'Add New' functionality, and ensuring the user is always taken to the edit
     * screen of the single Group when they access the Post Type.
     *
     * @since   1.3.8
     */
    public function limit_admin() {

        global $pagenow;

        switch ( $pagenow ) {
            /**
             * Edit
             * WP_List_Table
             */
            case 'edit.php':
                // Bail if no Post Type is supplied
                if ( ! isset( $_REQUEST['post_type'] ) ) {
                    break;
                }
                
                // Bail if we're not on our Group Post Type
                if ( $_REQUEST['post_type'] != Page_Generator_Pro_PostType::get_instance()->post_type_name ) {
                    break;
                }

                // Fetch first group
                $groups = new WP_Query( array(
                    'post_type'     => Page_Generator_Pro_PostType::get_instance()->post_type_name,
                    'post_status'   => 'publish',
                    'posts_per_page'=> 1,
                ) );

                // Bail if no Groups exist, so the user can create one
                if ( count( $groups->posts ) == 0 ) {
                    break;
                }

                // Redirect to the Group's edit screen
                wp_safe_redirect( 'post.php?post=' . $groups->posts[0]->ID . '&action=edit' );
                die();

                break;

            /**
             * Add New
             */
            case 'post-new.php':
            case 'press-this.php':
                // Bail if we don't know the Post Type
                if ( ! isset( $_REQUEST['post_type'] ) ) {
                    break;
                }

                // Bail if we're not on our Group Post Type
                if ( $_REQUEST['post_type'] != Page_Generator_Pro_PostType::get_instance()->post_type_name ) {
                    break;
                }

                // Fetch first group
                $groups = new WP_Query( array(
                    'post_type'     => Page_Generator_Pro_PostType::get_instance()->post_type_name,
                    'post_status'   => 'publish',
                    'posts_per_page'=> 1,
                ) );

                // Bail if no Groups exist, so the user can create one
                if ( count( $groups->posts ) == 0 ) {
                    break;
                }

                // Redirect to the Group's edit screen
                wp_safe_redirect( 'post.php?post=' . $groups->posts[0]->ID . '&action=edit' );
                die();
                
                break;
        }
            
    }

    /**
     * Limit creating more than one Group via XML-RPC
     *
     * @since   1.3.8
     *
     * @param   bool    $limit  Limit XML-RPC
     * @param   array   $post   Post Data
     * @return                  Limit XML-RPC
     */
    public function limit_xml_rpc( $limit, $post = array() ) {

        // Bail if we're not on an XMLRPC request
        if ( ! defined( 'XMLRPC_REQUEST' ) ||  XMLRPC_REQUEST != true ) {
            return $limit;
        }
        
        // Bail if no Post Type specified
        if ( ! isset( $post['post_type'] ) ) {
            return $limit;
        }
        if ( $post['post_type'] != Page_Generator_Pro_PostType::get_instance()->post_type_name ) {
            return $limit;
        }

        // If here, we're trying to create a Group. Don't let this happen.
        return true;

    }

}