<?php

function mm_image_card( $args ) {

	// Set our defaults and use them as needed.
	$defaults = array(
		'title'         => '',
		'image'         => '',
		'link_text'     => '',
		'link_target'   => '_self',
		'button_text'   => '',
		'content_align' => 'default',
		'button_link'          => '',
		'button_link_target'   => '_self',
		'button_text'          => '',
		'button_style'         => '',
		'button_border_weight' => 'default',
		'button_corner_style'  => '',
		'button_color'         => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$title         = $args['title'];
	$image         = $args['image'];
	$link          = $args['link'];
	$link_text     = $args['link_text'];
	$button_text   = $args['button_text'];
	$content_align = $args['content_align'];
	$button_link          = $args['button_link'];
	$button_link_target   = $args['button_link_target'];
	$button_text          = $args['button_text'];
	$button_style         = $args['button_style'];
	$button_border_weight = $args['button_border_weight'];
	$button_corner_style  = $args['button_corner_style'];
	$button_color         = $args['button_color'];

	$button_output = '';

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );
	$mm_classes .= ' mm-text-align-' . $content_align;

	$link_url    = $args['link'];
	$link_title  = $args['link_title'];
	$link_target = $args['link_target'];

	// Handle a VC link array.
	if ( 'url' === substr( $args['link'], 0, 3 ) && function_exists( 'vc_build_link' ) ) {
		$link_array  = vc_build_link( $args['link'] );
		$link_url    = $link_array['url'];
		$link_title  = $link_array['title'];
		$link_target = $link_array['target'];
	}

	// Support the image being an ID or a URL.
	if ( is_numeric( $image ) ) {
		$image_array = wp_get_attachment_image_src( $image, 'full' );
		$image_url   = $image_array[0];
	} else {
		$image_url = esc_url( $image );
	}

	if ( is_numeric( $link_text ) ) {
		$link_text = wp_get_attachment_url( $link_text );
	} else {
		$link_text = esc_url( $link_text );
	}

	// Build the button output.
	if ( $button_text ) {

		$button_args = array(
			'link'          => $link,
			'link_title'    => $button_text,
			'link_target'   => $link_target,
			'button_text'   => $button_text,
			'style'         => $button_style,
			'corner_style'  => 'pointed',
			'border_weight' => $button_border_weight,
			'color'         => $button_color,
			'alignment'     => $content_align,
		);

		$button_output = mm_button( $button_args );
	}

	$content_output = sprintf(
			'<a class="%s" href="%s" target="%s"><img src="%s"></a>',
			'mm-image-card-image',
			esc_url( $link_url ),
			esc_attr( $link_target ),
			esc_attr( $image_url )
		);

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">

		<div class="mm-image-card-wrap">

		<?php

		if ( ! empty( $title ) ) {
			printf(
				'<h2>%s</h2>',
				esc_html( $title )
			);
		}

		echo wp_kses_post( $content_output );

		echo wp_kses_post( $button_output );

		?>

		</div>

	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_image_card', 'mm_image_card_shortcode' );
/**
 * Image Card shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_image_card_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return mm_image_card( $atts );
}

add_action( 'vc_before_init', 'mm_vc_image_card' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_image_card() {

	$text_alignments        = mm_get_text_alignment_for_vc( 'mm-image-card' );
	$button_styles          = mm_get_button_styles_for_vc( 'mm-button' );
	$button_border_weights  = mm_get_button_border_weights_for_vc( 'mm-button' );
	$button_corner_styles   = mm_get_button_corner_styles_for_vc( 'mm-button' );
	$button_colors          = mm_get_colors_for_vc( 'mm-button' );

	vc_map( array(
		'name'     => __( 'Image Card', 'mm-components' ),
		'base'     => 'mm_image_card',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'       => 'attach_image',
				'heading'    => __( 'Image Card Image', 'mm-components' ),
				'param_name' => 'image',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Image Card URL', 'mm-components' ),
				'param_name' => 'link',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Content Alignment', 'mm-components' ),
				'param_name' => 'content_align',
				'value'      => $text_alignments,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Button Text', 'mm-components' ),
				'param_name'  => 'button_text',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Style', 'mm-components' ),
				'param_name' => 'button_style',
				'value'      => $button_styles,
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
				'heading'    => __( 'Button Color', 'mm-components' ),
				'param_name' => 'button_color',
				'value'      => $button_colors,
			),
		),
	) );
}