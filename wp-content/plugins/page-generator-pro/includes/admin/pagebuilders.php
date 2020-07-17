<?php
/**
 * Page Builders class
 * 
 * @package  Page Generator Pro
 * @author   Tim Carr
 * @version  1.3.7
 */
class Page_Generator_Pro_PageBuilders {

    /**
     * Holds the base object.
     *
     * @since   1.9.8
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     * 
     * @since   1.3.7
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Register Support for Page Builders
        // Where possible, Content Groups are automatically enabled within each Page Builder's settings to
        // save the User having to manually change Page Builder settings.

        // Avia
        add_filter( 'avf_builder_boxes', array( $this, 'register_avia_layout_builder_meta_boxes' ), 10, 1 );
        add_filter( 'avf_alb_supported_post_types', array( $this, 'register_avia_layout_builder_supported_post_types' ) );

        // Beaver Builder
        add_filter( 'fl_builder_post_types', array( $this, 'register_beaver_builder_support' ) );

        // BeTheme
        add_action( 'wp_loaded', array( $this, 'register_betheme_support' ) );

        // Divi
        // Divi: Adds Content Groups as a choice under third party Post Types
        add_filter( 'et_builder_third_party_post_types', array( $this, 'register_divi_support' ) );

        // Divi: Always enables Divi on Content Groups in the Backend, even if the user hasn't enabled it in Theme Options
        add_filter( 'et_builder_post_types', array( $this, 'register_divi_support' ) );

        // Divi: Always enables Divi on Content Groups in the Frontend, even if the user hasn't enabled it in Theme Options
        add_filter( 'et_fb_post_types', array( $this, 'register_divi_support' ) );
        
        // Divi: Show all Metabox options in Divi Settings
        add_action( 'page_generator_pro_groups_ui_add_meta_boxes', array( $this, 'register_divi_metabox_support' ) );

        // Divi: Make Layouts of any Post Type available to Content Groups
        add_filter( 'et_pb_show_all_layouts_built_for_post_type', array( $this, 'register_divi_layout_support' ) );

        // Elementor
        add_action( 'init', array( $this, 'register_elementor_support' ) );
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'register_elementor_scripts_css' ) );
        add_filter( 'page_generator_pro_groups_get_post_meta__elementor_data', array( $this, 'elementor_decode_meta' ) );
        add_filter( 'page_generator_pro_generate_should_process_shortcodes_on_post_content', array( $this, 'elementor_should_process_shortcodes_on_post_content' ), 10, 2 );
        add_filter( 'page_generator_pro_generate_set_post_meta__elementor_data', array( $this, 'elementor_encode_meta' ) );

        // Flatsome
        add_action( 'init', array( $this, 'register_flatsome_support' ) );

        // Flotheme (Porto2)
        add_filter( 'flo_sidebars_available_post_types', array( $this, 'register_flotheme_layout_sidebars_support' ) );
        add_filter( 'acf/get_field_group', array( $this, 'register_flotheme_layout_support' ) );

        // Fusion Builder (Avada)
        add_filter( 'fusion_builder_allowed_post_types', array( $this, 'register_fusion_builder_support' ) );
        add_filter( 'fusion_builder_default_post_types', array( $this, 'register_fusion_builder_support' ) );

        // Live Composer
        add_filter( 'dslc_can_edit_in_lc', array( $this, 'register_live_composer_page_builder_support' ), 10, 2 );
        add_filter( 'page_generator_pro_admin_body_class', array( $this, 'live_composer_body_class' ) );
        add_filter( 'page_generator_pro_screen_get_current_screen', array( $this, 'live_composer_set_current_screen' ), 10, 3 );

        // Make Theme
        add_action( 'init', array( $this, 'register_make_theme_page_builder_support' ) );

        // Medicenter Theme
        add_action( 'add_meta_boxes', array( $this, 'register_medicenter_support' ) );

        // Metabox.io Support (Themes that use this Plugin to register Meta Boxes + Custom Fields e.g. Construction Theme, Wize Law Theme)
        add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_box_io_support' ), 9999 );

        // Oxygen Page Builder
        add_filter( 'page_generator_pro_groups_get_post_meta_ct_builder_shortcodes', array( $this, 'oxygen_decode_meta' ) );

        // Ovic Addon Toolkit Plugin Metaboxes (KuteThemes e.g. Stuno Theme)
        add_filter( 'ovic_options_metabox', array( $this, 'register_ovic_toolkit_support' ) );

        // Salient
        add_action( 'init', array( $this, 'register_salient_support' ) );

        // SiteOrigin Page Builder
        add_filter( 'siteorigin_panels_settings_defaults', array( $this, 'register_siteorigin_page_builder_support' ) );
        add_filter( 'page_generator_pro_screen_get_current_screen', array( $this, 'siteorigin_page_builder_set_current_screen' ), 10, 3 );

        // The7 Theme
        add_filter( 'presscore_pages_with_basic_meta_boxes', array( $this, 'register_the7_support' ) );

        // Thebuilt Theme
        add_filter( 'init', array( $this, 'register_thebuilt_support' ) );

        // Thrive Architect
        add_action( 'tcb_hook_template_redirect', array( $this, 'register_thrive_architect_wp_editor_support' ) );

        // Visual Composer (visualcomposer.com)
        add_filter( 'init', array( $this, 'register_visual_composer_support' ) );
        add_filter( 'page_generator_pro_screen_get_current_screen', array( $this, 'visual_composer_set_current_screen' ), 10, 3 );
        add_filter( 'page_generator_pro_groups_get_post_meta_vcv-pageContent', array( $this, 'visual_composer_decode_meta' ) );
        add_filter( 'page_generator_pro_generate_set_post_meta_vcv-pageContent', array( $this, 'visual_composer_encode_meta' ) );

        // WPBakery Page Builder (wpbakery.com)
        add_action( 'init', array( $this, 'register_wpbakery_page_builder_support' ) );
        add_action( 'vc_before_init', array( $this, 'wpbakery_page_builder_enable_frontend' ), PHP_INT_MAX );
        add_action( 'vc_after_init', array( $this, 'wpbakery_page_builder_enable_frontend' ), PHP_INT_MAX );
        
        // Register all Post Type Templates to Page Builders
        add_filter( 'theme_page-generator-pro_templates', array( $this, 'add_all_post_type_templates_to_page_builders' ), 10, 4 );

    }

    /**
     * Registers all available Avia Layout Builder Metaboxes against Page Generator Pro's
     * Content Groups, so that they're available for configuration when editing a
     * Content Group.
     *
     * If we don't do this, the user can't configure e.g. Page Layout for generated Pages.
     *
     * @since   1.5.6
     *
     * @param   array   $meta_boxes     Meta Boxes
     * @return  array                   Meta Boxes
     */
    public function register_avia_layout_builder_meta_boxes( $meta_boxes ) {

        // Bail if no Meta Boxes exist
        if ( empty( $meta_boxes ) ) {
            return $meta_boxes;
        }

        // Define the Avia Meta Box IDs
        $avia_meta_box_ids = array(
            'avia_builder',
            'avia_sc_parser',
            'layout',
            'preview',
            'hierarchy',
        );

        /**
         * Defines the Avia Meta Boxes to include in Content Groups.
         *
         * @since   1.5.6
         *
         * @param   array   $avia_meta_box_ids      Avia Meta Box IDs to include in Content Groups
         * @param   array   $meta_boxes             Meta Boes
         */
        $avia_meta_box_ids = apply_filters( 'page_generator_pro_pagebuilders_register_avia_layout_builder_support', $avia_meta_box_ids, $meta_boxes );

        // Iterate through the existing Meta Boxes, to find the Avia specific ones
        foreach ( $meta_boxes as $key => $meta_box ) {
            // Skip if the ID isn't one we are looking for
            if ( ! in_array( $meta_box['id'], $avia_meta_box_ids ) ) {
                continue;
            }

            // Add Page Generator Pro's Groups to the 'page' array
            $meta_boxes[ $key ]['page'][] = 'page-generator-pro';
        }

        return $meta_boxes;

    }

