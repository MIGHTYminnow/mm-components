<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Posts
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Posts component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_posts( $args ) {

	$component  = 'mm-posts';

	// Set our defaults and use them as needed.
	$defaults = array(
		'query_type'          => 'collection',
		'post_ids'            => '',
		'post_type'           => 'post',
		'taxonomy'            => '',
		'term'                => '',
		'heading_level'       => 'h1',
		'per_page'            => 10,
		'pagination'          => '',
		'template'            => '',
		'show_featured_image' => false,
		'featured_image_size' => 'thumbnail',
		'show_post_info'      => false,
		'show_post_meta'      => false,
		'use_post_content'    => false,
		'ajax_filter'         => false,
		'filter_style'        => 'links',
		'link_title'          => true,
		'masonry'             => false,
		'current_page'        => '1',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$query_type      = sanitize_text_field( $args['query_type'] );
	$post_ids        = $args['post_ids'] ? str_getcsv( $args['post_ids'] ) : '';
	$post_type       = sanitize_text_field( $args['post_type'] );
	$taxonomy        = sanitize_text_field( $args['taxonomy'] );
	$term            = sanitize_text_field( $args['term'] );
	$heading_level   = $args['heading_level'];
	$per_page        = (int)$args['per_page'];
	$template        = sanitize_text_field( $args['template'] );
	$masonry         = mm_true_or_false( $args['masonry'] );
	$ajax_filter     = mm_true_or_false( $args['ajax_filter'] );
	$filter_style    = sanitize_text_field( $args['filter_style'] );
	$pagination      = sanitize_text_field( $args['pagination'] );

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Maybe add template class.
	if ( $template ) {
		$mm_classes = "$mm_classes $template";
	}

	// Maybe add AJAX pagination class.
	if ( $args['pagination'] == 'ajax-pagination' ) {
		$mm_classes .= ' mm-ajax-pagination';
		wp_enqueue_script( 'mm-jquery-pagination' );
	}

	// Maybe add category filter AJAX.
	if ( $ajax_filter || $args['pagination'] == 'ajax-pagination' ) {
		wp_enqueue_script( 'mm-posts-ajax' );
	}

	// Maybe set up masonry.
	if ( $masonry ) {
		wp_enqueue_script( 'mm-isotope' );
		$masonry_class = 'mm-masonry';
	} else {
		$masonry_class = '';
	}

	// Set up the context we're in.
	global $post;
	$global_post_id = (int)$post->ID;

	// Set up a generic query depending on query type.
	if ( $query_type == 'specific' ) {
		$query_args = array(
			'post_type'      => mm_get_post_types( 'mm-posts' ),
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
		);
	} else {
		$query_args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
		);
	}

	// Exclude the page we're on from the query to prevent an infinite loop.
	$query_args['post__not_in'] = array(
		$global_post_id
	);

	// Add to our query if additional params have been passed.
	if ( $post_ids ) {

		$query_args['post__in'] = $post_ids;
		$query_args['orderby']  = 'post__in';

	} elseif ( $taxonomy && $term ) {

		// First try the term by ID, then try by slug.
		if ( is_int( $term ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term
				),
			);
		} else {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $term
				),
			);
		}
	}

	// Allow the query to be filtered.
	$query_args = apply_filters( 'mm_posts_query_args', $query_args, $args );

	// Do the query.
	$query = new WP_Query( $query_args );

	// Store the global post object as the context we'll pass to our hooks.
	$context = $post;

	do_action( 'mm_posts_register_hooks', $context, $args );

	ob_start(); ?>

	<div class="mm-posts-wrapper">

		<?php do_action( 'mm_posts_before', $query, $context, $args ); ?>

		<div class="<?php echo esc_attr( $mm_classes ); echo esc_attr( $masonry_class );?>" <?php echo mm_posts_get_data_attributes( $query, $context, $args ); ?> >

			<?php do_action( 'mm_posts_before_loop', $query, $context, $args ); ?>

			<div class="mm-posts-loop">

				<?php while ( $query->have_posts() ) : $query->the_post(); ?>

					<?php setup_postdata( $query->post ); ?>

					<?php echo mm_posts_article( $post, $context, $args ); ?>

				<?php endwhile; ?>

			</div>

			<?php do_action( 'mm_posts_after_loop', $query, $context, $args ); ?>

		</div>

		<?php do_action( 'mm_posts_after', $query, $context, $args ); ?>

	</div>

	<?php

	wp_reset_postdata();

	do_action( 'mm_posts_reset_hooks' );

	return ob_get_clean();
}

add_shortcode( 'mm_posts', 'mm_posts_shortcode' );
/**
 * Posts shortcode.
 *
 * @since   1.0.0
 *
 * @param   array   $atts  Shortcode attributes.
 *
 * @return  string         Shortcode output.
 */
