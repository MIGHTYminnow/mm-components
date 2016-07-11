var $ = jQuery;

var mm_posts_data = function() {
	//Grab MM posts atts from data.
	$mmPosts          = $( '.mm-posts' );
	currentId         = typeof $mmPosts.data( 'current-post-id' ) != "undefined" ? $mmPosts.data( 'current-post-id' ) : '';
	queryType         = typeof $mmPosts.data( 'query-type' ) != "undefined" ? $mmPosts.data( 'query-type' ) : '';
	postIds           = typeof $mmPosts.data( 'post-ids' ) != "undefined" ? $mmPosts.data( 'post-ids' ) : '';
	postType          = typeof $mmPosts.data( 'post-type' ) != "undefined" ? $mmPosts.data( 'post-type' ) : '';
	taxonomy          = typeof $mmPosts.data( 'taxonomy' ) != "undefined" ? $mmPosts.data( 'taxonomy' ) : 'category';
	headingLevel      = typeof $mmPosts.data( 'heading-level' ) != "undefined" ? $mmPosts.data( 'heading-level' ) : '';
	perPage           = typeof $mmPosts.data( 'per-page' ) != "undefined" ? $mmPosts.data( 'per-page' ) : '';
	pagination        = typeof $mmPosts.data( 'pagination' ) != "undefined" ? $mmPosts.data( 'pagination' ) : '';
	ajaxPagination    = typeof $mmPosts.data( 'ajax-pagination' ) != "undefined" ? $mmPosts.data( 'ajaxPagination' ) : '';
	template          = typeof $mmPosts.data( 'template' ) != "undefined" ? $mmPosts.data( 'template' ) : '';
	showFeaturedImage = typeof $mmPosts.data( 'show-featured-image' ) != "undefined" ? $mmPosts.data( 'show-featured-image' ) : '';
	featuredImageSize = typeof $mmPosts.data( 'featured-image-size' ) != "undefined" ? $mmPosts.data( 'featured-image-size' ) : '';
	showPostInfo      = typeof $mmPosts.data( 'show-post-info' ) != "undefined" ? $mmPosts.data( 'show-post-info' ) : '';
	showPostMeta      = typeof $mmPosts.data( 'show-post-meta' ) != "undefined" ? $mmPosts.data( 'show-post-meta' ) : '';
	usePostContent    = typeof $mmPosts.data( 'use-post-content' ) != "undefined" ? $mmPosts.data( 'use-post-content' ) : '';
	linkTitle         = typeof $mmPosts.data( 'link-title' ) != "undefined" ? $mmPosts.data( 'link-title' ) : '';
	masonry           = typeof $mmPosts.data( 'masonry' ) != "undefined" ? $mmPosts.data( 'masonry' ) : '';
	fallbackImage     = typeof $mmPosts.data( 'fallback-image' ) != "undefined" ? $mmPosts.data( 'fallback-image' ) : '';
	imageTag          = typeof $mmPosts.data( 'image-tag' ) != "undefined" ? $mmPosts.data( 'image-tag' ) : '';
	imageTag          = typeof $mmPosts.data( 'paged' ) != "undefined" ? $mmPosts.data( 'paged' ) : '';
	totalPages        = typeof $mmPosts.data( 'total-pages' ) != "undefined" ? $mmPosts.data( 'total-pages' ) : '';
	paged             = typeof $mmPosts.data( 'paged' ) != "undefined" ? $mmPosts.data( 'paged' ) : '';
}

