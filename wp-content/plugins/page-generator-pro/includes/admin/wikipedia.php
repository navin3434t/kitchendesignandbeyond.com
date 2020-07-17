<?php
/**
 * Wikipedia class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.2.7
 */
class Page_Generator_Pro_Wikipedia {

    /**
     * Holds the base object.
     *
     * @since   2.2.7
     *
     * @var     object
     */
    public $base;

    /**
     * Holds fetched Wikipedia articles in single request cycle
     *
     * @since   2.2.7
     * 
     * @var     array
     */
    private $cache = array();

    /**
     * Checks if a Page exists for the given Term and Language
     *
     * @since   2.2.7
     *
     * @param   string  $term       Term / URL
     * @param   string  $language   Language
     * @return  mixed               WP_Error | true
     */
    public function page_exists( $term, $language ) {

        // If the page exists in cache, it exists
        if ( isset( $this->cache[ $term . '-' . $language ] ) ) {
            return true;
        }

        // If the Term is a Wikipedia URL, it exists
        if ( filter_var( $term, FILTER_VALIDATE_URL ) && strpos( $term, 'wikipedia.org' ) !== false ) {
            return true;
        } 

        // Query API to check if page exists on Wikipedia
        $url = 'http://' . $language . '.wikipedia.org/w/api.php?section=0&action=parse&page=' . $term . '&format=json&prop=text&redirects';
        $response = wp_remote_get( $url );

        // Bail if an error occured
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Bail if HTTP response code isn't valid
        if ( $response['response']['code'] != 200 ) {
            return new WP_Error( 'page_generator_pro_wikipedia_page_exists_http_error', $response['response']['code'] );
        }

        // Check response
        $body = wp_remote_retrieve_body( $response );
        $check = json_decode( $body );

        // Bail if an error was received from Wikipedia
        if ( isset( $check->error ) ) {
            return new WP_Error( 'page_generator_pro_wikipedia_page_exists_error', $check->error->code . ': ' . $check->error->info );
        }

        return true;

    }

