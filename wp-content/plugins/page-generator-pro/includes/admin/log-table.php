<?php
/**
 * Log Table class
 * 
 * @package Page Generator Pro
 * @author 	Tim Carr
 * @version 2.6.1
 */
class Page_Generator_Pro_Log_Table extends WP_List_Table {

    /**
     * Holds the base class object.
     *
     * @since 	2.6.1
     *
     * @var 	object
     */
    public $base;

    /**
     * Constructor.
     *
     * @since   2.6.1
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        parent::__construct( array(
			'singular'	=> 'page-generator-pro-log', 	// Singular label
			'plural' 	=> 'page-generator-pro-log', 	// plural label, also this well be one of the table css class
			'ajax'		=> false 						// We won't support Ajax for this table
		) );

    }

	/**
	 * Display dropdowns for Bulk Actions and Filtering.
	 *
	 * @since 	2.6.1
	 *
	 * @param 	string 	$which 	The location of the bulk actions: 'top' or 'bottom'. 
	 * 							This is designated as optional for backward compatibility.
	 */
	protected function bulk_actions( $which = '' ) {

		// Get Bulk Actions
		$this->_actions = $this->get_bulk_actions();

		// Define <select> name
		$bulk_actions_name = 'bulk_action' . ( $which != 'top' ? '2' : '' );
		?>
		<label for="bulk-action-selector-<?php echo esc_attr( $which ); ?>" class="screen-reader-text">
			<?php _e( 'Select bulk action' ); ?>
		</label>
		<select name="<?php echo $bulk_actions_name; ?>" id="bulk-action-selector-<?php echo esc_attr( $which ); ?>" size="1">
			<option value="-1"><?php _e( 'Bulk Actions' ); ?></option>

			<?php
			foreach ( $this->_actions as $name => $title ) {
				?>
				<option value="<?php echo $name; ?>"><?php echo $title; ?></option>
				<?php
			}
			?>
		</select>

		<?php
		// Output our custom filters to the top only
		if ( $which == 'top' ) {
			?>
			<!-- Custom Filters -->

			<!-- Group -->
			<select name="group_id" size="1">
				<option value=""<?php selected( $this->get_group_id(), '' ); ?>><?php _e( 'Filter by Group', $this->base->plugin->name ); ?></option>
				<?php
				foreach ( $this->base->get_class( 'groups' )->get_all_ids_names() as $group_id => $label ) {
					?>
					<option value="<?php echo $group_id; ?>"<?php selected( $this->get_group_id(), $group_id ); ?>>#<?php echo $group_id . ': ' . $label; ?></option>
					<?php
				}
				?>
			</select>

			<!-- Generation System -->
			<select name="system" size="1">
				<option value=""<?php selected( $this->get_system(), '' ); ?>><?php _e( 'Filter by System', $this->base->plugin->name ); ?></option>
				<?php
				foreach ( $this->base->get_class( 'common' )->get_generation_systems() as $system => $label ) {
					?>
					<option value="<?php echo $system; ?>"<?php selected( $this->get_system(), $system ); ?>><?php echo $label; ?></option>
					<?php
				}
				?>
			</select>

			<input type="date" name="generated_at_start_date" value="<?php echo $this->get_generated_at_start_date(); ?>" />
			-
			<input type="date" name="generated_at_end_date" value="<?php echo $this->get_generated_at_end_date(); ?>"/>
			<?php
		}

		submit_button( __( 'Filter' ), 'action', '', false, array( 'id' => "doaction" ) );
		?>

		<a href="<?php echo add_query_arg( array( 'bulk_action3' => 'export' ), $_SERVER['REQUEST_URI'] ); ?>" class="button">
			<?php _e( 'Export Log', $this->base->plugin->name ); ?>
		</a>

		<a href="<?php echo admin_url( 'admin.php?page=' . $this->base->plugin->name . '-logs&bulk_action3=delete_all' ); ?>" class="button red">
			<?php _e( 'Clear Log', $this->base->plugin->name ); ?>
		</a>
		<?php
		
	}
	
	/**
	 * Defines the message to display when no items exist in the table
	 *
	 * @since 	2.6.1
	 *
	 * @return 	string 	No Items Message
	 */
	public function no_items() {

		_e( 'No log entries found based on the given search and filter criteria.', $this->base->plugin->name );

	}

