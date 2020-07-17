<?php
/**
 * ACF class
 * 
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 2.6.3
 */
class Page_Generator_Pro_ACF {

    /**
     * Holds the base object.
     *
     * @since   2.6.3
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     *
     * @since   2.6.3
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        add_filter( 'acf/location/rule_types', array( $this, 'add_post_type_to_location_rules' ) );
        
        add_filter( 'acf/location/rule_values/page-generator-pro', array( $this, 'add_content_groups_to_location_rules' ) );
        add_filter( 'acf/location/rule_match/page-generator-pro' , array( $this, 'match_content_group_location_rule' ), 10, 4 );

        add_filter( 'acf/location/rule_values/page-generator-tax', array( $this, 'add_term_groups_to_location_rules' ) );
        add_filter( 'acf/location/rule_match/page-generator-tax', array( $this, 'match_term_group_location_rule' ), 10, 4 );

    }

    /**
     * Register Page Generator Pro Content and Term Groups as Location Rules
     *
     * @since   2.6.3
     *
     * @param   array   $choices    Location Choices
     * @return  array               Location Choices
     */
    public function add_post_type_to_location_rules( $choices ) {

        $choices[ $this->base->plugin->displayName ] = array(
            'page-generator-pro' => __( 'Content Group', 'page-generator-pro' ),
            'page-generator-tax' => __( 'Term Group', 'page-generator-pro' ),
        );

        return $choices;

    }

    /**
     * Registers all Content Groups as possible values that can be chosen for the Content Group Location Rule
     *
     * @since   2.6.3
     *
     * @param   array   $choices    Content Group Choices
     * @return  array               Content Group Choices
     */
    public function add_content_groups_to_location_rules( $choices ) {

        // Get all Group ID and Names
        $groups = $this->base->get_class( 'groups' )->get_all_ids_names();
        if ( ! $groups ) {
            return $choices;
        }

        asort( $groups );

        return $groups;

    }

    /**
     * When a Content Group Location Rule exists on the given Field Group, check that the rule matches
     * to determine whether the Field Group should display
     *
     * @since   2.6.3
     *
     * @param   bool    $match          Rule Matches
     * @param   array   $rule           Location Rule
     * @param   array   $options        Field Group Options
     * @param   array   $field_group    Field Group (false if older ACF version, which doesn't include this argument)
     * @return  bool                    Rule Matches
     */
    public function match_content_group_location_rule( $match, $rule, $options, $field_group = false ) {

        global $post;

        // Bail if we can't establish the Post
        if ( is_null( $post ) ) {
            return $match;
        }

        switch ( $rule['operator'] ) {
            case '!=':
                $match = ( $post->ID != (int) $rule['value'] );
                break;

            case '==':
                $match = ( $post->ID == (int) $rule['value'] );
                break;

            default:
                $match = apply_filters( 'page_generator_pro_acf_match_content_group_location_rule', $match, $rule, $options, $field_group );
                break;
        }

        return $match;

    }

    /**
     * Registers all Term Groups as possible values that can be chosen for the Term Group Location Rule
     *
     * @since   2.6.3
     *
     * @param   array   $choices    Term Group Choices
     * @return  array               Term Group Choices
     */
    public function add_term_groups_to_location_rules( $choices ) {

        // Get all Group ID and Names
        $groups = $this->base->get_class( 'groups_terms' )->get_all_ids_names();
        if ( ! $groups ) {
            return $choices;
        }

        asort( $groups );

        return $groups;

    }

    /**
     * When a Term Group Location Rule exists on the given Field Group, check that the rule matches
     * to determine whether the Field Group should display
     *
     * @since   2.6.3
     *
     * @param   bool    $match          Rule Matches
     * @param   array   $rule           Location Rule
     * @param   array   $options        Field Group Options
     * @param   array   $field_group    Field Group (false if older ACF version, which doesn't include this argument)
     * @return  bool                    Rule Matches
     */
    public function match_term_group_location_rule( $match, $rule, $options, $field_group = false ) {

        // Bail if we can't establish the Term
        if ( ! isset( $_REQUEST['tag_ID'] ) ) {
            return $match;
        }
        if ( ! isset( $_REQUEST['taxonomy'] ) ) {
            return $match;
        }

        // Get Taxonomy and Term ID
        $taxonomy = sanitize_text_field( $_REQUEST['taxonomy'] );
        $term_id = absint( sanitize_text_field( $_REQUEST['tag_ID'] ) );

        // Bail if not a Term Group
        if ( $taxonomy != $this->base->get_class( 'taxonomy' )->taxonomy_name ) {
            return $match;
        }

        switch ( $rule['operator'] ) {
            case '!=':
                $match = ( $term_id != (int) $rule['value'] );
                break;

            case '==':
                $match = ( $term_id == (int) $rule['value'] );
                break;

            default:
                $match = apply_filters( 'page_generator_pro_acf_match_term_group_location_rule', $match, $rule, $options, $field_group, $term_id );
                break;
        }

        return $match;

    }

}