    /**
     * Allows the Avia Layout Builder (which comes with the Enfold Theme) to inject
     * its Page Builder into Page Generator Pro's Groups when the Block Editor is used
     * (i.e. the Classic Editor isn't enabled)
     *
     * @since   2.3.4
     *
     * @param   array   $post_types     Post Types
     * @return  array                   Post Types
     */
    public function register_avia_layout_builder_supported_post_types( $post_types ) {

        $post_types[] = 'page-generator-pro';
        return $post_types;
           
    }

    /**
     * Allows Beaver Builder to inject its Page Builder
     * into Page Generator Pro's Groups
     *
     * @since   1.3.7
     *
     * @param   array   $post_types     Post Types Supporting Beaver Builder
     * @return  array                   Post Types Supporting Beaver Builder
     */
    public function register_beaver_builder_support( $post_types ) {

        $post_types[] = 'page-generator-pro';
        return $post_types;

    }

    /**
     * Allows BeTheme's Muffin Builder Meta Box to be output on Page Generator Pro's Groups
     *
     * @since   2.1.2
     */
    public function register_betheme_support() {

        // Bail if BeTheme isn't loaded
        if ( ! class_exists( 'Mfn_Post_Type' ) ) {
            return;
        }

        // Load class
        include_once( $this->base->plugin->folder . '/includes/admin/pagebuilders-betheme.php' );

        // Invoke class
        $mfn_post_type_page_generator_pro = new Mfn_Post_Type_Page_Generator_Pro();

    }

