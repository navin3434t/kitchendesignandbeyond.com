<?php
/**
 * Block Spin class
 * 
 * @package Page_Generator_Pro
 * @author  Tim Carr
 * @version 2.0.1
 */
class Page_Generator_Pro_Block_Spin {

    /**
     * Holds the base object.
     *
     * @since   2.0.1
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor.
     *
     * @since   2.0.1
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

    }

    /**
     * Parses content, which comprises of one or more paragraphs, denoted with #p# and #/p#.
     *
     * @since   2.0.1
     *
     * @param   string  $text   Block Spintax Text
     * @return  string          Spun Text
     */
    public function process( $text ) {

        // If #section# is specified, we need to reorder the paragraphs inside the section
        if ( strpos( $text, '#section' ) !== false ) {
            $spun_text = preg_replace_callback(
                "/\#section\#(.*?)\#\/section\#/si",
                array( $this, 'parse_section' ),
                $text
            );
        } else {
            $spun_text = $text;
        }

        // Parse #p# and #s# not inside a #section#
        $spun_text = preg_replace_callback(
            "/\#p\#(.*?)\#\/p\#/si",
            array( $this, 'parse_paragraph' ),
            $spun_text
        );

        // Return
        return $this->format_and_return( $text, $spun_text );

    }

    /**
     * Sanity checks the output, and performs some formatting to
     * the block spun content
     *
     * @since   2.3.0
     *
     * @param   string  $original_text   Original Block Spintax
     * @param   string  $spun_text   Spun Result
     */
    private function format_and_return( $original_text, $spun_text ) {

        // If the spun text is the same as the original text, just return the original text
        // This prevents non-spintax items from being modified
        if ( $spun_text == $original_text ) {
            return $original_text;
        }

        // Convert newlines into paragraphs, ignoring breaklines
        $spun_text = wpautop( trim( $spun_text ), false );

        // Strip all newlines and tabs from the block
        $spun_text = preg_replace( "/\r|\n|\t/", " ", $spun_text );

        // Strip any double spaces
        $spun_text = str_replace( '  ', ' ', $spun_text );

        // Strip any spaces immediately after a paragraph tag
        $spun_text = str_replace( '<p> ', '<p>', $spun_text );

        // Return
        return $spun_text;

    }

     /**
     * Parses an individual #section#, which comprises of one or more paragraph (#p# #/p#) blocks.
     *
     * Paragraphs are shuffled at random within each section.
     *
     * @since   2.0.1
     *
     * @param   array   $matches    preg_match_all matches
     * @return  string              Block Content
     */
    private function parse_section( $matches ) {

        $section = preg_replace_callback(
            "/\#p\#(.*?)\#\/p\#/si",
            array( $this, 'parse_paragraph' ),
            $matches[1]
        );

        // Split paragraphs into an array
        $paragraphs = explode( '</p>', $section );

        // Remove <p> tags from all paragraphs for now
        foreach ( $paragraphs as $index => $paragraph ) {
            $paragraphs[ $index ] = str_replace( array( '<p>', '</p>' ), '', $paragraph );
        }

        // Shuffle paragraphs
        shuffle( $paragraphs );

        // Implode into a string
        $section = '<p>' . implode( '</p><p>', $paragraphs ) . '</p>';

        return $section;

    }

    /**
     * Parses an individual #p# paragraph, which comprises of one or more sentence (#s /#s) blocks.
     *
     * @since   2.0.1
     *
     * @param   array   $matches    preg_match_all matches
     * @return  string              Block Content
     */
    private function parse_paragraph( $matches ) {

        return '<p>' . preg_replace_callback(
            "/\#s\#(.*?)\#\/s\#/s",
            array( $this, 'parse_sentence' ),
            $matches[1]
        ) . '</p>';

    }

    /**
     * Parses an individual sentence block, which comprises of one or more lines of
     * sentences, returning a single sentence at random.
     *
     * @since   2.0.1
     *
     * @param   array   $matches    preg_match_all matches
     * @return  string              Sentence Content
     */
    private function parse_sentence( $matches ) {

        // Explode the sentence spins
        $parts = explode( "\n", trim( $matches[1] ) );

        // Remove empty sentences
        foreach ( $parts as $index => $part ) {
            // Trim the sentence to remove any newlines, to avoid falsely finding a sentence
            // isn't empty when it is just a newline character
            $part = trim( $part );

            if ( empty( $part ) ) {
                unset( $parts[ $index ] );
            }
        }

        // Reindex
        $parts = array_values( $parts );

        // Return a random sentence from the available options
        return trim( $parts[ array_rand( $parts ) ] );

    }

}