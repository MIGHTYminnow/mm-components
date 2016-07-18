var $ = jQuery;

var mm_posts_ajax_data = function( newTerm, newPageVal ) {

	data = {
		action            : 'mm_posts_ajax_filter',
		currentPage       : newPageVal,
		globalPostId      : mmPostsData.global_post_id,
		taxonomy          : mmPostsData.taxonomy,
		queryType         : mmPostsData.query_type,
		postIds           : mmPostsData.postIds,
		postType          : mmPostsData.post_type,
		term              : newTerm,
		headingLevel      : mmPostsData.heading_level,
		perPage           : mmPostsData.per_page,
		paged             : mmPostsData.paged,
		pagination        : mmPostsData.pagination,
		template          : mmPostsData.template,
		showFeaturedImage : mmPostsData.show_featured_image,
		featuredImageSize : mmPostsData.featured_image_size,
		showPostInfo      : mmPostsData.show_post_info,
		showPostMeta      : mmPostsData.show_post_meta,
		usePostContent    : mmPostsData.use_post_content,
		linkTitle         : mmPostsData.link_title,
		masonry           : mmPostsData.masonry,
		totalPosts        : mmPostsData.total_posts,
		totalPages        : mmPostsData.total_pages,
		filterStyle       : mmPostsData.filter_style
	};
}

var mm_posts_ajax_filter = function( e, newPageVal ) {
	var $this = $( this );
	var $mmPosts = $( '.mm-posts' );
	var $mmPostsLoop = $mmPosts.find( '.mm-posts-loop' );
	var $filterLinks = $( '.mm-posts-filter a' );
	var $pagination = $( '.pagination' );
	var filterStyle = mmPostsData.filter_style;
	var totalPages = mmPostsData.total_pages;
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
			$( '.mm-posts-loop' ).after( '<span class="no-results">No Results Found.</span>' );
		}

		$( '.mm-loading' ).hide();

		//Remove loading text and total posts markup.
		$mmPosts.find( '.ajax-total-pages' ).remove();
		$filterLinks.bind( 'click', mm_posts_ajax_filter );
		$filterLinks.attr( 'href', "#" );

		$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );
	});

}

var mm_posts_ajax_pagination = function( newTerm ) {
	$this = $( this );
	var $mmPosts = $( '.mm-posts' );
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

	newTerm = mmPostsData.term;

	mm_posts_ajax_data( newTerm, newPageVal );

	$mmPostsLoop.css({ "visibility" : "hidden" });

	$( '.mm-loading' ).show();

	// Make the AJAX request.
	$.post( ajaxurl, data, function( response ) {

		$responseObj = $( response );

		// Format and update the posts loop.
		$mmPostsLoop.replaceWith( response );

		newTotalPages = $responseObj.filter( '.ajax-total-pages' ).text();

		$mmPosts.find( '.ajax-total-pages' ).remove();

		$( '.mm-loading' ).hide();

		$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );

	});

}

jQuery( document ).ready( function( $ ) {

	var totalPages;
	var $mmPosts = $( '.mm-posts' );

	loading = '<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw mm-loading"></i>';
	$( '.mm-posts' ).prepend( loading );
	$( '.mm-loading' ).hide();

	//Runs the AJAX filter.
	$( '.mm-posts-filter .cat-item a').on( 'click', mm_posts_ajax_filter );
	$( '.mm-posts-filter #term_dropdown' ).on( 'change', mm_posts_ajax_filter );

	//Only run AJAX pagination if activated.
	if ( $mmPosts.hasClass( 'mm-ajax-pagination' ) ) {

		$( '.mm-posts-ajax-pagination-wrapper' ).twbsPagination({
		    totalPages: mmPostsData.total_pages,
		    last : false,
		    first :false
		});

		$( '.pagination li:not(.disabled)' ).on( 'click', mm_posts_ajax_pagination );
	}

});