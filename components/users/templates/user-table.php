<?php
/**
 * User Table template for Mm Users.
 *
 * @since  1.0.0
 */

add_filter( 'mm_users_templates', 'mm_users_user_table_template', 0 );
/**
 * Register this template with Mm Users.
 */
function mm_users_user_table_template( $templates ) {

	$templates['user-table'] = __( 'User Table', 'mm-components' );

	return $templates;
}

add_action( 'mm_users_register_hooks', 'mm_users_user_table_hooks', 10, 2 );
/**
 * Modify the default hooks.
 */
function mm_users_user_table_hooks( $context, $args ) {

	// Only affect the output if this template is being used.
	if ( 'user-table' != $args['template'] ) {
		return;
	}

	// Turn off all default output.
	remove_action( 'mm_users_header', 'mm_users_output_username', 10 );

	// Hook custom output.
	add_action( 'mm_users_before', 'mm_users_user_table_output_table_open', 95 );

	add_action( 'mm_users_before', 'mm_output_thead_element_open', 96 );
	add_action( 'mm_users_before', 'mm_users_user_table_output_thead_row', 97, 3 );
	add_action( 'mm_users_before', 'mm_output_thead_element_close', 98 );

	add_action( 'mm_users_before', 'mm_output_tbody_element_open', 99 );
	add_action( 'mm_users_content', 'mm_users_user_table_output_user_row', 10, 3 );
	add_action( 'mm_users_after', 'mm_output_tbody_element_close', -98 );

	add_action( 'mm_users_after', 'mm_users_user_table_output_table_close', -99 );
}

add_filter( 'mm_users_args', 'mm_users_user_table_args', 10, 1 );
/**
 * Use custom args for this template.
 */
function mm_users_user_table_args( $args ) {

	// Only affect the output if this template is being used.
	if ( 'user-table' != $args['template'] ) {
		return $args;
	}

	$args['wrap_element'] = 'tr';

	return $args;
}

/**
 * Output our <table> open.
 */
function mm_users_user_table_output_table_open() {

	printf(
		'<table class="%s">',
		'mm-users-user-table'
	);
}

/**
 * Output thead row.
 */
function mm_users_user_table_output_thead_row( $query, $context, $args ) {

	ob_start(); ?>

	<tr>
		<th>
			<?php _e( 'User', 'mm-components' ); ?>
		</th>
	</tr>

	<?php

	echo apply_filters( 'mm_users_user_table_thead_row', ob_get_clean(), $query, $context, $args );
}

/**
 * Output our <table> close.
 */
function mm_users_user_table_output_table_close() {

	echo '</table>';
}

/**
 * Output a table user row.
 */
function mm_users_user_table_output_user_row( $user, $context, $args ) {

	ob_start(); ?>

	<td>
		<?php mm_users_output_username( $user, $context, $args ); ?>
	</td>

	<?php

	echo apply_filters( 'mm_users_user_table_user_row', ob_get_clean(), $user, $context, $args );
}