var mm_posts_ajax_filter = function( newPageVal ) {
	$( '.mm-posts-filter .cat-item a' ).on( 'click', function( e ) {
		e.preventDefault();
		var $this = $( this );
		var newTerm;
		var pageNumberRounded;

		//Set term-data value to empty when all terms are clicked.
		if( $this.text() == 'All' ) {
			$termText = '';
		} else {
			$termText = $this.text();
		}

		// Set the text of the clicked term as the term-data attribute.
		$this.parents( '.mm-posts-filter-wrapper' ).siblings( '.mm-posts' ).attr( 'data-term', $termText );

		// Grab the value of the new term-data.
		newTerm = $( '.mm-posts' ).attr( 'data-term' );

		var data = {
			action            : 'mm_posts_ajax_filter',
			currentPage       : newPageVal,
			currentId         : currentId,
			taxonomy          : taxonomy,
			queryType         : queryType,
			postIds           : postIds,
			postType          : postType,
			term              : newTerm,
			headingLevel      : headingLevel,
			perPage           : perPage,
			paged             : paged,
			pagination        : pagination,
			ajaxPagination    : ajaxPagination,
			template          : template,
			showFeaturedImage : showFeaturedImage,
			featuredImageSize : featuredImageSize,
			showPostInfo      : showPostInfo,
			showPostMeta      : showPostMeta,
			usePostContent    : usePostContent,
			linkTitle         : linkTitle,
			masonry           : masonry,
			fallbackImage     : fallbackImage,
			imageTag          : imageTag,
			totalPages        : totalPages
		}
		// Make the AJAX request.
		$.post( ajaxurl, data, function( response ) {

			// Format and update the posts loop.
			$( '.mm-posts-loop' ).replaceWith( response );

			if ( $('.mm-posts' ).hasClass( 'mm-ajax-pagination' ) ) {

				$responseObj = $( response );
				perPage = typeof $mmPosts.data( 'per-page' ) != "undefined" ? $mmPosts.data( 'per-page' ) : '';

				postsNumber = $responseObj.find( 'article').length;
				pageNumber = postsNumber / perPage;
				pageNumberRounded = Math.ceil( pageNumber );
				console.log( pageNumberRounded );

				$( '.pagination' ).twbsPagination({
				    totalPages: totalPages
				});
			}
		});
});

}

var mm_posts_ajax_pagination = function( newTerm ) {
	var $page;
	var newPageVal;
	var newTerm;
	$( '.pagination' ).on( 'click', function() {
		$this = $( this );

		//Set page-data value to the text of current clicked page number.
		$page = $this.find( 'li.active a' ).text();
		$this.parents( '.mm-posts' ).attr( 'data-current-page', $page );

		newPageVal = $( '.mm-posts' ).attr( 'data-current-page' );
		newTerm = $( '.mm-posts' ).attr( 'data-term' );

		var data = {
			action            : 'mm_posts_ajax_filter',
			currentPage       : newPageVal,
			currentId         : currentId,
			taxonomy          : taxonomy,
			queryType         : queryType,
			postIds           : postIds,
			postType          : postType,
			term              : newTerm,
			headingLevel      : headingLevel,
			perPage           : perPage,
			paged             : paged,
			pagination        : pagination,
			ajaxPagination    : ajaxPagination,
			template          : template,
			showFeaturedImage : showFeaturedImage,
			featuredImageSize : featuredImageSize,
			showPostInfo      : showPostInfo,
			showPostMeta      : showPostMeta,
			usePostContent    : usePostContent,
			linkTitle         : linkTitle,
			masonry           : masonry,
			fallbackImage     : fallbackImage,
			imageTag          : imageTag,
			totalPages        : totalPages
		};

		$.post( ajaxurl, data, function( response ) {
			$( '.mm-posts-loop' ).replaceWith( response );

		});
	});
}

jQuery( document ).ready( function( $ ) {

	mm_posts_data();

	//Runs the AJAX filter.
	mm_posts_ajax_filter();

	totalPages = $( '.mm-posts' ).attr( 'data-total-pages' );

	//Only run AJAX pagination if activated.
	if ( $( '.mm-posts' ).hasClass( 'mm-ajax-pagination' ) ) {

		$( '.mm-posts' ).twbsPagination({
		    totalPages: totalPages
		});

		mm_posts_ajax_pagination();
		mm_posts_ajax_filter();
	}

});