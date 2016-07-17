var $ = jQuery;

var mm_posts_data = function() {
	//Grab MM posts atts from data.
	$mmPosts          = $( '.mm-posts' );
	$mmPostsLoop      = $mmPosts.find( '.mm-posts-loop' );
	currentId         = typeof $mmPosts.data( 'current-post-id' ) != "undefined" ? $mmPosts.data( 'current-post-id' ) : '';
	queryType         = typeof $mmPosts.data( 'query-type' ) != "undefined" ? $mmPosts.data( 'query-type' ) : '';
	postIds           = typeof $mmPosts.data( 'post-ids' ) != "undefined" ? $mmPosts.data( 'post-ids' ) : '';
	postType          = typeof $mmPosts.data( 'post-type' ) != "undefined" ? $mmPosts.data( 'post-type' ) : '';
	taxonomy          = typeof $mmPosts.data( 'taxonomy' ) != "undefined" ? $mmPosts.data( 'taxonomy' ) : 'category';
	headingLevel      = typeof $mmPosts.data( 'heading-level' ) != "undefined" ? $mmPosts.data( 'heading-level' ) : '';
	perPage           = typeof $mmPosts.data( 'per-page' ) != "undefined" ? $mmPosts.data( 'per-page' ) : '';
	pagination        = typeof $mmPosts.data( 'pagination' ) != "undefined" ? $mmPosts.data( 'pagination' ) : '';
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
	totalPosts        = typeof $mmPosts.data( 'total-posts' ) != "undefined" ? $mmPosts.data( 'total-posts' ) : '';
	filterStyle       = typeof $mmPosts.data( 'filter-style' ) != "undefined" ? $mmPosts.data( 'filter-style' ) : '';
	paged             = typeof $mmPosts.data( 'paged' ) != "undefined" ? $mmPosts.data( 'paged' ) : '';
}

var mm_posts_ajax_data = function( newTerm, pageNumberRounded, newPageVal ) {

	data = {
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
		totalPosts        : totalPosts,
		totalPages        : totalPages,
		filterStyle       : filterStyle
	};
}

var mm_posts_ajax_filter = function( e, newPageVal ) {
		var $this = $( this );
		var $mmPostsLoop = $mmPosts.find( '.mm-posts-loop' );
		var $filterLinks = $( '.mm-posts-filter a' );
		var $pagination = $( '.pagination' );
		var newTerm;
		var $termText;
		var $responseObj;
		var newTotalPages;

		e.preventDefault();

		$filterLinks.removeAttr('href');

		$filterLinks.unbind( 'click' );

		$( '.mm-posts-filter li.active' ).removeClass( 'active' );

		//Set term-data value to empty when all terms are clicked.
		if( filterStyle == 'links' ) {
			$termText = $this.text();

			if( $this.hasClass( 'mm-posts-filter-all') ) {
				$termText = '';
			} else {
				$termText = $this.text();
			}
		}

		if( filterStyle == 'dropdown' ) {
			$termText = $this.val();

			if( $this.val() == -1 ) {
				$termText = '';
			} else {
				$termText = $this.val();
			}
		}

		$( '.mm-loading' ).show();

		$this.parent( 'li' ).addClass( 'active' );

		$( '.no-results' ).remove();

		// Set the text of the clicked term as the term-data attribute.
		$this.parents( '.mm-posts-filter-wrapper' ).siblings( '.mm-posts' ).attr( 'data-term', $termText );

		// Grab the value of the new term-data.
		newTerm = $mmPosts.attr( 'data-term' );
		mm_posts_ajax_data( newTerm, totalPages, newPageVal );

		$mmPostsLoop.empty();

		// Make the AJAX request.
		$.post( ajaxurl, data, function( response ) {

			$responseObj = $( response );

			// Format and update the posts loop.
			$mmPostsLoop.replaceWith( response );

			newTotalPages = $responseObj.filter( '.ajax-total-pages' ).text();

			$mmPosts.attr( 'data-total-pages', newTotalPages );

			//Reload pagination links when number of pages changes.
			if ( $mmPosts.hasClass( 'mm-ajax-pagination' ) ) {
				if ( newTotalPages > 1 ) {
					$pagination.empty().removeData("twbs-pagination").unbind("page").twbsPagination({
			        	totalPages: newTotalPages,
			        	last : false,
			        	first : false
			    	});
				} else {
					$pagination.empty().removeData("twbs-pagination").unbind("page");
				}
			}

			if( $( '.mm-posts-loop' ).find( 'article' ).length == 0 ) {
				$( '.mm-posts-loop' ).after( '<span class="no-results">No Results Found.</span>' );
			}

			//Remove loading text and total posts markup.
			$( '.mm-loading' ).hide();
			$mmPosts.find( '.ajax-total-pages' ).remove();
			$filterLinks.bind( 'click', mm_posts_ajax_filter );
			$filterLinks.attr( 'href', "#" );

			$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );
		});

}

var mm_posts_ajax_pagination = function( newTerm ) {
	$this = $( this );
	var $mmPostsLoop = $mmPosts.find( '.mm-posts-loop' );
	var $paginationWrapper = $( '.mm-posts-ajax-pagination-wrapper' );
	var $paginationLinks = $( '.pagination a' );
	var $page;
	var $responseObj;
	var newPageVal;
	var newTerm;
	var postsPerPage;
	var pageNumber;
	var pageNumberRounded;

	//Set page-data value to the text of current clicked page number.

	newPageVal = $paginationWrapper.find( '.pagination li.active a' ).text();

	$mmPostsLoop.attr( 'data-current-page', newPageVal );

	newTerm = $mmPosts.attr( 'data-term' );

	mm_posts_ajax_data( newTerm, pageNumberRounded, newPageVal );

	$mmPostsLoop.empty();

	$( '.mm-loading' ).show();

	// Make the AJAX request.
	$.post( ajaxurl, data, function( response ) {

		$responseObj = $( response );

		// Format and update the posts loop.
		$mmPostsLoop.replaceWith( response );

		newTotalPages = $responseObj.filter( '.ajax-total-pages' ).text();

		$mmPosts.attr( 'data-total-pages', newTotalPages );

		$mmPosts.find( '.ajax-total-pages' ).remove();

		$( '.mm-loading' ).hide();

		$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );

	});

}

jQuery( document ).ready( function( $ ) {

	var totalPages;

	mm_posts_data();

	loading = '<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw mm-loading"></i>';
	$( '.mm-posts' ).prepend( loading );
	$( '.mm-loading' ).hide();


	//Runs the AJAX filter.
	$( '.mm-posts-filter .cat-item a').on( 'click', mm_posts_ajax_filter );
	$( '.mm-posts-filter #term_dropdown' ).on( 'change', mm_posts_ajax_filter );

	totalPages = $( '.mm-posts' ).attr( 'data-total-pages' );

	//Only run AJAX pagination if activated.
	if ( $mmPosts.hasClass( 'mm-ajax-pagination' ) ) {

		$( '.mm-posts-ajax-pagination-wrapper' ).twbsPagination({
		    totalPages: totalPages,
		    last : false,
		    first :false
		});

		$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );
	}

});