    /**
     * Checks if a Page exists for the given Term and Language
     *
     * @since   2.2.7
     *
     * @param   string  $term                   Term / URL
     * @param   bool    $use_similar_page       Use a similar Page if the Term's Page cannot be found
     * @param   array   $sections               Section(s) to fetch
     * @param   string  $language               Language
     * @param   mixed   $elements               Element (string) or Elements (array)
     * @param   bool    $remove_links           Remove Links
     * @return  mixed                           WP_Error | string
     */
    public function get_page_sections( $term, $use_similar_page = false, $sections = false, $language = 'en', $elements = 'paragraphs', $remove_links = true ) {

        // If the page doesn't exist in cache, fetch it now
        if ( ! isset( $this->cache[ $term . '-' . $language ] ) ) {
            // Get entire Wikipedia Page
            $page = $this->get_page( $term, $language );
            if ( is_wp_error( $page ) ) {
                return $page;
            }

            // If the term is ambiguous, and could refer to one of several articles on Wikipedia, either fetch
            // that article or bail, depending on the $use_similar_page setting
            if ( $this->is_disambiguation_page( $page ) ) {
                if ( ! $use_similar_page ) {
                    return new WP_Error( 
                        'page_generator_pro_get_page_sections_ambiguous_term', 
                        sprintf(
                            __( 'The Term "%s" is ambiguous and could relate to one of several articles available on Wikipedia.  To use one of these similar articles, set use_similar_page=1 in your shortcode.', 'page-generator-pro' ),
                            $term
                        )
                    );
                }

                // Get similar term that has a full Wikipedia Page
                $term = $this->get_similar_term( $page );

                // Get entire similar Page
                $page = $this->get_page( $term, $language );
                if ( is_wp_error( $page ) ) {
                    return $page;
                }
            }

            // Get content
            $content = $this->get_content( $page );
            if ( is_wp_error( $content ) ) {
                return $content;
            }

            // Build cache
            $cache = array(
                'content' => $content,
            );

            // Get TOC Headings and Keys
            $headings = $this->get_headings( $page );
            if ( $headings ) {
                $cache['headings'] = $headings;
                $cache['headings_keys'] = array_keys( $headings );
            }

            // Store in cache
            $this->cache[ $term . '-' . $language ] = $cache;

            // Cleanup unused vars
            unset( $content, $headings );
        }

        // If no sections are specified, return the summary
        if ( ! $sections || is_array( $sections ) && count( $sections ) == 0 ) {
            $return_elements = $this->get_elements( $this->cache[ $term . '-' . $language ]['content'], $term, false, 'toc', $elements, $remove_links );
            
            // If no elements found, bail
            if ( count( $return_elements ) == 0 ) {
                return new WP_Error( 
                    'page_generator_pro_wikipedia_get_page_sections_no_elements_found',
                    sprintf(
                        __( 'No elements could be found in the summary section matching %s', 'page-generator-pro' ),
                        implode( ',', $elements )
                    )
                );
            }

            return $return_elements;
        }

        // If no headings could be found, return the summary
        if ( ! $this->cache[ $term . '-' . $language ]['headings'] ) {
            $return_elements = $this->get_elements( $this->cache[ $term . '-' . $language ]['content'], $term, false, 'toc', $elements, $remove_links );

            // If no elements found, bail
            if ( count( $return_elements ) == 0 ) {
                return new WP_Error( 
                    'page_generator_pro_wikipedia_get_page_sections_no_elements_found',
                    sprintf(
                        __( 'No headings could be found, and no elements could be found in the summary section matching %s', 'page-generator-pro' ),
                        implode( ',', $elements )
                    )
                );
            }

            return $return_elements;
        }

        // Iterate through each section, fetching elements
        $return_elements = array();
        foreach ( $sections as $section ) {
            unset( $result );

            switch ( $section ) {
                case 'summary':
                    $result = $this->get_elements( $this->cache[ $term . '-' . $language ]['content'], $term, false, 'toc', $elements, $remove_links );
                    break;

                default:
                    // Get index of this section from the array of headings
                    $index = $this->get_heading_index( $this->cache[ $term . '-' . $language ]['headings'], $section );

                    // If no index could be found, skip this section
                    if ( $index === false ) {
                        break;
                    }

                    // Based on the index of this heading, define the start and end heading keys (IDs)
                    $start_heading = $this->cache[ $term . '-' . $language ]['headings_keys'][ $index ];

                    // If this section is the last heading, there isn't a 'next' heading that we can use
                    // to determine the end of the content, so we use the navbox instead
                    if ( ! isset( $this->cache[ $term . '-' . $language ]['headings_keys'][ $index + 1 ] ) ) {
                        $end_heading = 'navbox';
                    } else {
                        $end_heading = $this->cache[ $term . '-' . $language ]['headings_keys'][ $index + 1 ];
                    }

                    // Extract elements
                    $result = $this->get_elements( $this->cache[ $term . '-' . $language ]['content'], $term, $start_heading, $end_heading, $elements, $remove_links );
                    break;
            }

            // Skip if no content found
            if ( ! isset( $result ) || count( $result ) == 0 ) {
                continue;
            }

            // Add the results (elements) to the main array
            $return_elements = array_merge( $return_elements, $result );
        }

        // If no elements found, bail
        if ( count( $return_elements ) == 0 ) {
            return new WP_Error( 
                'page_generator_pro_wikipedia_get_page_sections_no_content_found',
                sprintf( 
                    __( 'No content could be found in the sections %s for the elements %s', 'page-generator-pro' ),
                    implode( ', ', $sections ),
                    implode( ', ', $elements )
                )
            );
        }

        // Return elements
        return $return_elements;

    }

    /**
     * Returns a DOMDocument representing a Wikipedia Article for the given Term
     *
     * @since   2.2.7
     *
     * @param   string  $term       Term / URL
     * @param   string  $language   Language
     * @return  mixed               WP_Error | DOMDocument (Wikipedia Page)
     */
    private function get_page( $term, $language = 'en' ) {

        // Determine the URL to query
        if ( filter_var( $term, FILTER_VALIDATE_URL ) ) {
            $url = $term;
        } else {
            $url = 'http://' . $language . '.wikipedia.org/wiki/' . str_replace(' ', '_', $term );
        }

        // Attempt to fetch data from Wikipedia
        // User-agent ensures we get all content, not truncated invalid HTML markup
        $response = wp_remote_get( $url, array(
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36',
        ) );

        // Bail if an error occured
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Bail if HTTP response code isn't valid
        if ( $response['response']['code'] != 200 ) {
            return new WP_Error( 'page_generator_pro_wikipedia_get_page_http_error', $response['response']['code'] );
        }

        // Get body
        $body = wp_remote_retrieve_body( $response );
        if ( empty( $body ) ) {
            return new WP_Error( 'page_generator_pro_wikipedia_get_page_empty', __( 'Wikipedia Shortcode Error: No Wikipedia content found', 'page-generator-pro' ) );
        }

        // Parse into DOMDocument
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false; 

        // Convert encoding to UTF-8 if php-mbstring is installed
        if ( function_exists( 'mb_convert_encoding' ) ) {
            @$dom->loadHTML( mb_convert_encoding( $body, 'HTML-ENTITIES', 'UTF-8' ) );
        } else {
            // Cannot guarantee this works as mb_convert_encoding is not available
            @$dom->loadHTML( $body );
        }

        return $dom;

    }