	/**
	 * Displays the search box.
	 * 
	 * @param 	2.6.1
	 *
	 * @param 	string $text     	The 'submit' button label.
	 * @param 	string $input_id 	ID attribute value for the search input field.
	 */
	public function search_box( $text, $input_id ) {

		$input_id = $input_id . '-search-input';

		// Preserve Filters by storing any defined as hidden form values
		foreach ( $this->base->get_class( 'log' )->get_filters() as $filter ) {
			if ( ! empty( $_REQUEST[ $filter ] ) ) {
				?>
				<input type="hidden" name="<?php echo $filter; ?>" value="<?php echo esc_attr( $_REQUEST[ $filter ] ); ?>" />
				<?php
			}	
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" placeholder="<?php _e( 'Group ID or Name', $this->base->plugin->name ); ?>" />
			<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
		</p>
		<?php
	}
	 
	/**
 	 * Define the columns that are going to be used in the table
 	 *
 	 * @since 	2.6.1
 	 *
 	 * @return 	array 	Columns to use with the table
 	 */
	public function get_columns() {

		return array(
			'cb' 					=> '<input type="checkbox" class="toggle" />',
			'group_id'				=> __( 'Group', 'page-generator-pro' ),
			'post_id'				=> __( 'Generated Item', 'page-generator-pro' ),
			'system'				=> __( 'System', 'page-generator-pro' ),
			'test_mode'				=> __( 'Test Mode', 'page-generator-pro' ),
			'generated'				=> __( 'Generated', 'page-generator-pro' ),
			'keywords_terms'		=> __( 'Keywords/Terms', 'page-generator-pro' ),
			'message'				=> __( 'Result', 'page-generator-pro' ),
			'duration'				=> __( 'Duration (Seconds)', 'page-generator-pro' ),
			'memory_usage'			=> __( 'Memory Usage (MB)', 'page-generator-pro' ),
			'memory_peak_usage'		=> __( 'Memory Usage, Peak (MB)', 'page-generator-pro' ),
			'generated_at'			=> __( 'Generated At', 'page-generator-pro' ),
		);

	}
	
	/**
 	 * Decide which columns to activate the sorting functionality on
 	 *
 	 * @since 	2.6.1
 	 *
 	 * @return 	array 	Columns that can be sorted by the user
 	 */
	public function get_sortable_columns() {

		return array(
			'group_id'				=> array( 'group_id', true ),
			'post_id'				=> array( 'post_id', true ),
			'system'				=> array( 'system', true ),
			'test_mode'				=> array( 'test_mode', true ),
			'generated'				=> array( 'generated', true ),
			'message'				=> array( 'message', true ),
			'duration'				=> array( 'duration', true ),
			'memory_usage'			=> array( 'memory_usage', true ),
			'memory_peak_usage'		=> array( 'memory_peak_usage', true ),
			'generated_at'			=> array( 'generated_at', true ),
		);

	}
	
	/**
	 * Overrides the list of bulk actions in the select dropdowns above and below the table
	 *
	 * @since 	2.6.1
	 *
	 * @return 	array 	Bulk Actions
	 */
	public function get_bulk_actions() {

		return array(
			'delete' => __( 'Delete', 'page-generator-pro' ),
		);

	}
	
	/**
 	 * Prepare the table with different parameters, pagination, columns and table elements
 	 *
 	 * @since 	2.6.1
 	 */
	public function prepare_items() {

		global $_wp_column_headers;
		
		$screen = get_current_screen();
		
		// Get params
		$params 	= $this->base->get_class( 'log' )->get_search_params();
		$order_by 	= $this->base->get_class( 'log' )->get_order_by();
  		$order 		= $this->base->get_class( 'log' )->get_order();
		$page 		= $this->base->get_class( 'log' )->get_page();
		$per_page 	= 20;

		// Get total records for this query
		$total = $this->base->get_class( 'log' )->total( $params );

		// Define pagination
		$this->set_pagination_args( array(
			'total_items' 	=> $total,
			'total_pages' 	=> ceil( $total / $per_page ),
			'per_page' 		=> $per_page,
		) );
		
		// Set column headers
  		$this->_column_headers = array( 
  			$this->get_columns(),
  			array(),
  			$this->get_sortable_columns(),
  		);

  		// Set rows
  		$this->items = $this->base->get_class( 'log' )->search( $order_by, $order, $page, $per_page, $params );

	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * @since 	2.6.1
	 *
	 * @param 	string 	$which 	Location (top|bottom)
	 */
	protected function display_tablenav( $which ) {

		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<div class="alignleft actions bulkactions">
				<?php $this->bulk_actions( $which ); ?>
			</div>
			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>
		<?php

	}

	/**
	 * Display the rows of records in the table
	 *
	 * @since 	2.6.1
	 *
	 * @return 	HTML Row Output
	 */
	public function display_rows() {

		// Load view
		include( $this->base->plugin->folder . 'views/admin/log-row.php' );

	}

}