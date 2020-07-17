<?php
/**
 * Pexels Shortcode/Block class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.5.1
 */
class Page_Generator_Pro_Shortcode_Pexels {

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

        return 'pexels';

    }

    /**
     * Returns this shortcode / block's Title, Icon, Categories, Keywords
     * and properties for registering on generation and requiring CSS/JS.
     *
     * @since   2.5.1
     */
    public function get_overview() {

        return array(
            'title'     => __( 'Pexels', $this->base->plugin->name ),
            'description'   => __( 'Displays an image from Pexels, based on the given search parameters.', $this->base->plugin->name ),
            'icon'      => file_get_contents( $this->base->plugin->folder . '/assets/images/icons/pexels.svg' ),
            'category'  => $this->base->plugin->name,
            'keywords'  => array(
                __( 'Pexels', $this->base->plugin->name ),
                __( 'Dynamic Image', $this->base->plugin->name ),
                __( 'Image', $this->base->plugin->name ),
            ),

            // Register when Generation is running only
            'register_on_generation_only' => true,

            // Requires CSS and/or JS for output
            'requires_css'  => false,
            'requires_js'   => false,

            // Function to call when rendering the shortcode on the frontend
            'render_callback' => array( 'shortcode_pexels', 'render' ),
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
            // Search Parameters
            'term' => array(
                'label'                 => __( 'Term', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
                'description'           => __( 'The search term to use.  For example, "laptop" would return an image of a laptop.', 'page-generator-pro' ),
            ),
            'size' => array(
                'label'                 => __( 'Image Size', 'page-generator-pro' ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'pexels' )->get_image_sizes(),
                'default_value'         => $this->get_default_value( 'size' ),
                'description'           => __( 'The image size to output.', 'page-generator-pro' ),
            ),
            'orientation' => array(
                'label'                 => __( 'Image Orientation', 'page-generator-pro' ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'pexels' )->get_image_orientations(),
                'default_value'         => $this->get_default_value( 'orientation' ),
                'description'           => __( 'The image orientation to output.', 'page-generator-pro' ),
            ),

            // Output
            'title' => array(
                'label'                 => __( 'Title', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
                'description'           => __( 'Define the title for the image.', 'page-generator-pro' ),
            ),
            'caption' => array(
                'label'                 => __( 'Caption', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
                'description'           => __( 'Define the caption for the image.', 'page-generator-pro' ),
            ),
            'alt_tag' => array(
                'label'                 => __( 'Alt Tag', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
                'description'           => __( 'Define the alt text for the image.', 'page-generator-pro' ),
            ),
            'description' => array(
                'label'                 => __( 'Description', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
                'description'           => __( 'Define the description for the image.', 'page-generator-pro' ),
            ),
            'filename' => array(
                'label'                 => __( 'Filename', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
                'description'           => __( 'Define the filename for the image, excluding the extension.', 'page-generator-pro' ),
            ),

            // Link
            'link_href' => array(
                'label'                 => __( 'Link', 'page-generator-pro' ),
                'type'                  => 'text',
                'description'           => __( 'Define the link for the image. Leave blank for no link.', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'link_title' => array(
                'label'                 => __( 'Link Title', 'page-generator-pro' ),
                'type'                  => 'text',
                'description'           => __( 'Define the link title for the image.', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'link_rel' => array(
                'label'                 => __( 'Link Rel', 'page-generator-pro' ),
                'type'                  => 'text',
                'description'           => __( 'Define the link rel attribute for the image.', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'link_target' => array(
                'label'                 => __( 'Link Target', 'page-generator-pro' ),
                'type'                  => 'select',
                'description'           => __( 'Define the link target for the image.', 'page-generator-pro' ),
                'values'                => $this->base->get_class( 'common' )->get_link_target_options(),
                'default_value'         => $this->get_default_value( 'link_target' ),
            ),

            // EXIF
            'exif_latitude' => array(
                'label'                 => __( 'Latitude', 'page-generator-pro' ),
                'type'                  => 'text',
                'class'                 => 'wpzinc-autocomplete',
            ),
            'exif_longitude' => array(
                'label'                 => __( 'Longitude', 'page-generator-pro' ),
                'type'                  => 'text',
                'class'                 => 'wpzinc-autocomplete',
            ),
            'exif_comments' => array(
                'label'                 => __( 'Comments', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'exif_description' => array(
                'label'                 => __( 'Description', 'page-generator-pro' ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. building', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
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
            'search-parameters' => array(
                'label'         => __( 'Search Parameters', 'page-generator-pro' ),
                'description'   => __( 'Defines search query parameters to fetch an image at random from Pexels.', 'page-generator-pro' ),
                'class'         => 'link',
                'fields'        => array(
                    'term',
                    'size',
                    'orientation',
                ),
            ),
            'output' => array(
                'label'         => __( 'Output', 'page-generator-pro' ),
                'description'   => __( 'Defines output parameters for the Pexel image.', 'page-generator-pro' ),
                'class'         => 'tag',
                'fields'    => array(
                    'title',
                    'alt_tag',
                    'caption',
                    'description',
                    'filename',   
                ),
            ),
            'link' => array(
                'label'         => __( 'Link', 'page-generator-pro' ),
                'description'   => __( 'Defines parameters for linking the Pexels image.', 'page-generator-pro' ),
                'class'         => 'link',
                'fields'    => array(
                    'link_href',
                    'link_title',
                    'link_rel',
                    'link_target',
                ),
            ),
            'exif' => array(
                'label'         => __( 'EXIF', 'page-generator-pro' ),
                'description'   => __( 'Defines EXIF metadata to store in the image.', 'page-generator-pro' ),
                'class'         => 'aperture',
                'fields'    => array(
                    'exif_latitude',
                    'exif_longitude',
                    'exif_comments',
                    'exif_description',
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
            // Search Parameters
            'term'          => false,
            'size'          => 'full',
            'orientation'   => false,

            // Output
            'title'         => false,
            'caption'       => false,
            'alt_tag'       => false,
            'description'   => false,
            'filename'      => false,

            // Link
            'link_href'         => false,
            'link_title'        => false,
            'link_rel'          => false,
            'link_target'       => '_self',

            // EXIF
            'exif_description'  => false,
            'exif_comments'     => false,
            'exif_latitude'     => false,
            'exif_longitude'    => false,
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

        // If a Pexels API Key has been specified, use it instead of the class default.
        $api_key = $this->base->get_class( 'settings' )->get_setting( $this->base->plugin->name . '-pexels', 'api_key' );
        if ( ! empty( $api_key ) ) {
            $this->base->get_class( 'pexels' )->set_api_key( $api_key );
        }

        // Run images query
        $images = $this->base->get_class( 'pexels' )->photos_search( $atts['term'], $atts['size'], $atts['orientation'] );
        if ( is_wp_error( $images ) ) {
            // Couldn't fetch an image, so don't show an image at all.
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'Pexels Shortcode Error: %s', 'page-generator-pro' ), $images->get_error_message() );
            }

            return '';
        }

        // Pick an image at random from the resultset
        if ( count( $images ) == 1 ) {
            $image_index = 0;
        } else {
            $image_index = rand( 0, ( count( $images ) - 1 ) );
        }   

        // Import the image
        $image_id = $this->base->get_class( 'import' )->import_remote_image( 
            $images[ $image_index ]['url'], 
            0,
            $this->base->get_class( 'shortcode' )->get_group_id(),
            $this->base->get_class( 'shortcode' )->get_index(),
            $atts['filename'],
            ( ! $atts['title'] ? $images[ $image_index ]['title'] : $atts['title'] ), // title
            ( ! $atts['caption'] ? $images[ $image_index ]['title'] : $atts['caption'] ), // caption
            ( ! $atts['alt_tag'] ? $images[ $image_index ]['title'] : $atts['alt_tag'] ), // alt_tag
            ( ! $atts['description'] ? $images[ $image_index ]['title'] : $atts['description'] ) // description
        );

        // Bail if an error occured
        if ( is_wp_error( $image_id ) ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'Pexels Shortcode Error: %s', 'page-generator-pro' ), $image_id->get_error_message() );
            }

            return '';
        }

        // Store EXIF Data in Image
        $exif = $this->base->get_class( 'exif' )->write(
            $image_id,
            $atts['exif_description'],
            $atts['exif_comments'],
            $atts['exif_latitude'],
            $atts['exif_longitude']
        );

        if ( is_wp_error( $exif ) ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'Pexels Shortcode Error: %s', 'page-generator-pro' ), $exif->get_error_message() );
            }

            // Allow processing to continue as we can output an image; we just couldn't store the EXIF data
        }

        // Get img tag
        $html = wp_get_attachment_image( $image_id, 'full' );

        // If a link is specified, wrap the image in the link now
        if ( ! empty( $atts['link_href'] ) ) {
            $link = '<a href="' . $atts['link_href'] . '"';

            // Add title, if specified
            if ( ! empty( $atts['link_title'] ) ) {
                $link .= ' title="' . $atts['link_title'] . '"';
            }

            // Add rel attribute, if specified
            if ( ! empty( $atts['link_rel'] ) ) {
                $link .= ' rel="' . $atts['link_rel'] . '"';
            }

            // Add target, if specified
            if ( ! empty( $atts['link_target'] ) ) {
                $link .= ' target="' . $atts['link_target'] . '"';
            }

            $link .= '>';

            $html = $link . $html . '</a>';
        }

        /**
         * Filter the Pexels HTML output, before returning.
         *
         * @since   1.0.0
         *
         * @param   string  $html       HTML Output
         * @param   array   $atts       Shortcode Attributes
         * @param   int     $image_id   WordPress Media Library Image ID
         * @param   array   $images     Pexels Image Results
         */
        $html = apply_filters( 'page_generator_pro_shortcode_pexels', $html, $atts, $image_id, $images );

        // Return
        return $html;

    }

}