function mm_posts_shortcode( $atts = array() ) {

	return mm_posts( $atts );
}

add_action( 'mm_posts_register_hooks', 'mm_posts_register_default_hooks', 9, 2 );
/**
 * Set up our default hooks.
 *
 * @since  1.0.0
 *
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_register_default_hooks( $context, $args ) {

	if ( mm_true_or_false( $args['ajax_filter'] ) ) {
		add_action( 'mm_posts_before', 'mm_posts_output_taxonomy_term_filter', 10, 3 );
	}

	if ( mm_true_or_false( $args['masonry'] ) ) {
		add_action( 'mm_posts_before_loop', 'mm_posts_output_masonry_sizers', 12, 3 );
	}

	add_action( 'mm_posts_header', 'mm_posts_output_post_header', 10, 3 );

	if ( mm_true_or_false( $args['show_featured_image'] ) ) {
		add_action( 'mm_posts_content', 'mm_posts_output_post_image', 8, 3 );
	}

	add_action( 'mm_posts_content', 'mm_posts_output_post_content', 10, 3 );

	if ( mm_true_or_false( $args['show_post_meta'] ) ) {
		add_action( 'mm_posts_footer', 'mm_posts_output_post_meta', 10, 3 );
	}

	if ( ( $args['pagination'] == 'next-prev' || $args['pagination'] == 'page-numbers' ) ) {
		add_action( 'mm_posts_after', 'mm_posts_output_pagination', 12, 3 );
	}

	if ( $args['pagination'] == 'ajax-pagination' ) {
		add_action( 'mm_posts_after', 'mm_posts_output_ajax_pagination_wrapper', 12, 3 );
	}

}

add_action( 'mm_posts_reset_hooks', 'mm_posts_reset_default_hooks' );
/**
 * Reset all the hooks.
 *
 * @since  1.0.0
 */
function mm_posts_reset_default_hooks() {

	remove_all_actions( 'mm_posts_before' );
	remove_all_actions( 'mm_posts_before_loop' );
	//remove_all_actions( 'mm_posts_before_article' );
	//remove_all_actions( 'mm_posts_after_article' );
	remove_all_actions( 'mm_posts_header' );
	remove_all_actions( 'mm_posts_content' );
	remove_all_actions( 'mm_posts_footer' );
	remove_all_actions( 'mm_posts_after_loop' );
	remove_all_actions( 'mm_posts_after' );

	remove_all_filters( 'mm_posts_post_header' );
	remove_all_filters( 'mm_posts_post_title' );
	remove_all_filters( 'mm_posts_post_info' );
	remove_all_filters( 'mm_posts_post_image' );
	remove_all_filters( 'mm_posts_post_content' );
	remove_all_filters( 'mm_posts_post_meta' );
}

/**
 * Output masonry sizers.
 *
 * @since  1.0.0
 */
function mm_posts_output_masonry_sizers() {

	echo '<div class="mm-posts-masonry-gutter"></div>';
}

/**
 * Output ajax pagination wrapper.
 *
 * @since  1.0.0
 */
function mm_posts_output_ajax_pagination_wrapper() {

	echo '<div class="mm-posts-ajax-pagination-wrapper"></div>';
}

/**
 * Default post header output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_article( $post, $context, $args ) {

	ob_start(); ?>

	<?php do_action( 'mm_posts_before_article', $post, $context, $args ); ?>

	<article id="post-<?php the_ID( $post ); ?>" <?php post_class( 'mm-post' ); ?> itemscope itemtype="http://schema.org/BlogPosting" itemprop="blogPost" aria-label="Article">

		<?php do_action( 'mm_posts_header', $post, $context, $args ); ?>

		<?php do_action( 'mm_posts_content', $post, $context, $args ); ?>

		<?php do_action( 'mm_posts_footer', $post, $context, $args ); ?>

	</article>

	<?php do_action( 'mm_posts_after_article', $post, $context, $args ); ?>

	<?php return ob_get_clean();
}

/**
 * Output a taxonomy term filter.
 *
 * @since   1.0.0
 *
 * @param   WP_Query  $query    The query object.
 * @param   WP_Post   $context  The global post object.
 * @param   array     $args     The instance args.
 *
 * @return  string              The category filters HTML.
 */
