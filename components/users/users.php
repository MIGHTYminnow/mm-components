<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Users
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Users component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_users( $args ) {

	$component  = 'mm-users';

	// Set our defaults and use them as needed.
	$defaults = array(
		'user_id'      => '',
		'role'         => '',
		'number'       => 10,
		'template'     => '',
		'wrap_element' => 'article',
	);
	$args = apply_filters( 'mm_users_args', wp_parse_args( (array)$args, $defaults ) );

	// Get clean param values.
	$user_id  = (int)$args['user_id'];
	$role     = sanitize_text_field( $args['role'] );
	$number   = (int)$args['number'];
	$template = $args['template'];
	$element  = $args['wrap_element'];

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Maybe add template class.
	if ( $template ) {
		$mm_classes = "$mm_classes $template";
	}

	// Set up the context we're in.
	global $post;

	// Set up a generic query.
	$query_args = array(
		'number' => $number,
	);

	// Add to our query if additional params have been passed.
	if ( $user_id ) {
		$query_args['include'] = array(
			$user_id,
		);
	}

	// Only query a specific role if one has been passed.
	if ( $role ) {
		$query_args['role'] = $role;
	}

	// Allow the query to be filtered.
	$query_args = apply_filters( 'mm_users_query_args', $query_args, $args );

	// Do the query.
	$query = new WP_User_Query( $query_args );

	// Grab any users returned by the query.
	$users = $query->get_results();

	// Store the global post object as the context we'll pass to our hooks.
	$context = $post;

	do_action( 'mm_users_register_hooks', $context, $args );

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">

		<?php do_action( 'mm_users_before', $query, $context, $args ); ?>

		<?php if ( ! empty( $users ) ) : ?>

			<?php foreach ( $users as $user ) : ?>

				<?php printf(
					'<%s id="user-%s">',
					$element,
					$user->ID
				); ?>

					<?php do_action( 'mm_users_header', $user, $context, $args ); ?>

					<?php do_action( 'mm_users_content', $user, $context, $args ); ?>

					<?php do_action( 'mm_users_footer', $user, $context, $args ); ?>

				<?php printf(
					'</%s>',
					$element
				); ?>

			<?php endforeach; ?>

		<?php endif; ?>

		<?php do_action( 'mm_users_after', $query, $context, $args ); ?>

	</div>

	<?php

	do_action( 'mm_users_reset_hooks' );

	return ob_get_clean();
}

add_shortcode( 'mm_users', 'mm_users_shortcode' );
/**
 * Users shortcode.
 *
 * @since   1.0.0
 *
 * @param   array   $atts  Shortcode attributes.
 *
 * @return  string         Shortcode output.
 */
function mm_users_shortcode( $atts = array() ) {

	return mm_users( $atts );
}

add_action( 'mm_users_register_hooks', 'mm_users_register_default_hooks', 9, 2 );
/**
 * Set up our default hooks.
 *
 * @since  1.0.0
 *
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_users_register_default_hooks( $context, $args ) {

	add_action( 'mm_users_header', 'mm_users_output_username', 10, 3 );
}

add_action( 'mm_users_reset_hooks', 'mm_users_reset_default_hooks' );
/**
 * Reset all the hooks.
 *
 * @since  1.0.0
 */
function mm_users_reset_default_hooks() {

	remove_all_actions( 'mm_users_header' );
}

/**
 * Default username output.
 *
 * @since  1.0.0
 *
 * @param  object  $user     The current user object.
 * @param  object  $context  The global post object.
 * @param  array   $args     The instance args.
 */
function mm_users_output_username( $user, $context, $args ) {

	$first_name = mm_users_get_first_name( $user->ID );
	$last_name = mm_users_get_last_name( $user->ID );

	if ( $first_name && $last_name ) {
		$name = $first_name . ' ' . $last_name;
	} else {
		$name = $user->data->display_name;
	}

	printf(
		'<span class="mm-users-username">%s</span>',
		esc_html( $name )
	);
}

/**
 * Return a user's first name.
 *
 * @since  1.0.0
 *
 * @param  int  $user_id  The user ID.
 *
 * @return  string        The user's first name.
 */
function mm_users_get_first_name( $user_id = 0 ) {

	if ( 0 === $user_id ) {
		return '';
	}

	return get_user_meta( (int)$user_id, 'first_name', true );
}

/**
 * Return a user's last name.
 *
 * @since  1.0.0
 *
 * @param  int  $user_id  The user ID.
 *
 * @return  string        The user's first name.
 */
function mm_users_get_last_name( $user_id = 0 ) {

	if ( 0 === $user_id ) {
		return '';
	}

	return get_user_meta( (int)$user_id, 'last_name', true );
}

add_filter( 'mm_users_query_args', 'mm_users_filter_from_query_args', 10, 2 );
/**
 * Use specific query args present in the URL to alter the mm_users query.
 *
 * @since   1.0.0
 *
 * @param   array  $query_args  The original query args.
 * @param   array  $args        The instance args.
 *
 * @return  array  $query_args  The updated query args.
 */
function mm_users_filter_from_query_args( $query_args, $args ) {

	if ( isset( $_GET['role'] ) ) {
		$query_args['role'] = sanitize_text_field( $_GET['role'] );
	}

	return $query_args;
}

add_action( 'vc_before_init', 'mm_vc_users' );
/**
 * Visual Composer component.
 *
 * @since  1.0.0
 */
function mm_vc_users() {

	$roles = mm_get_user_roles_for_vc();
	$templates = mm_get_mm_users_templates_for_vc();
	$wrap_elements = mm_get_wrap_elements_for_vc();

	$empty_option = array(
		__( 'All Users', 'mm-components' ) => '',
	);

	$roles = $empty_option + $roles;

	vc_map( array(
		'name'     => __( 'Users', 'mm-components' ),
		'base'     => 'mm_users',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'User Role', 'mm-components' ),
				'param_name'  => 'role',
				'description' => __( 'Select a specific user role to only include users with that role', 'mm-components' ),
				'value'       => $roles,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Template', 'mm-components' ),
				'param_name'  => 'template',
				'description' => __( 'Select a template', 'mm-components' ),
				'value'       => $templates,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Wrap Element', 'mm-components' ),
				'param_name'  => 'wrap_element',
				'description' => __( 'Select a wrap element', 'mm-components' ),
				'value'       => $wrap_elements,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'User ID', 'mm-components' ),
				'param_name'  => 'user_id',
				'description' => __( 'Enter a user ID to display a single user', 'mm-components' ),
				'value'       => '',
			),
		)
	) );

}
