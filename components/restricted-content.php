<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Restricted Content
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_restricted_content', 'mm_restricted_content_shortcode' );
/**
 * Output Restricted Content.
 *
 * @since  1.0.0
 *
 * @param   array    $atts     Shortcode attributes.
 * @param   string   $content  Shortcode content.
 * @param   string   $tag      Shortcode tag.
 *
 * @return  string             Shortcode output.
 */
function mm_restricted_content_shortcode( $atts = array(), $content = null, $tag = '' ) {

	$atts = mm_shortcode_atts( array(
		'allowed_roles'     => '',
		'invalid_message'      => '',
	), $atts );

	$roles = ( strpos( $atts['allowed_roles'], ',' ) ) ? explode( ',', $atts['allowed_roles'] ) : (array)$atts['allowed_roles'];
	$valid_user = false;
	$invalid = $atts['invalid_message'];

	// Get Mm classes.
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	foreach ( $roles as $role ) {
		if ( mm_check_user_role_for_vc( $role ) ) {
			$valid_user = true;
			break;
		}
	}

	if ( $valid_user ) {

		$inner_output = do_shortcode( $content );
		$mm_classes .= ' valid-user';

	} else {

		$inner_output = do_shortcode( $invalid );
		$mm_classes .= ' invalid-user';
	}

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">
		<div class="mm-restricted-content-inner">
			<?php echo $inner_output; ?>
		</div>
	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_restricted_content' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_restricted_content() {

	$roles = mm_get_user_roles_for_vc();

	/**
	 * Restricted Content.
	 */
	vc_map( array(
		'name'         => __( 'Restricted Content', 'mm-components' ),
		'base'         => 'mm_restricted_content',
		'icon'         => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'as_parent'    => array( 'except' => '' ),
		'is_container' => true,
		'params' => array(
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Allowed User Roles', 'mm-components' ),
				'param_name'  => 'allowed_roles',
				'description' => __( 'Which user role should be allowed to view this content?', 'mm-components' ),
				'value'       => $roles,
			),
			array(
				'type'        => 'textarea',
				'heading'     => __( 'Invalid User Message', 'mm-components' ),
				'param_name'  => 'invalid_message',
				'description' => __( 'This message will be shown to users who do not have the specified role.', 'mm-components' ),
				'value'       => '',
			),
		),
		'js_view' => 'VcColumnView'
	) );

}

// This is necessary to make any element that wraps other elements work.
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_MM_Restricted_Content extends WPBakeryShortCodesContainer {
    }
}