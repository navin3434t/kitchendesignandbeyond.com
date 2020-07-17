<?php
/**
 * Creative Commons API class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 2.6.9
 */
class Page_Generator_Pro_Creative_Commons extends Page_Generator_Pro_API {

    /**
     * Holds the API endpoint
     *
     * @since   2.6.9
     *
     * @var     string
     */
    public $api_endpoint = 'https://api.creativecommons.engineering/';

    /**
     * Returns an array of aspect ratios (orientations) supported
     * by the API.
     *
     * @since   2.6.9
     *
     * @return  array   Supported Image Orientations
     */
    public function get_image_orientations() {

        return array(
            0       => __( 'Any', 'page-generator-pro' ),
            'tall'  => __( 'Portrait', 'page-generator-pro' ),
            'wide'  => __( 'Landscape', 'page-generator-pro' ), 
            'square'=> __( 'Square', 'page-generator-pro' ), 
        );

    }


    /**
     * Searches photos based on the given query
     *
     * @since   2.6.9
     *
     * @param   string  $query          Search Term(s)
     * @param   string  $size           Image Size (original, large, large2x, medium, small, tiny)
     * @param   mixed   $orientation    Image Orientation (false, tall, wide, square)
     * @param   int     $per_page       Number of Images to Return
     * @param   int     $page           Pagination Page Offset
     * @return  mixed                   WP_Error | array
     */
    public function photos_search( $query = false, $orientation = false, $per_page = 20, $page = 1 ) {

        // Set HTTP headers
        $this->set_headers();

        // Build array of arguments    
        $args = array(
            'q'             => $query,
            'license'       => implode( ',', array( 'BY-ND', 'BY-NC', 'PDM', 'BY-NC-ND', 'CC0', 'BY-SA', 'BY', 'BY-NC-SA' ) ),
            'license_type'  => implode( ',', array( 'all', 'all-cc', 'commercial', 'modification' ) ),
            'page_size'     => $per_page,
            'page'          => $page,
            'orientation'   =>  ( $orientation ? $orientation : 'tall,wide,square' ),
        );

        /**
         * Filters the API arguments to send to the Creative Commons /images endpoint
         *
         * @since   2.6.9
         *
         * @param   array   $args           API arguments
         * @param   string  $query          Search Term(s)
         * @param   string  $size           Image Size (original, large, large2x, medium, small, tiny)
         * @param   mixed   $orientation    Image Orientation (false, tall, wide, square)
         * @param   int     $per_page       Number of Images to Return
         * @param   int     $page           Pagination Page Offset
         */
        $args = apply_filters( 'page_generator_pro_creative_commons_photos_search_args', $args, $query, $orientation, $per_page, $page );
        
        // Run the query
        $results = $this->get( 'v1/images', $args );

        // Bail if an error occured
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Bail if no results were found
        if ( ! $results->result_count ) {
            return new WP_Error( 'page_generator_pro_creative_commons_photos_search', __( 'No images were found for the given search criteria.', 'page-generator-pro' ) );
        }

        // Parse results
        $images = array();
        foreach ( $results->results as $photo ) {
            $images[] = array(
                'url'       => $photo->url,
                'title'     => $photo->title,
            );
        }

        // Return array of images
        return $images;

    }

    /**
     * Sets the headers to include in the request
     *
     * @since   2.6.9
     *
     * @return  array   Headers
     */
    private function set_headers() {

        $this->headers = array(
            'Content-Type' => 'application/json',
        ); 

    }

}