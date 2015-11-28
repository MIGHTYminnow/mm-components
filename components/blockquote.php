<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Blockquote
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Blockquote component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_blockquote( $args ) {

	$component  = 'mm-blockquote';

	// Set our defaults and use them as needed.
	$defaults = array(
		'quote'    => '',
		'citation' => '',
		'image_id' => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$quote    = (string)$args['quote'];
	$citation = (string)$args['citation'];
	$image_id = (int)$args['image_id'];

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	ob_start() ?>

	<blockquote class="<?php echo esc_attr( $mm_classes ); ?>">

		<?php if ( is_int( $image_id ) && 0 !== $image_id ) : ?>
			<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
		<?php endif; ?>

		<?php echo '<p>' . wp_kses_post( $quote ) . '</p>'; ?>

		<?php if ( ! empty( $citation ) ) : ?>
			<cite><?php echo wp_kses_post( $citation ); ?></cite>
		<?php endif; ?>

	</blockquote>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_blockquote', 'mm_blockquote_shortcode' );
/**
 * Blockquote shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_blockquote_shortcode( $atts ) {

	return mm_blockquote( $atts );
}

add_action( 'vc_before_init', 'mm_vc_blockquote' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_blockquote() {

	vc_map( array(
		'name'     => __( 'Blockquote', 'mm-components' ),
		'base'     => 'mm_blockquote',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'attach_image',
				'heading'     => __( 'Image', 'mm-components' ),
				'param_name'  => 'image_id',
				'description' => __( 'Select an image from the library.', 'mm-components' ),
			),
			array(
				'type'       => 'textarea',
				'heading'    => __( 'Quote', 'mm-components' ),
				'param_name' => 'quote',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Citation', 'mm-components' ),
				'param_name' => 'citation',
			),
		)
	) );
}

add_action( 'widgets_init', 'mm_components_register_blockquote_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
*/
function mm_components_register_blockquote_widget() {

	register_widget( 'mm_blockquote_widget' );
}

/**
 * Blockquote widget.
 *
 * @since  1.0.0
 */
class Mm_Blockquote_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-blockquote',
			'description' => __( 'A Blockquote', 'mm-components' ),
		);

		parent::__construct(
			'mm_blockquote_widget',
			__( 'Mm Blockquote', 'mm-components' ),
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
			'title'    => '',
			'quote'    => '',
			'citation' => '',
			'image_id' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// At this point all instance options have been sanitized.
		$title    = apply_filters( 'widget_title', $instance['title'] );
		$quote    = $instance['quote'];
		$citation = $instance['citation'];
		$image_id = $instance['image_id'];

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo mm_blockquote( $instance );

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
			'title'    => '',
			'quote'    => '',
			'citation' => '',
			'image_id' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title     = $instance['title'];
		$quote     = $instance['quote'];
		$citation  = $instance['citation'];
		$image_id  = $instance['image_id'];
		$classname = $this->options['classname'];

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			'',
			$classname . '-title widefat',
			'title',
			$title
		);

		// Quote.
		$this->field_textarea(
			__( 'Quote', 'mm-components' ),
			'',
			$classname . '-quote widefat',
			'quote',
			$quote
		);

		// Citation.
		$this->field_text(
			__( 'Citation', 'mm-components' ),
			'',
			$classname . '-citation widefat',
			'citation',
			$citation
		);

		// Image.
		$this->field_single_media(
			__( 'Image', 'mm-components' ),
			__( 'Select an image from the library.', 'mm-components'),
			$classname . '-image widefat',
			'image_id',
			$image_id
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

		$instance             = $old_instance;
		$instance['title']    = wp_kses_post( $new_instance['title'] );
		$instance['quote']    = wp_kses_post( $new_instance['quote'] );
		$instance['citation'] = sanitize_text_field( $new_instance['citation'] );
		$instance['image_id'] = ( ! empty( $new_instance['image_id'] ) ) ? intval( $new_instance['image_id'] ) : '';

		return $instance;
	}
}