    /**
     * Flag to denote if the page is a disambiguation page, meaning that the term given
     * is too ambiguous to determine which article to fetch
     *
     * @since   2.2.8
     *
     * @param   DOMDocument     $dom    Wikipedia Page DOM
     * @return  bool                    Is Disambiguation Page
     */
    private function is_disambiguation_page( $dom ) {

        // Check if a disambiguation element exists
        $disambigbox = $dom->getElementById( 'disambigbox' );
        $disambig = $dom->getElementById( 'disambig' );
        if ( is_null( $disambigbox ) && is_null( $disambig ) ) {
            return false;
        }

        return true;

    }

    /**
     * Returns the first similar term from the list of "may refer to" links
     * where the given DOM represents a Wikipedia Disambiguation Page
     *
     * @since   2.2.8
     *
     * @param   DOMDocument     $dom        Wikipedia Page DOM
     * @param   string          $language   Language
     * @return  mixed                       WP_Error | string
     */
    private function get_similar_term( $dom, $language = 'en' ) {

        // Sanity check that a disambiguation element exists
        if ( ! $this->is_disambiguation_page( $dom ) ) {
            return new WP_Error( 
                'page_generator_pro_wikipedia_get_similar_page_not_disambiguation', 
                __( 'The given Page is not a disambiguation page, therefore no similar page can be fetched.', 'page-generator-pro' )
            );
        }

        // Get content
        $content = $this->get_content( $dom );
        if ( is_wp_error( $content ) ) {
            return $content;
        }

        // Get terms listed in the 'may refer to' part
        $links = $this->get_similar_page_terms( $content );
        if ( is_wp_error( $links ) ) {
            return $links;
        }

        // Return first link's term as a DOMDocument
        return $links[0];

    }

    /**
     * Returns an array of all Terms found in the Wikipedia article's
     * 'may refer to' links.
     *
     * @since   2.2.8
     *
     * @param   DOMElement  $content    Wikipedia Page Content
     * @return  mixed                   WP_Error | array
     */
    private function get_similar_page_terms( $content ) {

        // Fetch list items in content
        $similar_pages = $content->getElementsByTagName( 'li' );

        // Bail if no table of contents could be found
        if ( ! $similar_pages->length ) {
            return new WP_Error( 
                'page_generator_pro_wikipedia_get_similar_page_similar_pages_empty', 
                __( 'There are no similar page links on this disambiguation page.', 'page-generator-pro' )
            );
        }

        // Extract link names and anchors
        $terms = array();
        foreach ( $similar_pages as $similar_page ) {
            foreach ( $similar_page->childNodes as $link ) {
                // Skip if not a link
                if ( $link->nodeName != 'a' ) {
                    continue;
                }

                // Skip if the class name contains 'new' - there's no published article available
                if ( strpos( $link->getAttribute( 'class' ), 'new' ) !== false ) {
                    continue;
                }

                $terms[] = $link->nodeValue;
            }
        }       

        // Bail if no links found
        if ( count( $terms ) == 0 ) {
            return new WP_Error( 
                'page_generator_pro_wikipedia_get_similar_page_similar_pages_empty', 
                __( 'There are no similar page terms on this disambiguation page.', 'page-generator-pro' )
            );
        }     

        // Return terms
        return $terms;

    }

    /**
     * Returns the main content of the Wikipedia article
     *
     * @since   2.2.7
     *
     * @param   DOMDocument     $dom    Wikipedia Page DOM
     * @return  mixed                   WP_Error | DOMElement (Article Content)
     */
    private function get_content( $dom ) {

        // Get content
        $content = $dom->getElementById( 'mw-content-text' );

        // Bail if the table of contents element could not be found
        if ( is_null( $content ) ) {
            return new WP_Error( 'page_generator_pro_wikipedia_get_headings_content_element_not_found', __( 'No Content element could be found.', 'page-generator-pro' ) );
        }

        // Iterate through content until we find the .mw-parser-output element
        foreach ( $content->childNodes as $node ) {
            if ( $node->getAttribute( 'class' ) == 'mw-parser-output' ) {
                return $node; 
            }
        }

        // If here, we couldn't find the .mw-parser-output element
        return new WP_Error( 'page_generator_pro_wikipedia_get_content_mw_parser_output_class_missing', __( 'The mw-parser-output CSS class could not be found on the Wikipedia Page', 'page-generator-pro' ) );

    }

