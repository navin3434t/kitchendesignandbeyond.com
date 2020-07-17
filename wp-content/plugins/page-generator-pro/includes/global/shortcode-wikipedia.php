<?php
/**
 * Wikipedia Shortcode/Block class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.5.1
 */
class Page_Generator_Pro_Shortcode_Wikipedia {

    /**
     * Holds the base object.
     *
     * @since   2.5.1
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     * 
     * @since   2.5.1
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        add_filter( 'page_generator_pro_shortcode_add_shortcodes', array( $this, 'add_shortcode' ) );

    }

    /**
     * Registers this shortcode / block in Page Generator Pro
     *
     * @since   2.5.1
     */
    public function add_shortcode( $shortcodes ) {

        // Add this shortcode to the array of registered shortcodes
        $shortcodes[ $this->get_name() ] = array_merge(
            $this->get_overview(),
            array(
                'name'          => $this->get_name(),
                'fields'        => $this->get_fields(),
                'tabs'          => $this->get_tabs(),
                'default_values'=> $this->get_default_values(),
            )
        );

        // Return
        return $shortcodes;

    }

    /**
     * Returns this shortcode / block's programmatic name.
     *
     * @since   2.5.1
     */
    public function get_name() {

        return 'wikipedia';

    }

    /**
     * Returns this shortcode / block's Title, Icon, Categories, Keywords
     * and properties for registering on generation and requiring CSS/JS.
     *
     * @since   2.5.1
     */
    public function get_overview() {

        return array(
            'title'     => __( 'Wikipedia', $this->base->plugin->name ),
            'description'   => __( 'Displays content from Wikipedia based on the given Term(s).', $this->base->plugin->name ),
            'icon'      => file_get_contents( $this->base->plugin->folder . '/_modules/dashboard/feather/wikipedia.svg' ),
            'category'  => $this->base->plugin->name,
            'keywords'  => array(
                __( 'Wikipedia', $this->base->plugin->name ),
                __( 'Wiki', $this->base->plugin->name ),
            ),

            // Register when Generation is running only
            'register_on_generation_only' => true,

            // Requires CSS and/or JS for output
            'requires_css' => false,
            'requires_js' => false,

            // Function to call when rendering the shortcode on the frontend
            'render_callback' => array( 'shortcode_wikipedia', 'render' ),
        );

    }

