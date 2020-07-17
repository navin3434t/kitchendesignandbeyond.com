<?php
/**
 * Cartesian Product Class
 *
 * @package Page Generator Pro
 * @author  Tim Carr
 * @version 1.0.0
 */
class Page_Generator_Pro_Cartesian_Product implements IteratorAggregate, Countable {
    
    /**
     * Stores the multidimensional array of keywords and terms.
     *
     * @since   1.0.0
     *
     * @var     array
     */
    private $set = [];

    /**
     * Flat to indicate whether we're on a recursive step.
     *
     * @since   1.0.0
     *  
     * @var     bool
     */
    private $is_recursive_step = false;

    /**
     * @var int
     */
    private $count;

    /**
     * Constructor
     *
     * @since   1.0.0
     *
     * @param   array   $set    A multidimensionnal array
     */
    public function __construct( array $set ) {

        $this->set = $set;

    }

    /**
     * Return all combinations for the given set.
     *
     * 'yield' is the magic here.  We're not loading the entire array
     * into memory and returning the entire array to loop through.
     *
     * @since   1.0.0
     * 
     * @return \Generator
     */
    public function getIterator() {

        if ( ! empty( $this->set ) ) {
            $keys   = array_keys( $this->set );
            $key    = end( $keys );
            $subset = array_pop( $this->set );
            
            foreach ( self::subset( $this->set ) as $product ) {
                foreach ( $subset as $value ) {
                    yield $product + [ $key => $value ];
                }
            }
        } else {
            if ( $this->is_recursive_step ) {
                yield [];
            }
        }

    }

    /**
     * Returns the subset
     *
     * @since   1.0.0
     *
     * @param   array               $subset
     * @return  CartesianProduct
     */
    private static function subset( array $subset ) {
        
        $product = new self( $subset );
        $product->is_recursive_step = true;
        return $product;

    }

    /**
     * Return the number of combinations found.
     *
     * @since   1.0.0
     *
     * @return  int     Number of Combinations
     */
    public function count() {

        if ( null === $this->count ) {
            $this->count = (int) array_product( array_map( function ( $subset, $key ) {
                return count( $subset );
            }, $this->set, array_keys( $this->set ) ) );
        }

        return $this->count;
    
    }

}