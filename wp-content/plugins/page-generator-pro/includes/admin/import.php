<?php
/**
 * Importer class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 1.1.8
 */
class Page_Generator_Pro_Import {

    /**
     * Holds the base object.
     *
     * @since   1.9.8
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor.
     *
     * @since   1.9.8
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Import
        add_action( 'page_generator_pro_import', array( $this, 'import' ), 10, 2 );

    }

    /**
     * Import data created by this Plugin's export functionality
     *
     * @since   2.6.8
     *
     * @param   bool    $success    Success
     * @param   array   $import     Array
     * @return  mixed               WP_Error | bool
     */
    public function import( $success, $import ) {

        // Fetch data
        $data = $import['data'];

        // Keywords
        if ( isset( $data['keywords'] ) ) {
            foreach ( $data['keywords'] as $keyword ) {
                // Create keyword
                $this->base->get_class( 'keywords' )->save( $keyword );
            }
        }

        // Groups
        if ( isset( $data['groups'] ) && is_array( $data['groups'] ) ) {
            // Determine whether the Groups data is from 1.2.2 or earlier (where we didn't use a CPT for Groups),
            // or is 1.2.3+ (where we use a CPT for Groups)
            foreach ( $data['groups'] as $group ) {
                // If a groupID key exists, this is from 1.2.2 or older
                if ( isset( $group['groupID'] ) ) {
                    // Import is from <= 1.2.2
                    $args = array(
                        'post_type'     => $this->base->get_class( 'post_type' )->post_type_name,
                        'post_status'   => 'publish',
                        'post_title'    => $group['settings']['title'],
                        'post_content'  => $group['settings']['content'],
                    );
                    $settings = $group['settings'];
                } else {
                    // Import is from >= 1.2.3
                    $args = array(
                        'post_type'     => $this->base->get_class( 'post_type' )->post_type_name,
                        'post_status'   => 'publish',
                        'post_title'    => $group['title'],
                        'post_content'  => $group['content'],
                    );
                    $settings = $group;
                }

                // Create group
                $id = wp_insert_post( $args );

                // Skip if something went wrong
                if ( is_wp_error( $id ) ) {
                    continue;
                }

                // Save group settings
                $this->base->get_class( 'groups' )->save( $settings, $id ); 

                // If the group has a post_meta key, store it against the Group
                if ( isset( $group['post_meta'] ) && is_array( $group['post_meta'] ) ) {
                    foreach ( $group['post_meta'] as $meta_key => $meta_values ) {
                        update_post_meta( $id, $meta_key, $meta_values );
                    }
                }
            }
        }

        // Terms
        if ( isset( $data['terms'] ) && is_array( $data['terms'] ) ) {
            foreach ( $data['terms'] as $group ) {
                // Create group
                $id = wp_insert_term( $group['title'], $this->base->get_class( 'taxonomy' )->taxonomy_name, array(
                    'slug'          => $group['permalink'],
                    'description'   => $group['excerpt'],
                    'parent'        => $group['parent_term'],
                ) );
                
                // Skip if something went wrong
                if ( is_wp_error( $id ) ) {
                    continue;
                }

                // Save group settings
                $this->base->get_class( 'groups_terms' )->save( $group, $id ); 
            }
        }

        // Settings: General
        if ( isset( $data['general'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-general', $data['general'] );
        }
        
        // Settings: Google
        if ( isset( $data['google'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-google', $data['google'] );
        }

        // Settings: Generate
        if ( isset( $data['generate'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-generate', $data['generate'] );
        }

        // Settings: Georocket
        if ( isset( $data['georocket'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-georocket', $data['georocket'] );
        }

        // Settings: OpenWeatherMap
        if ( isset( $data['open_weather_map'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-open-weather-map', $data['open-weather-map'] );
        }

        // Settings: Pexels
        if ( isset( $data['pexels'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-pexels', $data['pexels'] );
        }

        // Settings: Pixabay
        if ( isset( $data['pixabay'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-pixabay', $data['pixabay'] );
        }

        // Settings: Spintax
        if ( isset( $data['spintax'] ) ) {
            $this->base->get_class( 'settings' )->update_settings( $this->base->plugin->name . '-spintax', $data['spintax'] );
        }

    }

    /**
     * Imports a remote image into the WordPress Media Library
     *
     * @since   1.1.8
     *
     * @param   string  $source     Source URL
     * @param   int     $post_id    Post ID
     * @param   int     $group_id   Group ID
     * @param   int     $index      Generation Index
     * @param   string  $filename   Target Filename to save source as
     * @param   string  $title      Image Title (optional)
     * @param   string  $caption    Image Caption (optional)
     * @param   string  $alt        Image Alt Tag (optional)
     * @return  mixed               Image ID | WP_Error
     */
    public function import_remote_image( $source, $post_id = 0, $group_id = 0, $index = 0, $filename = false, $title = '', $caption = '', $alt_tag = '', $description = '' ) {

        // If GD support is available, enable it now
        if ( $this->is_gd_available() ) {
            add_filter( 'wp_image_editors', array( $this, 'enable_gd_image_support' ) );
        }

        // Import the remote image
        if ( ! function_exists( 'media_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
        }

        // Get the remote image
        $tmp = download_url( $source );
        if ( is_wp_error( $tmp ) ) {
            return $tmp;
        }

        // Get image type
        $type = getimagesize( $tmp );
        if ( ! isset( $type['mime'] ) ) {
            return new WP_Error( __( 'Could not identify MIME type of imported image.', 'page-generator-pro' ) );
        }
        list( $type, $ext ) = explode( '/', $type['mime'] );
        unset( $type );

        // Define image filename
        $file_array['name']     = ( $filename != false ? $filename : basename( $source ) );
        $file_array['tmp_name'] = $tmp;

        // Add the extension to the filename if it doesn't exist
        // This happens if we streamed an image URL e.g. http://placehold.it/400x400
        if ( strpos( $file_array['name'], '.' . $ext ) === false ) {
            $file_array['name'] .= '.' . $ext;
        }

        // Import the image into the Media Library
        $image_id = media_handle_sideload( $file_array, $post_id, '' );
        if ( is_wp_error( $image_id ) ) {
            return $image_id;
        }
        
        // Store this Group ID and Index in the Attachment's meta
        update_post_meta( $image_id, '_page_generator_pro_group', $group_id );
        update_post_meta( $image_id, '_page_generator_pro_index', $index );

        // If a title or caption has been defined, set them now
        if ( ! empty( $title ) || ! empty( $caption ) ) {
            $attachment = get_post( $image_id );
            wp_update_post( array(
                'ID'            => $image_id,
                'post_title'    => sanitize_text_field( $title ),
                'post_content'  => sanitize_text_field( $description ),
                'post_excerpt'  => sanitize_text_field( $caption ),
            ) );
        }

        // If an alt tag has been specified, set it now
        if ( ! empty( $alt_tag ) ) {
            update_post_meta( $image_id, '_wp_attachment_image_alt', $alt_tag );
        }

        // Return the image ID
        return $image_id;

    }

    /**
     * Flag to denote if the GD image processing library is available
     *
     * @since   1.9.7
     *
     * @return  bool    GD Library Available in PHP
     */
    public function is_gd_available() {

        return extension_loaded( 'gd' ) && function_exists( 'gd_info' );

    }

    /**
     * Force using the GD Image Library for processing WordPress Images.
     *
     * @since   1.9.7
     *
     * @param   array   $editors    WordPress Image Editors
     */
    public function enable_gd_image_support( $editors ) {

        $gd_editor = 'WP_Image_Editor_GD';
        $editors = array_diff( $editors, array( $gd_editor ) );
        array_unshift( $editors, $gd_editor );
        return $editors;

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
        $name = 'import';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}