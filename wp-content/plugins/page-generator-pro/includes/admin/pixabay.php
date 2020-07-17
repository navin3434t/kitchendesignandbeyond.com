<?php
/**
 * Pixabay API class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 2.2.9
 */
class Page_Generator_Pro_Pixabay extends Page_Generator_Pro_API {

    /**
     * Holds the API endpoint
     *
     * @since   2.2.9
     *
     * @var     string
     */
    public $api_endpoint = 'https://pixabay.com/api/';

    /**
     * Holds the API Key
     *
     * @since   2.2.9
     *
     * @var     string
     */
    public $api_key = '13733126-38bca84073eedea378d529ff3';

    /**
     * Returns an array of language codes and names supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Languages
     */
    public function get_languages() {

        return array(
            'all'=> __( 'Any', 'page-generator-pro' ),
            'cs' => __( 'Čeština' ),
            'da' => __( 'Dansk' ),
            'de' => __( 'Deutsch' ),
            'en' => __( 'English' ),
            'es' => __( 'Español' ),
            'fr' => __( 'Français' ),
            'id' => __( 'Indonesia' ),
            'it' => __( 'Italiano' ),
            'hu' => __( 'Magyar' ),
            'nl' => __( 'Nederlands' ),
            'no' => __( 'Norsk nynorsk' ),
            'pl' => __( 'Polski' ),
            'pt' => __( 'Português' ),
            'ro' => __( 'Română' ),
            'sk' => __( 'Slovenčina' ),
            'fi' => __( 'Suomi' ),
            'sv' => __( 'Svenska' ),
            'tr' => __( 'Türkçe' ),
            'vi' => __( 'Tiếng Việt' ),
            'th' => __( 'ไทย' ),
            'bg' => __( 'Български' ),
            'ru' => __( 'Русский' ),
            'el' => __( 'Ελληνικά' ),
            'ja' => __( '日本語' ),
            'ko' => __( '한국어' ),
            'zh' => __( '简体中文' ),
        );

    }

