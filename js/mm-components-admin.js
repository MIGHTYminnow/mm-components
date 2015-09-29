/**
 * Mm Components Admin JS
 *
 * @since  1.0.0
 */

( function( $ ) {

	// Set up any alpha color pickers on initial page load.
	$( 'input.alpha-color-picker' ).not( '#widget-list input.alpha-color-picker' ).alphaColorPicker();

	// Set up any alpha color pickers when widgets are added or updated.
	$( document ).on( 'widget-added widget-updated', function( e, data ) {
		$( data[0] ).find( 'input.alpha-color-picker' ).alphaColorPicker();
	});

}( jQuery ));