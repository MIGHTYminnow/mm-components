var $ = jQuery;

var mm_posts_ajax_cat_filter = function( e ) {
	$this = $( this );
	$this.parents( 'ul' ).attr( 'data-category', $this.text() );
	$catList = $( document ).find( '.mm-posts-filter' ).data( 'category' );
	$catLink = $( '.mm-posts-filter .cat-item a' );
	e.preventDefault();


	$( '.mm-posts-loop archive').remove();

	// Build our AJAX data.
    var data = {
        action    : 'mm_posts_cat_filter',
        cat_data  : $catList
    }
	// Make the AJAX request.
    $.post( ajaxurl, data, function( resultData ) {

    	console.log( data );

        // Update the posts loop.
        $( '.mm-posts-loop' ).html( resultData );
    });
}

var mm_posts_ajax_setup = function() {
	$( '.mm-posts-filter .cat-item a' ).on( 'click', mm_posts_ajax_cat_filter );
}

jQuery( document ).ready( function( $ ) {
	mm_posts_ajax_setup();
});