    /**
     * Returns an array of all headings found in the Wikipedia article's
     * Table of Contents
     *
     * @since   2.2.7
     *
     * @param   DOMDocument     $dom    Wikipedia DOM
     * @return  mixed                   bool | array
     */
    private function get_headings( $dom ) {

        // Get table of contents
        $toc_element = $dom->getElementById( 'toc' );

        // Bail if the table of contents element could not be found
        if ( is_null( $toc_element ) ) {
            return false;
         }

        // Get table of contents
        $table_of_contents = $toc_element->getElementsByTagName( 'li' );

        // Bail if no table of contents could be found
        if ( ! $table_of_contents->length ) {
            return false;
        }

        // Extract heading names and anchors
        $headings = array();
        foreach ( $table_of_contents as $heading ) {
            // Skip if this is not a top level heading
            if ( strpos( $heading->getAttribute( 'class' ), 'toclevel-1' ) === false ) {
                continue;
            }

            foreach ( $heading->childNodes as $link ) {
                // Skip if not a link
                if ( $link->nodeName != 'a' ) {
                    continue;
                }

                // Get heading text parts, so we just get the text, not the number
                $heading_parts = $link->getElementsByTagName( 'span' );

                // If no heading parts found, just use the node as the text
                if ( ! $heading_parts->length ) {
                    $headings[ str_replace( '#', '', $link->getAttribute( 'href' ) ) ] = $link->nodeValue;
                    continue;
                }

                // Iterate through heading parts
                foreach ( $heading_parts as $heading_part ) {
                    $class = $heading_part->getAttribute( 'class' );
                    if ( $class != 'toctext' ) {
                        continue;
                    }

                    // We found the heading text
                    $headings[ str_replace( '#', '', $link->getAttribute( 'href' ) ) ] = $heading_part->nodeValue;
                    continue;
                }
            }
        }            

        // Return headings
        return $headings;

    }

    /**
     * Searches both keys and values for the given array of headings to find a heading
     *
     * @since   2.2.7
     *
     * @param   array   $headings   Headings
     * @param   string  $search     Heading to search for
     * @return  mixed               false | index
     */
    private function get_heading_index( $headings, $search ) {

        $search = strtolower( $search );

        $i = 0;
        foreach ( $headings as $heading => $label ) {
            if ( strtolower( $heading ) == $search ) {
                return $i;
            }

            if ( strtolower( $label ) == $search ) {
                return $i;
            }

            $i++;
        }

        return false;

    }

    /**
     * Returns an array of specified elements between the given start and end element
     *
     * @since   2.2.7
     *
     * @param   DOMElement  $content        Article Content Node
     * @param   string      $term           Term
     * @param   mixed       $start_element  false | string
     * @param   mixed       $end_element    false | string
     * @param   array       $elements       Elements to Return
     * @param   bool        $remove_links   Remove Links (default: true)
     * @return  array                       Elements
     */
    private function get_elements( $content, $term, $start_element = false, $end_element = false, $elements = 'paragraphs', $remove_links = true ) {

        // Define array to store elements in
        $return_elements = array();

        // Flag to denote whether we should start collecting elements
        $collect_elements = ( ! $start_element ? true : false );

        foreach ( $content->childNodes as $node ) {
            if ( ! $node instanceof DOMElement ) {
                continue;
            }

            // Start collecting elements if we've not yet started and this element matches our start element selector
            if ( $start_element != false && $this->is_element( $node, $start_element ) ) {
                $collect_elements = true;
            }

            // Stop collecting elements if we've reached the end element
            if ( $end_element != false && $this->is_element( $node, $end_element ) ) {
                $collect_elements = false;
                break;
            }

            // Skip if we're not yet collecting elements
            if ( ! $collect_elements ) {
                continue;
            }

            // Skip if not an element we want
            if ( ! in_array( $node->tagName, $this->get_tags_by_elements( $elements ) ) ) {
                continue;
            }

            // Get text
            $text = trim( $node->nodeValue );

            // Skip if empty
            if ( empty( $text ) ) {
                continue;
            }

            // Skip if this entire elements's nodeValue matches the keyword
            if ( strpos( $term, $text ) !== false ) {
                continue;
            }

            // Strip some child nodes that we don't want
            $node = $this->remove_child_nodes( $node );

            // Save HTML of node so we get the entire markup for this element 
            $content = $node->ownerDocument->saveHTML( $node );

            // Skip if the elements starts with certain characters
            if ( strpos( $content, '[[' ) !== false ) {
                continue;
            }

            // Remove footnotes
            $content = preg_replace( "/\[[a-z0-9]+\]/", null, $content );

            // Remove links, if required
            if ( $remove_links ) {
                $content = preg_replace( array( '"<a (.*?)>"', '"</a>"' ), array( '','' ), $content );
            }

            // Add elements to array
            $return_elements[] = $content;
        }

        return $return_elements;

    }

