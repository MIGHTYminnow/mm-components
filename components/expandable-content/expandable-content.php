<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Expandable Content
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Output Expandable Content.
 *
 * @since   1.0.0
 *
 * @param   array   $args  The args.
 *
 * @return  string         The HTML.
 */
function mm_expandable_content( $args ) {

	$component = 'mm-expandable-content';

	$defaults = array(
		'link_style'           => '',
		'link_text'            => '',
		'link_alignment'       => 'left',
		'fade'                 => '',
		'button_style'         => '',
		'button_border_weight' => '',
		'button_corner_style'  => '',
		'button_color'         => '',
		'content'              => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$link_style           = $args['link_style'];
	$link_text            = $args['link_text'];
	$link_alignment       = $args['link_alignment'];
	$fade                 = $args['fade'];
	$button_style         = $args['button_style'];
	$button_border_weight = $args['button_border_weight'];
	$button_corner_style  = $args['button_corner_style'];
	$button_color         = $args['button_color'];
	$content              = $args['content'];

	// Fix wpautop issues in $content.
	if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
		$content = wpb_js_remove_wpautop( $content, true );
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Build the trigger classes.
	$trigger_classes = 'mm-expandable-content-trigger mm-text-align-' . $link_alignment;
	$trigger_classes .= ( $fade ) ? ' ' . $fade : '';

	// Build the target classes.
	$target_classes = 'mm-expandable-content-target ' . $link_alignment;

	// Build the button or link trigger output.
	if ( 'button' === $link_style ) {

		$button_args = array(
			'button_text'   => $link_text,
			'class'         => 'mm-expandable-content-trigger-button',
			'style'         => $button_style,
			'corner_style'  => $button_corner_style,
			'border_weight' => $button_border_weight,
			'color'         => $button_color,
			'alignment'     => $link_alignment,
		);

		$trigger_link_output = mm_button( $button_args );

	} else {

		$trigger_link_output = sprintf(
			'<a class="mm-expandable-content-trigger-link %s" title="%s">%s</a>',
			esc_attr( $link_style ),
			esc_attr( $link_text ),
			esc_html( $link_text )
		);
	}

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">
		<div class="<?php echo esc_attr( $trigger_classes ); ?>">
			<?php echo $trigger_link_output; ?>
		</div>
		<div class="<?php echo esc_attr( $target_classes ); ?>">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_expandable_content', 'mm_expandable_content_shortcode' );
/**
 * Expandable Content shortcode.
 *
 * @since   1.0.0
 *
 * @param   array   $atts     Shortcode attributes.
 * @param   string  $content  Shortcode content.
 *
 * @return  string            Shortcode output.
 */
function mm_expandable_content_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return mm_expandable_content( $atts );
}

add_action( 'vc_before_init', 'mm_vc_expandable_content' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_expandable_content() {

	$button_styles         = mm_get_button_styles_for_vc( 'mm-expandable-content' );
	$button_border_weights = mm_get_button_border_weights_for_vc( 'mm-expandable-content' );
	$button_corner_styles  = mm_get_button_corner_styles_for_vc( 'mm-expandable-content' );
	$button_colors         = mm_get_colors_for_vc( 'mm-expandable-content' );
	$alignment             = mm_get_text_alignment_for_vc( 'mm-expandable-content' );

	/**
	 * Expandable Content.
	 */
	vc_map( array(
		'name'         => __( 'Expandable Content', 'mm-components' ),
		'base'         => 'mm_expandable_content',
		'icon'         => MM_COMPONENTS_ASSETS_URL . 'expandable-content-icon.png',
		'as_parent'    => array( 'except' => '' ),
		'is_container' => true,
		'params' => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Button or Link?', 'mm-components' ),
				'param_name'  => 'link_style',
				'description' => __( 'Should the trigger be a button or a link?', 'mm-components' ),
				'value' => array(
					__( 'Select Button or Link', 'mm-components' ),
					__( 'Button', 'mm-components' ) => 'button',
					__( 'Link', 'mm-components' )   => 'link',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Style', 'mm-components' ),
				'param_name' => 'button_style',
				'value'      => $button_styles,
				'dependency' => array(
					'element' => 'link_style',
					'value'   => 'button'
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Border Weight', 'mm-components' ),
				'param_name' => 'button_border_weight',
				'value'      => $button_border_weights,
				'dependency' => array(
					'element' => 'button_style',
					'value'   => array(
						'ghost',
						'solid-to-ghost',
					)
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Corner Style', 'mm-components' ),
				'param_name' => 'button_corner_style',
				'value'      => $button_corner_styles,
				'dependency' => array(
					'element' => 'link_style',
					'value'   => 'button'
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Color', 'mm-components' ),
				'param_name' => 'button_color',
				'value'      => $button_colors,
				'dependency' => array(
					'element' => 'link_style',
					'value'   => 'button'
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Button/Link Text', 'mm-components' ),
				'param_name'  => 'link_text',
				'description' => __( 'The text for the button/link', 'mm-components' ),
				'value'       => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button/Link Alignment', 'mm-components' ),
				'param_name' => 'link_alignment',
				'value'      => $alignment,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Fade in or Slide?', 'mm-components' ),
				'param_name' => 'fade',
				'value'      => array(
					__( 'None', 'mm-components' ) => '',
					__( 'Fade', 'mm-components' ) => 'fade',
					__( 'Slide', 'mm-components' ) => 'slide'
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Extra class name', 'mm-components' ),
				'param_name'  => 'class',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'mm-components' )
			),
		),
		'js_view' => 'VcColumnView'
	) );

}

// This is necessary to make any element that wraps other elements work.
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_MM_Expandable_Content extends WPBakeryShortCodesContainer {
	}
}

add_action( 'widgets_init', 'mm_components_register_expandable_content_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_expandable_content_widget() {

	register_widget( 'mm_expandable_content_widget' );
}

/**
 * Expandable Content widget.
 *
 * @since  1.0.0
 */
class Mm_Expandable_Content_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-expandable-content-widget',
			'description' => __( 'An Expandable Content Container', 'mm-components' ),
		);

		parent::__construct(
			'mm_expandable_content_widget',
			__( 'Mm Expandable Content', 'mm-components' ),
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
			'link_style'           => '',
			'link_text'            => '',
			'link_alignment'       => 'left',
			'fade'                 => '',
			'button_style'         => '',
			'button_border_weight' => '',
			'button_corner_style'  => '',
			'button_color'         => '',
			'content'              => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		echo $args['before_widget'];

		echo mm_expandable_content( $instance );

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
			'link_style'           => '',
			'link_text'            => '',
			'link_alignment'       => '',
			'fade'                 => '',
			'button_style'         => '',
			'button_border_weight' => '',
			'button_corner_style'  => '',
			'button_color'         => '',
			'content'              => '',
			'mm_custom_class'      => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$link_style           = $instance['link_style'];
		$link_text            = $instance['link_text'];
		$link_alignment       = $instance['link_alignment'];
		$fade                 = $instance['fade'];
		$button_style         = $instance['button_style'];
		$button_border_weight = $instance['button_border_weight'];
		$button_corner_style  = $instance['button_corner_style'];
		$button_color         = $instance['button_color'];
		$content              = $instance['content'];
		$mm_custom_class      = $instance['mm_custom_class'];
		$classname            = $this->options['classname'];

		// Link Style.
		$this->field_select(
			__( 'Button or Link?', 'mm-components' ),
			'Should the trigger be a button or a link?',
			$classname . '-link-style widefat',
			'link_style',
			$link_style,
			array(
				__( 'Select Button or Link', 'mm-components' ),
				'button' => __( 'Button', 'mm-components' ),
				'link' => __( 'Link', 'mm-components' ),
			)
		);

		// Link Text.
		$this->field_text(
			__( 'Button/Link Text', 'mm-components' ),
			'',
			$classname . '-link-text widefat',
			'link_text',
			$link_text
		);

		// Link Alignment.
		$this->field_select(
			__( 'Link Alignment', 'mm-components' ),
			'',
			$classname . '-link-alignment widefat',
			'link_alignment',
			$link_alignment,
			mm_get_text_alignment( 'mm-expandable-content' )
		);

		// Fade.
		$this->field_select(
			__( 'Fade in or Slide?', 'mm-components' ),
			'',
			$classname . '-fade widefat',
			'fade',
			$fade,
			array(
				'' => __( 'None', 'mm-components' ),
				'fade' => __( 'Fade', 'mm-components' ),
				'slide' => __( 'Slide', 'mm-components' ),
			)
		);

		// Button Style.
		$this->field_select(
			__( 'Button Style', 'mm-components' ),
			__( '', 'mm-components' ),
			$classname . '-button-style widefat',
			'button_style',
			$button_style,
			mm_get_button_styles( 'mm-expandable-content' )
		);

		// Button Border Weight.
		$this->field_select(
			__( 'Button Border Weight', 'mm-components' ),
			'',
			$classname . '-button-border-weight widefat',
			'button_border_weight',
			$button_border_weight,
			mm_get_button_border_weights( 'mm-expandable-content' )
		);

		// Button Corner Syles.
		$this->field_select(
			__( 'Button Corner Style', 'mm-components' ),
			'',
			$classname . '-button-corner-style widefat',
			'button_corner_style',
			$button_corner_style,
			mm_get_button_corner_styles( 'mm-expandable-content' )
		);

		// Button Color.
		$this->field_select(
			__( 'Button Color', 'mm-components' ),
			'',
			$classname . '-button-color widefat',
			'button_color',
			$button_color,
			mm_get_colors( 'mm-expandable-content' )
		);

		// Expandable Content.
		$this->field_textarea(
			__( 'Content to be expanded', 'mm-components' ),
			'',
			$classname . '-content widefat',
			'content',
			$content
		);

		// Custom class.
		$this->field_text(
			__( 'Custom class:', 'mm-components' ),
			'',
			$classname . '-mm-custom-class widefat',
			'mm_custom_class',
			$mm_custom_class
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

		$instance = $old_instance;
		$instance['link_style']           = sanitize_text_field( $new_instance['link_style'] );
		$instance['link_text']            = sanitize_text_field( $new_instance['link_text'] );
		$instance['link_alignment']       = sanitize_text_field( $new_instance['link_alignment'] );
		$instance['fade']                 = sanitize_text_field( $new_instance['fade'] );
		$instance['button_style']         = sanitize_text_field( $new_instance['button_style'] );
		$instance['button_border_weight'] = sanitize_text_field( $new_instance['button_border_weight'] );
		$instance['button_corner_style']  = sanitize_text_field( $new_instance['button_corner_style'] );
		$instance['button_color']         = sanitize_text_field( $new_instance['button_color'] );
		$instance['link_alignment']       = sanitize_text_field( $new_instance['link_alignment'] );
		$instance['content']              = wp_kses_post( $new_instance['content'] );
		$instance['mm_custom_class']      = sanitize_text_field( $new_instance['mm_custom_class'] );

		return $instance;
	}
}