function mm_posts_taxonomy_term_filter( $query, $context, $args ) {

	$taxonomy = $args['taxonomy'] ? ( $args['taxonomy'] ) : 'category';

	ob_start(); ?>

	<div class="mm-posts-filter-wrapper">

	<?php if ( $args['filter_style'] == 'dropdown' ) : ?>
		<div class="mm-posts-filter" >
			<?php wp_dropdown_categories(array(
				'taxonomy'         => $taxonomy,
				'orderby'          => 'name',
		        'show_option_none' => 'All',
				'hide_empty'       => 1,
		        'value_field'      => 'name',
				'name'             => 'term-select',
				'id'               => 'term_dropdown',
			)); ?>

		</div>

	<?php else: ?>

		<ul class="mm-posts-filter" >
			<li class="cat-item active"><a href="#" class="mm-posts-filter-all"><?php _e( 'All', 'mm-components' ); ?></a></li>
			<?php get_terms( array(
				'title_li'     => '',
				'hide_empty'   => 1,
				'hierarchical' => false,
				'taxonomy'     => $taxonomy,
				)
			); ?>
		</ul>

	<?php endif; ?>

	</div>

	<?php

	return apply_filters( 'mm_posts_taxonomy_term_filter', ob_get_clean(), $query, $context, $args );
}

/**
 * Echo the taxonomy term filter.
 *
 * @since   1.0.0
 *
 * @param   WP_Query  $query    The query object.
 * @param   WP_Post   $context  The global post object.
 * @param   array     $args     The instance args.
 *
 * @return  string              The category filters HTML.
 */
function mm_posts_output_taxonomy_term_filter( $query, $context, $args ) {
	echo mm_posts_taxonomy_term_filter( $query, $context, $args );
}

/**
 * Output json string of args.
 *
 * @since   1.0.0
 *
 * @param   WP_Query  $query    The query object.
 * @param   WP_Post   $context  The global post object.
 * @param   array     $args     The instance args.
 *
 * @return  string              The category filters HTML.
 */
function mm_posts_get_data_attributes( $query, $context, $args ) {

	$data_atts = array();
	$data_args = array(
		'query_type',
		'post_ids',
		'post_type',
		'taxonomy',
		'term',
		'heading_level',
		'per_page',
		'pagination',
		'template',
		'show_featured_image',
		'featured_image_size',
		'show_post_info',
		'show_post_meta',
		'use_post_content',
		'filter_style',
		'link_title',
		'masonry',
		'current_page',
	);

   // Convert args to data-* attributes.
   foreach ( $data_args as $data_arg ) {
       if ( ! empty( $args[ $data_arg ] ) ) {
           $data_atts[] = 'data-' . str_replace( '_', '-', $data_arg ) . '=' . (string)$args[ $data_arg ];
       }
   }

   // Add special data attributes.
   $data_atts[] = 'data-total-pages=' . $query->max_num_pages;
   $data_atts[] = 'data-total-posts=' . $query->found_posts;
   $data_atts[] = 'data-global-post-id=' . $context->ID;

   // Convert to a string.
   $data_atts = esc_attr( implode( ' ', $data_atts ) );

   return apply_filters( 'mm_posts_data_attributes', $data_atts, $query, $context, $args );
}

/**
 * Get AJAX filter args.
 *
 * @since  1.0.0
 *
 * @param  array   $args     The instance args.
 */
function mm_posts_get_ajax_args( $args ) {

	//Get data from AJAX call.
	$global_post_id      = sanitize_text_field( $_POST['globalPostId'] );
	$taxonomy            = sanitize_text_field( $_POST['taxonomy'] );
	$query_type          = sanitize_text_field( $_POST['queryType'] );
	$post_ids            = sanitize_text_field( $_POST['postIds'] );
	$post_type           = sanitize_text_field( $_POST['postType'] );
	$term                = sanitize_text_field( $_POST['term'] );
	$heading_level       = sanitize_text_field( $_POST['headingLevel'] );
	$per_page            = sanitize_text_field( $_POST['perPage'] );
	$pagination          = sanitize_text_field( $_POST['pagination'] );
	$template            = sanitize_text_field( $_POST['template'] );
	$show_featured_image = sanitize_text_field( $_POST['showFeaturedImage'] );
	$featured_image_size = sanitize_text_field( $_POST['featuredImageSize'] );
	$show_post_info      = sanitize_text_field( $_POST['showPostInfo'] );
	$show_post_meta      = sanitize_text_field( $_POST['showPostMeta'] );
	$use_post_content    = sanitize_text_field( $_POST['usePostContent'] );
	$link_title          = sanitize_text_field( $_POST['linkTitle'] );
	$masonry             = sanitize_text_field( $_POST['masonry'] );
	$current_page        = sanitize_text_field( $_POST['currentPage'] );
	$paged               = sanitize_text_field( $_POST['currentPage'] );
	$total_pages         = sanitize_text_field( $_POST['totalPages'] );
	$total_posts         = sanitize_text_field( $_POST['totalPosts'] );

	//Set data as new MM post args.
	$args = array(
		'global_post_id'      => $global_post_id,
		'post__not_in'        => array( $global_post_id ),
		'query_type'          => $query_type,
		'post_ids'            => $post_ids,
		'post_type'           => $post_type,
		'taxonomy'            => $taxonomy,
		'term'                => $term,
		'heading_level'       => $heading_level,
		'per_page'            => $per_page,
		'paged'               => $current_page,
		'pagination'          => $pagination,
		'template'            => $template,
		'show_featured_image' => $show_featured_image,
		'featured_image_size' => $featured_image_size,
		'show_post_info'      => $show_post_info,
		'show_post_meta'      => $show_post_meta,
		'use_post_content'    => $use_post_content,
		'link_title'          => $link_title,
		'masonry'             => $masonry,
		'current_page'        => $current_page,
		'total_pages'         => $total_pages,
		'total_posts'         => $total_posts,
	);

	return apply_filters( 'mm_posts_ajax_args', $args );
}