    /**
     * Allows The Divi Builder (and therefore Divi Theme 3.0+) to inject its Page Builder
     * into Page Generator Pro's Groups
     *
     * @since   1.2.7
     *
     * @param   array   $post_types     Post Types Supporting Divi
     * @return  array                   Post Types Supporting Divi
     */
    public function register_divi_support( $post_types ) {

        $post_types[] = 'page-generator-pro';
        return $post_types;

    }

    /**
     * Allows The Divi Builder (and therefore Divi Theme 3.0+) to inject its Page Builder
     * Meta Box into this Plugin's enabled Custom Post Types
     *
     * @since   1.4.1
     *
     * @param   obj     $post_type_instance     Post Type Instance
     */
    public function register_divi_metabox_support( $post_type_instance ) {

        // Don't need to do anything if we're not in the admin interface
        if ( ! is_admin() ) {
            return;
        }

        // Don't add the meta box if Divi Builder isn't active
        if ( ! function_exists( 'et_single_settings_meta_box' ) ) {
            return;
        }

        // Add Meta Box
        // We don't use add_meta_box( 'et_settings_meta_box'... because we need to change
        // the Post Type = post, so that all settings display, without changing the global $post
        add_meta_box( 
            'et_settings_meta_box',
            __( 'Divi Settings', 'page-generator-pro' ), 
            array( $this, 'output_divi_metabox' ), 
            $post_type_instance->post_type_name,
            'side',
            'high'
        );

    }

    /**
     * Outputs the Divi Settings Metabox
     *
     * @since   1.6.4
     */
    public function output_divi_metabox() {

        // Trick Divi into outputting Post settings
        global $post;
        $new_post = $post;
        $new_post->post_type = 'post';

        // Call metabox function directly.
        et_single_settings_meta_box( $new_post );        

    }

    /**
     * Allows The Divi Builder (and therefore Divi Theme 3.0+) to inject its Page Builder Layouts
     * into the chosen Custom Post Types
     *
     * @since   1.4.1
     *
     * @param   mixed   $post_types     string | array
     * @return  array                   Post Types to get Layouts from
     */
    public function register_divi_layout_support( $post_types ) {

        // Bail if we're not on Page Generator Pro
        if ( $post_types != 'page-generator-pro' ) {
            return $post_types;
        }

        // If $post_types isn't an array, make it one
        if ( ! is_array( $post_types ) ) {
            $post_types = array( $post_types );
        }

        // Fetch Public Post Types
        $public_post_types = $this->base->get_class( 'common' )->get_post_types();

        // Add all Public Post Types to $post_types
        foreach ( $public_post_types as $public_post_type ) {
            // Add Custom Post Type to Divi, so the Page Builder displays
            $post_types[] = $public_post_type->name;
        }

        // Remove duplicates
        $post_types = array_unique( $post_types );

        // Return
        return $post_types;

    }

    /**
     * Allows the Elementor Page Builder to inject its Page Builder
     * into Page Generator Pro's Groups
     *
     * @since   2.0.1
     */
    public function register_elementor_support() {

        add_post_type_support( 'page-generator-pro', 'elementor' );

    }

    /**
     * Elementor: Enqueue CSS and JS when editing a Content Group, so TinyMCE Plugins etc. work,
     * as Elementor removes actions hooked to admin_enqueue_scripts / wp_enqueue_scripts
     *
     * @since   2.5.7
     */
    public function register_elementor_scripts_css() {

        // Load Plugin CSS/JS
        $this->base->get_class( 'admin' )->admin_scripts_css();

    }

    /**
     * JSON decodes Elementor's Page Builder metadata into an array for a Content Group, 
     * so that the Generate Routine can iterate through it, replacing Keywords, Shortcodes etc.
     *
     * @since   2.6.1
     *
     * @param   string  $value  Elementor Page Builder Data
     * @return  string          Elementor Page Builder Data
     */
    public function elementor_decode_meta( $value ) {

        // JSON decode Elementor's data
        if ( is_string( $value ) && ! empty( $value ) ) {
            $value = json_decode( $value, true );
        }
        if ( empty( $value ) ) {
            $value = array();
        }

        return $value;

    }

