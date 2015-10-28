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
 * Restricted Content shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_restricted_content_shortcode( $atts = array(), $content = null, $tag = '' ) {

	$atts = mm_shortcode_atts( array(
		'allowed_roles'   => '',
		'invalid_message' => '',
	), $atts );

	$roles = ( strpos( $atts['allowed_roles'], ',' ) ) ? explode( ',', $atts['allowed_roles'] ) : (array)$atts['allowed_roles'];
	$valid_user = false;
	$invalid = $atts['invalid_message'];

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $tag, $atts );

	foreach ( $roles as $role ) {
		if ( mm_check_user_role( $role ) ) {
			$valid_user = true;
			break;
		}
	}

	if ( $valid_user ) {

		if( validBase64( $content ) ) {
			$inner_output = rawurldecode( base64_decode( $content ) );
		} else {
			$inner_output = $content;
		}

 		$mm_classes .= ' valid-user';

	} else {

		if( validBase64( $invalid ) ) {
			$inner_output = rawurldecode( base64_decode( $invalid ) );
		} else {
			$inner_output = $invalid;
		}

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

function mm_restricted_content( $args ) {

	$component  = 'mm-restricted-content';

	// Set our defaults and use them as needed.
	$defaults = array(
		'title'            => '',
		'roles'            => '',
		'invalid_message'  => '',
		'content'          => '',
	);
	$args = wp_parse_args( (array)$args, $defaults);

	// Get clean param values.
	$title   = (string)$args['title'];
	$roles   = (strpos($args['roles'], ',' ) ) ? explode( ',', $args['roles'] ) : (array)$args['roles'];
	$invalid = (string)$args['invalid_message'];
	$content = (string)$args['content'];

	if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
		$content = wpb_js_remove_wpautop( $content, true );
	}

	$valid_user = false;

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	foreach ( $roles as $role ) {
		if ( mm_check_user_role( $role ) ) {
			$valid_user = true;
			break;
		}
	}

	if ( $valid_user ) {

		if( validBase64( $content ) ) {
			$inner_output = rawurldecode( base64_decode( $content ) );
		} else {
			$inner_output = $content;
		}

 		$mm_classes .= ' valid-user';

	} else {

		if( validBase64( $invalid ) ) {
			$inner_output = rawurldecode( base64_decode( $invalid ) );
		} else {
			$inner_output = $invalid;
		}

		$mm_classes .= ' invalid-user';
	}

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">
		<div class="mm-restricted-content-inner">
			<?php echo do_shortcode( $inner_output ); ?>
		</div>
	</div>

	<?php

	return ob_get_clean();

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
				'param_name'  => 'roles',
				'description' => __( 'Which user role should be allowed to view this content?', 'mm-components' ),
				'value'       => $roles,
			),
			array(
				'type'        => 'textarea_raw_html',
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

add_action( 'widgets_init', 'mm_components_register_restricted_content_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_restricted_content_widget() {

	register_widget( 'mm_restricted_content_widget' );
}

/**
 * Restricted Content widget.
 *
 * @since  1.0.0
 */
class Mm_Restricted_Content_Widget extends Mm_Components_Widget {

	/**
	 * Global options for this widget.
	 *
	 * @since  1.0.0
	 */
	protected $options;

	/**
	 * Initialize an instance of the widget.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Set up the options to pass to the WP_Widget constructor.
		$this->options = array(
			'classname'   => 'mm-restricted-content',
			'description' => __( 'A Restricted Content Container', 'mm-components' ),
		);

		parent::__construct(
			'mm_restricted_content_widget',
			__( 'Mm Restricted Content', 'mm-components' ),
			$this->options
		);
	}

	/**
	 * Output the widget.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $args      The global options for the widget.
	 * @param  array  $instance  The options for the widget instance.
	 */
	public function widget( $args, $instance ) {

		$defaults = array(
			'title'            => '',
			'roles'            => '',
			'content'          => '',
			'invalid_message'  => '',
		);

		// At this point all instance options have been sanitized.
		$title           = apply_filters( 'widget_title', $instance['title'] );
		$roles           = $instance['roles'];
		$content         = $instance['content'];
		$invalid_message = $instance['invalid_message'];

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo mm_restricted_content( $instance );

		echo $args['after_widget'];

	}

	/**
	 * Output the Widget settings form.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $instance  The options for the widget instance.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'            => '',
			'roles'            => '',
			'content'          => '',
			'invalid_message'  => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title             = $instance['title'];
		$roles             = isset( $instance['roles'] ) ? $instance['roles'] : array();
		$content           = $instance['content'];
		$invalid_message   = $instance['invalid_message'];
		$classname         = $this->options['classname'];

		// Allowed User Roles.
		$this->field_multi_checkbox(
			__( 'Allowed User Roles', 'mm-components' ),
			$classname . '-roles widefat',
			'roles',
			$roles,
			mm_get_user_roles()
		);

		error_log( print_r( $roles , true ) );

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			$classname . '-title widefat',
			'title',
			$title
		);

		// Content Container.
		$this->field_textarea(
			__( 'Insert Shortcode Content', 'mm-components' ),
			$classname . '-content widefat',
			'content',
			$content
		);

		// Invalid User Message.
		$this->field_textarea(
			__( 'Invalid User Message', 'mm-components' ),
			$classname . '-invalid-message widefat',
			'invalid_message',
			$invalid_message
		);
	}

	/**
	 * Update the widget settings.
	 *
	 * @since  1.0.0
	 *
	 * @param   array  $new_instance  The new settings for the widget instance.
	 * @param   array  $old_instance  The old settings for the widget instance.
	 *
	 * @return  array  The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title']            = wp_kses_post( $new_instance['title'] );
		$instance['roles']            = sanitize_text_field( $new_instance['roles'] );
		$instance['content']          = wp_kses_post($new_instance['content'] );
		$instance['invalid_message']  = sanitize_text_field( $new_instance['invalid_message'] );

		return $instance;
	}
}