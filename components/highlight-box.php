<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Highlight Box
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_highlight_box', 'mm_highlight_box_shortcode' );
/**
 * Output Highlight Box.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_highlight_box_shortcode( $atts, $content = null, $tag ) {

	$atts = mm_shortcode_atts( array(
		'heading_text'   => '',
		'paragraph_text' => '',
		'link_text'      => '',
		'link'           => '',
		'link_target'    => '',
	), $atts );

	// Handle a raw link or a VC link array.
	$link_url    = '';
	$link_title  = '';
	$link_target = '';

	if ( ! empty( $atts['link'] ) ) {

		if ( 'url' === substr( $atts['link'], 0, 3 ) ) {

			if ( function_exists( 'vc_build_link' ) ) {

				$link_array  = vc_build_link( $atts['link'] );
				$link_url    = $link_array['url'];
				$link_title  = $link_array['title'];
				$link_target = $link_array['target'];
			}

		} else {

			$link_url    = $atts['link'];
			$link_title  = $atts['link_title'];
			$link_target = $atts['link_target'];
		}
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $tag, $atts );

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">

		<?php if ( ! empty( $atts['heading_text'] ) ) : ?>
			<h3><?php echo $atts['heading_text']; ?></h3>
		<?php endif; ?>

		<?php if ( ! empty( $atts['paragraph_text'] ) ) : ?>
			<p><?php echo $atts['paragraph_text']; ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $link_url ) && ! empty( $atts['link_text'] ) ) {
			printf( '<a href="%s" title="%s" target="%s">%s</a>',
				esc_url( $link_url ),
				esc_attr( $link_title ),
				esc_attr( $link_target ),
				esc_html( $atts['link_text'] )
			);
		} ?>

	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_highlight_box' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_highlight_box() {

	vc_map( array(
		'name' => __( 'Highlight Box', 'mm-components' ),
		'base' => 'mm_highlight_box',
		'class' => '',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Heading', 'mm-components' ),
				'param_name' => 'heading_text',
				'admin_label' => true,
			),
			array(
				'type' => 'textarea',
				'heading' => __( 'Paragraph Text', 'mm-components' ),
				'param_name' => 'paragraph_text',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Link Text', 'mm-components' ),
				'param_name' => 'link_text',
			),
			array(
				'type' => 'vc_link',
				'heading' => __( 'Link URL', 'mm-components' ),
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

		// At this point all instance options have been sanitized.
		$title          = apply_filters( 'widget_title', $instance['title'] );
		$heading_text   = $instance['heading_text'];
		$paragraph_text = $instance['paragraph_text'];
		$link_text      = $instance['link_text'];
		$link           = $instance['link'];

		$shortcode = sprintf(
			'[mm_highlight_box heading_text="%s" paragraph_text="%s" link_text="%s" link="%s"]',
			$heading_text,
			$paragraph_text,
			$link_text,
			$link
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
			'title'          => '',
			'heading_text'   => '',
			'paragraph_text' => '',
			'link_text'      => '',
			'link'           => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title          = $instance['title'];
		$heading_text   = $instance['heading_text'];
		$paragraph_text = $instance['paragraph_text'];
		$link_text      = $instance['link_text'];
		$link           = $instance['link'];
		$classname      = $this->options['classname'];

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			'',
			$classname . '-title widefat',
			'title',
			$title
		);

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
			'paragraph_text',
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
	 * @return  array  The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title']          = wp_kses_post( $new_instance['title'] );
		$instance['heading_text']   = wp_kses_post( $new_instance['heading_text'] );
		$instance['paragraph_text'] = wp_kses_post( $new_instance['paragraph_text'] );
		$instance['link_text']      = sanitize_text_field( $new_instance['link_text'] );
		$instance['link']           = ( '' !== $new_instance['link'] ) ? esc_url( $new_instance['link'] ) : '';

		return $instance;
	}
}
