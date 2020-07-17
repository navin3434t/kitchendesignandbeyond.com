<?php
/**
 * Keywords class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Keywords {

    /**
     * Holds the base class object.
     *
     * @since   1.9.7
     *
     * @var     object
     */
    public $base;

	/**
	 * Primary SQL Table
     *
     * @since   1.0.0
     *
     * @var     string
	 */
	public $table = 'page_generator_keywords';
	
	/**
	 * Primary SQL Table Primary Key
     *
     * @since   1.0.0
     *
     * @var     string
	 */
	public $key = 'keywordID';

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

    }

    /**
     * Activation routines for this Model
     *
     * @since   1.0.7
     *
     * @global  $wpdb   WordPress DB Object
     */
    public function activate() {

        global $wpdb;

        // Enable error output if WP_DEBUG is enabled.
        $wpdb->show_errors = true;

        // Create database tables
        $wpdb->query( " CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "page_generator_keywords (
                            `keywordID` int(10) NOT NULL AUTO_INCREMENT,
                            `keyword` varchar(191) NOT NULL,
                            `columns` text NOT NULL,
                            `delimiter` varchar(191) NOT NULL,
                            `data` mediumtext NOT NULL,
                            PRIMARY KEY `keywordID` (`keywordID`),
                            UNIQUE KEY `keyword` (`keyword`)
                        ) " . $wpdb->get_charset_collate() . " AUTO_INCREMENT=1" ); 

    }

    /**
     * Upgrades the Model's database table if required columns
     * are missing.
     *
     * @since   1.7.8
     *
     * @global  $wpdb   WordPress DB Object
     */
    public function upgrade() {

        global $wpdb;

        // Fetch columns
        $columns = $wpdb->get_results( "SHOW COLUMNS FROM " . $wpdb->prefix . "page_generator_keywords" );

        // Bail if no columns found
        if ( ! is_array( $columns ) || count( $columns ) == 0 ) {
            return true;
        }

        // Define columns we're searching for
        $required_columns = array(
            'columns'   => false,
            'delimiter' => false,
        );

        // Iterate through columns
        foreach ( $columns as $column ) {
            if ( array_key_exists( $column->Field, $required_columns ) ) {
                $required_columns[ $column->Field ] = true;
            }
        }

        // Iterate through our required columns, adding them to the database table if they don't exist
        foreach ( $required_columns as $column => $exists ) {
            if ( $exists ) {
                continue;
            }

            switch ( $column ) {
                case 'columns':
                    $wpdb->query( " ALTER TABLE " . $wpdb->prefix . "page_generator_keywords
                            ADD COLUMN `" . $column . "` text NOT NULL AFTER `keyword`" );
                    break;

                case 'delimiter':
                    $wpdb->query( " ALTER TABLE " . $wpdb->prefix . "page_generator_keywords
                            ADD COLUMN `" . $column . "` varchar(191) NOT NULL AFTER `keyword`" );
                    break;
            }
        }

        return true;

    }

    /**
     * Changes the 'columns' field from varchar to text, so that many column names can be stored
     * against a Keyword
     *
     * @since   2.4.5
     */
    public function upgrade_columns_type_to_text() {

        global $wpdb;

        // Fetch columns
        $columns = $wpdb->get_results( "SHOW COLUMNS FROM " . $wpdb->prefix . "page_generator_keywords" );

        // Find column
        foreach ( $columns as $column ) {
            if ( $column->Field != 'columns' ) {
                continue;
            }

            // If here, we found the column we want
            if ( $column->Type == 'text' ) {
                // Already set to the correct type
                return true;
            }

            // Change column from varchar to text
            $wpdb->query( " ALTER TABLE " . $wpdb->prefix . "page_generator_keywords
                            MODIFY COLUMN `" . $column->Field . "` text NOT NULL" );

            return true;
        }

        return true;

    }

    /**
     * Gets a record by its ID
     *
     * @since   1.0.0
     *
     * @param   int    $id  ID
     * @return  mixed       Record | false
     */
    public function get_by_id( $id ) {

        global $wpdb;
       
        // Get record
        $query = $wpdb->prepare("   SELECT *
                                    FROM " . $wpdb->prefix . $this->table . "
                                    WHERE " . $this->key . " = %d
                                    LIMIT 1",
                                    $id ); 
        $results = $wpdb->get_results( $query, ARRAY_A );
        
        // Check a record was found     
        if ( ! $results ) {
            return false;
        }             
        if ( count( $results ) == 0 ) {
            return false;
        }

        // Get single result from array
        $result = $results[0];

        // Stripslashes
        $result['data'] = stripslashes( $result['data'] );
        $result['delimiter'] = stripslashes( $result['delimiter'] );
        $result['columns'] = stripslashes( $result['columns'] );

        // Expand data into array
        $result['dataArr'] = explode( "\n", $result['data'] );
        $result['columnsArr'] = explode( ",", $result['columns'] );

        // Return record
        return $result;

    }
    
    /**
     * Gets all results by the key/value pair
     *
     * @since   1.0.0
     *
     * @param   string  $field  Field Name
     * @param   string  $value  Field Value
     * @return  array           Records
     */
    public function get_by( $field, $value ) {
        
        global $wpdb;
       
        // Get record
        $query = $wpdb->prepare("   SELECT *
                                    FROM " . $wpdb->prefix . $this->table . "
                                    WHERE " . $field . " = '%s'",
                                    $value ); 
        $results = $wpdb->get_results( $query, ARRAY_A );

        // Check a record was found     
        if ( ! $results ) {
            return false;
        }
        if ( count( $results ) == 0 ) {
            return false;
        }

        // Get single result from array
        $result = $results[0];

        // Stripslashes
        $result['data'] = stripslashes( $result['data'] );
        $result['delimiter'] = stripslashes( $result['delimiter'] );
        $result['columns'] = stripslashes( $result['columns'] );

        // Expand data into array
        $result['dataArr'] = explode( "\n", $result['data'] );
        $result['columnsArr'] = explode( ",", $result['columns'] );

        // Return
        return $result;

    }
	
    /**
     * Returns an array of records
     *
     * @since   1.0.0
     * 
     * @param   string  $order_by           Order By Column (default: keyword, optional)
     * @param   string  $order              Order Direction (default: ASC, optional)
     * @param   int     $paged              Pagination (default: 1, optional)
     * @param   int     $results_per_page   Results per page (default: 10, optional)
     * @param   string  $search             Search Keywords (optional)
     * @return  array                       Records
     */
    public function get_all( $order_by = 'keyword', $order = 'ASC', $paged = 1, $results_per_page = 10, $search = '' ) {
        
        global $wpdb;
        
        $get_all = ( ( $paged == -1 ) ? true : false );

       	// Search? 
        if ( ! empty( $search ) ) {
	    	$query = $wpdb->prepare( " 	SELECT *
                                    	FROM " . $wpdb->prefix . $this->table . "
                                    	WHERE keyword LIKE '%%%s%%'
                                    	ORDER BY " . $order_by . " " . $order,
                                    	$search );
        } else {
	        $query = " 	SELECT *
                        FROM " . $wpdb->prefix . $this->table . "
                        ORDER BY " . $order_by . " " . $order;
        }

        // Add Limit
        if ( ! $get_all ) {
            $query = $query . $wpdb->prepare( " LIMIT %d, %d",
                                                ( ( $paged - 1 ) * $results_per_page ),
                                                $results_per_page );
        }

        // Get results
        $results = $wpdb->get_results( $query );

        // Check a record was found     
        if ( ! $results ) {
            return false;
        }             
        if ( count( $results ) == 0 ) {
            return false;
        }

      	return stripslashes_deep( $results );

    }

    /**
     * Returns an array of results for the given column.
     *
     * @since   1.9.4
     * 
     * @param   string  $order_by           Order By Column (default: keyword, optional)
     * @param   string  $order              Order Direction (default: ASC, optional)
     * @return  array                       Keywords
     */
    public function get_column( $column = 'keyword', $order_by = 'keyword', $order = 'ASC' ) {
        
        global $wpdb;
        
        // Get results
        $results = $wpdb->get_col( "SELECT " . $column . "
                                    FROM " . $wpdb->prefix . $this->table . "
                                    ORDER BY " . $order_by . " " . $order );

        // Check a record was found     
        if ( ! $results ) {
            return false;
        }             
        if ( count( $results ) == 0 ) {
            return false;
        }

        return stripslashes_deep( $results );

    }

    /**
     * Returns keywords and keywords with individual column subsets.
     *
     * @since   1.9.7
     * 
     * @param   bool    $include_curly_braces   Include Curly Braces on Keywords in Results
     * @return  array                           Keywords
     */
    public function get_keywords_and_columns( $include_curly_braces = false ) {
        
        global $wpdb;
        
        // Get results
        $results = $wpdb->get_results( "SELECT keyword, columns, delimiter
                                        FROM " . $wpdb->prefix . $this->table . "
                                        ORDER BY keyword ASC", ARRAY_A );

        // Check a record was found     
        if ( ! $results ) {
            return false;
        }             
        if ( count( $results ) == 0 ) {
            return false;
        }

        // Iterate through results, building keywords
        $keywords = array();
        foreach ( $results as $result ) {
            // Add keywords
            $keywords[] = ( $include_curly_braces ? '{' : '' ) . $result['keyword'] . ( $include_curly_braces ? '}' : '' );

            // If the columns are empty, ignore
            if ( empty( $result['columns'] ) ) {
                continue;
            }

            // If the delimiter is missing, ignore
            if ( empty( $result['delimiter'] ) ) {
                continue;
            }

            // Get columns
            $columns = explode( ',', $result['columns'] );
            if ( count( $columns ) == 0 ) {
                continue;
            }
            if ( ! is_array( $columns ) ) {
                continue;
            }

            // Add each column as a keyword
            foreach ( $columns as $column ) {
                $keywords[] = ( $include_curly_braces ? '{' : '' ) . $result['keyword'] . '(' . trim( $column ) . ')' . ( $include_curly_braces ? '}' : '' );
            }
        }

        // Return
        return stripslashes_deep( $keywords );

    }

    /**
     * Confirms whether a keyword already exists.
     *
     * @since   1.0.0
     *
     * @param   string  $keyword    Keyword
     * @return  bool                Exists
     */
    public function exists( $keyword ) {
        
        global $wpdb;
       
        // Get record
        $query = $wpdb->prepare("   SELECT keywordID
                                    FROM " . $wpdb->prefix . $this->table . "
                                    WHERE keyword = '%s'",
                                    $keyword ); 
        $results = $wpdb->get_results( $query, ARRAY_A );

        // Check a record was found     
        if ( ! $results ) {
            return false;
        }             
        if ( count( $results ) == 0 ) {
            return false;
        }

        return true;

    }
    
    /**
     * Get the number of matching records
     *
     * @since   1.0.0
     *
     * @param   string  $search Search Keywords (optional)
     * @return  bool            Exists
     */
    public function total( $search = '' ) {

        global $wpdb;
        
        // Prepare query
        if ( ! empty( $search ) ) {
            $query = $wpdb->prepare( "  SELECT COUNT(" . $this->key . ")
                                        FROM " . $wpdb->prefix . $this->table . "
                                        WHERE keyword LIKE '%%%s%%'",
                                        $search ); 
        } else {
            $query = "  SELECT COUNT( " . $this->key . " )
                        FROM " . $wpdb->prefix . $this->table; 
    
        }
        
        // Return count
        return $wpdb->get_var( $query );

    }

    /**
     * Converts an uploaded text file of keyword data into a data string that can be inserted into the DB
     *
     * @since   1.0.7
     *
     * @param   string  $existing_data  Existing Data
     * @return  string                  New Data
     */ 
    public function import_text_file_data( $existing_data = '' ) {

        // Check a file has been uploaded
        if ( ! isset( $_FILES['file'] ) ) {
            return $existing_data;
        }

        // Check uploaded file is a supported filetype
        if ( ! ( ! empty( $_FILES['file']['type'] ) && preg_match( '/(text|txt|csv)$/i', $_FILES['file']['type'] ) ) && 
            ! preg_match( '/(text|txt|csv)$/i', $_FILES['file']['name'] ) ) {
            return $existing_data;
        }
            
        // Get file contents
        $handle = fopen( $_FILES['file']['tmp_name'], 'r' );
        $contents = fread( $handle, filesize( $_FILES['file']['tmp_name'] ) );
        fclose( $handle );

        // Remove UTF8 BOM sequences
        $contents = $this->remove_utf8_bom( $contents );
                
        // Add / append data
        $existing_data .= ( ( strlen( $existing_data ) > 0 ) ? "\n" . $contents : $contents );  

        // Return
        return $existing_data;

    }

    /**
     * Converts an uploaded CSV file of keywords data into an array of keywords and terms, that can then be added
     * to the keywords DB table
     *
     * @since   1.7.3
     *
     * @param   string  $keywords_location  Keywords Location in CSV File (columns|rows)
     *
     * @return  mixed   WP_Error | int
     */ 
    public function import_csv_file_data( $keywords_location = 'columns' ) {

        // Check a file has been uploaded
        if ( ! isset( $_FILES['file'] ) ) {
            return new WP_Error( 'page_generator_pro_keywords_import_csv_file_data_missing', __( 'No file was uploaded.', 'page-generator-pro' ) );
        }

        // Check uploaded file is a supported filetype
        if ( ! ( ! empty( $_FILES['file']['type'] ) && preg_match( '/(csv)$/i', $_FILES['file']['type'] ) ) && 
            ! preg_match( '/(csv)$/i', $_FILES['file']['name'] ) ) {
            return new WP_Error( 'page_generator_pro_keywords_import_csv_file_data_unsupported_file_tye', __( 'The file uploaded is not a supported file type.  Please ensure you are uploading a CSV file.', 'page-generator-pro' ) );
        }
            
        // Get file contents
        $handle = fopen( $_FILES['file']['tmp_name'], 'r' );
        $contents = trim( fread( $handle, filesize( $_FILES['file']['tmp_name'] ) ) );
        fclose( $handle );

        // Bail if file contents are empty
        if ( strlen( $contents ) == 0 || empty( $contents ) ) {
            return new WP_Error( 'page_generator_pro_keywords_import_csv_file_data_empty', __( 'The uploaded file contains no data.', 'page-generator-pro' ) );  
        }

        // Fetch rows
        $rows = explode( "\n", $contents );

        // Bail if no rows found
        if ( count( $rows ) < 2 ) {
            return new WP_Error( 'page_generator_pro_keywords_import_csv_file_data_no_rows', __( 'The uploaded file only contains one row of data.  There must be at least two rows; the first being the keywords.', 'page-generator-pro' ) );
        }

        // Build array comprising of keywords and their terms
        $keywords = array();
        $keywords_terms = array();
        foreach ( $rows as $index => $row ) {
            $terms = str_getcsv( $row );

            // Depending on where the keywords are, parse the terms
            switch ( $keywords_location ) {
                /**
                 * Columns
                 */
                case 'columns':
                    // First row are keywords
                    if ( $index == 0 ) {
                        foreach ( $terms as $term ) {
                            $keywords[] = $this->remove_utf8_bom( $term );
                        }
                        break;
                    }

                    // Add this row's terms to the keywords array
                    foreach ( $terms as $term_index => $term ) {
                        if ( ! isset( $keywords_terms[ $keywords[ $term_index ] ] ) || ! is_array( $keywords_terms[ $keywords[ $term_index ] ] ) ) {
                            $keywords_terms[ $keywords[ $term_index ] ] = array();
                        }

                        $keywords_terms[ $keywords[ $term_index ] ][] = $this->remove_utf8_bom( $term );
                    }
                    break;

                /**
                 * Rows
                 */
                case 'rows':
                    // First term is a keyword; all other terms are the keyword's terms
                    // Add this row's terms to the keywords array
                    foreach ( $terms as $term_index => $term ) {
                        // First term is the keyword
                        if ( $term_index == 0 ) {
                            $keyword = $this->remove_utf8_bom( $term );
                            $keywords[] = $keyword;
                            continue;
                        }

                        // Other terms are terms
                        if ( ! isset( $keywords_terms[ $keyword ] ) || ! is_array( $keywords_terms[ $keyword ] ) ) {
                            $keywords_terms[ $keyword ] = array();
                        }

                        $keywords_terms[ $keyword ][] = $this->remove_utf8_bom( $term );
                    }
                    break;
            }
        }

        // Bail if we couldn't get any keyword terms
        if ( empty( $keywords_terms ) || count( $keywords_terms ) == 0 ) {
            return new WP_Error( 'page_generator_pro_keywords_import_csv_file_data_no_keyword_terms', __( 'No keywords and/or terms could be found in the uploaded file.', 'page-generator-pro' ) );
        }

        // For each keyword, check that a keyword doesn't already exist in the database
        foreach ( $keywords as $keyword ) {
            $exists = $this->exists( $keyword );

            // If the keyword exists, exit
            if ( $exists ) {
                return new WP_Error( 'page_generator_pro_keywords_import_csv_file_keyword_exists', sprintf( __( 'The %s keyword already exists.  No keywords or terms were imported.', 'page-generator-pro' ), $keyword ) );
            }
        }

        // If here, we are OK to add keywords and their terms to the database
        // Iterate through keyword terms, adding them to the database
        foreach ( $keywords_terms as $keyword => $terms ) {
            $result = $this->save( array(
                'keyword' => $keyword,
                'data'    => implode( "\n", $terms ),
            ) );

            // If an error occured, bail
            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }

        // Return the number of keywords added
        return count( $keywords_terms );

    }

    /**
     * Removes UTF8 BOM sequences from the given string
     *
     * @since   2.2.1
     *
     * @param   string  $text   Possibly UTF8 BOM encoded string
     * @param   string          String with UTF8 BOM sequences removed
     */
    private function remove_utf8_bom( $text ) {
        
        $bom = pack( 'H*','EFBBBF' );
        $text = preg_replace( "/^$bom/", '', $text );
        
        return trim( $text );
    
    }

    /**
     * Adds or edits a record, based on the given data array.
     *
     * @since   1.0.0
     * 
     * @param   array   $data           Array of data to save
     * @param   int     $id             ID (if set, edits the existing record)
     * @param   bool    $append_terms   Whether to append terms to the existing Keyword Term data (false = replace)
     * @return  mixed                   ID or WP_Error
     */
    public function save( $data, $id = '', $append_terms = false ) {

        global $wpdb;

        // Check for required data fields
        if ( empty( $data['keyword'] ) ) {
            return new WP_Error( 'page_generator_pro_keywords_save_validation_error', __( 'Please complete the keyword field.', 'page-generator-pro' ) );
        }
        if ( empty( $data['data'] ) ) {
            return new WP_Error( 'page_generator_pro_keywords_save_validation_error', __( 'Please complete the keyword data field.', 'page-generator-pro' ) );
        }

        // Check that the keyword does not contain spaces
        if ( preg_match( '/[\\s\'\/~`\!@#\$%\^&\*\(\)\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $data['keyword'] ) ) {
            return new WP_Error( 'page_generator_pro_keywords_save_validation_error', __( 'The Keyword field can only contain letters, numbers and underscores.', 'page-generator-pro' ) );
        }

        // Check that the columns do not contain spaces
        if ( isset( $data['columns'] ) ) {
            $data['columns'] = str_replace( ' ', '',$data['columns'] );
        }

        // If column names are specified, check a delimiter exists
        if ( ! empty( $data['columns'] ) && empty( $data['delimiter'] ) ) {
            return new WP_Error( 
                'page_generator_pro_keywords_save_validation_error',
                __( 'Delimiter Field: When specifying column names in the Columns Field, a delimiter must also be specified.', 'page-generator-pro' ) );
        }

        // If a delimiter is supplied, perform some further validation checks
        if ( ! empty( $data['delimiter'] ) ) {
            // Check the delimiter isn't a pipe symbol, curly brace or bracket
            foreach ( $this->get_invalid_delimiters() as $invalid_delimiter ) {
                if ( strpos( $data['delimiter'], $invalid_delimiter ) !== false ) {
                    return new WP_Error( 
                        'page_generator_pro_keywords_save_validation_error',
                        sprintf(
                            __( 'Delimiter Field: %s cannot be used as a delimiter, as it may conflict with Keyword and Spintax syntax', 'page-generator-pro' ),
                            '<code>' . $data['delimiter'] . '</code>'
                        )
                    );
                }
            }

            // Check that column names are specified
            if ( ! isset( $data['columns'] ) || empty( $data['columns'] ) ) {
                return new WP_Error( 
                    'page_generator_pro_keywords_save_validation_error',
                    __( 'Columns Field: Two or more column names must be specified in the Columns Field When specifying a delimiter.', 'page-generator-pro' )
                );
            }

            // Check that there is a comma in the column names for separating columns
            if ( strpos( $data['columns'], ',' ) === false ) {
                return new WP_Error( 
                    'page_generator_pro_keywords_save_validation_error', 
                    __( 'Columns Field: The values specified in the Columns Field must be separated by a comma.', 'page-generator-pro' )
                );
            }

            // Check that the delimiter exists in the first term
            $first_term = trim( strtok( $data['data'], "\n" ) );
            if ( strpos( $first_term, $data['delimiter'] ) === false ) {
                return new WP_Error( 
                    'page_generator_pro_keywords_save_validation_error', 
                    sprintf(
                        __( 'Delimiter Field: The specified delimiter %s could not be found in the Terms lists\' first term.
                            Ensure the delimiter used in the Terms list matches the Delimiter Field.', 'page-generator-pro' ),
                        '<code>' . $data['delimiter'] . '</code>'
                    )
                );
            }

            // Check that the number of columns specified matches the number of deliniated items in the first term
            $term = str_getcsv( stripslashes( $first_term ), $data['delimiter'] );
            $columns = explode( ',', trim( $data['columns'] ) );
            if ( count( $term ) != count( $columns ) ) {
                 return new WP_Error( 
                    'page_generator_pro_keywords_save_validation_error',
                    __( 'Columns Field: The number of column names detected does not match the number of deliniated items in the first term.', 'page-generator-pro' )
                );
            }
        }

        // Strip empty newlines from Terms
        $data['data'] = trim( preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data['data'] ) );

        // If the data isn't UTF-8, UTF-8 encode it so it can be inserted into the DB
        if ( function_exists( 'mb_detect_encoding' ) && ! mb_detect_encoding( $data['data'], 'UTF-8', true ) ) {
            $data['data'] = utf8_encode( $data['data'] );
        }

        // Fill missing keys with empty values to avoid DB errors in the Free version
        if ( ! isset( $data['columns'] ) ) {
            $data['columns'] = '';
        }
        if ( ! isset( $data['delimiter'] ) ) {
            $data['delimiter'] = '';
        }

        // Depending on whether an ID has been defined, update or insert the keyword
        if ( ! empty( $id ) ) {
            if ( $append_terms ) {
                // Prepare query
                $query = $wpdb->prepare( "  UPDATE " . $wpdb->prefix . $this->table . "
                                            SET keyword = %s,
                                            delimiter = %s,
                                            columns = %s,
                                            data = concat(data, '" . addslashes( $data['data'] ) . "')
                                            WHERE " . $this->key . " = %s",
                                            $data['keyword'],
                                            $data['delimiter'],
                                            $data['columns'],
                                            $id );

                // Run query
                $result = $wpdb->query( $query );
            } else {
                // Editing an existing record
                $result = $wpdb->update( 
                    $wpdb->prefix . $this->table, 
                    $data, 
                    array(
                        $this->key => $id,
                    ),
                    array( 
                        '%s', 
                        '%s',
                        '%s',
                        '%s',   
                    )
                );
            }

            // Check query was successful
            if ( $result === FALSE ) {
                return new WP_Error( 'db_query_error', __( 'Keyword could not be updated in the database. Database error: ' . $wpdb->last_error ), $wpdb->last_error ); 
            }

            // Success!
            return $id;
        } else {
            // Create new record
            $result = $wpdb->insert( 
                $wpdb->prefix . $this->table, 
                $data, 
                array( 
                    '%s', 
                    '%s',
                    '%s',
                    '%s',   
                )
            );
          
            // Check query was successful
            if ( $result === FALSE ) {
                return new WP_Error( 'db_query_error', __( 'Keyword could not be added to the database. Database error: ' . $wpdb->last_error ), $wpdb->last_error ); 
            }
            
            // Get and return ID
            return $wpdb->insert_id;
        }    

    }
 
    /**
     * Deletes the record for the given primary key ID
     *
     * @since   1.0.0
     * 
     * @param   mixed   $data   Single ID or array of IDs
     * @return  bool            Success
     */
    public function delete( $data ) {

        global $wpdb;
        
        if ( is_array( $data ) ) {
            foreach ( $data as $keyword_id ) {
                // Delete Keyword
                $result = $wpdb->delete(
                    $wpdb->prefix . $this->table,
                    array(
                        'keywordID' => $keyword_id,
                    )
                );

                // Check query was successful
                if ( $result === FALSE ) {
                    return new WP_Error( 'db_query_error', __( 'Record(s) could not be deleted from the database. DB said: '.$wpdb->last_error ), $wpdb->last_error );
                }
            }
            $query = "  DELETE FROM " . $wpdb->prefix . $this->table . "
                        WHERE " . $this->key . " IN (" . implode( ',', $data ) . ")";
        } else {
            // Delete Keyword
            $result = $wpdb->delete(
                $wpdb->prefix . $this->table,
                array(
                    'keywordID' => $data,
                )
            );

            // Check query was successful
            if ( $result === FALSE ) {
                return new WP_Error( 'db_query_error', __( 'Record(s) could not be deleted from the database. DB said: '.$wpdb->last_error ), $wpdb->last_error );
            }
        }
        
        return true;

    }

    /**
     * Duplicates the given ID to a new row
     *
     * @since   1.7.8
     *
     * @param   int     $id     Keyword ID
     * @return  mixed           WP_Error | Copied Keyword ID
     */
    public function duplicate( $id ) {

        // Fetch keyword
        $keyword = $this->get_by_id( $id );

        // Bail if no keyword was found
        if ( ! $keyword ) {
            return new WP_Error( 'page_generator_pro_keywords_duplicate', __( 'Keyword could not be found for duplication.', 'page-generator-pro' ) );
        }

        // Delete some keys from the data
        unset( $keyword['keywordID'], $keyword['dataArr'], $keyword['columnsArr'] );

        // Rename the keyword
        $keyword['keyword'] .= '_copy';

        // Save the keyword as a new keyword
        $result = $this->save( $keyword );

        // Return the result (WP_Error | int)
        return $result;

    }

    /**
     * Outputs a <select> dropdown comprising of Keywords, including any
     * Keyword with Column combinations.
     *
     * @since   1.9.7
     *
     * @param   array   $keywords   Keywords
     * @param   string  $element    HTML Element ID to insert Keyword into when selected in dropdown
     */ 
    public function output_dropdown( $keywords, $element ) {

        // Load view
        include( $this->base->plugin->folder . 'views/admin/keywords-dropdown.php' );

    }

    private function get_invalid_delimiters() {

        return array(
            '|',
            '{',
            '}',
            '(',
            ')',
            ':',
        );

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
        $name = 'keywords';

        // Warn the developer that they shouldn't use this function.
        _deprecated_function( __FUNCTION__, '1.9.8', 'Page_Generator_Pro()->get_class( \'' . $name . '\' )' );

        // Return the class
        return Page_Generator_Pro()->get_class( $name );

    }

}