add_action( 'wp_ajax_nopriv_mm_posts_ajax_filter', 'mm_posts_ajax_filter', 1 );
add_action( 'wp_ajax_mm_posts_ajax_filter', 'mm_posts_ajax_filter', 1 );
/**
 * AJAX handler for filtering posts.
 *
 * @since  1.0.0
 */
function mm_posts_ajax_filter( $args ) {

	$args = mm_posts_get_ajax_args( $args );
	$taxonomy = ( '' !== $args['taxonomy'] ) ? $args['taxonomy'] : 'category';

	global $post;
	$post = get_post( $args['global_post_id'] );
	$query_args = array(
		'post_type'    => $args['post_type'],
		'paged'        => $args['current_page'],
		'post__not_in' => array(
				$args['global_post_id']
			),
		'posts_per_page' => $args['per_page'],
		'post_status'    => 'publish',
		'max_num_pages'  => $args[ 'total_pages'],
	);

	if( '' !== $args['term'] ) {
	$query_args['tax_query'] = array (
		array(
			'taxonomy' => $taxonomy,
			'field'    => 'name',
			'terms'    => $args['term']
			)
		);
	}

	if( $args['pagination'] == 'ajax-pagination' ) {
		$page = ( get_query_var( 'page' ) ) ? (int)get_query_var( 'page' ) : 1;
	}

	// Do the query.
	$query = new WP_Query( $query_args );

	// Store the global post object as the context we'll pass to our hooks.
	$context = $post;

	do_action( 'mm_posts_register_hooks', $post, $context, $args ); ?>

	<div class="mm-posts-loop">

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

			<?php setup_postdata( $query->post ); ?>

			<?php echo mm_posts_article( $post, $context, $args ); ?>

		<?php endwhile; ?>

	</div>

	<?php

	// Grab the new number of posts per page to update pagination links.
	$new_page_number = ceil( $query->found_posts / $args['per_page']) ?>

	<span class="ajax-total-pages"><?php echo $new_page_number; ?></span>

	<?php wp_reset_postdata();

	do_action( 'mm_posts_reset_hooks' );

	die();

}

