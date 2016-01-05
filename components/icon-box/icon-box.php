<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Icon Box
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Icon Box component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_icon_box( $args ) {

	$component = 'mm-icon-box';

	// Set our defaults and use them as needed.
	$defaults = array(
		'icon_type'        => 'fontawesome',
		'icon_fontawesome' => '',
		'icon_openiconic'  => '',
		'icon_typicons'    => '',
		'icon_entypo'      => '',
		'icon_linecons'    => '',
		'icon_size'        => 'normal',
		'heading_text'     => '',
		'content'          => '',
		'link'             => '',
		'link_text'        => '',
		'link_title'       => '',
		'link_target'      => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$icon_type        = $args['icon_type'];
	$icon_fontawesome = $args['icon_fontawesome'];
	$icon_openiconic  = $args['icon_openiconic'];
	$icon_typicons    = $args['icon_typicons'];
	$icon_entypo      = $args['icon_entypo'];
	$icon_linecons    = $args['icon_linecons'];
	$icon_size        = $args['icon_size'];
	$heading_text     = $args['heading_text'];
	$content          = $args['content'];
	$link_url         = $args['link'];
	$link_title       = $args['link_title'];
	$link_target      = $args['link_target'];

	// Handle a VC link array.
	if ( 'url' === substr( $args['link'], 0, 3 ) && function_exists( 'vc_build_link' ) ) {
		$link_array  = vc_build_link( $args['link'] );
		$link_url    = $link_array['url'];
		$link_title  = $link_array['title'];
		$link_target = $link_array['target'];
	}

	// Fix wpautop issues in $content.
	if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
		$content = wpb_js_remove_wpautop( $content, true );
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Add icon size class.
	if ( $icon_size ) {
		$mm_classes .= ' icon-size-' . $icon_size;
	}

	// Get the icon classes.
	switch ( $icon_type ) {
		case 'fontawesome':
			$icon = ! empty( $icon_fontawesome ) ? $icon_fontawesome : 'fa fa-adjust';
			break;
		case 'openiconic':
			$icon = $icon_openiconic;
			break;
		case 'typicons':
			$icon = $icon_typicons;
			break;
		case 'entypo':
			$icon = $icon_entypo;
			break;
		case 'linecons':
			$icon = $icon_linecons;
			break;
		default:
			$icon = 'fa fa-adjust';
	}

	// Enqueue the icon font that we're using.
	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
		vc_icon_element_fonts_enqueue( $icon_type );
	}

	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes ); ?>">

		<i class="mm-icon <?php echo esc_attr( $icon ); ?>"></i>

		<?php if ( ! empty( $atts['heading_text'] ) ) : ?>
			<h3 class="icon-box-heading"><?php echo esc_html( $atts['heading_text'] ); ?></h3>
		<?php endif; ?>

		<?php if ( $content ) : ?>
			<div class="icon-box-content"><?php echo wp_kses_post( $content ); ?></div>
		<?php endif; ?>

		<?php if ( ! empty( $link_text ) && ! empty( $link_url ) ) {
			printf( '<a href="%s" title="%s" target="%s" class="icon-box-link">%s</a>',
				esc_url( $link_url ),
				esc_attr( $link_title ),
				esc_attr( $link_target ),
				esc_html( $atts['link_text'] )
			);
		} ?>

	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'mm_icon_box', 'mm_icon_box_shortcode' );
/**
 * Icon Box Shortcode.
 *
 * @since  1.0.0
 *
 * @param   array   $atts     Shortcode attributes.
 * @param   string  $content  Shortcode content.
 *
 * @return  string            Shortcode output.
 */
function mm_icon_box_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return mm_icon_box( $atts );
}

