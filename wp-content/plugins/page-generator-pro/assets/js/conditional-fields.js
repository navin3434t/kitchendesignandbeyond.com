/**
 * Initialize autocomplete instances for Classic Editor
 * and Meta Boxes, based on the globally registered
 * selectors.
 *
 * @since 	2.5.4
 */
function page_generator_pro_conditional_fields_initialize() {

	( function( $ ) {
		
		$( 'body' ).on( 'change', 'select.wpzinc-conditional, .wpzinc-conditional select', function() {

			// Get container that holds all fields that are controlled by this <select>
			var container = $( this ).data( 'container' );

			// Hide all fields with classes matching each option value
			$( 'option', $( this ) ).each( function() {
				$( '.' + $( this ).val(), $( container ) ).parent().hide();
    		} );

    		// Show fields with class matching the selected option value
    		$( '.' + $( this ).val(), $( container ) ).parent().show();

		} );

	} )( jQuery );

}