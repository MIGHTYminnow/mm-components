/**
 * Mm Components Admin JS
 *
 * @since  1.0.0
 */

( function( $ ) {

	$( document ).ready( function() {

		// Set up any alpha color picker fields on initial page load.
		$( 'input.alpha-color-picker' ).not( '#widget-list input.alpha-color-picker' ).alphaColorPicker();

		// Set up any single media fields.
		$( '.mm-single-media-wrap' ).mmSingleMediaField();
	});

	// Reset or initialize certain fields when widgets are added or updated.
	$( document ).on( 'widget-added widget-updated', function( e, data ) {

		$( data[0] ).find( 'input.alpha-color-picker' ).alphaColorPicker();
		$( data[0] ).find( '.mm-single-media-wrap' ).mmSingleMediaField();
	});

	/**
	 * Set up one or many single media fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmSingleMediaField = function() {

		return this.each( function() {

			var $field        = $( this );
			var $elements     = $();
			var $uploadButton = $field.find( '.upload-btn' );
			var $imagePreview = $field.find( '.mm-single-media-image-preview' );
			var $noImage      = $field.find( '.mm-single-media-no-image' );
			var $clearButton  = $field.find( '.clear-btn' );

			$elements = $uploadButton.add( $imagePreview ).add( $noImage );

			// Set up the interaction with wp.media.
			$elements.on( 'click', function( e ) {
				e.preventDefault();

				$field.mmSingleMediaUpload();
			});

			// Set up the clear button.
			$clearButton.on( 'click', function( e ) {
				e.preventDefault();

				$field.find( '.mm-single-media-image' ).val( '' );
				$field.find( '.mm-single-media-image-preview-wrap' ).addClass( 'no-image' );
			});
		});
	};

	/**
	 * Handle the interaction with wp.media for one or many single media upload fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmSingleMediaUpload = function() {

		return this.each( function() {

			var $field = $( this );

			var mmSingleMedia = wp.media( {
				title    : 'Upload Image or File',
				multiple : false
			}).open().on( 'select', function( e ) {

				var uploadedMedia    = mmSingleMedia.state().get( 'selection' ).first();
				var mmSingleMediaId  = uploadedMedia.id;
				var mmSingleMediaUrl = uploadedMedia.attributes.url;

				$field.find( '.mm-single-media-image' ).val( mmSingleMediaId );
				$field.find( '.mm-single-media-image-preview-wrap' ).removeClass( 'no-image' );
				$field.find( '.mm-single-media-image-preview' ).attr( 'src', mmSingleMediaUrl );
			});
		});
	};

}( jQuery ));