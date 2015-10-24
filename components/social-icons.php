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
		'icon_type'       => 'fontawesome',
		'image_size'      => 'thumbnail',
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
	$social_networks = mm_get_social_networks();

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	ob_start() ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">

		<?php foreach ( $social_networks as $social_network_name => $social_network ) {

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

	$social_icons_types = mm_get_mm_social_icons_types();
	$image_sizes = mm_get_image_sizes_for_vc();
	$social_networks = mm_get_social_networks();

	vc_map( array(
		'name'     => __( 'Social Icons', 'mm-components' ),
		'base'     => 'mm_social_icons',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Icon Type', 'mm-components' ),
				'param_name' => 'icon_type',
				'value' => $social_icons_types,
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
