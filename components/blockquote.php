<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Blockquote
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_blockquote', 'mm_blockquote_shortcode' );
/**
 * Output Blockquote.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_blockquote_shortcode( $atts, $content = null, $tag ) {

	$atts = mm_shortcode_atts( array(
		'image_id' => '',
		'quote'    => '',
		'citation' => '',
	), $atts );

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_components_custom_classes', $mm_classes, $tag, $atts );

	// Get param values.
	$image_id = (int)$atts['image_id'];
	$quote    = ( ! empty( $atts['quote'] ) ) ? '<p>' . esc_html( $atts['quote'] ) . '</p>' : '';
	$citation = ( ! empty( $atts['citation'] ) ) ? esc_html( $atts['citation'] ) : '';

	ob_start() ?>

	<blockquote class="<?php echo $mm_classes; ?>">

		<?php if ( 0 !== $image_id ) : ?>
			<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
		<?php endif; ?>

		<?php echo $quote; ?>

		<?php if ( $citation ) : ?>
			<cite><?php echo $citation; ?></cite>
		<?php endif; ?>

	</blockquote>

	<?php

	$output = ob_get_clean();

	return $output;
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
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
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
			'image_id' => '',
			'quote'    => '',
			'citation' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// At this point all instance options have been sanitized.
		$title    = apply_filters( 'widget_title', $instance['title'] );
		$image_id = $instance['image_id'];
		$quote    = $instance['quote'];
		$citation = $instance['citation'];

		$shortcode = sprintf(
			'[mm_blockquote image_id="%s" quote="%s" citation="%s"]',
			$image_id,
			$quote,
			$citation
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
			'title'    => '',
			'image_id' => '',
			'quote'    => '',
			'citation' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title     = $instance['title'];
		$image_id  = $instance['image_id'];
		$quote     = $instance['quote'];
		$citation  = $instance['citation'];
		$classname = $this->options['classname'];

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			$classname . '-title widefat',
			'title',
			$title
		);

		// Image.
		$this->field_text(
			__( 'Image ID', 'mm-components' ),
			$classname . '-image widefat',
			'image_id',
			$image_id
		);

		// Quote.
		$this->field_textarea(
			__( 'Quote', 'mm-components' ),
			$classname . '-quote widefat',
			'quote',
			$quote
		);

		// Citation.
		$this->field_text(
			__( 'Citation', 'mm-components' ),
			$classname . '-citation widefat',
			'citation',
			$citation
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

		$instance             = $old_instance;
		$instance['title']    = wp_kses_post( $new_instance['title'] );
		$instance['image_id'] = ( isset( $new_instance['image_id'] ) && '' !== $new_instance['image_id'] ) ? intval( $new_instance['image_id'] ) : '';
		$instance['quote']    = wp_kses_post( $new_instance['quote'] );
		$instance['citation'] = sanitize_text_field( $new_instance['citation'] );

		return $instance;
	}
}
