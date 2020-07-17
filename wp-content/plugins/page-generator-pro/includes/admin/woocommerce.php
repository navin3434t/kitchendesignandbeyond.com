<?php
/**
 * WooCommerce class
 * 
 * @package  Page Generator Pro
 * @author   Tim Carr
 * @version  2.6.9
 */
class Page_Generator_Pro_WooCommerce {

    /**
     * Holds the base object.
     *
     * @since   2.6.9
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     * 
     * @since   2.6.9
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        add_filter( 'page_generator_pro_common_get_excluded_taxonomies', array( $this, 'excluded_taxonomies' ) );
        add_filter( 'woocommerce_screen_ids', array( $this, 'register_content_group_as_woocommerce_screen' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_product_js' ), 9999 ); // Allow WC_Admin_Assets::admin_scripts() to register dependencies first
        add_action( 'page_generator_pro_groups_ui_add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_filter( 'woocommerce_data_stores', array( $this, 'woocommerce_data_stores' ) );
        add_action( 'save_post', array( $this, 'save_product' ), 10, 2 );

    }

    /**
     * Removes WooCommerce Product Type, Variation/Visibility and Shipping Classes
     * Taxonomies from Content Groups.
     *
     * Terms for these Taxonomies are defined in WooCommerce's Product Data Metabox
     *
     * @since   2.6.9
     *
     * @param   array   $excluded_taxonomies    Excluded Taxonomies
     * @return  array                           Excluded Taxonomies
     */
    public function excluded_taxonomies( $excluded_taxonomies ) {

        return array_merge( $excluded_taxonomies, array(
            'product_type',
            'product_visibility',
            'product_shipping_class',
        ) );    

    }

    /**
     * Registers Content Groups as a WooCommerce screen, ensuring CSS dependencies are enqueued
     *
     * @since   2.6.9
     *
     * @param   array   $screen_ids     Screen IDs
     * @return                          Screen IDs
     */
    public function register_content_group_as_woocommerce_screen( $screen_ids ) {

        $screen_ids[] = $this->base->get_class( 'post_type' )->post_type_name;
        return $screen_ids;

    }

    /**
     * Enqueues JS dependencies
     *
     * @since   2.6.9
     *
     * @param   array   $screen_ids     Screen IDs
     * @return                          Screen IDs
     */
    public function enqueue_product_js() {

        global $post;

        // Bail if WooCommerce isn't active
        if ( ! $this->is_active() ) {
            return;
        }

        // Bail if we're not editing a Content Group
        if ( ! $this->is_editing_content_group() ) {
            return;
        }

        // Define Post ID, suffix and WC version
        $post_id = ( isset( $post->ID ) ? $post->ID : '' );
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '' );
        $version = ( defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : false );

        // Enqueue
        wp_enqueue_media();

        wp_enqueue_script( 'wc-admin-product-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-product' . $suffix . '.js', array( 'wc-admin-meta-boxes', 'media-models' ), $version );
        wp_enqueue_script( 'wc-admin-variation-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-product-variation' . $suffix . '.js', array( 'wc-admin-meta-boxes', 'serializejson', 'media-models' ), $version );

