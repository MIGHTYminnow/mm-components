<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Demo
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_demo', 'mm_demo_shortcode' );
/**
 * Output Demo.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_demo_shortcode( $atts, $content = null, $tag ) {

	// Specify defaults here.
	$atts = mm_shortcode_atts( array(
		'text_field'        => '',
		'alpha_color_field' => '',
	), $atts );

	// Do any additional validation here.
	$text_field        = wp_kses_post( $atts['text_field'] );
	$alpha_color_field = esc_html( $atts['alpha_color_field'] );

	// Get Mm classes.
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">

		<?php // Output goes here... ?>

		<ul>
			<li><?php echo __( 'Text Field:', 'mm-components' ) . ' ' . $text_field; ?></li>
			<li><?php echo __( 'Alpha Color Field:', 'mm-components' ) . ' ' . $alpha_color_field; ?></li>
		</ul>

	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_demo' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_demo() {

	vc_map( array(
		'name' => __( 'Demo', 'mm-components' ),
		'base' => 'mm_demo',
		'class' => '',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Text Field', 'mm-components' ),
				'param_name' => 'text_field',
				'admin_label' => true,
			),
		)
	) );
}

add_action( 'widgets_init', 'mm_components_register_demo_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_demo_widget() {

	register_widget( 'mm_demo_widget' );
}

/**
 * Demo widget.
 *
 * @since  1.0.0
 */
class Mm_Demo_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-demo',
			'description' => __( 'A Demo', 'mm-components' ),
		);

		parent::__construct(
			'mm_demo_widget',
			__( 'Mm Demo', 'mm-components' ),
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
			'title'             => '',
			'text_field'        => '',
			'alpha_color_field' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// At this point all instance options have been sanitized.
		$title             = apply_filters( 'widget_title', $instance['title'] );
		$text_field        = $instance['text_field'];
		$alpha_color_field = $instance['alpha_color_field'];

		$shortcode = sprintf(
			'[mm_demo text_field="" alpha_color_field=""]',
			$text_field,
			$alpha_color_field
		);

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo do_shortcode( $shortcode );

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
			'title'             => '',
			'text_field'        => '',
			'alpha_color_field' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title             = $instance['title'];
		$text_field        = $instance['text_field'];
		$alpha_color_field = $instance['alpha_color_field'];
		$classname         = $this->options['classname'];

		// Text.
		$this->field_text(
			__( 'Text Field', 'mm-components' ),
			$classname . '-text widefat',
			'text_field',
			$text_field
		);

		// Alpha Color Picker.
		$this->field_alpha_color_picker(
			__( 'Alpha Color Field', 'mm-components' ),
			$classname . '-alpha-color-field',
			'alpha_color_field',
			$alpha_color_field,
			array(
				'rgba(255, 0, 0, 0.7)',
				'rgba(54, 0, 170, 0.8)',
				'#FFCC00',
				'rgba( 20, 20, 20, 0.8 )',
				'#00CC77',
			),
			'#00CC99',
			true
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

		$instance                      = $old_instance;
		$instance['title']             = wp_kses_post( $new_instance['title'] );
		$instance['text_field']        = sanitize_text_field( $new_instance['text_field'] );
		$instance['alpha_color_field'] = sanitize_text_field( $new_instance['alpha_color_field'] );

		return $instance;
	}
}