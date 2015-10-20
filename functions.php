<?php
/**
 * Mm Components General Functionality.
 *
 * @since 1.0.0
 *
 * @package mm-components
 */

add_filter( 'mm_shortcode_custom_classes', 'mm_shortcode_custom_classes', 10, 3 );
/**
 * Add custom shortcode classes.
 *
 * The following atts are parsed into classes by this function:
 *
 * 1. Atts whose key begins with: mm_class_*.
 * 2. The custom class att defined by $custom_class_key below.
 *
 * @since   1.0.0
 *
 * @param   string  $classes  Initial classes.
 * @param   string  $tag      Shortcode tag.
 * @param   array   $atts     Shortcoder atts.
 *
 * @return  string            Modified classes.
 */
function mm_shortcode_custom_classes( $classes, $tag, $atts ) {

	// Define attribute key identifiers.
	$custom_class_prefix = 'mm_class_';
	$new_custom_class_prefix = 'mm-';
	$custom_class_key = 'mm_custom_class';

	// Set up classes array.
	$class_array = explode( ' ', $classes );

	// Loop through each att and add class as needed.
	foreach ( $atts as $key => $value ) {

		// Add class in the following format: $key-$class
		// Exclude custom class att as this needs to be unprefixed.
		if ( false !== strpos( $key, $custom_class_prefix ) && $value ) {

			// Replace custom class prefix with simpler 'mm-' prefix and format appropriately.
			$key = str_replace( $custom_class_prefix, $new_custom_class_prefix, $key );
			$key = str_replace( '_', '-', $key );
			$class_array[] = "{$key}-{$value}";
		}
	}

	// Add mm_custom_class att as unprefixed class.
	if ( ! empty ( $atts[ $custom_class_key ] ) ) {
		$class_array[] = $atts[ $custom_class_key ];
	}

	// Add custom classes to existing classes.
	$classes = implode( ' ', $class_array );

	return $classes;
}

/**
 * Parse args with defaults, allowing for unexpected args.
 *
 * @since 1.0.0
 *
 * @param array $defaults Default values.
 * @param array $atts     Atts to be parsed.
 *
 * @return array Updated atts
 */
function mm_shortcode_atts( $defaults = array(), $atts = array() ) {
	return wp_parse_args( $atts, $defaults );
}

/**
 * Return custom VC image upload description.
 *
 * @since     1.0.0
 *
 * @param     string    $image_size    Image size slug.
 *
 * @return    string    Image upload description.
 */
function mm_custom_image_field_description( $image_size = '' ) {

	$default_message = __( 'Upload an image that is large enough to be output without stretching.', 'mm-components' );

	// Do default image message if no specific image_size is passed.
	if ( ! $image_size ) {
		return $default_message;
	}

	// Get dimensions of image.
	$image_dimensions = mm_get_image_size_dimensions( $image_size );

	// Do default message if the specified image size doesn't exists.
	if ( ! $image_dimensions ) {
		return $default_message;
	}

	$width = $image_dimensions['width'] * 2;
	$height = $image_dimensions['height'] * 2;

	return sprintf( __( 'Upload an image that is at least <b>%dpx</b> × <b>%dpx</b> to ensure that it is not stretched.', 'mm-components' ), $width, $height );
}

/**
 * Get the dimensions of WP default and add-on image sizes.
 *
 * @since     1.0.0
 *
 * @param     string    $image_size          Image size slug.
 *
 * @return    array     $image_dimensions    Array of image width/height.
 */
