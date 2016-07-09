var $ = jQuery;

var mm_posts_ajax_call = function( catList ) {

	// Build our AJAX data.
    var data = {
        action    : 'mm_posts_cat_filter',
        tax   	  : catList
    }
	// Make the AJAX request.
    $.post( ajaxurl, data, function( response ) {
    	var $formattedResponse;

        // Format and update the posts loop.
        $formattedResponse = $( response );
        console.log( response );

        $( 'article.mm-post' ).remove();
        $( '.mm-posts-loop' ).append( response );
    });
}

var mm_posts_ajax_filter= function() {
	$( '.mm-posts-filter .cat-item a' ).on( 'click', function( e ) {
		var $this = $( this );
		var catList;
		var $catLink;

		e.preventDefault();

		$this.parents( 'ul' ).attr( 'data-taxonomy', $this.text() );
		catList = $this.parents( '.mm-posts-filter' ).attr( 'data-taxonomy' ).toLowerCase();
		$catLink = $( '.mm-posts-filter .cat-item a' );

		mm_posts_ajax_call( catList );
	});
}

jQuery( document ).ready( function( $ ) {
	mm_posts_ajax_filter();
});