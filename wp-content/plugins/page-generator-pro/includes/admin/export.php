<?php
/**
 * Export class
 * 
 * @package   Page_Generator_Pro
 * @author    Tim Carr
 * @version   2.6.8
 */
class Page_Generator_Pro_Export {

    /**
     * Holds the base class object.
     *
     * @since   2.6.8
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     * 
     * @since   2.6.8
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Export
        add_filter( 'page_generator_pro_export', array( $this, 'export' ) );

    }

    /**
     * Export data
     *
     * @since   2.6.8
     *
     * @param   array   $data   Export Data
     * @return  array           Export Data
     */
    public function export( $data ) {

        // Keywords
        $data['keywords'] = $this->base->get_class( 'keywords' )->get_all( 'keyword', 'ASC', -1 );

        // Groups
        $data['groups'] = $this->base->get_class( 'groups' )->get_all();
        $data['terms'] = $this->base->get_class( 'groups_terms' )->get_all();

        // Settings
        $data['general'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-general' );
        $data['google'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-google' );
        $data['generate'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-generate' );
        $data['georocket'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-georocket' );
        $data['open-weather-map'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-open-weather-map' );
        $data['pexels'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-pexels' );
        $data['pixabay'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-pixabay' );
        $data['spintax'] = $this->base->get_class( 'settings' )->get_settings( $this->base->plugin->name . '-spintax' );
        
        return $data;
        
    }

}