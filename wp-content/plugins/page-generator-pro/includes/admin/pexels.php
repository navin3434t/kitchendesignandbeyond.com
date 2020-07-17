<?php
/**
 * Pexels API class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 2.2.9
 */
class Page_Generator_Pro_Pexels extends Page_Generator_Pro_API {

    /**
     * Holds the API endpoint
     *
     * @since   2.2.9
     *
     * @var     string
     */
    public $api_endpoint = 'https://api.pexels.com/';

    /**
     * Holds the API Key
     *
     * @since   2.2.9
     *
     * @var     string
     */
    public $api_key = '563492ad6f9170000100000113545ff30aa14515888b11a213970c6f';

    /**
     * Returns an array of video sizes supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Video Types
     */
    public function get_image_sizes() {

        return array(
            'original'  => __( 'Full Size Original', 'page-generator-pro' ),
            'large2x'   => __( 'Large 2x', 'page-generator-pro' ),
            'large'     => __( 'Large', 'page-generator-pro' ),
            'medium'    => __( 'Medium', 'page-generator-pro' ),
            'small'     => __( 'Small', 'page-generator-pro' ),
            'tiny'      => __( 'Tiny', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of image orientations supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Image Orientations
     */
    public function get_image_orientations() {

        return array(
            0           => __( 'Any', 'page-generator-pro' ),
            'portrait'  => __( 'Portrait', 'page-generator-pro' ),
            'landscape' => __( 'Landscape', 'page-generator-pro' ), 
        );

    }


    /**
     * Searches photos based on the given query
     *
     * @since   2.2.9
     *
     * @param   string  $query          Search Term(s)
     * @param   string  $size           Image Size (original, large, large2x, medium, small, tiny)
     * @param   mixed   $orientation    Image Orientation (false, portrait, landscape)
     * @param   int     $per_page       Number of Images to Return
     * @param   int     $page           Pagination Page Offset
     * @return  mixed                   WP_Error | array
     */
    public function photos_search( $query = false, $size = 'original', $orientation = false, $per_page = 15, $page = 1 ) {

        // Set HTTP headers
        $this->set_headers();

        // Build array of arguments    
        $args = array(
            'query'     => $query,
            'per_page'  => $per_page,
            'page'      => $page,
        );
        
        /**
         * Filters the API arguments to send to the Pexels /search endpoint
         *
         * @since   2.2.9
         *
         * @param   array   $args           API arguments
         * @param   string  $query          Search Term(s)
         * @param   string  $size           Image Size (original, large, large2x, medium, small, portrait, landscape, tiny)
         * @param   int     $per_page       Number of Images to Return
         * @param   int     $page           Pagination Page Offset
         */
        $args = apply_filters( 'page_generator_pro_pexels_photos_search_args', $args, $query, $size, $orientation, $per_page, $page );
        
        // Run the query
        $results = $this->get( 'v1/search', $args );

        // Bail if an error occured
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Bail if no results were found
        if ( ! $results->total_results ) {
            return new WP_Error( 'page_generator_pro_pexels_photos_search', __( 'No images were found for the given search criteria.', 'page-generator-pro' ) );
        }

        // Determine whether to fetch by orientation or size
        $photo_type = ( $orientation != false ? $orientation : $size ); 

        // Parse results
        $images = array();
        foreach ( $results->photos as $photo ) {
            $images[] = array(
                'url'       => $photo->src->{ $photo_type }, // original, large, large2x, medium, small, portrait, landscape, tiny
                'title'     => $photo->photographer,
            );
        }

        // Return array of images
        return $images;

    }

    /**
     * Searches videos based on the given query
     *
     * @since   2.2.9
     *
     * @param   string  $query          Search Term(s)
     * @param   int     $width          Video Width (640,960,1280,2560)
     * @param   int     $height         Video Height (360,540,720,1440)
     * @param   int     $min_duration   Minimum Duration
     * @param   int     $max_duration   Maximum Duration
     * @param   int     $per_page       Number of Videos to Return
     * @param   int     $page           Pagination Page Offset
     * @return  mixed                   WP_Error | array
     */
    public function videos_search( $query = false, $width = 1280, $height = 720, $min_duration = false, $max_duration = false, $per_page = 15, $page = 1 ) {

        // Set HTTP Headers
        $this->set_headers();

        // Build array of arguments    
        $args = array(
            'query'     => $query,
            'per_page'  => $per_page,
            'page'      => $page,
        );

        // Add optional arguments
        if ( $min_duration != false && is_numeric( $min_duration ) ) {
            $args['min_duration'] = $min_duration;
        }
        if ( $max_duration != false && is_numeric( $max_duration ) ) {
            $args['max_duration'] = $max_duration;
        }
        
        /**
         * Filters the API arguments to send to the Pexels /search endpoint
         *
         * @since   2.2.9
         *
         * @param   array   $args           API arguments
         * @param   string  $query          Search Term(s)
         * @param   int     $width          Video Width (640,960,1280,2560)
         * @param   int     $height         Video Height (360,540,720,1440)
         * @param   int     $min_duration   Minimum Duration
         * @param   int     $max_duration   Maximum Duration
         * @param   int     $per_page       Number of Videos to Return
         * @param   int     $page           Pagination Page Offset
         */
        $args = apply_filters( 'page_generator_pro_pexels_videos_search_args', $args, $query, $width, $height, $min_duration, $max_duration, $per_page, $page );
        
        // Run the query
        $results = $this->get( 'videos/search', $args );

        // Bail if an error occured
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Parse results
        $videos = array();
        foreach ( $results->videos as $video ) {
            // Get video matching the required width
            foreach ( $video->video_files as $video_file ) {
                // Skip non-mp4 videos
                if ( $video_file->file_type != 'video/mp4' ) {
                    continue;
                }

                // Skip video if it doesn't match the required width and height
                if ( $video_file->width != $width ) {
                    continue;
                }
                if ( $video_file->height != $height ) {
                    continue;
                }

                // Add to videos results and break the loop
                $videos[] = array(
                    'url'       => $video_file->link,

                    // Set width and height parameters for image still
                    'image_url' => add_query_arg( array(
                            'w' => $width,
                            'h' => $height,
                        ), remove_query_arg( array(
                            'w',
                            'h',
                        ), $video->image )
                    ),

                    'title' => $video->user->name,
                );
                break;
            }
        }

        // Return array of videos
        return $videos;

    }

    /**
     * Sets the headers to include in the request
     *
     * @since   2.2.9
     *
     * @return  array   Headers
     */
    private function set_headers() {

        $this->headers = array(
            'Authorization' => $this->api_key,
        ); 

    }

}