    /**
     * Disable processing Shortcodes on the main Post Content when the Content Group is edited using Elementor,
     * as the Post Content isn't output by Elementor.
     *
     * @since   2.6.1
     *
     * @param   bool    $process    Process Shortcodes on Post Content
     * @param   array   $settings   Group Settings
     * @return  bool                Process Shortcodes on Post Content
     */
    public function elementor_should_process_shortcodes_on_post_content( $process, $settings ) {

        // Honor the original status for processing shortcodes on content if no Post Meta
        if ( ! isset( $settings['post_meta'] ) ) {
            return $process;
        }

        // Honor the original status for processing shortcodes on content if we're not using Elementor
        if ( ! isset( $settings['post_meta']['_elementor_edit_mode'] ) ) {
            return $process;
        }
        if ( $settings['post_meta']['_elementor_edit_mode'] != 'builder' ) {
            return $process;
        }

        // We're using Elementor for this Content Group, so don't process shortcodes on the Post Content
        // as the Post Content isn't used
        return false;

    }

    /**
     * JSON encodes Elementor's Page Builder metadata into a string immediately before it's
     * copied to the Generated Page.
     *
     * @since   2.6.1
     *
     * @param   array  $value   Elementor Page Builder Data
     * @return  string          Elementor Page Builder Data
     */
    public function elementor_encode_meta( $value ) {

        // Encode with slashes, just how Elementor does
        return wp_slash( wp_json_encode( $value ) );

    }

    /**
     * Allows the Flatsome Theme's UX Builder to inject its Page Builder
     * into Page Generator Pro's Groups
     *
     * @since   1.7.8
     */
    public function register_flatsome_support() {

        // Bail if the Flatsome Theme isn't enabled
        if ( ! function_exists( 'add_ux_builder_post_type' ) ) {
            return;
        }

        // Add Page Generator Pro Groups
        add_ux_builder_post_type( 'page-generator-pro' );

    }

    /**
     * Allows Flotheme's Layout and Sidebars ACF Group to display on
     * Page Generator Pro's Groups
     *
     * @since   2.5.9
     *
     * @param   array   $location_array     ACF acf_add_local_field_group() location-compatible conditions
     * @return  array                       ACF acf_add_local_field_group() location-compatible conditions
     */
    public function register_flotheme_layout_sidebars_support( $location_array ) {

        // Add Page Generator Pro Content Group CPT
        $location_array[] = array(
            array(
                'param'     => 'post_type',
                'operator'  => '==',
                'value'     => 'page-generator-pro',
            ),
        );

        return $location_array;

    }

    /**
     * Allows Flotheme's Layout ACF Group to display on
     * Page Generator Pro's Groups by modifying the location parameter
     *
     * @since   2.5.9
     *
     * @param   array   $group  Field Group
     * @return  array           Field Group
     */
    public function register_flotheme_layout_support( $group ) {

        // Skip if this isn't the Location Group
        if ( $group['key'] != 'group_59b6784711f0a' ) {
            return $group;
        }

        // Modify the location parameter to include Page Generator Pro Content Group CPT
        $group['location'][1] = array(
            array(
                'param'     => 'post_type',
                'operator'  => '==',
                'value'     => 'page-generator-pro', 
            ),
        );

        return $group;

    }

    /**
     * Allows Fusion Builder (and therefore Avada Theme) to inject its Page Builder
     * into Page Generator Pro's Groups
     *
     * @since   1.2.8
     *
     * @param   array   $post_types     Post Types Supporting Divi
     * @return  array                   Post Types Supporting Divi
     */
    public function register_fusion_builder_support( $post_types ) {

        $post_types[] = 'page-generator-pro';
        return $post_types;

    }

    /**
     * Allows Live Composer to inject its Page Builder into Page Generator Pro's Groups,
     *
     * @since   1.6.8
     */
    public function register_live_composer_page_builder_support( $can_edit, $post_type ) {

        // Bail if we're not on a Page Generator Pro group
        if ( $post_type != 'page-generator-pro' ) {
            return $can_edit;
        }

        // Enable Live Composer on Page Generator Pro Groups
        return true;

    }

