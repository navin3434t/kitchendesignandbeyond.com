<?php
/**
 * Thesaurus API class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.6.3
 */
class Page_Generator_Pro_Thesaurus extends Page_Generator_Pro_API {

    /**
     * Holds the API endpoint
     *
     * @since   2.6.3
     *
     * @var     string
     */
    public $api_endpoint = 'https://www.thesaurus.com';

    /**
     * Returns synonyms for the given keyword
     *
     * @since   2.6.3
     *
     * @param   string  $keyword    Keyword
     * @return  mixed               WP_Error | array
     */
	public function get_synonyms( $keyword ) {

        // Run the query
        $html = $this->get( 'browse/' . $keyword, false, false );

        // Bail if an error occured
        if ( is_wp_error( $html ) ) {
            return $html;
        }

        // Load HTML into DOMDocument
        libxml_use_internal_errors( true );
        $dom = new DOMDocument();
        $dom->loadHTML( $html );
        libxml_use_internal_errors( false );
        
        // Load DOMDocument into DOMXpath
        $xpath = new DOMXPath( $dom );

        // Find synonyms
        $synonyms = $xpath->query( '//span[contains(@class, "etbu2a32")]' );

        // Bail if no synonyms found
        if ( ! $synonyms->count() ) {
            return new WP_Error( 
                'page_generator_pro_thesaurus_get_synonyms_no_results', 
                sprintf(
                    __( 'No synonyms found for the Keyword %s', 'page-generator-pro' ),
                    $keyword
                )
            );
        }
        
        // Build an array of results
        $results = array();
        foreach ( $synonyms as $synonym ) {
            $results[] = trim( (string) $synonym->textContent );
        }
       
        // Filter results, removing blank values and duplicates
        $results = array_values( array_filter( array_unique( $results ) ) );

        // Bail if no results found from synonyms
        if ( ! count( $results ) ) {
            return new WP_Error( 
                'page_generator_pro_thesaurus_get_synonyms_no_results', 
                sprintf(
                    __( 'No synonyms found for the Keyword %s', 'page-generator-pro' ),
                    $keyword
                )
            );
        }
        
        // Return synonyms
        return $results;

	}

}