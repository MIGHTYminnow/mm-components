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

		// Set up field dependencies for Mm Restricted Content.
		$( '.widget[id*="mm_restricted_content_widget"]' ).mmRestrictedContentFields();

		// Set up field dependencies for Mm Hero Banner.
		$( '.widget[id*="mm_hero_banner_widget"]' ).mmHeroBannerFields();

		// Set up field dependencies for Mm Hero Banner.
		$( '.widget[id*="mm_expandable_content_widget"]' ).mmExpandableContentFields();
	});

	// Reset or initialize certain fields when widgets are added or updated.
	$( document ).on( 'widget-added widget-updated', function( e, data ) {

		$( data[0] ).find( 'input.alpha-color-picker' ).alphaColorPicker();
		$( data[0] ).find( '.mm-multi-checkbox-wrap' ).mmMultiCheckboxField();
		$( data[0] ).find( '.mm-single-media-wrap' ).mmSingleMediaField();
		$( data[0] ).find( '.mm-multi-media-wrap' ).mmMultiMediaField();

		if ( $( data[0] ).is( '.widget[id*="mm_restricted_content_widget"]' ) ) {
			$( data[0] ).mmRestrictedContentFields();
		}

		if ( $( data[0] ).is( '.widget[id*="mm_hero_banner_widget"]' ) ) {
			$( data[0] ).mmHeroBannerFields();
		}

		if ( $( data[0] ).is( '.widget[id*="mm_expandable_content_widget"]' ) ) {
			$( data[0] ).mmExpandableContentFields();
		}
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

	/**
	 * Dependency for single checkbox widget fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmRestrictedContentFields = function() {

		return this.each( function() {

			var $widget = $( this );
			var $checkbox = $widget.find( '.mm-restricted-content-specific-roles' );
			var $rolesCheckboxes = $widget.find( '.mm-multi-checkbox-field-wrap' ).has( '.mm-restricted-content-roles' );

			if ( ! $checkbox.is( ':checked' ) ) {
				$rolesCheckboxes.addClass( 'mm-hidden' );
			}

			$checkbox.on( 'click', function() {
				$rolesCheckboxes.toggleClass( 'mm-hidden' );
			});
		});
	}

	/**
	 * Dependency for dropdown widget fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmHeroBannerFields = function() {

		return this.each( function() {

			var $widget             = $( this );
			var $overlayColor       = $widget.find( '.mm-hero-banner-widget-overlay-color' );
			var $overlayOpacityWrap = $widget.find( '.mm-select-field-wrap' ).has( '.mm-hero-banner-widget-overlay-opacity' );
			var $buttonStyle        = $widget.find( '.mm-hero-banner-widget-button-style' );
			var $buttonBorderWrap   = $widget.find( '.mm-select-field-wrap' ).has( '.mm-hero-banner-widget-button-border-weight' );

			if ( '' === $overlayColor.find( 'option:selected' ).attr( 'value' ) ) {
				$overlayOpacityWrap.addClass( 'mm-hidden' );
			}

			if ( 'ghost' === $buttonStyle.find( 'option:selected' ).attr( 'value' ) || 'solid-to-ghost' === $buttonStyle.find('option:selected').attr( 'value' ) ) {
				$buttonBorderWrap.removeClass( 'mm-hidden' );
			} else {
				$buttonBorderWrap.addClass( 'mm-hidden' );
			}

			$overlayColor.on( 'change', function() {
				if ( '' !== $overlayColor.find( 'option:selected' ).attr( 'value' ) ) {
					$overlayOpacityWrap.removeClass( 'mm-hidden' );
				} else {
					$overlayOpacityWrap.addClass( 'mm-hidden' );
				}
			});

			$buttonStyle.on( 'change', function() {
				if ( 'ghost' === $buttonStyle.find('option:selected').attr( 'value' ) || 'solid-to-ghost' === $buttonStyle.find('option:selected').attr( 'value' ) ) {
					$buttonBorderWrap.removeClass( 'mm-hidden' );
				} else {
					$buttonBorderWrap.addClass( 'mm-hidden' );
				}
			});
		});
	}

		/**
	 * Dependency for Expandable Content dropdown widget fields.
	 *
	 * @since  1.0.0
	 */
	$.fn.mmExpandableContentFields = function() {

		return this.each( function() {

			var $widget            = $( this );
			var $linkTypeWrap      = $widget.find( '.mm-select-field-wrap' ).has( '.mm-expandable-content-widget-link-style' );
			var $linkType          = $widget.find( '.mm-expandable-content-widget-link-style' );
			var $buttonStyleWrap   = $widget.find( '.mm-select-field-wrap' ).has( '.mm-expandable-content-widget-button-style' );
			var $buttonStyle       = $widget.find( '.mm-expandable-content-widget-button-style' );
			var $buttonBorderWrap  = $widget.find( '.mm-select-field-wrap' ).has( '.mm-expandable-content-widget-button-border-weight' );
			var $buttonBorder      = $widget.find( '.mm-expandable-content-widget-button-border-weight' );
			var $buttonCornerWrap  = $widget.find( '.mm-select-field-wrap' ).has( '.mm-expandable-content-widget-button-corner-style' );
			var $buttonCorner      = $widget.find( '.mm-expandable-content-widget-button-corner-style' );
			var $buttonColorWrap   = $widget.find( '.mm-select-field-wrap' ).has( '.mm-expandable-content-widget-button-color' );
			var $buttonColor       = $widget.find( '.mm-expandable-content-widget-button-color' );

			if ( 'ghost' === $buttonStyle.find( 'option:selected' ).attr( 'value' ) || 'solid-to-ghost' === $buttonStyle.find('option:selected').attr( 'value' ) ) {
				$buttonBorderWrap.removeClass( 'mm-hidden' );
			} else {
				$buttonBorderWrap.addClass( 'mm-hidden' );
			}

			$buttonStyle.on( 'change', function() {
				if ( 'ghost' === $buttonStyle.find('option:selected').attr( 'value' ) || 'solid-to-ghost' === $buttonStyle.find('option:selected').attr( 'value' ) ) {
					$buttonBorderWrap.removeClass( 'mm-hidden' );
				} else {
					$buttonBorderWrap.addClass( 'mm-hidden' );
				}
			});

			$linkType.on( 'change', function() {
				if ( 'button' === $linkType.find( 'option:selected' ).attr( 'value' ) ) {
					$buttonStyleWrap.removeClass( 'mm-hidden' );
					$buttonCornerWrap.removeClass( 'mm-hidden' );
					$buttonColorWrap.removeClass( 'mm-hidden' ); 
					
					if ( 'ghost' === $buttonStyle.find('option:selected').attr( 'value' ) || 'solid-to-ghost' === $buttonStyle.find('option:selected').attr( 'value' )) {
						$buttonBorderWrap.removeClass( 'mm-hidden' );
					}
					
				} else {
					$buttonStyleWrap.addClass( 'mm-hidden' );
					$buttonBorderWrap.addClass( 'mm-hidden' );
					$buttonCornerWrap.addClass( 'mm-hidden' );
					$buttonColorWrap.addClass( 'mm-hidden' );
				}

			});

			if ( 'button' !== $linkType.find( 'option:selected' ).attr( 'value' ) ) {
				$buttonStyleWrap.addClass( 'mm-hidden' );
				$buttonBorderWrap.addClass( 'mm-hidden' );
				$buttonCornerWrap.addClass( 'mm-hidden' );
				$buttonColorWrap.addClass( 'mm-hidden' );
			}

		});
	}

}( jQuery ));