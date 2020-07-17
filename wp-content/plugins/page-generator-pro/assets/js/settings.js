jQuery( document ).ready( function( $ ) {

	/**
	 * Settings: Spintax
	 */
	$( 'select[name="page-generator-pro-spintax[provider]"]' ).on( 'change.page-generator-pro', function( e ) {

		var provider = $( this ).val();

		// Hide all divs
		$( 'option', $( this ) ).each( function() {
			if ( $( this ).val().length > 0 ) {
				$( '#' + $( this ).val() ).hide();
			}
		} );

		// Show div relative to selected option
		$( '#' + provider ).show();
		
	} );
	$( 'select[name="page-generator-pro-spintax[provider]"]' ).trigger( 'change.page-generator-pro' );

} );