        // Localize 
        $this->localize_woocommerce_admin_meta_boxes_variations( $post_id );
        $this->localize_woocommerce_admin_meta_boxes( $post_id );

    }

    /**
     * Localize the Product Meta Box for Variations
     *
     * This is conditionally performed by WC_Admin_Assets if editing a Product,
     * so we have to manually localize here.
     *
     * @since   2.6.9
     *
     * @param   int $post_id    Post ID
     */
    private function localize_woocommerce_admin_meta_boxes_variations( $post_id ) {

        $params = array(
            'post_id'                             => $post_id,
            'plugin_url'                          => WC()->plugin_url(),
            'ajax_url'                            => admin_url( 'admin-ajax.php' ),
            'woocommerce_placeholder_img_src'     => wc_placeholder_img_src(),
            'add_variation_nonce'                 => wp_create_nonce( 'add-variation' ),
            'link_variation_nonce'                => wp_create_nonce( 'link-variations' ),
            'delete_variations_nonce'             => wp_create_nonce( 'delete-variations' ),
            'load_variations_nonce'               => wp_create_nonce( 'load-variations' ),
            'save_variations_nonce'               => wp_create_nonce( 'save-variations' ),
            'bulk_edit_variations_nonce'          => wp_create_nonce( 'bulk-edit-variations' ),
            /* translators: %d: Number of variations */
            'i18n_link_all_variations'            => esc_js( sprintf( __( 'Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max %d per run).', 'woocommerce' ), Automattic\Jetpack\Constants::is_defined( 'WC_MAX_LINKED_VARIATIONS' ) ? Automattic\Jetpack\Constants::get_constant( 'WC_MAX_LINKED_VARIATIONS' ) : 50 ) ),
            'i18n_enter_a_value'                  => esc_js( __( 'Enter a value', 'woocommerce' ) ),
            'i18n_enter_menu_order'               => esc_js( __( 'Variation menu order (determines position in the list of variations)', 'woocommerce' ) ),
            'i18n_enter_a_value_fixed_or_percent' => esc_js( __( 'Enter a value (fixed or %)', 'woocommerce' ) ),
            'i18n_delete_all_variations'          => esc_js( __( 'Are you sure you want to delete all variations? This cannot be undone.', 'woocommerce' ) ),
            'i18n_last_warning'                   => esc_js( __( 'Last warning, are you sure?', 'woocommerce' ) ),
            'i18n_choose_image'                   => esc_js( __( 'Choose an image', 'woocommerce' ) ),
            'i18n_set_image'                      => esc_js( __( 'Set variation image', 'woocommerce' ) ),
            'i18n_variation_added'                => esc_js( __( 'variation added', 'woocommerce' ) ),
            'i18n_variations_added'               => esc_js( __( 'variations added', 'woocommerce' ) ),
            'i18n_no_variations_added'            => esc_js( __( 'No variations added', 'woocommerce' ) ),
            'i18n_remove_variation'               => esc_js( __( 'Are you sure you want to remove this variation?', 'woocommerce' ) ),
            'i18n_scheduled_sale_start'           => esc_js( __( 'Sale start date (YYYY-MM-DD format or leave blank)', 'woocommerce' ) ),
            'i18n_scheduled_sale_end'             => esc_js( __( 'Sale end date (YYYY-MM-DD format or leave blank)', 'woocommerce' ) ),
            'i18n_edited_variations'              => esc_js( __( 'Save changes before changing page?', 'woocommerce' ) ),
            'i18n_variation_count_single'         => esc_js( __( '%qty% variation', 'woocommerce' ) ),
            'i18n_variation_count_plural'         => esc_js( __( '%qty% variations', 'woocommerce' ) ),
            'variations_per_page'                 => absint( apply_filters( 'woocommerce_admin_meta_boxes_variations_per_page', 15 ) ),
        );

        wp_localize_script( 'wc-admin-variation-meta-boxes', 'woocommerce_admin_meta_boxes_variations', $params );

    }

    /**
     * Localize the Product Meta Box
     *
     * This is conditionally performed by WC_Admin_Assets if editing a Product,
     * so we have to manually localize here.
     *
     * @since   2.6.9
     *
     * @param   int $post_id    Post ID
     */
    private function localize_woocommerce_admin_meta_boxes( $post_id ) {

        $currency           = ''; // ?
        
        $params = array(
            'remove_item_notice'            => __( 'Are you sure you want to remove the selected items?', 'woocommerce' ),
            'i18n_select_items'             => __( 'Please select some items.', 'woocommerce' ),
            'i18n_do_refund'                => __( 'Are you sure you wish to process this refund? This action cannot be undone.', 'woocommerce' ),
            'i18n_delete_refund'            => __( 'Are you sure you wish to delete this refund? This action cannot be undone.', 'woocommerce' ),
            'i18n_delete_tax'               => __( 'Are you sure you wish to delete this tax column? This action cannot be undone.', 'woocommerce' ),
            'remove_item_meta'              => __( 'Remove this item meta?', 'woocommerce' ),
            'remove_attribute'              => __( 'Remove this attribute?', 'woocommerce' ),
            'name_label'                    => __( 'Name', 'woocommerce' ),
            'remove_label'                  => __( 'Remove', 'woocommerce' ),
            'click_to_toggle'               => __( 'Click to toggle', 'woocommerce' ),
            'values_label'                  => __( 'Value(s)', 'woocommerce' ),
            'text_attribute_tip'            => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ),
            'visible_label'                 => __( 'Visible on the product page', 'woocommerce' ),
            'used_for_variations_label'     => __( 'Used for variations', 'woocommerce' ),
            'new_attribute_prompt'          => __( 'Enter a name for the new attribute term:', 'woocommerce' ),
            'calc_totals'                   => __( 'Recalculate totals? This will calculate taxes based on the customers country (or the store base country) and update totals.', 'woocommerce' ),
            'copy_billing'                  => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
            'load_billing'                  => __( "Load the customer's billing information? This will remove any currently entered billing information.", 'woocommerce' ),
            'load_shipping'                 => __( "Load the customer's shipping information? This will remove any currently entered shipping information.", 'woocommerce' ),
            'featured_label'                => __( 'Featured', 'woocommerce' ),
            'prices_include_tax'            => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
            'tax_based_on'                  => esc_attr( get_option( 'woocommerce_tax_based_on' ) ),
            'round_at_subtotal'             => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
            'no_customer_selected'          => __( 'No customer selected', 'woocommerce' ),
            'plugin_url'                    => WC()->plugin_url(),
            'ajax_url'                      => admin_url( 'admin-ajax.php' ),
            'order_item_nonce'              => wp_create_nonce( 'order-item' ),
            'add_attribute_nonce'           => wp_create_nonce( 'add-attribute' ),
            'save_attributes_nonce'         => wp_create_nonce( 'save-attributes' ),
            'calc_totals_nonce'             => wp_create_nonce( 'calc-totals' ),
            'get_customer_details_nonce'    => wp_create_nonce( 'get-customer-details' ),
            'search_products_nonce'         => wp_create_nonce( 'search-products' ),
            'grant_access_nonce'            => wp_create_nonce( 'grant-access' ),
            'revoke_access_nonce'           => wp_create_nonce( 'revoke-access' ),
            'add_order_note_nonce'          => wp_create_nonce( 'add-order-note' ),
            'delete_order_note_nonce'       => wp_create_nonce( 'delete-order-note' ),
            'calendar_image'                => WC()->plugin_url() . '/assets/images/calendar.png',
            'post_id'                       => isset( $post->ID ) ? $post->ID : '',
            'base_country'                  => WC()->countries->get_base_country(),
            'currency_format_num_decimals'  => wc_get_price_decimals(),
            'currency_format_symbol'        => get_woocommerce_currency_symbol( $currency ),
            'currency_format_decimal_sep'   => esc_attr( wc_get_price_decimal_separator() ),
            'currency_format_thousand_sep'  => esc_attr( wc_get_price_thousand_separator() ),
            'currency_format'               => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS.
            'rounding_precision'            => wc_get_rounding_precision(),
            'tax_rounding_mode'             => wc_get_tax_rounding_mode(),
            'product_types'                 => array_unique( array_merge( array( 'simple', 'grouped', 'variable', 'external' ), array_keys( wc_get_product_types() ) ) ),
            'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce' ),
            'i18n_permission_revoke'        => __( 'Are you sure you want to revoke access to this download?', 'woocommerce' ),
            'i18n_tax_rate_already_exists'  => __( 'You cannot add the same tax rate twice!', 'woocommerce' ),
            'i18n_delete_note'              => __( 'Are you sure you wish to delete this note? This action cannot be undone.', 'woocommerce' ),
            'i18n_apply_coupon'             => __( 'Enter a coupon code to apply. Discounts are applied to line totals, before taxes.', 'woocommerce' ),
            'i18n_add_fee'                  => __( 'Enter a fixed amount or percentage to apply as a fee.', 'woocommerce' ),
        );

        wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $params );

    }

    /**
     * Adds WooCommerce Product Meta Boxes to Content Groups
     *
     * @since   2.6.9
     */
    public function add_meta_boxes() {

        // Bail if WooCommerce isn't active
        if ( ! $this->is_active() ) {
            return;
        }

        // Bail if we're not editing a Content Group
        if ( ! $this->is_editing_content_group() ) {
            return;
        }

        //add_meta_box( 'postexcerpt', __( 'Product short description', 'woocommerce' ), 'WC_Meta_Box_Product_Short_Description::output', $this->base->get_class( 'post_type' )->post_type_name, 'normal' );
        
        add_meta_box( 'woocommerce-product-data', __( 'Product data', 'woocommerce' ), 'WC_Meta_Box_Product_Data::output', $this->base->get_class( 'post_type' )->post_type_name, 'normal' );
        //add_meta_box( 'woocommerce-product-data', __( 'Product data', 'woocommerce' ), 'Page_Generator_Pro_WC_Meta_Box_Product_Data::output', $this->base->get_class( 'post_type' )->post_type_name, 'normal' );

        //add_meta_box( 'woocommerce-product-images', __( 'Product gallery', 'woocommerce' ), 'WC_Meta_Box_Product_Images::output', $this->base->get_class( 'post_type' )->post_type_name, 'side', 'low' );

    }

    /**
     * Replaces WC_Product_Data_Store_CPT with Page_Generator_Pro_WC_Product_Data_Store_CPT,
     * which bypasses the Product Post Type check that WC_Product_Data_Store_CPT::read() uses
     * that results in an "Invalid product" exception when editing a Content Group.
     *
     * @since   2.6.9
     *
     * @param   array   $stores     WooCommerce Data Stores
     * @return  array               WooCommerce Data Stores
     */
    public function woocommerce_data_stores( $stores ) {

        // Bail if WooCommerce isn't active
        if ( ! $this->is_active() ) {
            return $stores;
        }

        // Bail if we're not editing a Content Group
        if ( ! $this->is_editing_content_group() ) {
            return $stores;
        }

        // @TODO handle
        /*
         'product-grouped' => string 'WC_Product_Grouped_Data_Store_CPT' (length=33)
          'product-variable' => string 'WC_Product_Variable_Data_Store_CPT' (length=34)
          'product-variation' => string 'WC_Product_Variation_Data_Store_CPT' (length=35)
          */
        $stores['product'] = 'Page_Generator_Pro_WC_Product_Data_Store_CPT';
        return $stores;

    }

    /**
     * Saves WooCommerce Product information within a Content Group, by
     * - Adding the necessary action hooks to process and save Product Meta,
     * - Calling WooCommerce's action to process and save Product Meta
     *
     * @since   2.6.9
     *
     * @param   int         $post_id    Post ID
     * @param   WP_Post     $post       Post
     */
    public function save_product( $post_id, $post ) {

        // Bail if WooCommerce isn't active
        if ( ! $this->is_active() ) {
            return;
        }

        // Bail if we're not editing a Content Group
        if ( ! $this->is_editing_content_group() ) {
            return;
        }

        // Add WooCommerce actions that process and save product data now, as WooCommerce won't have
        // registered these because we're not saving a Product
        add_action( 'woocommerce_process_product_meta', 'WC_Meta_Box_Product_Data::save', 10, 2 );
        add_action( 'woocommerce_process_product_meta', 'WC_Meta_Box_Product_Images::save', 20, 2 );

        // This will call the above hooks in WooCommerce which perform the save
        do_action( 'woocommerce_process_product_meta', $post_id, $post );

    }

    /**
     * Checks if the WooCommerce Plugin is active
     *
     * @since   2.6.9
     *
     * @return  bool    WooCommerce Plugin Active
     */
    private function is_active() {

        // Assume WooCommerce isn't active if we can't use WordPress' function
        if ( ! function_exists( 'is_plugin_active' ) ) {
            return false;
        }

        return is_plugin_active( 'woocommerce/woocommerce.php' );
        
    }

    /**
     * Checks if we're editing a Content Group
     *
     * @since   2.6.9
     *
     * @return  bool    Editing Content Group
     */
    private function is_editing_content_group() {

        $screen = $this->base->get_class( 'screen' )->get_current_screen();
        if ( $screen['screen'] != 'content_groups' ) {
            return false;
        }
        if ( $screen['section'] != 'edit' ) {
            return false;
        }

        return true;

    }

}

