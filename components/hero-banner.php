<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Hero Banner
 *
 * @package mm-components
 * @since   1.0.0
 */

function mm_hero_banner( $args ) {

	$component = 'mm-hero-banner';

	// Set our defaults and use them as needed.
	$defaults = array(
		'background_image'    => '',
		'background_position' => 'center center',
		'overlay_color'       => '',
		'overlay_opacity'     => '',
		'heading'             => '',
		'content'             => '',
		'text_position'       => 'left',
		'button_link'         => '',
		'button_link_target'  => '',
		'button_text'         => __( 'Read More', 'mm-components' ),
		'button_style'        => '',
		'button_border'       => '',
		'button_corner_style' => '',
		'button_color'        => '',
		'secondary_cta'       => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$background_image    = $args['background_image'];
	$background_position = $args['background_position'];
	$overlay_color       = $args['overlay_color'];
	$overlay_opacity     = $args['overlay_opacity'];
	$heading             = $args['heading'];
	$content             = $args['content'];
	$text_position       = $args['text_position'];
	$button_link         = $args['button_link'];
	$button_link_target  = $args['button_link_target'];
	$button_text         = $args['button_text'];
	$button_style        = $args['button_style'];
	$button_border       = $args['button_border'];
	$button_color        = $args['button_color'];
	$button_corner_style = $args['button_corner_style'];
	$secondary_cta       = $args['secondary_cta'];
	$heading_output = '';
	$content_output = '';
	$overlay = '';
	$button_shortcode = '';
	$secondary_cta_output  = '';

	// Get MM classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );
	$mm_classes .= ' mm-text-align-' . $args['text_position'];
	$mm_classes .= ' full-width';

	/**
	 * Parse images.
	 *
	 * These can be passed either as an attachment ID (VC method), or manually
	 * as a URL.
	 */

	// Main image.
	if ( is_numeric( $background_image ) ) {
		$image_array = wp_get_attachment_image_src( $background_image, 'full' );
		$image = $image_array[0];
	} else {
		$image = $background_image;
	}

	// Compose style tag.
	$style = "background-image: url($image);";
	$style .= " background-position: $background_position;";

	// Do background overlay.
	if ( $overlay_color && $overlay_opacity ) {
		$styles_array = array();
		$styles_array[] = "background-color: $overlay_color;";
		$styles_array[] = "opacity: $overlay_opacity;";

		$overlay_opacity_ie = $overlay_opacity * 100;
		$styles_array[] = "filter: alpha(opacity=$overlay_opacity_ie);";
		$styles = implode( ' ', $styles_array );

		$overlay = '<div class="color-overlay" style="' . esc_attr( $styles ) . '"></div>';

	}

	// Build the heading.

	if ( $heading ) {
		$heading_output = '<div>' . esc_attr( $heading ) . '</div>';
	}

	// Build the content.

	if ( $content ) {
		$content_output = '<div>' . esc_attr( $content ) . '</div>';
	}

	// Build the button shortcode.

	if ( $button_text ) {

		$button_shortcode = sprintf(
			'[mm_button color="%s" style="%s" corner_style="%s" border_weight="%s" alignment="%s" link="%s" link_title="%s" link_target="%s" link-title]%s[/mm_button]',
			esc_attr( $button_color ),
			esc_attr( $button_style ),
			esc_attr( $button_corner_style ),
			esc_attr( $button_border ),
			esc_attr( $text_position ),
			$button_link,
			$button_text,
			$button_link_target,
			$button_text
		);

	}

	if ( strpos( $secondary_cta, '<' ) ) {

		/* We have HTML */
		$secondary_cta_output = ( function_exists( 'wpb_js_remove_wpautop' ) ) ? wpb_js_remove_wpautop( $secondary_cta, true ) : $secondary_cta;

	} elseif ( mm_is_base64( $secondary_cta ) ) {

		/* We have a base64 encoded string */
		$secondary_cta_output = rawurldecode( base64_decode( $secondary_cta ) );

	} else {

		/* We have a non-HTML string */
		$secondary_cta_output = $secondary_cta;
	}

	if ( $secondary_cta ) {

		$secondary_cta_output = '<div class="secondary-cta">' . wp_kses_post( $secondary_cta_output ) . '</div>';
	}

	ob_start(); ?>

	<div class="mm-hero-banner <?php echo esc_attr( $mm_classes ); ?>" style="<?php echo $style; ?> background-size: cover">
		<?php echo $overlay; ?>

		<div class="hero-text-wrapper">
			<div class="wrapper">

				<?php echo $heading_output; ?>

				<?php echo $content_output; ?>

				<?php echo do_shortcode( $button_shortcode ); ?>

				<?php echo do_shortcode( $secondary_cta_output ); ?>

			</div>
		</div>

	</div>

	<?php

	$output = ob_get_clean();

	return $output;

}

add_shortcode( 'mm_hero_banner', 'mm_hero_banner_shortcode' );
/**
 * Output Hero Banner.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_hero_banner_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return mm_hero_banner( $atts );

}

add_action( 'vc_before_init', 'mm_vc_hero_banner' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_hero_banner() {

	vc_map( array(
		'name' => __( 'Hero Banner', 'mm-components' ),
		'base' => 'mm_hero_banner',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'attach_image',
				'heading' => __( 'Background Image', 'mm-components' ),
				'param_name' => 'background_image',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Background Position', 'mm-components' ),
				'param_name' => 'background_position',
				'value' => mm_get_background_position_for_vc()
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Overlay Color', 'mm-components' ),
				'param_name' => 'overlay_color',
				'value' => mm_get_overlay_colors_for_vc()
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Overlay Opacity', 'mm-components' ),
				'param_name' => 'overlay_opacity',
				'value' => mm_get_overlay_opacity_values_for_vc(),
				'dependency' => array(
					'element' => 'overlay_color',
					'not_empty' => '1',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Hero Banner Heading', 'mm-components' ),
				'param_name' => 'heading',
				'admin_label' => true,
			),
			array(
				'type' => 'textarea_html',
				'heading' => __( 'Hero Banner Content', 'mm-components' ),
				'param_name' => 'content',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Position', 'mm-components' ),
				'param_name' => 'text_position',
				'value' => mm_get_text_alignment_for_vc(),
			),
			array(
				'type' => 'vc_link',
				'heading' => __( 'Button URL', 'mm-components' ),
				'param_name' => 'button_link',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Button Text', 'mm-components' ),
				'param_name' => 'button_text',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Button Style', 'mm-components' ),
				'param_name' => 'button_style',
				'value' => mm_get_button_styles_for_vc(),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Border Weight', 'mm-components' ),
				'param_name' => 'border_weight',
				'value'      => mm_get_button_border_weights_for_vc(),
				'dependency' => array(
					'element' => 'button_style',
					'value'   => array(
						'ghost',
						'solid-to-ghost',
					)
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Button Corner Style', 'mm-components' ),
				'param_name' => 'button_corner_style',
				'value' => mm_get_button_corner_styles_for_vc(),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Button Color', 'mm-components' ),
				'param_name' => 'button_color',
				'value' => mm_get_available_colors_for_vc(),
			),
			array(
				'type' => 'textarea_raw_html',
				'heading' => __( 'Secondary Call to Action', 'mm-components' ),
				'param_name' => 'secondary_cta',
				'description' => __( 'Outputs below the main button, can include HTML markup.', 'mm-components' ),
			),
		),
	) );
}

add_action( 'widgets_init', 'mm_components_register_hero_banner_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_hero_banner_widget() {

	register_widget( 'mm_hero_banner_widget' );
}

/**
 * Restricted Content widget.
 *
 * @since  1.0.0
 */
class Mm_Hero_Banner_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-hero-banner',
			'description' => __( 'A Hero Banner Container', 'mm-components' ),
		);

		parent::__construct(
			'mm_hero_banner_widget',
			__( 'Mm Hero Banner', 'mm-components' ),
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
			'background_image'    => '',
			'background_position' => 'center center',
			'overlay_color'       => '',
			'overlay_opacity'     => '',
			'heading'             => '',
			'content'             => '',
			'text_position'       => 'left',
			'button_link'         => '',
			'button_link_target'  => '',
			'button_text'         => __( 'Read More', 'mm-components' ),
			'button_style'        => '',
			'button_corner_style' => '',
			'button_color'        => '',
			'secondary_cta'       => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo mm_hero_banner( $instance );

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
			'background_image'    => '',
			'background_position' => 'center center',
			'overlay_color'       => '',
			'overlay_opacity'     => '',
			'heading'             => '',
			'content'             => '',
			'text_position'       => 'left',
			'button_link'         => '',
			'button_link_target'  => '',
			'button_text'         => __( 'Read More', 'mm-components' ),
			'button_style'        => '',
			'button_border'       => '',
			'button_corner_style' => '',
			'button_color'        => '',
			'secondary_cta'       => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$background_image    = $instance['background_image'];
		$background_position = $instance['background_position'];
		$overlay_color       = $instance['overlay_color'];
		$overlay_opacity     = $instance['overlay_opacity'];
		$heading             = $instance['heading'];
		$content             = $instance['content'];
		$text_position       = $instance['text_position'];
		$button_link         = $instance['button_link'];
		$button_link_target  = $instance['button_link_target'];
		$button_text         = $instance['button_text'];
		$button_style        = $instance['button_style'];
		$button_border       = $instance['button_border'];
		$button_color        = $instance['button_color'];
		$button_corner_style = $args['button_corner_style'];
		$secondary_cta       = $instance['secondary_cta'];

		$classname          = $this->options['classname'];

		// Background Image.
		$this->field_single_media(
			__( 'Background Image:', 'mm-components' ),
			__( 'Upload an image that is large enough to be output without stretching.', 'mm-components' ),
			$classname . '-background-image widefat',
			'background_image',
			$background_image
		);

		// Background Position.
		$this->field_text(
			__( 'Background Position', 'mm-components' ),
			__( '', 'mm-components' ),
			$classname . '-background-position widefat',
			'background_position',
			$background_position,
			mm_get_background_position()
		);

		// Overlay Color.
		$this->field_select(
			__( 'Overlay Color', 'mm-components' ),
			'',
			$classname . '-overlay_color widefat',
			'overlay_color',
			$overlay_color,
			mm_get_overlay_colors()
		);

		// Overlay Opacity.
		$this->field_select(
			__( 'Overlay Opacity', 'mm-components' ),
			'',
			$classname . '-overlay-opacity widefat',
			'overlay_opacity',
			$overlay_opacity,
			mm_get_overlay_opacity_values()
		);

		// Heading.
		$this->field_text(
			__( 'Hero Banner Heading', 'mm-components' ),
			'',
			$classname . '-heading widefat',
			'heading',
			$heading
		);

		// Content.
		$this->field_textarea(
			__( 'Hero Banner Content', 'mm-components' ),
			'',
			$classname . '-content widefat',
			'content',
			$content
		);

		// Text Position.
		$this->field_select(
			__( 'Text Position', 'mm-components' ),
			'',
			$classname . '-text-position widefat',
			'text_position',
			$text_position,
			mm_get_text_alignment()
		);

		// Button Link.
		$this->field_text(
			__( 'Button Link', 'mm-components' ),
			'',
			$classname . '-button-link widefat',
			'button_link',
			$button_link
		);

		// Button Text.
		$this->field_text(
			__( 'Button Text', 'mm-components' ),
			'',
			$classname . '-button-text widefat',
			'button_text',
			$button_text
		);

		// Button Style.
		$this->field_select(
			__( 'Button Style', 'mm-components' ),
			'',
			$classname . '-button-style widefat',
			'button_style',
			$button_style,
			mm_get_button_styles()
		);

		// Button Border Weight.
		$this->field_select(
			__( 'Button Border Weight', 'mm-components' ),
			'',
			$classname . '-button-border-weight widefat',
			'button_border',
			$button_border,
			mm_get_button_border_weights()
		);

		// Button Corner Style.
		$this->field_select(
			__( 'Button Corner Style', 'mm-components' ),
			'',
			$classname . '-button-corner-style widefat',
			'button_corner_style',
			$button_corner_style,
			mm_get_button_corner_styles()
		);

		// Button Color.
		$this->field_select(
			__( 'Button Color', 'mm-components' ),
			'',
			$classname . '-button-color widefat',
			'button_color',
			$button_color,
			mm_get_available_colors()
		);

		// Secondary CTA.
		$this->field_textarea(
			__( 'Secondary CTA', 'mm-components' ),
			'',
			$classname . '-secondary-cta widefat',
			'secondary_cta',
			$secondary_cta
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
		$instance['background_image']    = sanitize_text_field( $new_instance['background_image'] );
		$instance['background_position'] = sanitize_text_field( $new_instance['background_position'] );
		$instance['overlay_color']       = wp_kses_post( $new_instance['overlay_color'] );
		$instance['overlay_opacity']     = wp_kses_post( $new_instance['overlay_opacity'] );
		$instance['heading']             = sanitize_text_field( $new_instance['heading'] );
		$instance['content']             = wp_kses_post( $new_instance['content'] );
		$instance['text_position']       = sanitize_text_field( $new_instance['text_position'] );
		$instance['button_link']         = sanitize_text_field( $new_instance['button_link'] );
		$instance['button_link_target']  = sanitize_text_field( $new_instance['button_link_target'] );
		$instance['button_text']         = sanitize_text_field( $new_instance['button_text'] );
		$instance['button_style']        = sanitize_text_field( $new_instance['button_style'] );
		$instance['button_border']       = sanitize_text_field( $new_instance['button_border'] );
		$instance['button_color']        = sanitize_text_field( $new_instance['button_color'] );
		$instance['button_corner_style'] = sanitize_text_field( $new_instance['button_corner_style'] );
		$instance['secondary_cta']       = wp_kses_post( $new_instance['secondary_cta'] );

		return $instance;
	}
}