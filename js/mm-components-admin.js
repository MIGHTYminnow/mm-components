/**
 * Mm Components Admin JS
 *
 * @since  1.0.0
 */

( function( $ ) {

	$( document ).ready( function() {

		// Set up any alpha color picker fields.
		$( 'input.alpha-color-picker' ).not( '#widget-list input.alpha-color-picker' ).alphaColorPicker();

		// Set up any multi checkbox fields.
		$( '.mm-multi-checkbox-wrap' ).mmMultiCheckboxField();

		// Set up any single media fields.
		$( '.mm-single-media-wrap' ).mmSingleMediaField();

		// Set up any multi media fields.
		$( '.mm-multi-media-wrap' ).mmMultiMediaField();
	});

	// Reset or initialize certain fields when widgets are added or updated.
	$( document ).on( 'widget-added widget-updated', function( e, data ) {

		$( data[0] ).find( 'input.alpha-color-picker' ).alphaColorPicker();
		$( data[0] ).find( '.mm-multi-checkbox-wrap' ).mmMultiCheckboxField();
		$( data[0] ).find( '.mm-single-media-wrap' ).mmSingleMediaField();
		$( data[0] ).find( '.mm-multi-media-wrap' ).mmMultiMediaField();
	});

	/**
	 * Set up one or many multi checkbox fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmMultiCheckboxField = function() {

		return this.each( function() {

			var $inputs = $( this ).find( 'input[type="checkbox"]' );
			var $input  = $( this ).find( 'input[type="hidden"]' );
			var values  = [];
			var value   = '';

			$inputs.on( 'click', function() {
				values = [];

				$.each( $inputs, function( i ) {
					if ( $( this ).is( ':checked' ) ) {
						values.push( $( this ).val() );
					}
				});
				value = values.join( ',' );

				$input.val( value );
			});
		});
	};

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

	/**
	 * Set up one or many multi media fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmMultiMediaField = function() {

		return this.each( function() {

			var $field         = $( this );
			var $elements      = $();
			var $uploadButton  = $field.find( '.upload-btn' );
			var $imagesPreview = $field.find( '.mm-multi-media-images-preview' );
			var $noImages      = $field.find( '.mm-multi-media-no-images' );
			var $clearButton   = $field.find( '.clear-btn' );

			$elements = $uploadButton.add( $imagesPreview ).add( $noImages );

			// Set up the interaction with wp.media.
			$elements.on( 'click', function( e ) {
				e.preventDefault();

				$field.mmMultiMediaUpload();
			});

			// Set up the clear button.
			$clearButton.on( 'click', function( e ) {
				e.preventDefault();

				$field.find( '.mm-multi-media-images-preview-wrap' ).addClass( 'no-images' ).find( '.mm-multi-media-images-preview' ).remove();
				$field.find( '.mm-multi-media-images' ).val( '' );
			});
		});
	};

	/**
	 * Handle the interaction with wp.media for one or many multi media upload fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmMultiMediaUpload = function() {

		return this.each( function() {

			var $field = $( this );

			var mmMultiMedia = wp.media( {
				title    : 'Upload Images or Files',
				multiple : true
			}).open().on( 'select', function( e ) {

				var data = mmMultiMedia.state().get( 'selection' ).toJSON();

				// If no media items were selected, clear the preview wrapper and bail.
				if ( 0 === data.length ) {
					$field.find( '.mm-multi-media-images-preview-wrap' ).addClass( 'no-images' ).find( '.mm-multi-media-images-preview' ).remove();
					$field.find( '.mm-multi-media-images' ).val( '' );
					return;
				}

				var ids      = [];
				var urls     = [];
				var previews = '';

				$.each( data, function() {
					ids.push( this.id );
					urls.push( this.url );
				});

				// Store the attachment IDs as a comma separated string.
				$field.find( '.mm-multi-media-images' ).val( ids.toString() );

				// Output all of the media item previews.
				$.each( urls, function() {
					previews += '<img class="mm-multi-media-images-preview" src="' + this + '" title="Media Items" alt="Media Items" />';
				});

				$field.find( '.mm-multi-media-images-preview' ).remove();
				$field.find( '.mm-multi-media-images-preview-wrap' ).removeClass( 'no-images' ).append( previews );
			});
		});
	};

}( jQuery ));