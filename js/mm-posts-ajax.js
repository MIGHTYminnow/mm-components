var $ = jQuery;

var mm_posts_data = function( $mmPosts ) {
	//Grab MM posts atts from data.
	$mmPosts.each( function() {
		$this = $( this );
		currentId         = $this.data( 'current-post-id' );
		queryType         = $this.data( 'query-type' );
		postIds           = $this.data( 'post-ids' );
		postType          = $this.data( 'post-type' );
		taxonomy          = $this.data( 'taxonomy' );
		headingLevel      = $this.data( 'heading-level' );
		perPage           = $this.data( 'per-page' );
		pagination        = $this.data( 'pagination' );
		template          = $this.data( 'template' );
		showFeaturedImage = $this.data( 'show-featured-image' );
		featuredImageSize = $this.data( 'featured-image-size' );
		showPostInfo      = $this.data( 'show-post-info' );
		showPostMeta      = $this.data( 'show-post-meta' );
		usePostContent    = $this.data( 'use-post-content' );
		linkTitle         = $this.data( 'link-title' );
		masonry           = $this.data( 'masonry' );
		fallbackImage     = $this.data( 'fallback-image' );
		imageTag          = $this.data( 'image-tag' );
		imageTag          = $this.data( 'paged' );
		totalPages        = $this.data( 'total-pages' );
		totalPosts        = $this.data( 'total-posts' );
		filterStyle       = $this.data( 'filter-style' );
		paged             = $this.data( 'paged' );

	});

}


var mm_posts_ajax_data = function( newTerm, newPageVal ) {

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
	var $mmPosts = $this.parents( '.mm-posts-wrapper' ).find( '.mm-posts' );
	var $mmPostsFilter = $this.parents( '.mm-posts-wrapper' ).find( '.mm-posts-filter' );
	var $mmPostsLoop = $mmPosts.find( '.mm-posts-loop' );
	var $mmLoading = $mmPosts.find( '.mm-loading' );
	var $filterLinks = $( '.mm-posts-filter a' );
	var $pagination = $this.parents( '.mm-posts-wrapper' ).find( '.pagination' );
	var newTerm;
	var $termText;
	var $responseObj;
	var newTotalPages;

	mm_posts_data( $mmPosts );

	e.preventDefault();

	$filterLinks.removeAttr('href');

	$filterLinks.unbind( 'click' );

	$mmPostsFilter.find( 'li.active' ).removeClass( 'active' );

	filterStyle = $mmPosts.attr( 'data-filter-style' );

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

		if( $this.val() == -1 ) {
			$termText = '';
		} else {
			$termText = $this.val();
		}
	}

	$mmLoading.show();

	$this.parent( 'li' ).addClass( 'active' );

	$( '.no-results' ).remove();

	// Grab the value of the new term-data.
	newTerm = $termText;

	mm_posts_ajax_data( newTerm, newPageVal );

	$mmPostsLoop.empty();

	// Make the AJAX request.
	$.post( ajaxurl, data, function( response ) {

		$responseObj = $( response );

		// Format and update the posts loop.
		$mmPostsLoop.replaceWith( response );

		newTotalPages = $responseObj.filter( '.ajax-total-pages' ).text();

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
			$( '.mm-posts-loop' ).before( '<span class="no-results">No Results Found.</span>' );
		}

		$mmLoading.hide();

		//Remove loading text and total posts markup.
		$mmPosts.find( '.ajax-total-pages' ).remove();
		$filterLinks.bind( 'click', mm_posts_ajax_filter );
		$filterLinks.attr( 'href', "#" );

		$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );
	});

}

var mm_posts_ajax_pagination = function( newTerm ) {
	$this = $( this );
	var $mmPosts = $this.prev( '.mm-posts' );
	var $mmPostsLoop = $mmPosts.find( '.mm-posts-loop' );
	var $mmLoading = $mmPosts.parent( '.mm-posts-wrapper' ).find( '.mm-loading' );
	var newPageVal = $this.find( 'li.active a' ).text();
	var newTerm = $mmPosts.attr( 'data-term' );

	mm_posts_data( $mmPosts );

	mm_posts_ajax_data( newTerm, newPageVal );

	$mmPostsLoop.css({ "visibility" : "hidden" });

	$mmLoading.show();

	// Make the AJAX request.
	$.post( ajaxurl, data, function( response ) {

		$responseObj = $( response );

		// Format and update the posts loop.
		$mmPostsLoop.replaceWith( response );

		newTotalPages = $responseObj.filter( '.ajax-total-pages' ).text();

		$mmPosts.find( '.ajax-total-pages' ).remove();

		$mmLoading.hide();

	});

}

jQuery( document ).ready( function( $ ) {

	var $mmPosts = $( '.mm-posts' );
	var $mmPostsPaginationWrapper = $( '.mm-posts-ajax-pagination-wrapper' );

	loading = '<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw mm-loading"></i>';
	$( '.mm-posts' ).prepend( loading );
	$( '.mm-loading' ).hide();

	//loop overy every mm posts instance.
	$mmPosts.each( function() {
		var $this = $( this );
		var $paginationWrapper = $this.parents( '.mm-posts-wrapper' ).find( '.mm-posts-ajax-pagination-wrapper' );
		totalPages = $this.data( 'total-pages' );

		//Only run AJAX pagination if activated.
		if ( $this.hasClass( 'mm-ajax-pagination' ) ) {
			$paginationWrapper.twbsPagination({
			    totalPages: totalPages,
			    last : false,
			    first :false,
			    initiateStartPageClick: false,
			   	onPageClick: mm_posts_ajax_pagination,
			});
		}
	});

	//Runs the AJAX filter.
	$( '.mm-posts-filter .cat-item a').on( 'click', mm_posts_ajax_filter );
	$( '.mm-posts-filter #term_dropdown' ).on( 'change', mm_posts_ajax_filter );

});