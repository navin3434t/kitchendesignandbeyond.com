<?php
/**
 * Yelp Shortcode/Block class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.5.1
 */
class Page_Generator_Pro_Shortcode_Yelp {

    /**
     * Holds the base object.
     *
     * @since   2.5.1
     *
     * @var     object
     */
    public $base;

    /**
     * Flag denoting if we've output the required Yelp logo
     * if a Yelp shortcode was used.
     *
     * This prevents outputting it multiple times when
     * the shortcode is used more than once
     *
     * @since   2.5.1
     *
     * @var     bool
     */
    public $yelp_logo_output = false;

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

        return 'yelp';

    }

    /**
     * Returns this shortcode / block's Title, Icon, Categories, Keywords
     * and properties for registering on generation and requiring CSS/JS.
     *
     * @since   2.5.1
     */
    public function get_overview() {

        return array(
            'title'     => __( 'Yelp', $this->base->plugin->name ),
            'description'   => __( 'Displays business listings from Yelp based on the given search parameters.', $this->base->plugin->name ),
            'icon'      => file_get_contents( $this->base->plugin->folder . '/_modules/dashboard/feather/yelp.svg' ),
            'category'  => $this->base->plugin->name,
            'keywords'  => array(
                __( 'Yelp', $this->base->plugin->name ),
            ),

            // Register when Generation is running only
            'register_on_generation_only' => true,

            // Requires CSS and/or JS for output
            'requires_css' => true,
            'requires_js' => false,

            // Function to call when rendering the shortcode on the frontend
            'render_callback' => array( 'shortcode_yelp', 'render' ),
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
                'label'                 => __( 'Term', $this->base->plugin->name ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. restaurants', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'location'       => array(
                'label'                 => __( 'Location', $this->base->plugin->name ),
                'type'                  => 'text',
                'placeholder'           => __( 'e.g. Birmingham, UK', 'page-generator-pro' ),
                'class'                 => 'wpzinc-autocomplete',
            ),
            'radius'       => array(
                'label'                 => __( 'Radius', $this->base->plugin->name ),
                'type'                  => 'number',
                'min'                   => 1,
                'max'                   => 20,
                'step'                  => 1,
                'default_value'         => $this->get_default_value( 'radius' ),
                'description'           => __( 'The maximum radius, in miles, from the Location to search Business Listings for.', 'page-generator-pro' ),
            ),
            'minimum_rating'       => array(
                'label'                 => __( 'Minimum Rating', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'yelp' )->get_rating_options(),
                'default_value'         => $this->get_default_value( 'minimum_rating' ),
                'description'           => __( 'The minimum rating a business listing must have to be displayed.', 'page-generator-pro' ),
            ),
            'locale'       => array(
                'label'                 => __( 'Language', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'yelp' )->get_locales(),
                'default_value'         => $this->get_default_value( 'locale' ),
            ),
            'price'       => array(
                'label'                 => __( 'Price', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'yelp' )->get_price_options(),
                'default_value'         => $this->get_default_value( 'price' ),
            ),
            'limit'       => array(
                'label'                 => __( 'Number of Listings', $this->base->plugin->name ),
                'type'                  => 'number',
                'min'                   => 1,
                'max'                   => 50,
                'step'                  => 1,
                'default_value'         => $this->get_default_value( 'limit' ),
            ),
            'sort_by'       => array(
                'label'                 => __( 'Sort Listings', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => $this->base->get_class( 'yelp' )->get_sort_by_options(),
                'default_value'         => $this->get_default_value( 'sort_by' ),
            ),
            'image'       => array(
                'label'                 => __( 'Image', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => array(
                    1 => __( 'Show', 'page-generator-pro' ),
                    0 => __( 'Hide', 'page-generator-pro' ),
                ),
                'default_value'         => $this->get_default_value( 'image' ),
            ),
            'image_alt_tag'       => array(
                'label'                 => __( 'Image Alt Tag', $this->base->plugin->name ),
                'type'                  => 'text',
                'default_value'         => $this->get_default_value( 'image_alt_tag' ),
            ),
            'rating'       => array(
                'label'                 => __( 'Rating', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => array(
                    1 => __( 'Show', 'page-generator-pro' ),
                    0 => __( 'Hide', 'page-generator-pro' ),
                ),
                'default_value'         => $this->get_default_value( 'rating' ),
            ),
            'categories'       => array(
                'label'                 => __( 'Categories', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => array(
                    1 => __( 'Show', 'page-generator-pro' ),
                    0 => __( 'Hide', 'page-generator-pro' ),
                ),
                'default_value'         => $this->get_default_value( 'categories' ),
            ),
            'phone'       => array(
                'label'                 => __( 'Phone Number', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => array(
                    1 => __( 'Show', 'page-generator-pro' ),
                    0 => __( 'Hide', 'page-generator-pro' ),
                ),
                'default_value'         => $this->get_default_value( 'phone' ),
            ),
            'address'       => array(
                'label'                 => __( 'Address', $this->base->plugin->name ),
                'type'                  => 'select',
                'values'                => array(
                    1 => __( 'Show', 'page-generator-pro' ),
                    0 => __( 'Hide', 'page-generator-pro' ),
                ),
                'default_value'         => $this->get_default_value( 'address' ),
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
                'description'   => __( 'Defines search query parameters to fetch business listings from Yelp.', 'page-generator-pro' ),
                'class'         => 'link',
                'fields'        => array(
                    'term',
                    'location',
                    'radius',
                    'minimum_rating',
                    'locale',
                    'price',
                    'limit',
                    'sort_by',
                ),
            ),
            'output' => array(
                'label'         => __( 'Output', 'page-generator-pro' ),
                'description'   => __( 'Defines what to output for each Yelp business listing.', 'page-generator-pro' ),
                'class'         => 'tag',
                'fields'    => array(
                    'image',
                    'image_alt_tag',
                    'rating',
                    'categories',
                    'phone',
                    'address', 
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
            'term'          => '',
            'location'      => '',
            'radius'        => 0,
            'minimum_rating'=> 0,
            'locale'        => 'en_US', // get_locale() may return 'en' which is not valid for Yelp
            'price'         => 0,
            'limit'         => 5,
            'sort_by'       => '',

            // Output
            'image'         => 1,
            'image_alt_tag' => '%business_name',
            'rating'        => 1,
            'categories'    => 1,
            'phone'         => 1,
            'address'       => 1,
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
        
        // Copy shortcode attributes to Yelp API arguments, removing some unused keys
        $args = array();
        $api_keys = array(
            'term',
            'location',
            'radius',
            'minimum_rating',
            'locale',
            'price',
            'limit',
            'sort_by',
        );
        foreach ( $api_keys as $api_key ) {
            if ( ! isset( $atts[ $api_key ] ) ) {
                continue;
            }
            if ( ! $atts[ $api_key ] ) {
                continue;
            }

            $args[ $api_key ] = $atts[ $api_key ];
        }

        // Send request to Yelp API
        $results = $this->base->get_class( 'yelp' )->businesses_search( $args );

        // Check for errors from the Yelp API
        if ( is_wp_error( $results ) ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return sprintf( __( 'Yelp Shortcode Error: %s', 'page-generator-pro' ), $results->get_error_message() );
            }

            // Just return a blank string
            return '';
        }

        // Check if any businesses were found
        if ( ! $results ) {
            if ( defined( 'PAGE_GENERATOR_PRO_DEBUG' ) && PAGE_GENERATOR_PRO_DEBUG === true ) {
                return __( 'Yelp Shortcode Error: No businesses found', 'page-generator-pro' );
            }

            return '';
        }

        // Iterate through results, building HTML
        $html = '<div class="' . $this->base->plugin->name . '-yelp">';
        foreach ( $results as $count => $business ) {

            $html .= '
            <div class="business">
                <div class="name">' . $business->name . '</div>';

            // Display Image, if enabled
            if ( $atts['image'] == 1 ) { 
                $html .= '
                    <div class="image">
                        <img src="' . $business->image_url . '" alt="' . $this->replace_yelp_variables( $atts['image_alt_tag'], $business ) . '" />
                    </div>';
            }

            // Display Rating, if enabled
            if ( $atts['rating'] == 1 ) {
                $html .= '
                    <div class="rating">
                        <div class="rating-stars rating-stars-' . str_replace( '.', '-', $business->rating ) . '"></div>
                        ' . $business->review_count . ' ' . ( $business->review_count == 1 ? __( 'review', 'page-generator-pro' ) : __( 'reviews', 'page-generator-pro' ) ) . '
                    </div>';
            }

            // Display Categories, if enabled
            if ( $atts['categories'] == 1 ) {
                $html .= '<div class="categories">';
                
                $total_categories = count( $business->categories );
                foreach ( $business->categories as $cCount => $category ) {
                    $html .= $category->title;
                    if ( ( $cCount + 1 ) != $total_categories ) {
                        $html .= ', ';
                    }
                }
                    
                $html .= '</div>';
            }

            // Display Phone Number, if enabled
            if ( $atts['phone'] == 1 ) {
                $html .= '<div class="phone">' . $business->phone . '</div>';
            }

            // Display Address, if enabled
            if ( $atts['address'] == 1 ) {
                $html .= '<div class="address">';
            
                // Address
                $total_address_lines = count( $business->location->display_address );
                foreach ( $business->location->display_address as $aCount => $address ) {
                    $html .= $address;
                    if ( ( $aCount + 1 ) != $total_address_lines ) {
                        $html .= ', ';
                    }
                }
                
                $html .= '</div>'; 
            }
            
            $html .= '</div>';
            
            // Check if limit reached
            if ( ( $count + 1 ) == $atts['limit'] ) {
                break;
            }
        }
        
        /**
         * Filter the Yelp Shortcode HTML output, before returning.
         *
         * @since   1.0.0
         *
         * @param   string  $html   HTML Output
         * @param   array   $atts   Shortcode Attributes
         */
        $html = apply_filters( 'page_generator_pro_shortcode_yelp', $html, $atts );

        // Add Yelp logo, if we haven't yet output it.
        // This is required to meet the display requirements below, which is why this is done after filtering
        // http://www.yelp.co.uk/developers/getting_started/display_requirements
        if ( ! $this->yelp_logo_output ) {
            $html .= '<a href="https://www.yelp.com" rel="nofollow noreferrer noopener" target="_blank"><img src="https://s3-media1.ak.yelpcdn.com/assets/2/www/img/55e2efe681ed/developers/yelp_logo_50x25.png" /></a>';
            $this->yelp_logo_output = true;
        }

        $html .= '</div>';

        // Return
        return $html;

    }

    /**
     * Replaces Post variables with the Post's data.
     *
     * @since   2.6.3
     *
     * @param   string  $text       Text
     * @param   object  $business   Yelp Business Listing
     * @return  string              Text
     */
    private function replace_yelp_variables( $text, $business ) {

        // Build categories
        $categories = array();
        if ( isset( $business->categories ) && is_array( $business->categories )  && count( $business->categories ) > 0 ) {
            foreach ( $business->categories as $category ) {
                $categories[] = $category->title;
            }
        }

        // Define search and replacements
        $searches = array(
            '%business_name%',
            '%business_address1%',
            '%business_address2%',
            '%business_address3%',
            '%business_city%',
            '%business_zip_code%',
            '%business_country%',
            '%business_state%',
            '%business_display_address%',
            '%business_phone%',
            '%business_display_phone%',
            '%business_distance%',
            '%business_categories%',
        );

        $replacements = array(
            $business->name,
            ( isset( $business->location->address1 ) ? $business->location->address1 : '' ),
            ( isset( $business->location->address2 ) ? $business->location->address2 : '' ),
            ( isset( $business->location->address3 ) ? $business->location->address3 : '' ),
            ( isset( $business->location->city ) ? $business->location->city : '' ),
            ( isset( $business->location->zip_code ) ? $business->location->zip_code : '' ),
            ( isset( $business->location->country ) ? $business->location->country : '' ),
            ( isset( $business->location->state ) ? $business->location->state : '' ),
            implode( ', ', $business->location->display_address ),
            $business->phone,
            $business->display_phone,
            $business->distance,
            implode( ', ', $categories ),
        );

        // Perform search and replace
        $text = str_ireplace( $searches, $replacements, $text );

        // Return
        return $text;

    }

}