/**
 * General Scripts.
 *
 * @since 1.0.0
 *
 * @package mm-components
 */

( function( $ ) {

	/**
	 * Reusable utility functions.
	 */

	// Debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function( func, threshold ) {
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
			timeout = setTimeout( delayed, threshold || 50 );
		};
	};

	// Function to initialize vertical centering.
	$.fn.mmInitVerticalCenter = function( offset ) {
		var selector, eventData, ourEvents, eventSet, thisEvent, eventName;

		selector = this.selector;
		offset   = offset || 0;

		// Check if our global already exists.
		if ( window.mmVerticalCenterItems ) {
			// It does, so copy the current object in it.
			window.mmVerticalCenterItems[selector] = offset;
		} else {
			// It doesn't, so create the global and store the current object in it.
			window.mmVerticalCenterItems = {};
			window.mmVerticalCenterItems[selector] = offset;
		}

		// Grab the current event data from the window object if it exists.
		eventData = $._data( window, 'events' ) || {};

		// Store the events that will retrigger doVerticalCenter().
		ourEvents = [ 'resize', 'orientationchange', 'mmverticalcenter' ];

		// Loop through each event and attach our handler if it isn't attached already.
		$( ourEvents ).each( function() {
			eventSet  = false;
			thisEvent = this;
			eventName = this + '.mmverticalcenter';

			// Check whether this event is already on the window.
			if ( eventData[ thisEvent ] ) {

				// Be careful not to disturb any unrelated listeners.
				$( eventData[ thisEvent ] ).each( function() {

					// Confirm that the event has our namespace.
					if ( this.namespace == 'mmverticalcenter' ) {

						// It does, so set our flag to true.
						eventSet = true;
					}
				});
			}

			// If our flag is still false we can safely attach the event.
			if ( ! eventSet ) {

				// Debounce it to be safe.
				$( window ).on( eventName, debounce( mmTriggerVerticalCenter ) );
			}
		});

		// Trigger the first vertical centering.
		mmTriggerVerticalCenter();

		// Make this function chainable.
		return this;
	};

	// Function to trigger the vertical centering.
	function mmTriggerVerticalCenter() {

		// Loop through each object in our global and call doVerticalCenter.
		$.each( window.mmVerticalCenterItems, function( selector, offset ) {

			$( selector ).mmDoVerticalCenter( offset );
		});
	}

	// Function to do the vertical centering.
	$.fn.mmDoVerticalCenter = function( offset ) {
		var offset = offset || 0;

		// Selector might match multiple items, so do all
		// centering calculations on one item at a time.
		$( this ).each( function() {
			var parentHeight = $( this ).parent().height();

			// Make sure the element is block level.
			if ( $( this ).css( 'display' ) === 'inline' ) {
				$( this ).css( 'display', 'inline-block' );
			}

			// Calculate and add the margin-top to center the element.
			$( this ).css(
				'margin-top',
				( ( ( parentHeight - $( this ).outerHeight() ) / 2 ) + parseInt( offset ) )
			).addClass( 'mm-vc-complete' );
		});
	}

	/**
	 * Countdown Component.
	 */
	function mmStartCountdowns() {
		var $targets = $( '.mm-countdown' );

		$targets.each( function() {

			var $this = $( this );
			var year = parseInt( $this.data( 'year' ) );
			var month = parseInt( $this.data( 'month' ) - 1 ); // Not sure why, but this one property is indexed differently
			var day = parseInt( $this.data( 'day' ) );
			var hour = parseInt( $this.data( 'hour' ) );
			var minute = parseInt( $this.data( 'minute' ) );
			var second = parseInt( $this.data( 'second' ) );
			var timezone_offset = parseInt( $this.data( 'timezone-offset' ) );

			// Form end date object (not including timezone).
			var dateObj = new Date( Date.UTC( year, month, day, hour, minute, second, 0 ) );

			// Adjust end date object to factor in timezone offset.
			dateObj.setUTCHours( dateObj.getUTCHours() - timezone_offset );

			$this
				.countdown( dateObj )
				.on( 'update.countdown finish.countdown', function( event ) {
			   		$this.html( event.strftime( ''
			     	+ '<div class="unit days"><span class="count">%-D</span><span class="label">day%!D</span></div>'
			     	+ '<div class="unit hours"><span class="count">%-H</span><span class="label">hour%!H</span></div>'
			     	+ '<div class="unit minutes"><span class="count">%-M</span><span class="label">minute%!M</span></div>'
			     	+ '<div class="unit seconds"><span class="count">%-S</span><span class="label">second%!S</span></div>'
		    	));
			});
		});
	}

	/**
	 * Expandable Content.
	 */
	function mmSetupExpandableContent() {
		$( '.mm-expandable-content' ).each( function() {

			var $trigger = $( this ).find( '.mm-expandable-content-trigger' );
			var $target = $( this ).find( '.mm-expandable-content-target' );

			$trigger.on( 'click', function( e ) {

				e.preventDefault();

				$trigger.toggleClass( 'open' );

				if ( $trigger.hasClass( 'fade' ) ) {
					$target.toggleClass( 'open' ).fadeToggle();
				} else {
					$target.toggleClass( 'open' ).toggle();
				}
			});

		});
	};

	/**
	 * Hero Banner.
	 */
	function mmSetupHeroBanners() {

		$( '.mm-hero-banner .hero-content-wrap' ).mmInitVerticalCenter();
	}

	/**
	 * Masonry layouts for Mm Posts.
	 */
	function mmPostsInitMasonry() {

	 	if ( typeof $().isotope !== 'function' ) {
	 		return;
	 	}

	 	$( '.mm-posts.mm-masonry' ).each( function() {

	 		var options = {
				itemSelector: '.mm-post',
				percentPosition: true,
				masonry: {
					columnWidth: '.mm-post'
				}
			};

			if ( ! $( this ).hasClass( 'no-gutter' ) ) {
				options.masonry.gutter = '.mm-posts-masonry-gutter';
			}

			var $this = $( this ).imagesLoaded( function() {
				$this.isotope( options );
			});
	 	});
	}

	/**
	 * Start the party.
	 */
	$( document ).ready( function() {
		mmStartCountdowns();
		mmSetupExpandableContent();
		mmPostsInitMasonry();
	});

	/**
	 * Start the after party.
	 */
	$( window ).load( function() {
		mmSetupHeroBanners();
	});

})( jQuery );