    /**
     * Registers Live Composer's screen name to ensure the wpzinc class is added to the <body> tag
     *
     * @since   2.5.8
     *
     * @param   array   $screens    Screen Names
     * @return  array               Screen Names
     */
    public function live_composer_body_class( $screens ) {

        $screens[] = 'livecomposer_editor';
        return $screens;

    }

    /**
     * Tells the Screen class that we're editing a Content Group when editing it with Live Composer.
     *
     * @since   2.5.8
     *
     * @param   array       $result     Screen and Section
     * @param   string      $screen_id  Screen
     * @param   WP_Screen   $screen     WordPress Screen object
     * @return  array                   Screen and Section
     */
    public function live_composer_set_current_screen( $result, $screen_id, $screen ) {

        // Bail if we're not on the Live Composer Editor screen
        if ( $screen_id != 'livecomposer_editor' ) {
            return $result;
        }

        // Check if we're editing a Content Group
        if ( ! isset( $_REQUEST['page_id'] ) ) {
            return $result;
        }
        if ( $this->base->plugin->name != get_post_type( absint( $_REQUEST['page_id'] ) ) ) {
            return $result;
        }

        // Return a modified screen array to tell the Screen class that we're editing a Content Group
        return array(
            'screen'    => 'content_groups',
            'section'   => 'edit',
        );

    }

    /**
     * Calls add_post_type_support to register Content Groups as supporting Make Theme's
     * Page Builder.
     *
     * @since   2.1.5
     */
    public function register_make_theme_page_builder_support() {

        add_post_type_support( 'page-generator-pro', 'make-builder' );

    }

    /**
     * Registers Medicenter Theme's Meta Boxes on Page Generator Pro's Groups
     *
     * @since   2.6.2
     */
    public function register_medicenter_support() {

        // Bail if Medicenter isn't active
        if ( ! function_exists( 'mc_theme_add_custom_box' ) ) {
            return;
        }

        // Register Medicenter Metaboxes on Page Generator Pro's Content Groups
        // Medicenter's JS, which injects Sidebars into the Attributes section,
        // will also have its settings saved as a Medicenter nonce is now output
        // through the called functions below.
        add_meta_box( 
            'options',
            __( 'Medicenter: Post Options', 'page-generator-pro' ),
            'mc_theme_inner_custom_box_post',
            'page-generator-pro',
            'normal'
        ); 

    }

    /**
     * Allows Metabox.io to register its metaboxes into Page Generator Pro (Themes that use this Plugin to register Meta Boxes + 
     * Custom Fields e.g. Construction Theme, Wize Law Theme)
     * 
     * @since   2.6.3
     *
     * @param   array   $meta_boxes     Meta Boxes
     * @return  array                   Meta Boxes
     */
    public function register_meta_box_io_support( $meta_boxes ) {

        if ( ! is_array( $meta_boxes ) ) {
            return $meta_boxes;
        }

        if ( ! count( $meta_boxes ) ) {
            return $meta_boxes;
        }

        // Add Meta Boxes to Page Generator Pro
        foreach ( $meta_boxes as $index => $meta_box ) {
            // Some themes use 'pages', others use 'post_types'
            if ( isset( $meta_box['pages'] ) && is_array( $meta_box['pages'] ) && count( $meta_box['pages'] ) > 0 ) {
                $meta_boxes[ $index ]['pages'][] = 'page-generator-pro';
                continue;    
            }

            if ( isset( $meta_box['post_types'] ) && is_array( $meta_box['post_types'] ) && count( $meta_box['post_types'] ) > 0 ) {
                $meta_boxes[ $index ]['post_types'][] = 'page-generator-pro';
                continue;    
            }
        }

        return $meta_boxes;

    }

    /**
     * Decodes Oxygen Page Builder metadata, by calling oxygen_vsb_filter_shortcode_content_decode()
     * to undo oxygen_vsb_filter_shortcode_content_encode(), which converts square brackets to
     * _OXY_OPENING_BRACKET_ and _OXY_CLOSING_BRACKET_.
     *
     * If this isn't done, shortcodes aren't recognized by Page Generator Pro, so can't be parsed
     * into HTML during the generation process. 
     *
     * @since   2.7.2
     *
     * @param   string  $value  Visual Composer Page Builder Data
     * @return  array           Visual Composer Page Builder Data
     */
    public function oxygen_decode_meta( $value ) {

        $value = str_replace( '_OXY_OPENING_BRACKET_', '[', $value );
        $value = str_replace( '_OXY_CLOSING_BRACKET_', ']', $value );

        return $value;

    }

