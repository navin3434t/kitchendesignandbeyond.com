<?php
	/**
	 * Theme functions and definitions
	 * test comment
	 * @link https://developer.wordpress.org/themes/basics/theme-functions/
	 */

	function cfwc_create_custom_field() {
		$args = array(
		'id' => 'box_pieces',
		'label' => __( 'Box Pieces', 'cfwc' ),
		'class' => 'cfwc-custom-field',
		'desc_tip' => true,
		'description' => __( 'Enter the box pieces.', 'ctwc' ),
		);
		woocommerce_wp_text_input( $args );
	   
	   
	   
	   }
	   add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field' );
	   
	   function cfwc_save_custom_field( $post_id ) {
		$product = wc_get_product( $post_id );
		$title = isset( $_POST['box_pieces'] ) ? $_POST['box_pieces'] : '';
		$product->update_meta_data( 'box_pieces', sanitize_text_field( $title ) );
		$product->save();
	   }
	   add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );
	   
	   
	   
	   function cfwc_create_custom_field1() {
		   $args = array(
		   'id' => 'availability',
		   'label' => __( 'Availability', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Availability', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field1' );
		  
		  function cfwc_save_custom_field1( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['availability'] ) ? $_POST['availability'] : '';
		   $product->update_meta_data( 'availability', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field1' );
	   
	   
	   
	   
		  function cfwc_create_custom_field2() {
		   $args = array(
		   'id' => 'Countertops_Residential',
		   'label' => __( 'Countertops Residential', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Countertops Residential', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field2' );
		  
		  function cfwc_save_custom_field2( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['Countertops_Residential'] ) ? $_POST['Countertops_Residential'] : '';
		   $product->update_meta_data( 'Countertops_Residential', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field2' );
	   
	   
	   
	   
		  function cfwc_create_custom_field3() {
		   $args = array(
		   'id' => 'Countertops_Commercial',
		   'label' => __( 'Countertops Commercial', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Countertops Commercial', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field3' );
		  
		  function cfwc_save_custom_field3( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['Countertops_Commercial'] ) ? $_POST['Countertops_Commercial'] : '';
		   $product->update_meta_data( 'Countertops_Commercial', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field3' );
	   
	   
	   
		  function cfwc_create_custom_field4() {
		   $args = array(
		   'id' => 'Flooring_Residential',
		   'label' => __( 'Flooring Residential', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Flooring Residential', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field4' );
		  
		  function cfwc_save_custom_field4( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['Flooring_Residential'] ) ? $_POST['Flooring_Residential'] : '';
		   $product->update_meta_data( 'Flooring_Residential', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field4' );
	   
	   
	   
		  function cfwc_create_custom_field5() {
		   $args = array(
		   'id' => 'Flooring_Commercial',
		   'label' => __( 'Flooring Commercial', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Flooring Commercial', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field5' );
		  
		  function cfwc_save_custom_field5( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['Flooring_Commercial'] ) ? $_POST['Flooring_Commercial'] : '';
		   $product->update_meta_data( 'Flooring_Commercial', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field5' );
	   
	   
	   
		  function cfwc_create_custom_field6() {
		   $args = array(
		   'id' => 'Wall_Residential',
		   'label' => __( 'Wall Residential', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Wall Residential', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field6' );
		  
		  function cfwc_save_custom_field6( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['Wall_Residential'] ) ? $_POST['Wall_Residential'] : '';
		   $product->update_meta_data( 'Wall_Residential', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field6' );
	   
	   
	   
		  function cfwc_create_custom_field7() {
		   $args = array(
		   'id' => 'Wall_Commercial',
		   'label' => __( 'Wall Commercial', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Wall Commercial', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field7' );
		  
		  function cfwc_save_custom_field7( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['Wall_Commercial'] ) ? $_POST['Wall_Commercial'] : '';
		   $product->update_meta_data( 'Wall_Commercial', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field7' );
	   
	   
	   
	   
	   
		  function cfwc_create_custom_field8() {
		   $args = array(
		   'id' => 'same_product_title',
		   'label' => __( 'Product Title', 'cfwc' ),
		   'class' => 'cfwc-custom-field',
		   'desc_tip' => true,
		   'description' => __( 'Product Title', 'ctwc' ),
		   );
		   woocommerce_wp_text_input( $args );
		  
		   
		  }
		  add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field8' );
		  
		  function cfwc_save_custom_field8( $post_id ) {
		   $product = wc_get_product( $post_id );
		   $title = isset( $_POST['same_product_title'] ) ? $_POST['same_product_title'] : '';
		   $product->update_meta_data( 'same_product_title', sanitize_text_field( $title ) );
		   $product->save();
		  }
		  add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field8' );
	   
	   
	   
	   function filter_plugin_updates( $value ) {
		   unset( $value->response['woocommerce-measurement-price-calculator/woocommerce-measurement-price-calculator.php'] );
		   return $value;
	   }
	   add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );
	   
	   /**
		* Handle a custom 'customvar' query var to get products with the 'customvar' meta.
		* @param array $query - Args for WP_Query.
		* @param array $query_vars - Query vars from WC_Product_Query.
		* @return array modified $query

		*/ 
		/* Test Comment */
	   function handle_custom_query_var( $query, $query_vars ) {
		   if ( ! empty( $query_vars['same_product_title'] ) ) {
			   $query['meta_query'][] = array(
				   'key' => 'same_product_title',
				   'value' => esc_attr( $query_vars['same_product_title'] ),
			   );
		   }
	   
		   return $query;
	   }
	   add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'handle_custom_query_var', 10, 2 );