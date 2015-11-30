<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Social Icons
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Social Icons component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_social_icons( $args ) {

	$component  = 'mm-social-icons';

	// Set our defaults and use them as needed.
	$defaults = array(
		'icon_type'  => 'fontawesome',
		'image_size' => 'thumbnail',
		'alignment'  => 'left',
		'style'      => 'icon-only',
		'ghost'      => '',
		'color'      => '',
		'size'       => 'normal-size',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Allow custom output to be used.
	$custom_output = apply_filters( 'mm_social_icons_custom_output', '', $args );

	if ( '' !== $custom_output ) {
		return $custom_output;
	}

	// Get clean param values.
	$icon_type       = $args['icon_type'];
	$image_size      = $args['image_size'];
	$alignment       = $args['alignment'];
	$style           = $args['style'];
	$ghost           = $args['ghost'];
	$color           = $args['color'];
	$size            = $args['size'];
	$social_networks = mm_get_social_networks( 'mm-social-icons' );

	// Set up the icon classes.
	$classes = array();

	if ( 'images' == $icon_type ) {
		$classes[] = 'images';
		$alignment = 'mm-image-align-' .$args['alignment'];
	} else {
		$classes[] = 'icons';
		$alignment = 'mm-text-align-' . $args['alignment'];
	}
	if ( ! empty( $style ) ) {
		$classes[] = $style;
	}
	if ( mm_true_or_false( $ghost ) ) {
		$classes[] = 'ghost';
	}
	if ( ! empty( $color ) ) {
		$classes[] = $color;
	}
	if ( ! empty( $size ) ) {
		$classes[] = $size;
	}

	$classes = implode( ' ', $classes );

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Combine Mm classes with icon classes.
	$mm_classes = $mm_classes . ' ' . $classes . ' ' . $alignment;

	ob_start() ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">

		<?php foreach ( $social_networks as $social_network => $social_network_name ) {

			$link = ( isset( $args[ $social_network . '_link' ] ) ) ? $args[ $social_network . '_link' ] : '';
			$image = ( isset( $args[ $social_network . '_image' ] ) ) ? $args[ $social_network . '_image' ] : '';

			if ( $link ) {

				$icon = ( 'images' == $icon_type && (int)$image ) ? wp_get_attachment_image( (int)$image, $image_size ) : '<i class="icon fa fa-' . esc_attr( $social_network ) . '"></i>';

				printf(
					'<a href="%s" class="%s">%s</a>',
					esc_url( $link ),
					esc_attr( $social_network . '-link' ),
					$icon
				);
			}
		} ?>

	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_social_icons', 'mm_social_icons_shortcode' );
/**
 * Social Icons shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_social_icons_shortcode( $atts ) {

	return mm_social_icons( $atts );
}

add_filter( 'mm_social_icons_types', 'mm_social_icons_register_core_types', 0 );
/**
 * Register our core types.
 *
 * @since  1.0.0
 *
 * @param   array  The old array of types.
 *
 * @return  array  The new array of types.
 */
function mm_social_icons_register_core_types( $types ) {

	$types = (array)$types;

	$types[ __( 'Font Awesome', 'mm-components' ) ] = 'fontawesome';
	$types[ __( 'Images', 'mm-components' ) ] = 'images';

	return $types;
}

add_action( 'vc_before_init', 'mm_vc_social_icons' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_social_icons() {

	$social_icons_types = mm_get_mm_social_icons_types( 'mm-social-icons' );
	$image_sizes        = mm_get_image_sizes_for_vc( 'mm-social-icons' );
	$text_alignment     = mm_get_text_alignment_for_vc( 'mm-social-icons' );
	$colors             = mm_get_colors_for_vc( 'mm-social-icons' );
	$social_networks    = mm_get_social_networks_for_vc( 'mm-social-icons' );

	// Add our brand colors option.
	$brand_colors = array(
		__( 'Brand Colors', 'mm-components' ) => 'brand-colors'
	);
	$colors = array_merge( $colors, $brand_colors );

	vc_map( array(
		'name'     => __( 'Social Icons', 'mm-components' ),
		'base'     => 'mm_social_icons',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Type', 'mm-components' ),
				'param_name' => 'icon_type',
				'value'      => $social_icons_types,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Image Size', 'mm-components' ),
				'param_name' => 'image_size',
				'value'      => $image_sizes,
				'dependency' => array(
					'element' => 'icon_type',
					'value'   => 'images',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Alignment', 'mm-components' ),
				'param_name' => 'alignment',
				'value'      => $text_alignment,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Style', 'mm-components' ),
				'param_name' => 'style',
				'value'      => array(
					__( 'Icon Only', 'mm-components' )      => '',
					__( 'Circle', 'mm-components' )         => 'circle',
					__( 'Square', 'mm-components' )         => 'square',
					__( 'Rounded Square', 'mm-components' ) => 'rounded-square',
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value'   => 'fontawesome',
				),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Ghost Mode?', 'mm-components' ),
				'param_name' => 'ghost',
				'description' => __( 'Colored icon and icon border with a transparent background', 'mm-components' ),
				'value'      => array(
					__( 'Yes' ) => 1,
				),
				'dependency' => array(
					'element' => 'style',
					'value'   => array(
						'circle',
						'square',
						'rounded-square',
					),
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Color', 'mm-components' ),
				'param_name' => 'color',
				'value'      => $colors,
				'dependency' => array(
					'element' => 'icon_type',
					'value'   => 'fontawesome',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Icon Size', 'mm-components' ),
				'param_name' => 'size',
				'value'      => array(
					__( 'Normal', 'mm-components' ) => '',
					__( 'Small', 'mm-components' )  => 'small',
					__( 'Large', 'mm-components' )  => 'large',
				),
			),
		)
	) );

	$social_network_params = array();

	foreach ( $social_networks as $social_network_label => $social_network ) {

		$social_network_params[] = array(
			'type'       => 'textfield',
			'heading'    => $social_network_label . ' ' . __( 'Link', 'mm-components' ),
			'param_name' => $social_network . '_link',
			'value'      => '',
		);

		$social_network_params[] = array(
			'type'        => 'attach_image',
			'heading'     => $social_network_label . ' ' . __( 'Image', 'mm-components' ),
			'param_name'  => $social_network . '_image',
			'dependency' => array(
				'element' => 'icon_type',
				'value'   => 'images',
			)
		);
	}

	vc_add_params( 'mm_social_icons', $social_network_params );
}
