var $ = jQuery;

var mm_posts_ajax_call = function( termList ) {
	var $formattedResponse;

	//Grab MM posts atts from data.
	var currentId         = typeof $( '.mm-posts-filter' ).data( 'current-post-id' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'current-post-id' ) : '';
	var queryType         = typeof $( '.mm-posts-filter' ).data( 'query-type' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'query-type' ) : '';
	var postIds           = typeof $( '.mm-posts-filter' ).data( 'post-ids' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'post-ids' ) : '';
	var postType          = typeof $( '.mm-posts-filter' ).data( 'post-type' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'post-type' ) : '';
	var taxonomy          = typeof $( '.mm-posts-filter' ).data( 'taxonomy' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'taxonomy' ) : 'category';
	var headingLevel      = typeof $( '.mm-posts-filter' ).data( 'heading-level' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'heading-level' ) : '';
	var perPage           = typeof $( '.mm-posts-filter' ).data( 'per-page' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'per-page' ) : '';
	var pagination        = typeof $( '.mm-posts-filter' ).data( 'pagination' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'pagination' ) : '';
	var template          = typeof $( '.mm-posts-filter' ).data( 'template' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'template' ) : '';
	var showFeaturedImage = typeof $( '.mm-posts-filter' ).data( 'show-featured-image' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'show-featured-image' ) : '';
	var featuredImageSize = typeof $( '.mm-posts-filter' ).data( 'featured-image-size' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'featured-image-size' ) : '';
	var showPostInfo      = typeof $( '.mm-posts-filter' ).data( 'show-post-info' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'show-post-info' ) : '';
	var showPostMeta      = typeof $( '.mm-posts-filter' ).data( 'show-post-meta' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'show-post-meta' ) : '';
	var usePostContent    = typeof $( '.mm-posts-filter' ).data( 'use-post-content' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'use-post-content' ) : '';
	var linkTitle         = typeof $( '.mm-posts-filter' ).data( 'link-title' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'link-title' ) : '';
	var masonry           = typeof $( '.mm-posts-filter' ).data( 'masonry' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'masonry' ) : '';
	var fallbackImage     = typeof $( '.mm-posts-filter' ).data( 'fallback-image' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'fallback-image' ) : '';
	var imageTag          = typeof $( '.mm-posts-filter' ).data( 'image-tag' ) != "undefined" ? $( '.mm-posts-filter' ).data( 'image-tag' ) : '';

    var data = {
        action            : 'mm_posts_term_filter',
        currentId         : currentId,
        taxonomy          : taxonomy,
        queryType         : queryType,
		postIds           : postIds,
		postType          : postType,
		term              : termList,
		headingLevel      : headingLevel,
		perPage           : perPage,
		pagination        : pagination,
		template          : template,
		showFeaturedImage : showFeaturedImage,
		featuredImageSize : featuredImageSize,
		showPostInfo      : showPostInfo,
		showPostMeta      : showPostMeta,
		usePostContent    : usePostContent,
		linkTitle         : linkTitle,
		masonry           : masonry,
		fallbackImage     : fallbackImage,
		imageTag          : imageTag
    }
	// Make the AJAX request.
    $.post( ajaxurl, data, function( response ) {

        // Format and update the posts loop.
        $( '.mm-posts-loop' ).replaceWith( response );
    });
}

var mm_posts_ajax_filter= function() {
	$( '.mm-posts-filter .cat-item a' ).on( 'click', function( e ) {
		var $this = $( this );
		var $catLink;
		var termList;

		e.preventDefault();

		if( $this.text() == 'All' ) {
			$termText = '';
		} else {
			$termText = $this.text();
		}

		$this.parents( 'ul' ).attr( 'data-term', $termText );
		termList = $this.parents( '.mm-posts-filter' ).attr( 'data-term' ).toLowerCase();
		$catLink = $( '.mm-posts-filter .cat-item a' );

		mm_posts_ajax_call( termList );
	});
}

jQuery( document ).ready( function( $ ) {
	mm_posts_ajax_filter();
});