/**
 * Page Generator Pro implementation of WC_Product_Data_Store_CPT, which
 * bypasses the Post Type check that would make our Content Group fail to load
 * WooCommerce Product Data, because our Content Group isn't a Product Post Type
 *
 * @since   2.6.9
 */
if ( class_exists( 'WC_Product_Data_Store_CPT' ) ) {
    class Page_Generator_Pro_WC_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT {

        /**
         * Method to read a product from the database.
         *
         * @param WC_Product $product Product object.
         * @throws Exception If invalid product.
         */
        public function read( &$product ) {

            $product->set_defaults();

            $post_object = get_post( $product->get_id() );

            /*
            // This code exists in WC_Product_Data_Store_CPT, but rightly throws an exception
            // as we're editing a Content Group Post Type with Product data, not a Product Post Type
            if ( ! $product->get_id() || ! $post_object || 'product' !== $post_object->post_type ) {
                throw new Exception( __( 'Invalid product.', 'woocommerce' ) );
            }
            */

            $product->set_props(
                array(
                    'name'              => $post_object->post_title,
                    'slug'              => $post_object->post_name,
                    'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
                    'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
                    'status'            => $post_object->post_status,
                    'description'       => $post_object->post_content,
                    'short_description' => $post_object->post_excerpt,
                    'parent_id'         => $post_object->post_parent,
                    'menu_order'        => $post_object->menu_order,
                    'post_password'     => $post_object->post_password,
                    'reviews_allowed'   => 'open' === $post_object->comment_status,
                )
            );

            $this->read_attributes( $product );
            $this->read_downloads( $product );
            $this->read_visibility( $product );
            $this->read_product_data( $product );
            $this->read_extra_data( $product );
            $product->set_object_read( true );

            do_action( 'woocommerce_product_read', $product->get_id() );

        }

    }
}