<?php
/**
 * Simple List template for Mm Posts.
 *
 * @since  1.0.0
 */

add_filter( 'mm_posts_templates', 'mm_posts_simple_list_template', 0 );
/**
 * Register this template with Mm Posts.
 */
function mm_posts_simple_list_template( $templates ) {

	$template_name = __( 'Simple List', 'mm-components' );

	$templates[ $template_name ] = 'simple-list';

	return $templates;
}

add_action( 'mm_posts_register_hooks', 'mm_posts_simple_list_hooks', 10, 2 );
/**
 * Modify the default hooks.
 */
function mm_posts_simple_list_hooks( $context, $atts ) {

	// Only affect the output if this template is being used.
	if ( 'simple-list' != $atts['template'] ) {
		return;
	}

	// Turn off all default output.
	remove_action( 'mm_posts_header', 'mm_posts_output_post_header', 10 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_image', 8 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_content', 10 );
	remove_action( 'mm_posts_footer', 'mm_posts_output_post_meta', 10 );

	// Include our custom output.
	add_action( 'mm_posts_content', 'mm_posts_output_post_title', 10, 3 );
	add_action( 'mm_posts_content', 'mm_posts_simple_list_info', 11, 3 );
}

/**
 * Maybe output the post info.
 */
function mm_posts_simple_list_info( $post, $context, $atts ) {

	if ( 1 != (int)$atts['show_post_info'] ) {
		return;
	}

	echo '<span class="entry-info-wrap"><span class="entry-info">— ';

	$format = get_option( 'date_format' );
	$time   = get_the_date( $format );

	printf(
		'<time class="%s" itemprop="datePublished">%s</time>',
		'entry-time',
		$time
	);

	echo '</span></span>';
}