/**
 * Default post header output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_post_header( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_header', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	echo '<header class="entry-header">';

	mm_posts_output_post_title( $post, $context, $args );

	if ( mm_true_or_false( $args['show_post_info'] ) ) {
		mm_posts_output_post_info( $post, $context, $args );
	}

	echo '</header>';
}

/**
 * Default post title output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_post_title( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_title', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	$heading_level = $args['heading_level'];
	$link_title    = mm_true_or_false( $args['link_title'] );

	if ( $link_title ) {

		printf(
			'<%s class="entry-title" itemprop="headline"><a href="%s" title="%s" rel="bookmark">%s</a></%s>',
			esc_attr( $heading_level ),
			get_permalink( $post->ID ),
			get_the_title( $post->ID ),
			get_the_title( $post->ID ),
			esc_attr( $heading_level )
		);

	} else {

		printf(
			'<%s class="entry-title" itemprop="headline">%s</%s>',
			esc_attr( $heading_level ),
			get_the_title( $post->ID ),
			esc_attr( $heading_level )
		);
	}
}

/**
 * Default post info output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_post_info( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_info', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	echo '<span class="entry-info-wrap">';

		// If the site is running Genesis, use the Genesis post info.
		if ( function_exists( 'genesis_post_info' ) ) {

			genesis_post_info();

		} else {

			echo '<span class="entry-info">';

			$format = get_option( 'date_format' );
			$time   = get_the_date( $format );

			printf(
				'<time class="%s" itemprop="datePublished">%s</time>',
				'entry-time',
				$time
			);

			printf(
				' %s ',
				__( 'by', 'mm-components' )
			);

			printf(
				'<a class="%s" href="%s">%s</a>',
				'entry-author',
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				get_the_author()
			);

			echo '</span>';
		}

	echo '</span>';
}

/**
 * Default featured image output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_post_image( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_image', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	// Default to using the 'post-thumbnail' size.
	if ( '' !== $args['featured_image_size'] ) {
		$image_size = esc_attr( $args['featured_image_size'] );
	} else {
		$image_size = 'post-thumbnail';
	}

	if ( has_post_thumbnail( $post->ID ) ) {

		printf(
			'<div class="entry-image"><a href="%s">%s</a></div>',
			get_permalink( $post->ID ),
			get_the_post_thumbnail( $post->ID, $image_size )
		);
	}
}

/**
 * Default post content output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_post_content( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_content', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	echo '<div class="entry-content" itemprop="text">';

	if ( mm_true_or_false( $args['use_post_content'] ) ) {

		the_content();

	} else {

		the_excerpt();
	}

	echo '</div>';
}

/**
 * Default post meta output.
 *
 * @since  1.0.0
 *
 * @param  object  $post     The current post object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_post_meta( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_meta', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	echo '<div class="entry-meta-wrap">';

	// If the site is running Genesis, use the Genesis post meta.
	if ( function_exists( 'genesis_post_meta' ) ) {

		genesis_post_meta();

	} else {

		$cats = get_the_category_list();
		$tags = get_the_tag_list( '<ul class="post-tags"><li>', '</li><li>', '</li></ul>' );

		echo '<div class="entry-meta">';

		if ( $cats ) {
			echo $cats;
		}

		if ( $tags ) {
			echo $tags;
		}

		echo '</div>';
	}

	echo '</div>';
}

/**
 * Output the pagination links.
 *
 * @since  1.0.0
 *
 * @param  object  $query    The query object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_posts_output_pagination( $query, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_output_pagination', '', $query, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	// Get the page query arg from the URL.
	$page = ( get_query_var( 'page' ) ) ? (int)get_query_var( 'page' ) : 1;

	// Bail if we don't have any additional pages to show.
	if ( 1 >= $query->max_num_pages ) {
		return;
	}

	// Default to next/prev links.
	if ( ! $args['pagination'] ) {
		$args['pagination'] = 'next-prev';
	}

	echo '<div class="pagination-wrap pagination-' . esc_attr( $args['pagination'] ) . '">';

	switch ( $args['pagination'] ) {

		case 'next-prev':

			if ( 1 < $page ) {
				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . ( $page - 1 ),
					'pagination-link prev button',
					__( 'Previous', 'mm-components' ),
					__( 'Previous', 'mm-components' )
				);
			}

			if ( $query->max_num_pages > $page ) {
				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . ( $page + 1 ),
					'pagination-link next button',
					__( 'Next', 'mm-components' ),
					__( 'Next', 'mm-components' )
				);
			}

			break;

		case 'page-numbers':

			if ( 5 >= $query->max_num_pages ) {

				// We have 5 or less total pages.
				for ( $i = 1; $i <= $query->max_num_pages; $i++ ) {
					if ( $i == $page ) {
						$link_classes = 'pagination-link page-' . $i . ' button selected';
					} else {
						$link_classes = 'pagination-link page-' . $i . ' button';
					}
					printf(
						'<a href="%s" class="%s" title="%s">%s</a>',
						'?page=' . $i,
						$link_classes,
						$i,
						$i
					);
				}

			} elseif ( 3 <= $page && ( $query->max_num_pages - 2 ) >= $page ) {

				// We have 6 or more total pages and we're showing a page between 3 and (total - 2).
				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=1',
					'pagination-link page-1 button',
					'1',
					'1'
				);

				if ( 3 != $page ) {
					echo '<span>&hellip;</span>';
				}

				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . ( $page - 1 ),
					'pagination-link page-' . ( $page - 1 ) . ' button',
					$page - 1,
					$page - 1
				);
				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . $page,
					'pagination-link page-' . $page . ' button selected',
					$page,
					$page
				);
				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . ( $page + 1 ),
					'pagination-link page-' . ( $page + 1 ) . ' button',
					$page + 1,
					$page + 1
				);

				if ( $page != ( $query->max_num_pages - 2 ) ) {
					echo '<span>&hellip;</span>';
				}

				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . $query->max_num_pages,
					'pagination-link page-' . $query->max_num_pages . ' button',
					$query->max_num_pages,
					$query->max_num_pages
				);

			} elseif ( 3 > $page ) {

				// We have more than 6 pages and we're showing page 1 or 2.
				for ( $i = 1; $i <= 3; $i++ ) {
					if ( $i == $page ) {
						$link_classes = 'pagination-link page-' . $i . ' button selected';
					} else {
						$link_classes = 'pagination-link page-' . $i . ' button';
					}
					printf(
						'<a href="%s" class="%s" title="%s">%s</a>',
						'?page=' . $i,
						$link_classes,
						$i,
						$i
					);
				}

				echo '<span>&hellip;</span>';

				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=' . $query->max_num_pages,
					'pagination-link page-' . $query->max_num_pages . ' button',
					$query->max_num_pages,
					$query->max_num_pages
				);

			} else {

				// We have more than 6 pages and we're showing the last or second to last page.
				printf(
					'<a href="%s" class="%s" title="%s">%s</a>',
					'?page=1',
					'pagination-link page-1 button',
					'1',
					'1'
				);

				echo '<span>&hellip;</span>';

				for ( $i = ( $query->max_num_pages - 2 ); $i <= $query->max_num_pages; $i++ ) {
					if ( $i == $page ) {
						$link_classes = 'pagination-link page-' . $i . ' button selected';
					} else {
						$link_classes = 'pagination-link page-' . $i . ' button';
					}
					printf(
						'<a href="%s" class="%s" title="%s">%s</a>',
						'?page=' . $i,
						$link_classes,
						$i,
						$i
					);
				}
			}
			break;
	}

	echo '</div>';
}

/**
 * Output multiple postmeta values in a standard format.
 *
 * @since  1.0.0
 *
 * @param  int     $post_id   The post ID.
 * @param  array   $keys      The postmeta keys.
 * @param  string  $outer_el  The outer wrapper element.
 * @param  string  $inner_el  The inner wrapper element.
 */