    /**
     * Returns an array of image types supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Image Types
     */
    public function get_image_types() {

        return array(
            'all'           => __( 'Any', 'page-generator-pro' ),
            'illustration'  => __( 'Illustration', 'page-generator-pro' ),
            'photo'         => __( 'Photo', 'page-generator-pro' ),
            'vector'        => __( 'Vector', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of video types supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Video Types
     */
    public function get_video_types() {

        return array(
            'all'       => __( 'Any', 'page-generator-pro' ),
            'animation' => __( 'Animation', 'page-generator-pro' ),
            'film'      => __( 'Film', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of video sizes supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Video Types
     */
    public function get_video_sizes() {

        return array(
            'large' => __( 'Large', 'page-generator-pro' ),
            'medium'=> __( 'Medium', 'page-generator-pro' ),
            'small' => __( 'Small', 'page-generator-pro' ),
            'tiny'  => __( 'Tiny', 'page-generator-pro' ),
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
            'all'           => __( 'Any', 'page-generator-pro' ),
            'horizontal'    => __( 'Landscape', 'page-generator-pro' ),
            'portrait'      => __( 'Portrait', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of categories supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Categories
     */
    public function get_categories() {

        return array(
            ''              => __( 'Any', 'page-generator-pro' ),
            'animals'       => __( 'Animals', 'page-generator-pro' ),
            'backgrounds'   => __( 'Backgrounds', 'page-generator-pro' ),
            'buildings'     => __( 'Buildings', 'page-generator-pro' ),
            'business'      => __( 'Business', 'page-generator-pro' ),
            'computer'      => __( 'Computer', 'page-generator-pro' ),
            'education'     => __( 'Education', 'page-generator-pro' ),
            'fashion'       => __( 'Fashion', 'page-generator-pro' ),
            'feelings'      => __( 'Feelings', 'page-generator-pro' ),
            'food'          => __( 'Foods', 'page-generator-pro' ),
            'health'        => __( 'Health', 'page-generator-pro' ),
            'industry'      => __( 'Industry', 'page-generator-pro' ),
            'music'         => __( 'Music', 'page-generator-pro' ),
            'nature'        => __( 'Nature', 'page-generator-pro' ),
            'people'        => __( 'People', 'page-generator-pro' ),
            'places'        => __( 'Places', 'page-generator-pro' ),
            'religion'      => __( 'Religion', 'page-generator-pro' ),
            'science'       => __( 'Science', 'page-generator-pro' ),
            'sports'        => __( 'Sports', 'page-generator-pro' ),
            'transportation'=> __( 'Transportation', 'page-generator-pro' ),
            'travel'        => __( 'Travel', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of image colors supported
     * by the API.
     *
     * @since   2.2.9
     *
     * @return  array   Supported Image Colors
     */
    public function get_colors() {

        return array(
            ''              => __( 'Any', 'page-generator-pro' ),
            'black'         => __( 'Black', 'page-generator-pro' ),
            'blue'          => __( 'Blue', 'page-generator-pro' ),
            'brown'         => __( 'Brown', 'page-generator-pro' ),
            'gray'          => __( 'Gray', 'page-generator-pro' ),
            'grayscale'     => __( 'Grayscale', 'page-generator-pro' ),
            'green'         => __( 'Green', 'page-generator-pro' ),
            'lilac'         => __( 'Lilac', 'page-generator-pro' ),
            'orange'        => __( 'Orange', 'page-generator-pro' ),
            'pink'          => __( 'Pink', 'page-generator-pro' ),
            'red'           => __( 'Red', 'page-generator-pro' ),
            'transparent'   => __( 'Transparent', 'page-generator-pro' ),
            'turquoise'     => __( 'Turquoise', 'page-generator-pro' ),
            'white'         => __( 'White', 'page-generator-pro' ),
            'yellow'        => __( 'Yellow', 'page-generator-pro' ),
        );

    }

    /**
     * Searches photos based on the given query
     *
     * @since   2.2.9
     *
     * @param   string  $query          Search Term(s)
     * @param   string  $language       Language ( see get_languages() for valid values )
     * @param   string  $image_type     Image Type ( see get_image_types() for valid values )
     * @param   string  $orientation    Image Orientation ( see get_image_orientations() for valid values )
     * @param   string  $category       Image Category ( see get_categories() for valid values )
     * @param   int     $min_width      Minimum Image Width 
     * @param   int     $min_height     Minimum Image Height
     * @param   string  $color          Color ( see get_colors() for valid values )
     * @param   bool    $safe_search    Safe Search
     * @param   int     $per_page       Number of Images to Return
     * @param   int     $page           Pagination Page Offset
     * @return  mixed                   WP_Error | array
     */
    public function photos_search( $query = false, $language = 'en', $image_type = 'all', $orientation = 'all', $category = false, 
        $min_width = 0, $min_height = 0, $color = false, $safe_search = false, $per_page = 20, $page = 1 ) {

        // Build array of arguments    
        $args = array(
            'key'           => $this->api_key,
            'q'             => $query,
            'lang'          => $language,
            'image_type'    => $image_type,
            'orientation'   => $orientation,
            'min_width'     => $min_width,
            'min_height'    => $min_height,
            'safe_search'   => $safe_search,
            'per_page'      => $per_page,
            'page'          => $page,
        );

        // Add optional arguments
        if ( $category != false ) {
            $args['category'] = $category;
        }
        if ( $color != false ) {
            $args['colors'] = $color;
        }

        /**
         * Filters the API arguments to send to the Pexels /search endpoint
         *
         * @since   2.2.9
         *
         * @param   array   $args           API arguments
         * @param   string  $query          Search Term(s)
         * @param   string  $language       Language ( see get_languages() for valid values )
         * @param   string  $image_type     Image Type ( see get_image_types() for valid values )
         * @param   string  $orientation    Image Orientation ( see get_image_orientations() for valid values )
         * @param   string  $category       Image Category ( see get_categories() for valid values )
         * @param   int     $min_width      Minimum Image Width 
         * @param   int     $min_height     Minimum Image Height
         * @param   string  $color          Color ( see get_colors() for valid values )
         * @param   bool    $safe_search    Safe Search
         * @param   int     $per_page       Number of Images to Return
         * @param   int     $page           Pagination Page Offset
         */
        $args = apply_filters( 'page_generator_pro_pixabay_photos_search_args', $args, $query, $language, $image_type, $orientation, $category, $min_width, $min_height, $color, $safe_search, $per_page, $page );
        
        // Run the query
        $results = $this->get( '/', $args );

        // Bail if an error occured
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Bail if no results found
        if ( ! count( $results->hits ) ) {
            return new WP_Error( 'page_generator_pro_pixabay_photos_search_no_results', __( 'No results were found.', 'page-generator-pro' ) );
        }

        // Parse results
        $images = array();
        foreach ( $results->hits as $photo ) {
            $images[] = array(
                'url'       => $photo->largeImageURL,
                'title'     => $photo->tags,
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
     * @param   string  $language       Language ( see get_languages() for valid values )
     * @param   string  $video_type     Video Type ( see get_video_types() for valid values )
     * @param   string  $category       Video Category ( see get_categories() for valid values )
     * @param   string  $size           Video Size ( see get_video_sizes() for valid values )
     * @param   bool    $safe_search    Safe Search
     * @param   int     $per_page       Number of Images to Return
     * @param   int     $page           Pagination Page Offset
     * @return  mixed                   WP_Error | array
     */
    public function videos_search( $query = false, $language = 'en', $video_type = 'all', $category = false, 
        $size = 'large', $safe_search = false, $per_page = 20, $page = 1 ) {

        // Build array of arguments    
        $args = array(
            'key'           => $this->api_key,
            'q'             => $query,
            'lang'          => $language,
            'video_type'    => $video_type,
            'safe_search'   => $safe_search,
            'per_page'      => $per_page,
            'page'          => $page,
        );

        // Add optional arguments
        if ( $category != false ) {
            $args['category'] = $category;
        }

        /**
         * Filters the API arguments to send to the Pexels /search endpoint
         *
         * @since   2.2.9
         *
         * @param   array   $args           API arguments
         * @param   string  $query          Search Term(s)
         * @param   string  $language       Language ( see get_languages() for valid values )
         * @param   string  $video_type     Video Type ( see get_video_types() for valid values )
         * @param   string  $category       Video Category ( see get_categories() for valid values )
         * @param   string  $size           Video Size ( see get_video_sizes() for valid values )
         * @param   bool    $safe_search    Safe Search
         * @param   int     $per_page       Number of Images to Return
         * @param   int     $page           Pagination Page Offset
         */
        $args = apply_filters( 'page_generator_pro_pixabay_videos_search_args', $args, $query, $language, $video_type, $category, $size, $safe_search, $per_page, $page );
        
        // Run the query
        $results = $this->get( '/videos/', $args );

        // Bail if an error occured
        if ( is_wp_error( $results ) ) {
            return $results;
        }

        // Parse results
        $videos = array();
        foreach ( $results->hits as $video ) {
            // Add to videos results and break the loop
            $videos[] = array(
                'url'   => $video->videos->{ $size }->url,
                'title' => $video->tags,
            );
        }

        // Return array of videos
        return $videos;

    }

}