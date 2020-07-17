/**
 * Initialises the OpenWeatherMap modal popup by registering a button
 * in the TinyMCE instance.
 *
 * @since 	2.4.8
 */
( function() {

	tinymce.PluginManager.add( 'page_generator_pro_open_weather_map', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton( 'page_generator_pro_open_weather_map', {
			title: 	'Insert OpenWeatherMap',
			image: 	url + '../../../../_modules/dashboard/feather/sun.svg',
			cmd: 	'page_generator_pro_open_weather_map',
		} );	

		// Load View when button clicked
		editor.addCommand( 'page_generator_pro_open_weather_map', function() {
			// Open the TinyMCE Modal
			editor.windowManager.open( {
				id: 	'page-generator-pro-modal-body',
				title: 	'Insert OpenWeatherMap',
                width: 	600,
                height: 255,
                inline: 1,
                buttons:[],
            } );

			// Perform an AJAX call to load the modal's view
			jQuery.post( 
	            ajaxurl,
	            {
	                'action': 	'page_generator_pro_output_tinymce_modal',
	                'shortcode':'open-weather-map'
	            },
	            function( response ) {
	            	// Inject HTML into modal
	            	jQuery( '#page-generator-pro-modal-body-body' ).html( response );

	            	// Reload autocomplete instances
	            	page_generator_pro_autocomplete_initialize();
	            }
	        );
		} );
	} );

} )();