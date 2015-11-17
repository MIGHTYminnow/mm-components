/**
 * Mm Posts Template Builder JS
 *
 * @since  1.0.0
 */

var MmPostsTemplateBuilder = ( function( $ ) {

	var _templatesJSON = {};

	var templates = [];

	/**
	 * Debouncing function from John Hann.
	 * http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	 */
	var _debounce = function( func, threshold ) {
		var timeout;
		return function debounced() {
			var obj = this;
			var args = arguments;
			function delayed() {
				func.apply( obj, args );
				timeout = null;
			}
			if ( timeout ) {
				clearTimeout( timeout );
			}
			timeout = setTimeout( delayed, threshold || 100 );
		};
	};

	var _getTemplates = function() {
		return $.parseJSON( _templatesJSON ).templates;
	}

	var _getTemplate = function( template ) {
		var templates = _getTemplates();
		var found = false;

		$.each( templates, function( i ) {
			if ( template === this.name && ! found ) {
				found = templates[i];
			}
		});

		return found;
	}

	var initialize = function() {

		var templatesJSON = $( '#mm-posts-template-builder-template-json' ).val();

		// Store the template JSON as a property on this object.
		_templatesJSON = templatesJSON;

		// If we don't have any JSON, we must be loading for the first time.
		// Trigger our modal and build our initial JSON.
		if ( ! templatesJSON ) {
			_loadFirstTime();
		}

		// Initialize inputs.
		_initDragDropAndSort();
		_initTemplateSelect();
		_initDropAreaComponents();
	};

	var _loadFirstTime = function() {

		console.log( 'We must be loading for the first time' );
	}

	var _initDragDropAndSort = function() {

		var $dropAreas = $( '.template-builder-wrap .drop-area' );

		$( '.template-builder-wrap .template-component' ).draggable({
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
				var dropAreaComponent = _buildDropAreaComponentHTML( key, ui.draggable.text() );
				$( dropAreaComponent ).appendTo( this );

				// Update the template JSON.
				_updateTemplatesJSON();

				// Reset events.
				_initDropAreaComponents();
			}
		}).sortable({
			items: '.drop-area-component:not(.placeholder)',
			sort: function() {

				// This is necessary. It helps the interaction between $.draggable() and $.sortable().
				$( this ).removeClass( 'ui-state-default' );

				// Update the template JSON.
				_updateTemplatesJSON();
			}
		});
	};

	var _initDropAreaComponents = function() {

		// Set up the remove click action.
		$( '.template-builder-wrap .drop-area-component .remove' ).on( 'click', function() {
			$( this ).parent().remove();
			_updateTemplatesJSON();
		});
	};

	var _buildDropAreaComponentHTML = function( key, text ) {

		return '<div class="drop-area-component ' + key + '" data-key=' + key + '>' + text + '<button class="remove"></button></div>';
	};

	var _initTemplateSelect = function() {

		var $select = $( '.template-builder-wrap .template-select' );
		var $input  = $( '.template-builder-wrap .current-template' );

		$select.on( 'change', function() {
			$input.val( $select.val() );
			_loadTemplate( $select.val() );
		});

		$input.val( $select.val() );
		_loadTemplate( $select.val() );
	};

	var _loadTemplate = function( template ) {

		var template = _getTemplate( template );
		var $header   = $( '.template-builder-wrap .header-drop-area' );
		var $content  = $( '.template-builder-wrap .content-drop-area' );
		var $footer   = $( '.template-builder-wrap .footer-drop-area' );

		$header.empty();
		$content.empty();
		$footer.empty();

		for ( i = 0; i < template.header.length; i++ ) {
			var displayName = template.header[i].replace( 'mm_posts_output', '' ).replace( /_/g, ' ' );
			$header.append( _buildDropAreaComponentHTML( template.header[i], displayName ) );
		}

		for ( i = 0; i < template.content.length; i++ ) {
			var displayName = template.content[i].replace( 'mm_posts_output', '' ).replace( /_/g, ' ' );
			$content.append( _buildDropAreaComponentHTML( template.content[i], displayName ) );
		}

		for ( i = 0; i < template.footer.length; i++ ) {
			var displayName = template.footer[i].replace( 'mm_posts_output', '' ).replace( /_/g, ' ' );
			$footer.append( _buildDropAreaComponentHTML( template.footer[i], displayName ) );
		}

		_initDropAreaComponents();
	};

	var _updateTemplateJSON = function( templateName, header, content, footer ) {

		var templates        = _getTemplates();
		var newTemplates     = {};
		var newTemplatesJSON = '';

		// Find the template we're modifying and update it.
		$.each( templates, function( i ) {
			if ( templateName === this.name ) {
				templates[i].header = header || [];
				templates[i].content = content || [];
				templates[i].footer = footer || [];
			}
		});

		newTemplates.templates = templates;

		var newTemplatesJSON = JSON.stringify( newTemplates );

		return newTemplatesJSON;
	};

	var _updateTemplatesJSON = function() {

		console.log( 'updateTemplatesJSON has been called' );

		var template  = $( '.template-builder-wrap .current-template' ).val();
		var templates = _getTemplates();
		var header    = [];
		var content   = [];
		var footer    = [];

		$( '.template-builder-wrap .header-drop-area .drop-area-component' ).each( function() {
			header.push( $( this ).attr( 'data-key' ) );
		});

		$( '.template-builder-wrap .content-drop-area .drop-area-component' ).each( function() {
			content.push( $( this ).attr( 'data-key' ) );
		});

		$( '.template-builder-wrap .footer-drop-area .drop-area-component' ).each( function() {
			footer.push( $( this ).attr( 'data-key' ) );
		});

		var newTemplatesJSON = _updateTemplateJSON( template, header, content, footer );

		$( '#mm-posts-template-builder-template-json' ).val( newTemplatesJSON );
	};

	return {
		initialize: initialize,
	};

})( jQuery );

jQuery( document ).ready( function( $ ) {

	// Start the party.
	MmPostsTemplateBuilder.initialize();
});