add_action( 'vc_before_init', 'mm_vc_icon_box' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_icon_box() {

	vc_map( array(
		'name'     => __( 'Icon Box', 'mm-components' ),
		'base'     => 'mm_icon_box',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'    => 'dropdown',
				'heading' => __( 'Icon library', 'mm-components' ),
				'value'   => array(
					__( 'Font Awesome', 'mm-components' ) => 'fontawesome',
					__( 'Open Iconic', 'mm-components' )  => 'openiconic',
					__( 'Typicons', 'mm-components' )     => 'typicons',
					__( 'Entypo', 'mm-components' )       => 'entypo',
					__( 'Linecons', 'mm-components' )     => 'linecons',
				),
				'param_name'  => 'icon_type',
				'description' => __( 'Select an icon library.', 'mm-components' ),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_fontawesome',
				'settings'   => array(
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array(
					'element' => 'icon_type',
					'value'   => 'fontawesome',
				),
				'description' => __( 'Select an icon from the library.', 'mm-components' ),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_openiconic',
				'settings'   => array(
					'emptyIcon'    => false,
					'type'         => 'openiconic',
					'iconsPerPage' => 200,
				),
				'dependency'  => array(
					'element' => 'icon_type',
					'value'   => 'openiconic',
				),
				'description' => __( 'Select an icon from the library.', 'mm-components' ),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_typicons',
				'settings'   => array(
					'emptyIcon'    => false,
					'type'         => 'typicons',
					'iconsPerPage' => 200,
				),
				'dependency'  => array(
					'element' => 'icon_type',
					'value'   => 'typicons',
				),
				'description' => __( 'Select an icon from the library.', 'mm-components' ),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_entypo',
				'settings'   => array(
					'emptyIcon'    => false,
					'type'         => 'entypo',
					'iconsPerPage' => 300,
				),
				'dependency'  => array(
					'element' => 'icon_type',
					'value'   => 'entypo',
				),
			),
			array(
				'type'       => 'iconpicker',
				'heading'    => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_linecons',
				'settings'   => array(
					'emptyIcon'    => false,
					'type'         => 'linecons',
					'iconsPerPage' => 200,
				),
				'dependency'  => array(
					'element' => 'icon_type',
					'value'   => 'linecons',
				),
				'description' => __( 'Select an icon from the library.', 'mm-components' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Icon Box Size', 'mm-components' ),
				'param_name'  => 'icon_size',
				'description' => __( 'Select a general size for the icon box', 'mm-components' ),
				'value'       => array(
					__( 'Normal', 'mm-components' ) => 'normal',
					__( 'Large', 'mm-components' )  => 'large',
					__( 'Small', 'mm-components' )  => 'small',
				),
			),
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
				'type'       => 'vc_link',
				'heading'    => __( 'Link URL', 'mm-components' ),
				'param_name' => 'link',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Link Text', 'mm-components' ),
				'param_name' => 'link_text',
			),
		)
	) );
}

add_action( 'register_shortcode_ui', 'mm_components_mm_icon_box_shortcode_ui' );
/**
 * Register UI for Shortcake.
 *
 * @since  1.0.0
*/
function mm_components_mm_icon_box_shortcode_ui() {

	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	shortcode_ui_register_for_shortcode(
		'mm_icon_box',
		array(
			'label'         => esc_html__( 'Mm Icon Box', 'mm-components' ),
			'listItemImage' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
			'attrs'         => array(
				array(
					'label'       => esc_html__( 'Icon library', 'mm-components' ),
					'description' => esc_html__( 'Select an icon library.', 'mm-components' ),
					'attr'        => 'icon_type',
					'type'        => 'select',
					'options'     => array(
						'fontawesome' => esc_html__( 'Font Awesome', 'mm-components' ),
						'openiconic'  => esc_html__( 'Open Iconic', 'mm-components' ),
						'typicons'    => esc_html__( 'Typicons', 'mm-components' ),
						'entypo'      => esc_html__( 'Entypo', 'mm-components' ),
						'linecons'    => esc_html__( 'Linecons', 'mm-components' ),
					),
				),
				array(
					'label'       => esc_html( 'Icon', 'mm-components' ),
					'description' => esc_html__( 'Inform an icon from the library.', 'mm-components' ),
					'attr'        => 'icon',
					'type'        => 'text',
				),
				array(
					'label'       => esc_html__( 'Icon Box Size', 'mm-components' ),
					'description' => esc_html__( 'Select a general size for the icon box', 'mm-components' ),
					'attr'        => 'icon_size',
					'type'        => 'select',
					'options'     => array(
						'normal' => esc_html__( 'Normal', 'mm-components' ),
						'large'  => esc_html__( 'Large', 'mm-components' ),
						'small'  => esc_html__( 'Small', 'mm-components' ),
					),
				),
				array(
					'label'       => esc_html( 'Heading', 'mm-components' ),
					'attr'        => 'heading_text',
					'type'        => 'text',
				),
				array(
					'label'       => esc_html__( 'Paragraph Text', 'mm-components' ),
					'attr'        => 'content',
					'type'        => 'textarea',
				),
				array(
					'label'       => esc_html__( 'Link URL', 'mm-components' ),
					'attr'        => 'link',
					'type'        => 'url',
				),
				array(
					'label'       => esc_html( 'Link Text', 'mm-components' ),
					'attr'        => 'link_text',
					'type'        => 'text',
				),
			),
		)
	);
}