    /**
     * Allows KuteThemes that use the OVIC Toolkit to register its metaboxes into Page Generator Pro's Groups.
     *
     * @since   2.4.4
     *
     * @param   array   $options   Metaboxes' Configuration Options
     */
    public function register_ovic_toolkit_support( $options ) {

        // Bail if no options
        if ( ! is_array( $options ) || empty( $options ) || ! count( $options ) ) {
            return $options;
        }

        // Make a copy of the options, setting the Post Type of each to Page Generator Pro's Content Groups
        $content_group_options = array();
        foreach ( $options as $index => $option ) {
            $option['post_type'] = 'page-generator-pro';
            $content_group_options[] = $option;
        }

        // Merge options
        $options = array_merge( $options, $content_group_options );

        // Return
        return $options;

    }

    /**
     * Calls Salient's nectar_metabox_page() function, which registers Salient Page Meta Boxes
     * when creating or editing a Content Group.
     *
     * These are then copied to the Page Generator Pro Post Type in $wp_meta_boxes.
     *
     * @since   1.8.7
     */
    public function register_salient_support() {

        // Bail if Salient isn't active
        if ( ! function_exists( 'nectar_metabox_page' ) ) {
            return;
        }

        // Enqueue JS and CSS
        add_action( 'admin_enqueue_scripts', 'nectar_metabox_scripts' );
        add_action( 'admin_enqueue_scripts', 'nectar_metabox_styles' );
        add_action( 'admin_enqueue_scripts', 'nectar_enqueue_media' );

        // Force Salient to register its metaboxes when editing a Content Group
        add_action( 'add_meta_boxes_page-generator-pro', 'nectar_metabox_page', 1 );

        // Copy Salient Metaboxes to Page Generator Pro, now they're registered above on the 'page' Post Type
        add_action( 'add_meta_boxes_page-generator-pro', array( $this, 'register_salient_metaboxes' ) ); 
      
    }

    /**
     * Copies the registered Salient Meta Boxes from the Page Post Type to the Content Group Post Type,
     * so they are available in the Content Groups UI
     *
     * @since   1.8.7
     */
    public function register_salient_metaboxes() {

        global $wp_meta_boxes;

        // Force Salient Meta Boxes into Page Generator Pro, if they exist
        if ( isset( $wp_meta_boxes['page']['normal']['high']['nectar-metabox-fullscreen-rows'] ) ) {
            $wp_meta_boxes['page-generator-pro']['normal']['high']['nectar-metabox-fullscreen-rows'] = $wp_meta_boxes['page']['normal']['high']['nectar-metabox-fullscreen-rows'];
        }
        if ( isset( $wp_meta_boxes['page']['normal']['high']['nectar-metabox-page-header'] ) ) {
            $wp_meta_boxes['page-generator-pro']['normal']['high']['nectar-metabox-page-header'] = $wp_meta_boxes['page']['normal']['high']['nectar-metabox-page-header'];
        }

    }

    /**
     * Allows SiteOrigin Page Builder to inject its Page Builder into Page Generator Pro's Groups.
     *
     * @since   2.0.1
     *
     * @param   array   $default_settings   Default Settings
     */
    public function register_siteorigin_page_builder_support( $default_settings ) {

        $default_settings['post-types'][] = 'page-generator-pro';
        return $default_settings;

    }

    /**
     * Tells the Screen class that we're editing a Content Group when editing it with SiteOrigin's Page Builder
     * and we've clicked an element to edit it, which fires an AJAX call.
     *
     * @since   2.5.8
     *
     * @param   array       $result     Screen and Section
     * @param   string      $screen_id  Screen
     * @param   WP_Screen   $screen     WordPress Screen object
     * @return  array                   Screen and Section
     */
    public function siteorigin_page_builder_set_current_screen( $result, $screen_id, $screen ) {

        global $post;

        // Bail if this isn't an AJAX request
        if ( ! defined( 'DOING_AJAX' ) ) {
            return $result;
        }
        if ( ! DOING_AJAX ) {
            return $result;
        }

        // Bail if this isn't a SiteOrigins Page Builder request
        if ( ! isset( $_REQUEST['action'] ) ) {
            return $result;
        }
        if ( sanitize_text_field( $_REQUEST['action'] ) != 'so_panels_widget_form' ) {
            return $result;
        }

        // Bail if we can't get the calling URL
        $referer_url = wp_get_referer();
        if ( ! $referer_url ) {
            return $result;
        }

        // Parse referer URL
        parse_str( parse_url( $referer_url, PHP_URL_QUERY ), $referrer );

        // Check if we're editing a Content Group
        if ( ! isset( $referrer['post'] ) ) {
            return $result;
        }
        if ( $this->base->plugin->name != get_post_type( absint( $referrer['post'] ) ) ) {
            return $result;
        }

        // Return a modified screen array to tell the Screen class that we're editing a Content Group
        return array(
            'screen'    => 'content_groups',
            'section'   => 'edit',
        );

    }

