var $ = jQuery;

var mm_posts_ajax_call = function( catList ) {

	// Build our AJAX data.
    var data = {
        action    : 'mm_posts_cat_filter',
        cat_data  : catList
    }
	// Make the AJAX request.
    $.post( ajaxurl, data, function( response ) {
    	var $formattedResponse;

        // Format and update the posts loop.
        $formattedResponse = $( response ).wrap( '<div />' ).parent();
        $formattedResponse.find( '.mm-posts-filter-wrapper' ).remove();
        $( '.mm-posts' ).replaceWith( $formattedResponse );
    });
}

var mm_posts_ajax_filter= function() {
	$( '.mm-posts-filter .cat-item a' ).on( 'click', function( e ) {
		$this = $( this );
		$this.parents( 'ul' ).attr( 'data-category', $this.text() );
		catList = $this.parents( '.mm-posts-filter' ).attr( 'data-category' ).toLowerCase();
		$catLink = $( '.mm-posts-filter .cat-item a' );
		e.preventDefault();

		mm_posts_ajax_call( catList );
	});
}

jQuery( document ).ready( function( $ ) {
	mm_posts_ajax_filter();
});