function mm_get_image_size_dimensions( $image_size = '' ) {

	global $_wp_additional_image_sizes;

	if ( in_array( $image_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

		$image_dimensions['width'] = get_option( $image_size . '_size_w' );
		$image_dimensions['height'] = get_option( $image_size . '_size_h' );

	} elseif ( isset( $_wp_additional_image_sizes[ $image_size ] ) ) {

		$image_dimensions = array(
			'width' => $_wp_additional_image_sizes[ $image_size ]['width'],
			'height' => $_wp_additional_image_sizes[ $image_size ]['height'],
		);

	} else {
		return false;
	}

	return $image_dimensions;
}

/**
 * Possibly wrap content in a link.
 *
 * @since 1.0.0
 *
 * @param mixed $content Content to go in link.
 * @param array  $link_array Array of link data: url|title|target
 *
 * @return string HTML output.
 */
function mm_maybe_wrap_in_link( $content, $link_array = array() ) {

	if ( empty( $link_array['url'] ) ) {
		return $content;
	}

	return sprintf( '<a href="%s" title="%s" target="%s">%s</a>',
		$link_array['url'],
		$link_array['title'],
		$link_array['target'],
		$content
	);
}

/**
 * Return true or false based on the passed in value.
 *
 * @since  1.0.0
 *
 * @param   mixed  $value  The value to be tested.
 * @return  bool
 */
function mm_true_or_false( $value ) {

	if ( ! isset( $value ) ) {
		return false;
	}

	if ( true === $value || 'true' === $value || 1 === $value || '1' === $value || 'yes' === $value || 'on' === $value ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Utility function to check if a user has a specific role.
 *
 * @since  1.0.0
 *
 * @param  string  $role     The role we want to check.
 * @param  int     $user_id  The current user's ID.
 */
function mm_check_user_role( $role, $user_id = null ) {

    if ( is_numeric( $user_id ) ) {
        $user = get_userdata( $user_id );
    } else {
        $user = wp_get_current_user();
    }

    if ( empty( $user ) ) {
        return false;
    }

    return in_array( $role, (array)$user->roles );
}

/**
 * Return an array of all public post types.
 *
 * @since   1.0.0
 *
 * @return  array  The array of formatted post types.
 */
function mm_get_post_types() {

	$post_type_args = array(
		'public' => true,
		'_builtin' => false
	);

	$custom_post_types = get_post_types( $post_type_args, 'objects', 'and' );

	$formatted_cpts = array();

	foreach( $custom_post_types as $post_type ) {

		$formatted_cpts[ $post_type->name ] = $post_type->labels->singular_name;
	}

	// Manually add 'post' and an empty option.
	$default_post_types = array(
		'post' => __( 'Post', 'mm-components' ),
	);

	$post_types = array_merge( $default_post_types, $formatted_cpts );

	return $post_types;
}

/**
 * Return an array of post types for use in a Visual Composer dropdown param.
 *
 * @since   1.0.0
 *
 * @return  array  The array of formatted post types.
 */
function mm_get_post_types_for_vc() {

	$post_type_args = array(
		'public' => true,
		'_builtin' => false
	);

	$custom_post_types = get_post_types( $post_type_args, 'objects', 'and' );

	$formatted_cpts = array();

	foreach( $custom_post_types as $post_type ) {

		$formatted_cpts[ $post_type->labels->singular_name ] = $post_type->name;
	}

	// Manually add 'post' and an empty option.
	$default_post_types = array(
		__( 'Select a Post Type', 'mm-components' ) => '',
		__( 'Post', 'mm-components' ) => 'post',
	);

	$post_types = array_merge( $default_post_types, $formatted_cpts );

	return $post_types;
}

/**
 * Return an array of registered taxonomies.
 *
 * @since   1.0.0
 *
 * @return  array  The array of formatted taxonomies.
 */
function mm_get_taxonomies() {

	$taxonomy_args = array(
		'public'   => true,
		'_builtin' => false
	);

	$custom_taxonomies = get_taxonomies( $taxonomy_args, 'objects', 'and' );

	// Manually add 'category' and 'tag'.
	$taxonomies = array(
		'category' => __( 'Category', 'mm-components' ),
		'post_tag' => __( 'Tag', 'mm-components' ),
	);

	// Format the taxonomies.
	foreach ( $custom_taxonomies as $taxonomy ) {

		$taxonomies[ $taxonomy->name ] = $taxonomy->labels->singular_name;
	}

	return $taxonomies;
}

/**
 * Return an array of registered taxonomies for use in a Visual Composer dropdown param.
 *
 * @since   1.0.0
 *
 * @return  array  The array of formatted taxonomies.
 */
function mm_get_taxonomies_for_vc() {

	$taxonomy_args = array(
		'public'   => true,
		'_builtin' => false
	);

	$custom_taxonomies = get_taxonomies( $taxonomy_args, 'objects', 'and' );

	// Manually add 'category', 'tag', and an empty option.
	$taxonomies = array(
		__( 'Select a Taxonomy', 'mm-components' ) => '',
		__( 'Category', 'mm-components' ) => 'category',
		__( 'Tag', 'mm-components' ) => 'post_tag',
	);

	// Format the taxonomies.
	foreach ( $custom_taxonomies as $taxonomy ) {

		$taxonomies[ $taxonomy->labels->singular_name ] = $taxonomy->name;
	}

	return $taxonomies;
}

/**
 * Return an array of registered image sizes.
 *
 * @since   1.0.0
 *
 * @return  array  The array of formatted image sizes.
 */
function mm_get_image_sizes() {

	$image_sizes = get_intermediate_image_sizes();
	$formatted_image_sizes = array();

	foreach ( $image_sizes as $image_size ) {

		$formatted_image_size = ucwords( str_replace( '_', ' ', str_replace( '-', ' ', $image_size ) ) );
		$formatted_image_sizes[ $image_size ] = $formatted_image_size;
	}

	// Manually add in the 'Full' size.
	$formatted_image_sizes['full'] = __( 'Full', 'mm-components' );

	return $formatted_image_sizes;
}

/**
 * Return an array of registered image sizes for use in a Visual Composer dropdown param.
 *
 * @since   1.0.0
 *
 * @return  array  The array of formatted image sizes.
 */
function mm_get_image_sizes_for_vc() {

	$image_sizes = get_intermediate_image_sizes();

	// Add the empty first option.
	$formatted_image_sizes = array(
		__( 'Default', 'mm-components' ) => '',
	);

	foreach ( $image_sizes as $image_size ) {

		$formatted_image_size = ucwords( str_replace( '_', ' ', str_replace( '-', ' ', $image_size ) ) );
		$formatted_image_sizes[ $formatted_image_size ] = $image_size;
	}

	// Manually add in the 'Full' size.
	$full_size = __( 'Full', 'mm-components' );
	$formatted_image_sizes[ $full_size ] = 'full';

	return $formatted_image_sizes;
}

/**
 * Return an array of Mm Posts templates for use in a Visual Composer dropdown param.
 *
 * @since   1.0.0
 *
 * @return  array  The array of template names.
 */
function mm_get_mm_posts_templates_for_vc() {

	$templates = array(
		__( 'Select a template', 'mm-components' ) => '',
	);

	// All core and custom templates should be registered using this filter.
	$templates = apply_filters( 'mm_posts_templates', $templates );

	return $templates;
}

/**
 * Return an array of registered user roles for use in a Visual Composer checkbox param.
 *
 * @since   1.0.0
 *
 * @return  array  The array of user roles.
 */
function mm_get_user_roles_for_vc() {

	global $wp_roles;

	$user_roles = array();

	foreach ( $wp_roles->roles as $role => $role_params ) {

		$role_name = ( isset( $role_params['name'] ) ) ? $role_params['name'] : $role;

		$user_roles[ $role_name ] = $role;
	}

	return $user_roles;
}