function mm_posts_output_postmeta_values( $post_id, $keys, $outer_el = 'ul', $inner_el = 'li' ) {

	if ( ! is_array( $keys ) || empty( $keys ) ) {
		return;
	}

	printf(
		'<%s class="%s">',
		$outer_el,
		'entry-meta-wrap'
	);

	foreach ( $keys as $key ) {
		mm_posts_output_postmeta_value( $post_id, $key, $inner_el );
	}

	printf(
		'</%s>',
		$outer_el
	);
}

/**
 * Output a specific postmeta value in a standard format.
 *
 * @since  1.0.0
 *
 * @param  int     $post_id  The post ID.
 * @param  string  $key      The postmeta key.
 * @param  string  $element  The wrapper element.
 */
function mm_posts_output_postmeta_value( $post_id, $key, $element = 'div' ) {

	$value = get_post_meta( $post_id, $key, true );

	if ( $value ) {
		printf(
			'<%s class="%s">%s</%s>',
			$element,
			'entry-' . esc_attr( $key ),
			esc_html( $value ),
			$element
		);
	}
}

add_filter( 'mm_posts_query_args', 'mm_posts_filter_from_query_args', 10, 2 );
/**
 * Use specific query args present in the URL to alter the mm_posts query.
 *
 * @since   1.0.0
 *
 * @param   array  $query_args  The original query args.
 * @param   array  $args        The instance args.
 *
 * @return  array  $query_args  The updated query args.
 */
function mm_posts_filter_from_query_args( $query_args, $args ) {

	if ( isset( $_GET['per_page'] ) ) {
		$query_args['posts_per_page'] = (int)$_GET['per_page'];
	}

	if ( ! empty( $args['pagination'] ) && get_query_var( 'page' ) ) {
		$query_args['paged'] = (int)get_query_var( 'page' );
	}

	if ( isset( $_GET['author'] ) ) {
		$query_args['author'] = (int)$_GET['author'];
	}

	if ( isset( $_GET['cat'] ) ) {
		$query_args['cat'] = (int)$_GET['cat'];
	}

	if ( isset( $_GET['tag'] ) ) {
		$query_args['tag'] = sanitize_title_for_query( $_GET['tag'] );
	}

	if ( isset( $_GET['tag_id'] ) ) {
		$query_args['tag_id'] = (int)$_GET['tag_id'];
	}

	return $query_args;
}

add_action( 'init', 'mm_vc_posts', 12 );
/**
 * Visual Composer component.
 *
 * We're firing a bit late because we want to come after all
 * custom post types and taxonomies have been registered.
 *
 * @since  1.0.0
 */