    /**
     * Returns this shortcode / block's Fields
     *
     * @since   2.5.1
     */
    public function get_fields() {

        if ( ! $this->base->is_admin_or_frontend_editor() ) {
            return false;
        }

        return array(
            'term'       => array(
                'label'                 => __( 'Term(s) / URL(s)', $this->base->plugin->name ),
                'type'                  => 'text_multiple',
                'data'                  => array(
                    'delimiter' => ';',
                ),
                'class'                 => 'wpzinc-selectize-freeform',
                'description'           => __( 'Specify one or more terms or Wikipedia URLs to search for on Wikipedia, in order. Contents will be used from the first term / URL that produces a matching Wikipedia Page', 'page-generator-pro' ),
            ),
            'use_similar_page'       => array(
                'label'                 => __( 'Use Similar Page', $this->base->plugin->name ),
                'type'                  => 'toggle',
                'default_value'         => $this->get_default_value( 'use_similar_page' ),
                'description'           => __( 'If enabled, a similar Wikipedia Article will be used where a Term specified above could not be found, and Wikipedia provides 
                                                alternate Articles when viewing said Term. Refer to the Documentation for more information.', 'page-generator-pro' ),
            ),
            'language'              => array(
                'label'                 => __( 'Language', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'common' )->get_wikipedia_languages(),
                'default_value'         => $this->get_default_value( 'language' ),
            ),
            'sections'       => array(
                'label'                 => __( 'Sections', $this->base->plugin->name ),
                'type'                  => 'text_multiple',
                'data'                  => array(
                    'delimiter' => ';',
                ),
                'class'                 => 'wpzinc-selectize-freeform',
                'description'           => __( 'Optional; specify one or more Wikipedia top level Table of Content sections to pull content from.  
                                                If no sections are specified, the summary (text before the Table of Contents) will be used.', 'page-generator-pro' ),
            ),

            'elements'       => array(
                'label'                 => __( 'Elements', $this->base->plugin->name ),
                'type'                  => 'select_multiple',
                'default_value'         => $this->get_default_value( 'elements' ),
                'values'                => $this->base->get_class( 'wikipedia' )->get_supported_elements(),
                'class'                 => 'wpzinc-selectize-drag-drop',
                'description'           => __( 'Specify the HTML elements to return from the Wikipedia Article. If no elements are specified, paragraphs will be returned', 'page-generator-pro' ),
            ),
            'remove_links'       => array(
                'label'                 => __( 'Remove Links?', $this->base->plugin->name ),
                'type'                  => 'toggle',
                'default_value'         => $this->get_default_value( 'remove_links' ),
                'description'           => __( 'If enabled, any links found in the Wikipedia Article will be removed.', 'page-generator-pro' ),
            ),
            'paragraphs'  => array(
                'label'                 => __( 'Limit', $this->base->plugin->name ),
                'type'                  => 'number',
                'min'                   => 0,
                'max'                   => 999,
                'step'                  => 1,
                'default_value'         => $this->get_default_value( 'paragraphs' ),
                'description'           => __( 'The maximum number of elements to output after all above sections have been fetched and combined.', 'page-generator-pro' ),
            ),
            'apply_synonyms'       => array(
                'label'                 => __( 'Spin?', $this->base->plugin->name ),
                'type'                  => 'toggle',
                'default_value'         => $this->get_default_value( 'apply_synonyms' ),
                'description'           => __( 'If enabled, the Wikipedia content will be spun to produce a unique variation.', 'page-generator-pro' ),
            ), 
        );

    }

    /**
     * Returns this shortcode / block's UI Tabs
     *
     * @since   2.5.1
     */
    public function get_tabs() {

        if ( ! $this->base->is_admin_or_frontend_editor() ) {
            return false;
        }

        return array(
            'general' => array(
                'label'     => __( 'Search Parameters', 'page-generator-pro' ),
                'fields'    => array(
                    'term',
                    'use_similar_page',
                    'language',
                    
                ),
            ),

            'output' => array(
                'label'         => __( 'Output', 'page-generator-pro' ),
                'class'         => 'link',
                'description'   => __( 'Defines the output of Wikipedia Content.', 'page-generator-pro' ),
                'fields'        => array(
                    'sections',
                    'elements',
                    'remove_links',
                    'paragraphs',
                    'apply_synonyms',
                ),
            ),
        );

    }

    /**
     * Returns this shortcode / block's Default Values
     *
     * @since   2.5.1
     */
    public function get_default_values() {

        return array(
            'term'              => '',
            'use_similar_page'  => 0,
            'sections'          => '',
            'elements'          => 'paragraphs',
            'remove_links'      => 1,               // Removes <a> links
            'paragraphs'        => 0,               // Number of elements
            'apply_synonyms'    => 0, 
            'language'          => 'en',
        );

    }

    /**
     * Returns the given shortcode / block's field's Default Value
     *
     * @since   2.5.1
     */
    public function get_default_value( $field ) {

        $defaults = $this->get_default_values();
        if ( isset( $defaults[ $field ] ) ) {
            return $defaults[ $field ];
        }

        return '';
    }

    /**
     * Returns this shortcode / block's output
     *
     * @since   2.5.1
     *
     * @param  array   $atts   Shortcode Attributes
     * @return string          Output
     */
    public function render( $atts ) {

        // Parse shortcode attributes, defining fallback defaults if required
        $atts = shortcode_atts( $this->get_default_values(), $atts, $this->base->plugin->name . '-' . $this->get_name() );

        // Define Term, Sections and Elements
        $atts['term'] = explode( ';', $atts['term'] );
        $atts['sections'] = ( empty( $atts['sections'] ) ? false : explode( ';', $atts['sections'] ) );
        $atts['elements'] = explode( ',', $atts['elements'] );

        // Iterate through terms until we find a page
        $errors = array();
        foreach ( $atts['term'] as $term ) {
            // Check if a Wikipedia Page exists for the given Term and Language
            $page_exists = $this->base->get_class( 'wikipedia' )->page_exists( $term, $atts['language'] );
            
            // Collect errors
            if ( is_wp_error( $page_exists ) ) {
                if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                    $errors[] = sprintf( 
                        __( 'Term: %s: Response: %s', 'page-generator-pro' ),
                        $term,
                        $page_exists->get_error_message()
                    );
                }

                continue;
            }

            // Get elements from Wikipedia Article
            $elements = $this->base->get_class( 'wikipedia' )->get_page_sections( 
                $term,
                $atts['use_similar_page'], 
                $atts['sections'],
                $atts['language'],
                $atts['elements'],
                $atts['remove_links']
            );

            // Collect errors
            if ( is_wp_error( $elements ) ) {
                if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                    $errors[] = sprintf( 
                        __( 'Term: %s: Response: %s', 'page-generator-pro' ),
                        $term,
                        $elements->get_error_message()
                    );
                }

                continue;
            }

            // If here, we managed to fetch elements
            // Unset errors and break the loop
            unset( $errors );
            break;
        }

        // If errors exist, bail
        if ( isset( $errors ) && count( $errors ) > 0 ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'Wikipedia Shortcode Error(s):<br />%s', 'page-generator-pro' ), implode( '<br />', $errors ) );
            }

            return '';
        }

        // If a paragraph limit has been specified, apply it now
        if ( isset( $atts['paragraphs'] ) && is_numeric( $atts['paragraphs'] ) && $atts['paragraphs'] > 0 ) {
            $elements = array_slice( $elements, 0, absint( $atts['paragraphs'] ) );
        }

        // Convert elements array into string
        $content = implode( '', $elements );
        
        // Apply synonyms for spintax, if enabled
        if ( $atts['apply_synonyms'] ) {
            $content = $this->base->get_class( 'spintax' )->add_spintax( $content );
            $content = $this->base->get_class( 'spintax' )->process( $content );
        }

        // Build HTML
        $html = '<div class="' . $this->base->plugin->name . '-wikipedia">' . $content . '</div>';

        /**
         * Filter the Wikipedia Shortcode HTML output, before returning.
         *
         * @since   1.0.0
         *
         * @param   string  $html       HTML Output
         * @param   array   $atts       Shortcode Attributes
         * @param   string  $build      Wikipedia Content
         * @param   array   $elements   Wikipedia Elements in Wikipedia Article based on $atts
         */
        $html = apply_filters( 'page_generator_pro_shortcode_wikipedia', $html, $atts, $content, $elements );

        // Return
        return $html;

    }

}