    /**
     * Returns an array of supported elements that can be fetched from
     * a Wikipedia Article, with their values being label names
     *
     * @since   2.7.1
     *
     * @return  array   Supported Elements
     */
    public function get_supported_elements() {

        return array(
            'paragraphs'    => __( 'Paragraphs', 'page-generator-pro' ),
            'headings'      => __( 'Headings', 'page-generator-pro' ),
            'lists'         => __( 'Lists', 'page-generator-pro' ),
            'tables'        => __( 'Tables', 'page-generator-pro' ),
            'images'        => __( 'Images', 'page-generator-pro' ),
        );

    }

    /**
     * Returns an array of supported elements that can be fetched from
     * a Wikipedia Article, with their values being an array of HTML tags
     *
     * @since   2.7.1
     *
     * @return  array   Supported Elements
     */
    public function get_supported_elements_tags() {

        return array(
            'paragraphs'    => array( 'p' ),
            'headings'      => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ),
            'lists'         => array( 'ol', 'ul' ),
            'tables'        => array( 'table' ),
            'images'        => array( 'img' ),
        );

    }

    /**
     * Returns an array of HTML tags (e.g. p,h1) for the given element names (e.g. paragraphs,headings)
     *
     * @since   2.7.1
     *
     * @param   mixed   $elements   Elements (string|array)
     * @return                      HTML Elements
     */
    private function get_tags_by_elements( $elements ) {

        // Convert elements to an array if it's a string
        if ( ! is_array( $elements ) ) {
            $elements = array( $elements );
        }

        // Get element names and their tags
        $elements_tags = $this->get_supported_elements_tags();

        // Build array of HTML tags
        $tags = array();
        foreach ( $elements as $element ) {
            // Skip if element isn't supported
            if ( ! isset( $elements_tags[ $element ] ) ) {
                continue;
            }

            $tags = array_merge( $tags, $elements_tags[ $element ] );
        }

        // Return
        return $tags;

    }

    /**
     * Recursively iterates through the node to see if it, or any descendents,
     * have an ID or class attribute matching the given search
     *
     * @since   2.2.7
     *
     * @param   DOMNode     $node       Node
     * @param   string      $search     Search Class or ID
     * @return  bool                    Element matches Search by ID or class
     */
    private function is_element( $node, $search ) {

        // Return true if the element's ID matches our search term
        if ( $node->getAttribute( 'id' ) == $search ) {
            return true;
        }

        // Return true if a class name matches our search term
        $classes = explode( ' ', $node->getAttribute( 'class' ) );
        if ( in_array( $search, $classes ) ) {
            return true;
        }

        // If children exist, iterate them now
        if ( $node->childNodes ) {
            foreach( $node->childNodes as $child_node ) {
                if ( ! $child_node instanceof DOMElement ) {
                    continue;
                }

                if ( $this->is_element( $child_node, $search ) ) {
                    return true;
                }
            }
        }

        return false;

    }

    /**
     * Removes links, if specified, from the given node, as well as some predefined
     * child nodes that we don't want, such as Wikipedia Edit Links
     *
     * @since   2.7.1
     *
     * @param   DOMNode     $node   Node
     * @return  DOMNode             Node
     */
    private function remove_child_nodes( $node ) {

        // Define tags and CSS class combinations to remove
        $tags = array(
            'sup' => array(
                'reference',
            ),
            'span' => array(
                'mw-editsection',
                'rt-commentedText',
            )
        );

        // Iterate through tags
        foreach ( $tags as $tag => $classes ) {
            $child_nodes = $node->getElementsByTagName( $tag );

            // If no child nodes matching the tag exist, bail
            if ( ! $child_nodes->length ) {
                continue;
            }

            // Iterate through tags
            foreach ( $child_nodes as $child_node ) {
                // Get CSS classes
                $child_node_classes = $child_node->getAttribute( 'class' );

                // Skip if no classes
                if ( empty( $child_node_classes ) ) {
                    continue;
                }

                // Iterate through classes that would require us to remove this child node
                foreach ( $classes as $class ) {
                    // Skip if this class doesn't exist in the child node's classes
                    if ( strpos( $child_node_classes, $class ) === false ) {
                        continue;
                    }

                    // If here, we need to remove this child node
                    try {
                        $node->removeChild( $child_node );
                    } catch ( Exception $e ) {
                    }
                }
            }
        }

        return $node;

    }

}