function mm_vc_posts() {

	// Only proceed if we're in the admin and Visual Composer is active.
	if ( ! is_admin() ) {
		return;
	}

	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}

	$query_types    = mm_get_query_types_for_vc( 'mm-posts' );
	$titles         = mm_get_post_titles_for_vc( 'mm-posts' );
	$post_types     = mm_get_post_types_for_vc( 'mm-posts' );
	$taxonomies     = mm_get_taxonomies_for_vc( 'mm-posts' );
	$heading_levels = mm_get_heading_levels_for_vc( 'mm-posts' );
	$image_sizes    = mm_get_image_sizes_for_vc( 'mm-posts' );
	$templates      = mm_get_mm_posts_templates_for_vc( 'mm-posts' );

	// Grab post type values with capital letters for description and title fields.
	$post_types_formatted = mm_get_post_types( 'mm-posts ');

	// Modify the array of post titles for better formatting.
	$last = array_slice( $post_types_formatted, -1 );
	$first = join( ', ', array_slice( $post_types_formatted, 0, -1 ) );
	$both = array_filter( array_merge( array( $first ), $last ), 'strlen');
	$formatted_titles = join(' or ', $both);
	$fomatted_plural_titles = str_replace( array( ',', ' or' ), array( 's,' , 's or' ), trim( $formatted_titles) ).'s';

	$title_heading = sprintf(
		__( 'Enter the Title(s) of Specific %s to Display', 'mm-components' ),
		esc_html( $fomatted_plural_titles )
	);

	$title_description = sprintf(
		__( 'Enter a specific %s to display', 'mm-components' ),
		esc_html( $formatted_titles )
	);

	vc_map( array(
		'name'              => __( 'Posts', 'mm-components' ),
		'base'              => 'mm_posts',
		'class'             => '',
		'icon'              => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category'          => __( 'Content', 'mm-components' ),
		'admin_enqueue_css' => MM_COMPONENTS_URL . '/css/post-titles-autocomplete.css',
		'params'            => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Selection Type', 'mm-components' ),
				'param_name'  => 'query_type',
				'description' => __( 'Select posts by title or select posts of a specific type, taxonomy or term', 'mm-components' ),
				'value'       => $query_types,
			),
			array(
				'type'        => 'autocomplete',
				'heading'     => $title_heading,
				'param_name'  => 'post_ids',
				'description' => $title_description,
				'settings'    => array(
					'values'  => $titles,
				),
				'dependency' => array(
					'element' => 'query_type',
					'value'   => array(
						'specific',
					)
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Post Type', 'mm-components' ),
				'param_name'  => 'post_type',
				'description' => __( 'Select a post type to display multiple posts', 'mm-components' ),
				'value'       => $post_types,
				'dependency' => array(
					'element' => 'query_type',
					'value'   => array(
						'collection',
					)
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Taxonomy', 'mm-components' ),
				'param_name'  => 'taxonomy',
				'description' => __( 'Select a taxonomy and term to only include posts that have the term', 'mm-components' ),
				'value'       => $taxonomies,
				'dependency' => array(
					'element' => 'query_type',
					'value'   => array(
						'collection',
					)
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Term', 'mm-components' ),
				'param_name'  => 'term',
				'description' => __( 'Specify a term in the selected taxonomy to only include posts that have the term', 'mm-components' ),
				'value'       => '',
				'dependency' => array(
					'element' => 'query_type',
					'value'   => array(
						'collection',
					)
				),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Use AJAX Filter?', 'mm-components' ),
				'description' => __( 'Allow AJAX post filtering accoring to taxonomy specified above', 'mm-components' ),
				'param_name' => 'ajax_filter',
				'value'      => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
				'dependency' => array(
					'element'   => 'query_type',
					'value'     => array(
						'collection',
					),
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'AJAX Filter Terms List Style', 'mm-components' ),
				'param_name' => 'filter_style',
				'value'      => array(
					__( 'Links', 'mm-components' )    => 'links',
					__( 'Dropdown', 'mm-components' ) => 'dropdown',
				),
				'dependency' => array(
					'element'   => 'ajax_filter',
					'not_empty' => true,
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Posts Per Page', 'mm-components' ),
				'param_name'  => 'per_page',
				'description' => __( 'Specify the maximum number of posts to show at once', 'mm-components' ),
				'value'       => '10',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Pagination', 'mm-components' ),
				'param_name' => 'pagination',
				'value'      => array(
					__( 'None', 'mm-components' )         => '',
					__( 'Next/Prev', 'mm-components' )    => 'next-prev',
					__( 'Page Numbers', 'mm-components' ) => 'page-numbers',
					__( 'AJAX Pagination', 'mm-components' ) => 'ajax-pagination',
				),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Show the Featured Image?', 'mm-components' ),
				'param_name' => 'show_featured_image',
				'value'      => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Featured Image Size', 'mm-components' ),
				'param_name' => 'featured_image_size',
				'value'      => $image_sizes,
				'dependency' => array(
					'element'   => 'show_featured_image',
					'not_empty' => true,
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Template', 'mm-components' ),
				'param_name'  => 'template',
				'description' => __( 'Select a custom template for custom output', 'mm-components' ),
				'value'       => $templates,
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Use Masonry?', 'mm-components' ),
				'param_name' => 'masonry',
				'value'      => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Heading Level', 'mm-components' ),
				'param_name'  => 'heading_level',
				'description' => __( 'Select the post title heading level', 'mm-components' ),
				'value'       => $heading_levels,
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Link Title?', 'mm-components' ),
				'param_name' => 'link_title',
				'std'        => 1,
				'value'      => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Show post info?', 'mm-components' ),
				'param_name'  => 'show_post_info',
				'description' => __( 'Default post info output includes post date and author.', 'mm-components' ),
				'value'       => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Show post meta?', 'mm-components' ),
				'param_name'  => 'show_post_meta',
				'description' => __( 'Default post meta output includes category and tag links.', 'mm-components' ),
				'value'       => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Use full post content?', 'mm-components' ),
				'param_name'  => 'use_post_content',
				'description' => __( 'By default the excerpt will be used. Check this to output the full post content.', 'mm-components' ),
				'value'       => array(
					__( 'Yes', 'mm-components' ) => 1,
				),
			),
		)
	) );

	// Because this component is registered on init we will call a custom action here
	// so that any templates adding extra params have a better hook to add them on.
	do_action( 'mm_posts_register_extra_vc_params' );
}

add_action( 'register_shortcode_ui', 'mm_components_mm_posts_shortcode_ui' );
/**
 * Register UI for Shortcake.
 *
 * @since  1.0.0
*/
function mm_components_mm_posts_shortcode_ui() {

	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	$post_types  = mm_get_post_types( 'mm-posts' );
	$image_sizes = mm_get_image_sizes( 'mm-posts' );
	$taxonomies  = mm_get_taxonomies( 'mm-posts' );
	$templates   = mm_get_mm_posts_templates( 'mm-posts' );

	shortcode_ui_register_for_shortcode(
		'mm_posts',
		array(
			'label'         => esc_html__( 'Mm Posts', 'mm-components' ),
			'listItemImage' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
			'attrs'         => array(
				array(
					'label'       => esc_html( 'Post ID', 'mm-components' ),
					'description' => esc_html__( 'Enter a post ID to display a single post', 'mm-components' ),
					'attr'        => 'post_id',
					'type'        => 'text',
				),
				array(
					'label'       => esc_html__( 'Post Type', 'mm-components' ),
					'description' => esc_html__( 'Select a post type to display multiple posts', 'mm-components' ),
					'attr'        => 'post_type',
					'type'        => 'select',
					'options'     => $post_types,
				),
				array(
					'label'       => esc_html__( 'Taxonomy', 'mm-components' ),
					'description' => esc_html__( 'Select a taxonomy and term to only include posts that have the term', 'mm-components' ),
					'attr'        => 'taxonomy',
					'type'        => 'select',
					'options'     => $taxonomies,
				),
				array(
					'label'       => esc_html__( 'Term', 'mm-components' ),
					'description' => esc_html__( 'Specify a term in the selected taxonomy to only include posts that have the term', 'mm-components' ),
					'attr'        => 'term',
					'type'        => 'text',
				),
				array(
					'label'       => esc_html__( 'Posts Per Page', 'mm-components' ),
					'description' => esc_html__( 'Specify the maximum number of posts to show at once', 'mm-components' ),
					'value'       => '10',
					'attr'        => 'per_page',
					'type'        => 'text',
				),
				array(
					'label'       => esc_html__( 'Pagination', 'mm-components' ),
					'attr'        => 'pagination',
					'type'        => 'select',
					'options'     => array(
						''             => esc_html__( 'None', 'mm-components' ),
						'next-prev'    => esc_html__( 'Next/Prev', 'mm-components' ),
						'page-numbers' => esc_html__( 'Page Numbers', 'mm-components' ),
					),
				),
				array(
					'label'       => esc_html__( 'Template', 'mm-components' ),
					'description' => esc_html__( 'Select a custom template for custom output', 'mm-components' ),
					'attr'        => 'template',
					'type'        => 'select',
					'options'     => $templates,
				),
				array(
					'label'       => esc_html__( 'Use Masonry?', 'mm-button' ),
					'attr'        => 'masonry',
					'type'        => 'checkbox',
				),
				array(
					'label'       => esc_html__( 'Show the Featured Image?', 'mm-button' ),
					'attr'        => 'show_featured_image',
					'type'        => 'checkbox',
				),
				array(
					'label'       => esc_html__( 'Featured Image Size', 'mm-components' ),
					'attr'        => 'featured_image_size',
					'type'        => 'select',
					'options'     => $image_sizes,
				),
				array(
					'label'       => esc_html__( 'Show post info?', 'mm-button' ),
					'description' => esc_html__( 'Default post info output includes post date and author.', 'mm-components' ),
					'attr'        => 'show_post_info',
					'type'        => 'checkbox',
				),
				array(
					'label'       => esc_html__( 'Show post meta?', 'mm-button' ),
					'description' => esc_html__( 'Default post meta output includes category and tag links.', 'mm-components' ),
					'attr'        => 'show_post_meta',
					'type'        => 'checkbox',
				),
				array(
					'label'       => esc_html__( 'Use full post content?', 'mm-button' ),
					'description' => esc_html__( 'By default the excerpt will be used. Check this to output the full post content.', 'mm-components' ),
					'attr'        => 'use_post_content',
					'type'        => 'checkbox',
				),
			),
		)
	);
}

