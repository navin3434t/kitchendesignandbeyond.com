<?php
/**
 * Keywords Table class
 * 
 * @package Page_Generator_Pro
 * @author 	Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Keywords_Table extends WP_List_Table {

    /**
     * Holds the base class object.
     *
     * @since 	1.3.8
     *
     * @var 	object
     */
    public $base;

    /**
     * Constructor.
     *
     * @since   1.0.0
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        parent::__construct( array(
			'singular'	=> 'keyword', 	// Singular label
			'plural' 	=> 'keywords', 	// plural label, also this well be one of the table css class
			'ajax'		=> false 		// We won't support Ajax for this table
		));

    }
	
	/**
	 * Defines the message to display when no items exist in the table
	 *
	 * @since 	1.0.0
	 *
	 * @return 	No Items Message
	 */
	public function no_items() {

		_e( 'Keywords are used to product unique content for each Page, Post or Custom Post Type that is generated.', 'page-generator-pro' );
		echo ( '<br /><a href="admin.php?page=' . $this->base->plugin->name . '-keywords&cmd=form" class="button">' . __( 'Create first keyword.', 'page-generator-pro' ).'</a>' );
	
	}
	 
	/**
 	 * Define the columns that are going to be used in the table
 	 *
 	 * @since 	1.0.0
 	 *
 	 * @return 	array 	Columns to use with the table
 	 */
	public function get_columns() {

		return array(
			'cb' 					=> '<input type="checkbox" class="toggle" />',
			'col_field_keyword' 	=> __( 'Keyword', 'page-generator-pro' ),
			'col_field_term_count'	=> __( 'Number of Terms', 'page-generator-pro' ),
		);

	}
	
	/**
 	 * Decide which columns to activate the sorting functionality on
 	 *
 	 * @since 	1.0.0
 	 *
 	 * @return 	array 	Columns that can be sorted by the user
 	 */
	public function get_sortable_columns() {

		return $sortable = array(
			'col_field_keyword' => array( 'keyword', true )
		);

	}
	
	/**
	 * Overrides the list of bulk actions in the select dropdowns above and below the table
	 *
	 * @since 	1.0.0
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
 	 * @since 	1.0.0
 	 */
	public function prepare_items() {

		global $_wp_column_headers;
		
		$screen = get_current_screen();
		
		// Get params
		$search 	= $this->get_search();
		$order_by 	= $this->get_order_by();
  		$order 		= $this->get_order();
		$page 		= $this->get_page();
		$per_page 	= $this->get_items_per_page( 'page_generator_pro_keywords_per_page', 20 );

		// Get total records for this query
		$total = $this->base->get_class( 'keywords' )->total( $search );

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

  		$this->items = $this->base->get_class( 'keywords' )->get_all( $order_by, $order, $page, $per_page, $search );

	}

	/**
	 * Display the rows of records in the table
	 *
	 * @since 	1.0.0
	 *
	 * @return 	HTML Row Output
	 */
	public function display_rows() {
		
		// Get rows and columns
		$records = $this->items;
		list( $columns, $hidden ) = $this->get_column_info();
		
		// Bail if no records found
		if ( empty( $records ) ) {
			return;
		}

		// Get params
		$params = array(
			'page'		=> $this->base->plugin->name . '-keywords',
			's' 		=> $this->get_search(),
			'orderby' 	=> $this->get_order_by(),
			'order' 	=> $this->get_order(),
			'paged' 	=> $this->get_page(),
		);

		// Iterate through records
		foreach ( $records as $key => $record ) {
			// Start row
			echo ('<tr id="record_' . $record->keywordID . '"' . ( ( $key % 2 == 0 ) ? ' class="alternate"' : '') . '>' );

			// Iterate through columns
			foreach ( $columns as $column_name => $display_name ) {
				switch ( $column_name ) {
					
					/**
					 * Checkbox
					 */
					case 'cb':
						echo ('	<th scope="row" class="check-column">
									<input type="checkbox" name="ids[' . $record->keywordID . ']" value="' . $record->keywordID . '" />
								</th>'); 
						break;

					/**
					 * Keyword
					 */
					case 'col_field_keyword':
						// Build URLs for edit, delete and duplicate
						$edit_url = add_query_arg( array(
							'page' 	=> $this->base->plugin->name . '-keywords',
							'cmd' 	=> 'form',
							'id' 	=> absint( $record->keywordID ),
						), 'admin.php' );

						$delete_url = add_query_arg( array_merge( $params, array(
							'cmd' 	=> 'delete',
							'id' 	=> absint( $record->keywordID ),
						) ), 'admin.php' );

						$duplicate_url = add_query_arg( array_merge( $params, array(
							'cmd' 	=> 'duplicate',
							'id' 	=> absint( $record->keywordID ),
						) ), 'admin.php' );

						echo ( '<td class="' . $column_name . ' column-' . $column_name . '">
									<strong>
										<a href="' . $edit_url . '" title="' . __( 'Edit this keyword', 'page-generator-pro' ) . '">
											' . $record->keyword . '
										</a>
									</strong>
									<div class="row-actions">
										<span class="edit">
											<a href="' . $edit_url . '" title="' . __( 'Edit this keyword', 'page-generator-pro' ) . '">
											' . __( 'Edit', 'page-generator-pro' ) . '
											</a> | 
										</span>
										<span class="edit">
											<a href="' . $duplicate_url . '" title="' . __( 'Duplicate this keyword', 'page-generator-pro' ) . '">
											' . __( 'Duplicate', 'page-generator-pro' ) . '
											</a> | 
										</span>
										<span class="trash">
											<a href="' . $delete_url . '" title="' . __( 'Delete this keyword', 'page-generator-pro' ).'" class="delete">
											' . __( 'Delete', 'page-generator-pro' ) . '
											</a>
										</span>
									</div>
								</td>'); 
						break;

					/**
					 * Number of Terms
					 */
					case 'col_field_term_count':
						echo ( 	'<td class="' . $column_name . ' column-' . $column_name . '">' . number_format( count( explode( "\n", $record->data ) ) ) . '</td>' ); 
						break;

				}
			}

			// End row
			echo (' </tr>' );
		}

	}

	/**
	 * Get the Search requested by the user
	 *
	 * @since 	2.6.5
	 *
	 * @return 	string
	 */
	public function get_search() {

		return ( isset( $_GET['s'] ) ? sanitize_text_field( urldecode( $_GET['s'] ) ) : '' );

	}

	/**
	 * Get the Order By requested by the user
	 *
	 * @since 	2.6.5
	 *
	 * @return 	string
	 */
	private function get_order_by() {

		return ( isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'keyword' );

	}

	/**
	 * Get the Order requested by the user
	 *
	 * @since 	2.6.5
	 *
	 * @return 	string
	 */
	private function get_order() {

		return ( isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'ASC' );

	}

	/**
	 * Get the Pagination Page requested by the user
	 *
	 * @since 	2.6.5
	 *
	 * @return 	string
	 */
	private function get_page() {

		return ( ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) ? absint( $_GET['paged'] ) : 1 );

	}

}