    /**
     * Allows The7 Theme to inject its Meta Boxes into Page Generator Pro's Groups.
     *
     * @since   2.3.6
     */
    public function register_the7_support( $post_types ) {

        $post_types[] = 'page-generator-pro';
        return $post_types;

    }

    /**
     * Calls TheBuilt Theme Addons thebuilt_pages_settings_box() and thebuilt_post_settings_box() functions, 
     * which registers TheBuilt Theme Addon Meta Boxes when creating or editing a Content Group.
     *
     * @since   2.3.6
     */
    public function register_thebuilt_support() {

        // Bail if the thebuilt_pages_settings_box function doesn't exist
        if ( ! function_exists( 'thebuilt_pages_settings_box' ) ) {
            return;
        }

        add_action( 'add_meta_boxes', array( $this, 'register_thebuilt_metaboxes' ) );

    }

    /**
     * Thrive Architect: Enqueue CSS, JS and re-register TinyMCE Plugins when editing a Content Group, so TinyMCE Plugins etc. work,
     * as Thrive removes actions hooked to admin_enqueue_scripts, wp_enqueue_scripts, mce_external_plugins, mce_buttons
     *
     * @since   2.5.8
     */
    public function register_thrive_architect_wp_editor_support() {

        // Load Plugin CSS/JS
        $this->base->get_class( 'admin' )->admin_scripts_css();

        // Add filters to register TinyMCE Plugins
        // Low priority ensures this works with Frontend Page Builders
        add_filter( 'mce_external_plugins', array( $this->base->get_class( 'editor' ), 'register_tinymce_plugins' ), 99999 );
        add_filter( 'mce_buttons', array( $this->base->get_class( 'editor' ), 'register_tinymce_buttons' ), 99999 );

    }

    /**
     * Registers TheBuilt Theme Addons Metaboxes on Page Generator Pro's Groups
     *
     * @since   2.3.6
     */
    public function register_thebuilt_metaboxes() {

        add_meta_box(
            'thebuilt_pages_settings_box',
            esc_html__( 'Page settings', 'thebuilt-cpt' ),
            'thebuilt_pages_settings_inner_box',
            'page-generator-pro',
            'normal',
            'high'
        );
        add_meta_box(
            'thebuilt_post_settings_box',
            esc_html__( 'Post settings', 'thebuilt-cpt' ),
            'thebuilt_post_settings_inner_box',
            'page-generator-pro',
            'normal',
            'high'
        );

    }

    /**
     * Registers the Visual Composer filter to inject its Page Builder into Page Generator Pro's Groups.
     *
     * @since   2.0.1
     */
    public function register_visual_composer_support() {

        // Bail if the vchelper function doesn't exist
        if ( ! function_exists( 'vchelper' ) ) {
            return;
        }

        // Visual Composer uses its own filter system, not WordPress standard filters
        // Register the filter
        $filter = vchelper( 'Filters' );
        $filter->listen( 'vcv:helpers:access:editorPostType', array( $this, 'register_visual_composer_support_post_type' ), 1 );

    }

    /**
     * Allows Visual Composer to inject its Page Builder into Page Generator Pro's Groups.
     *
     * @since   2.0.1
     */
    public function register_visual_composer_support_post_type( $post_types ) {

        $post_types[] = 'page-generator-pro';
        return $post_types;

    }

    /**
     * Tells the Screen class that we're editing a Content Group when editing it with Visual Composer.
     *
     * @since   2.5.8
     *
     * @param   array       $result     Screen and Section
     * @param   string      $screen_id  Screen
     * @param   WP_Screen   $screen     WordPress Screen object
     * @return  array                   Screen and Section
     */
    public function visual_composer_set_current_screen( $result, $screen_id, $screen ) {

        // Bail if we're not on the Visual Composer Editor screen
        if ( ! array_key_exists( 'vcv-action', $_REQUEST ) ) {
            return $result;
        }

        if ( ! array_key_exists( 'post', $_REQUEST ) ) {
            return $result;
        }

        // Check if we're editing a Content Group
        if ( ! isset( $_REQUEST['post'] ) ) {
            return $result;
        }
        if ( $this->base->plugin->name != get_post_type( absint( $_REQUEST['post'] ) ) ) {
            return $result;
        }

        // Return a modified screen array to tell the Screen class that we're editing a Content Group
        return array(
            'screen'    => 'content_groups',
            'section'   => 'edit',
        );

    }

