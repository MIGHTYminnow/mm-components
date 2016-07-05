<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Highlight Box
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Highlight Box component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_highlight_box( $args ) {

	$component = 'mm-highlight-box';

	$defaults = array(
		'heading_text'   => '',
		'content'        => '',
		'link'           => '',
		'link_text'      => '',
		'link_target'    => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	$heading_text   = $args['heading_text'];
	$paragraph_text = $args['content'];
	$link_url       = $args['link'];
	$link_text      = $args['link_text'];
	$link_title     = $args['link_text'];
	$link_target    = $args['link_target'];

	// Handle a VC link array.
	if ( 'url' === substr( $link_url, 0, 3 ) && function_exists( 'vc_build_link' ) ) {
		$link_array  = vc_build_link( $link_url );
		$link_url    = $link_array['url'];
		$link_title  = $link_array['title'];
		$link_target = $link_array['target'];
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">

		<?php if ( ! empty( $heading_text ) ) : ?>
			<h3><?php echo esc_html( $heading_text ); ?></h3>
		<?php endif; ?>

		<?php if ( ! empty( $paragraph_text ) ) : ?>
			<p><?php echo do_shortcode( wp_kses_post( $paragraph_text ) ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $link_url ) && ! empty( $link_text ) ) {
			printf( '<a href="%s" title="%s" target="%s">%s</a>',
				esc_url( $link_url ),
				esc_attr( $link_title ),
				esc_attr( $link_target ),
				esc_html( $link_text )
			);
		} ?>

	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_highlight_box', 'mm_highlight_box_shortcode' );
/**
 * Highlight Box shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_highlight_box_shortcode( $atts, $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return mm_highlight_box( $atts );
}

add_action( 'vc_before_init', 'mm_vc_highlight_box' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_highlight_box() {

	vc_map( array(
		'name'     => __( 'Highlight Box', 'mm-components' ),
		'base'     => 'mm_highlight_box',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Heading', 'mm-components' ),
				'param_name'  => 'heading_text',
				'admin_label' => true,
			),
			array(
				'type'       => 'textarea_html',
				'heading'    => __( 'Paragraph Text', 'mm-components' ),
				'param_name' => 'content',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Link Text', 'mm-components' ),
				'param_name' => 'link_text',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Link URL', 'mm-components' ),
				'param_name' => 'link',
			),
		)
	) );
}

add_action( 'widgets_init', 'mm_components_register_highlight_box_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_highlight_box_widget() {

	register_widget( 'mm_highlight_box_widget' );
}

/**
 * Highlight box widget.
 *
 * @since  1.0.0
 */
class Mm_Highlight_Box_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-highlight-box',
			'description' => __( 'A Highlight Box', 'mm-components' ),
		);

		parent::__construct(
			'mm_highlight_box_widget',
			__( 'Mm Highlight Box', 'mm-components' ),
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
			'heading_text'   => '',
			'content'        => '',
			'link_text'      => '',
			'link'           => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		echo $args['before_widget'];

		echo mm_highlight_box( $instance );

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
			'title'          => '',
			'heading_text'   => '',
			'content'        => '',
			'link_text'      => '',
			'link'           => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$heading_text   = $instance['heading_text'];
		$paragraph_text = $instance['content'];
		$link_text      = $instance['link_text'];
		$link           = $instance['link'];
		$classname      = $this->options['classname'];

		// Heading Text.
		$this->field_text(
			__( 'Heading Text', 'mm-components' ),
			'',
			$classname . '-heading-text widefat',
			'heading_text',
			$heading_text
		);

		// Paragraph Text.
		$this->field_textarea(
			__( 'Paragraph Text', 'mm-components' ),
			'',
			$classname . '-paragraph-text widefat',
			'content',
			$paragraph_text
		);

		// Link Text.
		$this->field_text(
			__( 'Link Text', 'mm-components' ),
			'',
			$classname . '-link-text widefat',
			'link_text',
			$link_text
		);

		// Link.
		$this->field_text(
			__( 'Link', 'mm-components' ),
			'',
			$classname . '-link widefat',
			'link',
			$link
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
	 * @return  array                 The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                   = $old_instance;
		$instance['heading_text']   = wp_kses_post( $new_instance['heading_text'] );
		$instance['content']        = do_shortcode( wp_kses_post( $new_instance['content'] ) );
		$instance['link_text']      = sanitize_text_field( $new_instance['link_text'] );
		$instance['link']           = sanitize_text_field( $new_instance['link'] );

		return $instance;
	}
}
