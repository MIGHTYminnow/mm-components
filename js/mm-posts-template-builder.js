/**
 * Mm Posts Template Builder JS
 *
 * @since  1.0.0
 */

( function( $ ) {

	$( document ).ready( function() {

		var $dropAreas = $( ".template-builder-wrap .drop-area" );

		$( ".template-builder-wrap .template-component" ).draggable({
			//appendTo: "body",
			helper: "clone"
		});

		$dropAreas.droppable({
			activeClass: 'ui-state-default',
			hoverClass: 'ui-state-hover',
			accept: ':not(.ui-sortable-helper)',
			drop: function( event, ui ) {

				var key = ui.draggable.attr( 'data-key' );

				// Remove the placeholder if it is there.
				$( this ).find( '.placeholder' ).remove();

				// Build and insert the component HTML.
				var dropAreaComponent = '<div class="drop-area-component ' + key + '">' + ui.draggable.text() + '<button class="remove"></button></div>';
				$( dropAreaComponent ).appendTo( this );

				// Get the current template.
				var template = $( '.template-builder-wrap .current-template' ).val();

				// Update the template JSON in our hidden textarea.
				$dropAreas.mmUpdateTemplateTextarea( template );

				console.log( $( '.drop-area-component.' + key ) );
				console.log( key );

				// Set up the remove click action.
				$( '.drop-area-component.' + key ).find( '.remove' ).on( 'click', function() {
					console.log( $( this ) );
					$( this ).parent().remove();
				});
			}
		}).sortable({
			items: '.drop-area-component:not(.placeholder)',
			sort: function() {

				// This is necessary. It helps the interaction between $.draggable() and $.sortable().
				$( this ).removeClass( 'ui-state-default' );

				// Get the current template.
				var template = $( '.template-builder-wrap .current-template' ).val();

				// Update the template JSON in our hidden textarea.
				$dropAreas.mmUpdateTemplateTextarea( template );
			}
		});

	});

	$.fn.mmUpdateTemplateTextarea = function( template ) {

		return this.each( function() {

			console.log( this );
			console.log( template );

		});
	}

})( jQuery );