    /**
     * JSON decodes Visual Composer's Page Builder metadata into an array, so that the Generate Routine
     * can iterate through it, replacing Keywords, Shortcodes etc.
     *
     * @since   2.6.1
     *
     * @param   string  $value  Visual Composer Page Builder Data
     * @return  array           Visual Composer Page Builder Data
     */
    public function visual_composer_decode_meta( $value ) {

        return json_decode( rawurldecode( $value ), true );

    }

    /**
     * JSON encodes Visual Composer's Page Builder metadata into a string immediately before it's
     * copied to the Generated Page.
     *
     * @since   2.6.1
     *
     * @param   array  $value   Visual Composer Page Builder Data
     * @return  string          Visual Composer Page Builder Data
     */
    public function visual_composer_encode_meta( $value ) {

        return rawurlencode( wp_json_encode( $value ) );

    }

    /**
     * Allows WPBakery Page Builder to inject its Page Builder into Page Generator Pro's Groups,
     * by adding the vc_access_rules_post_types/page-generator-pro Role for Administrators
     * if we're in the WP Admin.
     *
     * @since   1.3.7
     */
    public function register_wpbakery_page_builder_support() {

        // Bail if not in the WordPress Admin
        if ( ! is_admin() ) {
            return;
        }

        // Fetch the roles that need to be granted Page Builder access.
        $roles = array(
            'administrator',
            'editor',
        );

        /**
         * Filter the roles that need to be granted Page Builder access.
         *
         * @since   1.3.7
         *
         * @param   array   $roles  WordPress User Roles
         */
        $roles = apply_filters( 'page_generator_pro_pagebuilders_register_wpbakery_page_builder_support_roles', $roles );

        foreach ( (array) $roles as $role_name ) {
            // Skip if this role already has the capabilities
            $role = get_role( $role_name );
            if ( isset( $role->capabilities['vc_access_rules_post_types/page-generator-pro'] ) &&
                 isset( $role->capabilities['vc_access_rules_frontend_editor'] ) ) {
                continue;
            }   

            // Add the capabilities to this role
            // Both are required to ensure correct working functionality!
            $role->add_cap( 'vc_access_rules_post_types/page-generator-pro' );
            $role->add_cap( 'vc_access_rules_frontend_editor' );  
        }

    }

    /**
     * Stop Themes and other Plugins disabling WPBakery Page Builder on all other Post Types except their own.
     *
     * Ensures that the 'Edit with Visual Composer' is always available on Groups
     *
     * @since   1.4.5
     */
    public function wpbakery_page_builder_enable_frontend() {

        vc_disable_frontend( false );

    }

    /**
     * Force Page Builders, which use get_post_templates / get_page_templates() with
     * the Page Generator Pro Post Type specified, to display all available Templates
     * across all Post Types
     *
     * @since   1.9.3
     *
     * @param   array       $post_templates     Post Templates for the given $post_type
     * @param   WP_Theme    $wp_theme           WP Theme class object
     * @param   WP_Post     $post               WordPress Post
     * @param   string      $post_type          Post Type $post_templates are for
     * @return  array                           All Post Templates across all Post Types
     */
    public function add_all_post_type_templates_to_page_builders( $post_templates, $wp_theme, $post, $post_type ) {

        // Fetch array of templates by each Post Type
        $post_type_templates = $wp_theme->get_post_templates();

        // Bail if empty
        if ( empty( $post_type_templates ) ) {
            return $post_templates;
        }

        // Build flat list of templates
        $all_templates = array();
        foreach ( $post_type_templates as $post_type_templates_post_type => $templates ) {
            $all_templates = array_merge( $all_templates, $templates );
        }

        /**
         * Filter the Post Type Templates to register on Page Builders.
         *
         * @since   1.9.3
         *
         * @param   array       $all_templates      All Post Templates
         * @param   array       $post_templates     Post Templates for the given $post_type
         * @param   WP_Theme    $wp_theme           WP Theme class object
         * @param   WP_Post     $post               WordPress Post
         * @param   string      $post_type          Post Type $post_templates are for
         */
        $all_templates = apply_filters( 'page_generator_pro_groups_add_post_type_templates', $all_templates, $post_templates, $wp_theme, $post, $post_type );

        // Return all templates
        return $all_templates;

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
        $name = 'page_builders';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}