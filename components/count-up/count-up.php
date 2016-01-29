<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Count Up
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Count Up component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_count_up( $args ) {

	$component  = 'mm-count-up';

	$defaults = array(
		'before_text' => '',
		'after_text'  => '',
		'number'      => '',
		'duration'    => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	$before_text = $args['before_text'];
	$after_text  = $args['after_text'];
	$number      = $args['number'];
	$duration    = (int)$args['duration'];

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>" data-number="<?php echo esc_attr( $number ); ?>" data-duration="<?php echo esc_attr( $duration ); ?>">
		<div class="mm-count-up-inner-wrap">
			<span class="mm-count-up-before-wrap"><?php echo esc_html( $before_text ); ?></span>
			<span class="mm-count-up-number-wrap"></span>
			<span class="mm-count-up-after-wrap"><?php echo esc_html( $after_text ); ?></span>
		</div>
	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_count_up', 'mm_count_up_shortcode' );
/**
 * Count Up shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_count_up_shortcode( $atts ) {

	return mm_count_up( $atts );
}

add_action( 'vc_before_init', 'mm_vc_count_up' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_count_up() {

	vc_map( array(
		'name'     => __( 'Count Up', 'mm-components' ),
		'base'     => 'mm_count_up',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Before Text', 'mm-components' ),
				'param_name'  => 'before_text',
				'admin_label' => false,
				'value'       => '',
				'description' => __( 'Enter any text that should appear before the number. Example: $', 'mm-components' )
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'After Text', 'mm-components' ),
				'param_name'  => 'after_text',
				'admin_label' => false,
				'value'       => '',
				'description' => __( 'Enter any text that should appear after the number. Example: %', 'mm-components' )
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Number', 'mm-components' ),
				'param_name'  => 'number',
				'admin_label' => false,
				'value'       => '',
				'description' => __( 'Enter the number that will be counted up to.', 'mm-components' )
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Duration', 'mm-components' ),
				'param_name'  => 'duration',
				'admin_label' => false,
				'value'       => '',
				'description' => __( 'Enter the duration it should take for the counting to complete in seconds. Example: 2', 'mm-components' )
			),
		)
	) );
}

add_action( 'widgets_init', 'mm_components_register_count_up_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
*/
function mm_components_register_count_up_widget() {

	register_widget( 'mm_count_up_widget' );
}

/**
 * Count Up widget.
 *
 * @since  1.0.0
 */
class Mm_Count_Up_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-count-up',
			'description' => __( 'An Animated Count Up', 'mm-components' ),
		);

		parent::__construct(
			'mm_count_up_widget',
			__( 'Mm Count Up', 'mm-components' ),
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
			'title'       => '',
			'before_text' => '',
			'after_text'  => '',
			'number'      => '',
			'duration'    => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// Grab the title and run it through the right filter.
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo mm_count_up( $instance );

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
			'title'       => '',
			'before_text' => '',
			'after_text'  => '',
			'number'      => '',
			'duration'    => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title       = $instance['title'];
		$before_text = $instance['before_text'];
		$after_text  = $instance['after_text'];
		$number      = $instance['number'];
		$duration    = $instance['duration'];
		$classname   = $this->options['classname'];

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			'',
			$classname . '-title widefat',
			'title',
			$title
		);

		// Before Text.
		$this->field_text(
			__( 'Before Text', 'mm-components' ),
			__( 'Enter any text that should appear before the number. Example: $', 'mm-components' ),
			$classname . '-before-text widefat',
			'before_text',
			$before_text
		);

		// After Text.
		$this->field_text(
			__( 'After Text', 'mm-components' ),
			__( 'Enter any text that should appear after the number. Example: %', 'mm-components' ),
			$classname . '-after-text widefat',
			'after_text',
			$after_text
		);

		// Number.
		$this->field_text(
			__( 'Number', 'mm-components' ),
			__( 'Enter the number that will be counted up to.', 'mm-components' ),
			$classname . '-number widefat',
			'number',
			$number
		);

		// Duration.
		$this->field_text(
			__( 'Duration', 'mm-components' ),
			__( 'Enter the duration it should take for the counting to complete in seconds. Example: 2', 'mm-components' ),
			$classname . '-duration widefat',
			'duration',
			$duration
		);
	}

	/**
	 * Update the widget settings.
	 *
	 * @since   1.0.0
	 *
	 * @param   array  $new_instance  The new settings for the widget instance.
	 * @param   array  $old_instance  The old settings for the widget instance.
	 *
	 * @return  array  The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                = $old_instance;
		$instance['title']       = sanitize_text_field( $new_instance['title'] );
		$instance['before_text'] = sanitize_text_field( $new_instance['before_text'] );
		$instance['after_text']  = sanitize_text_field( $new_instance['after_text'] );
		$instance['number']      = sanitize_text_field( $new_instance['number'] );
		$instance['duration']    = sanitize_text_field( $new_instance['duration'] );

		return $instance;
	}
}
