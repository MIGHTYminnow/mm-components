<?php
/**
 * Image Grid template for Mm Posts.
 *
 * @since  1.0.0
 */

add_filter( 'mm_posts_templates', 'mm_posts_image_grid_template', 0 );
/**
 * Register this template with Mm Posts.
 */
function mm_posts_image_grid_template( $templates ) {

	$templates['image-grid'] = __( 'Image Grid', 'mm-components' );

	return $templates;
}

add_action( 'mm_posts_register_hooks', 'mm_posts_image_grid_hooks', 10, 2 );
/**
 * Modify the default hooks.
 */
function mm_posts_image_grid_hooks( $context, $args ) {

	// Only affect the output if this template is being used.
	if ( 'image-grid' != $args['template'] ) {
		return;
	}

	// Turn off all default output.
	remove_action( 'mm_posts_header', 'mm_posts_output_post_header', 10 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_image', 8 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_content', 10 );
	remove_action( 'mm_posts_footer', 'mm_posts_output_post_meta', 10 );

	// Include our custom output.
	add_action( 'mm_posts_content', 'mm_posts_output_post_image', 8, 3 );
	add_action( 'mm_posts_content', 'mm_posts_output_post_title', 10, 3 );
}

add_filter( 'mm_components_custom_classes', 'mm_posts_image_grid_custom_classes', 11, 3 );
/**
 * Add custom template param classes for this template.
 *
 * @param   string  $classes    The component classes.
 * @param   string  $component  The component name.
 * @param   array   $args       The component args.
 *
 * @return  string              The new component classes.
 */
function mm_posts_image_grid_custom_classes( $classes, $component, $args ) {

	// Only affect Mm Posts.
	if ( 'mm-posts' != $component ) {
		return $classes;
	}

	if ( isset( $args['image_grid_titles_inside'] ) && 1 == $args['image_grid_titles_inside'] ) {
		$classes .= ' titles-inside';
	}

	if ( isset( $args['image_grid_no_gutter'] ) && 1 == $args['image_grid_no_gutter'] ) {
		$classes .= ' no-gutter';
	}

	return $classes;
}

add_action( 'mm_posts_register_extra_vc_params', 'mm_posts_image_grid_extra_vc_params' );
/**
 * Add extra params for this template to VC element.
 */
function mm_posts_image_grid_extra_vc_params() {

	$image_grid_params = array();

	$image_grid_params[] = array(
		'type'        => 'checkbox',
		'heading'     => __( 'Titles Inside Images?', 'mm-components' ),
		'param_name'  => 'image_grid_titles_inside',
		'description' => __( 'Image Grid Option: This will position the post titles inside the images.', 'mm-components' ),
		'value'       => array(
			__( 'Yes', 'mm-components' ) => 1,
		),
		'dependency' => array(
			'element' => 'template',
			'value'   => 'image-grid',
		),
	);

	$image_grid_params[] = array(
		'type'        => 'checkbox',
		'heading'     => __( 'No Gutter Mode?', 'mm-components' ),
		'param_name'  => 'image_grid_no_gutter',
		'description' => __( 'Image Grid Option: This will remove the padding between the images.', 'mm-components' ),
		'value'       => array(
			__( 'Yes', 'mm-components' ) => 1,
		),
		'dependency'  => array(
			'element' => 'template',
			'value'   => 'image-grid',
		),
	);

	vc_add_params( 'mm_posts', $image_grid_params );
}