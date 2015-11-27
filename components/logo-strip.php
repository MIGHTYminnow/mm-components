<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Logo Strip
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Logo Strip component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_logo_strip( $args ) {

	$component = 'mm-logo-strip';

	// Set our defaults and use them as needed.
	$defaults = array(
		'title'           => '',
		'title_heading'   => 'h2',
		'title_alignment' => 'center',
		'images'          => '',
		'image_size'      => 'medium',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$title           = $args['title'];
	$title_heading   = $args['title_heading'];
	$title_alignment = $args['title_alignment'];
	$images          = $args['images'];
	$image_size      = $args['image_size'];

	// Bail if no images are specified.
	if ( ! $images ) {
		return;
	}

	// Create array from comma-separated image list.
	$images = explode( ',', ltrim( $images ) );

	// Only allow valid headings.
	if ( ! in_array( $title_heading, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
		$title_heading = 'h2';
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Set up the title classes.
	$title_class = 'mm-logo-strip-heading mm-text-align-' . $title_alignment;

	// Count how many images we have.
	$image_count = count( $images );
	$image_count = 'logo-count-' . (int)$image_count;

	ob_start();

	?>

	<div class="<?php echo esc_attr( $mm_classes ); ?> <?php echo esc_attr( $image_count ); ?>">

		<?php if ( $title ) {
			printf(
				'<%s class="%s">%s</%s>',
				$title_heading,
				esc_attr( $title_class ),
				esc_html( $title ),
				$title_heading
			);
		} ?>

		<?php
			foreach ( $images as $image ) {
				printf(
					'<div class="logo">%s</div>',
					wp_get_attachment_image( (int)$image, $image_size )
				);
			}
		?>

	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_logo_strip', 'mm_logo_strip_shortcode' );
/**
 * Logo Strip shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_logo_strip_shortcode( $atts ) {

	return mm_logo_strip( $atts );
}

add_action( 'vc_before_init', 'mm_vc_logo_strip' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_logo_strip() {

	$image_sizes    = mm_get_image_sizes_for_vc( 'mm-logo-strip' );
	$heading_levels = mm_get_heading_levels_for_vc( 'mm-logo-strip' );

	vc_map( array(
		'name'     => __( 'Logo Strip', 'mm-components' ),
		'base'     => 'mm_logo_strip',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Title', 'mm-components' ),
				'param_name'  => 'title',
				'admin_label' => true,
				'value'       => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Title Heading Level', 'mm-components' ),
				'param_name' => 'title_heading',
				'value'      => $heading_levels,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Title Alignment', 'mm-components' ),
				'param_name' => 'title_alignment',
				'value'      => array(
					__( 'Center', 'mm-components' ) => 'center',
					__( 'Left', 'mm-components' )   => 'left',
					__( 'Right', 'mm-components' )  => 'right',
				),
			),
			array(
				'type'        => 'attach_images',
				'heading'     => __( 'Logos', 'mm-components' ),
				'param_name'  => 'images',
				'description' => __( 'The bigger the image size, the better.', 'mm-components' ),
				'value'       => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Logo Image Size', 'mm-components' ),
				'param_name' => 'image_size',
				'value'      => $image_sizes,
			),
		)
	) );
}

add_action( 'widgets_init', 'mm_components_register_logo_strip_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_logo_strip_widget() {

	register_widget( 'mm_logo_strip_widget' );
}

/**
 * Highlight box widget.
 *
 * @since  1.0.0
 */
class Mm_Logo_Strip_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-logo-strip',
			'description' => __( 'A Logo Strip', 'mm-components' ),
		);

		parent::__construct(
			'mm_logo_strip_widget',
			__( 'Mm Logo Strip', 'mm-components' ),
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
			'title'           => '',
			'title_heading'   => 'h2',
			'title_alignment' => 'center',
			'images'          => '',
			'image_size'      => 'medium',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// At this point all instance options have been sanitized.
		$title           = apply_filters( 'widget_title', $instance['title'] );
		$title_heading   = $instance['title_heading'];
		$title_alignment = $instance['title_alignment'];
		$images          = $instance['images'];
		$image_size      = $instance['image_size'];

		echo $args['before_widget'];

		echo mm_logo_strip( $instance );

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
			'title'           => '',
			'title_heading'   => 'h2',
			'title_alignment' => 'center',
			'images'          => '',
			'image_size'      => 'medium',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title           = $instance['title'];
		$title_heading   = $instance['title_heading'];
		$title_alignment = $instance['title_alignment'];
		$images          = $instance['images'];
		$image_size      = $instance['image_size'];
		$classname       = $this->options['classname'];
		$image_sizes     = mm_get_image_sizes( 'mm-logo-strip' );
		$heading_levels  = mm_get_heading_levels( 'mm-logo-strip' );

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			'',
			$classname . '-title widefat',
			'title',
			$title
		);

		// Title heading.
		$this->field_select(
			__( 'Title Heading Level', 'mm-components' ),
			'',
			$classname . '-title-heading widefat',
			'title_heading',
			$title_heading,
			$heading_levels
		);

		// Title alignment.
		$this->field_select(
			__( 'Title Alignment', 'mm-components' ),
			'',
			$classname . '-title-alignment widefat',
			'title_alignment',
			$title_alignment,
			array(
				'left'   => __( 'Left', 'mm-components' ),
				'center' => __( 'Center', 'mm-components' ),
				'right'  => __( 'Right', 'mm-components' ),
			)
		);

		// Images.
		$this->field_multi_media(
			__( 'Images', 'mm-components' ),
			'',
			$classname . '-images widefat',
			'images',
			$images
		);

		// Image size.
		$this->field_select(
			__( 'Image Size', 'mm-components' ),
			'',
			$classname . '-image-size widefat',
			'image_size',
			$image_size,
			$image_sizes
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

		$instance                    = $old_instance;
		$instance['title']           = wp_kses_post( $new_instance['title'] );
		$instance['title_heading']   = sanitize_text_field( $new_instance['title_heading'] );
		$instance['title_alignment'] = sanitize_text_field( $new_instance['title_alignment'] );
		$instance['images']          = wp_kses_post( $new_instance['images'] );
		$instance['image_size']      = sanitize_text_field( $new_instance['image_size'] );

		return $instance;
	}
}
