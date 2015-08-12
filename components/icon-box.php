<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Icon Box
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_icon_box', 'mm_icon_box_shortcode' );
/**
 * Output Icon Box.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_icon_box_shortcode( $atts, $content = null, $tag ) {

	$atts = mm_shortcode_atts( array(
		'icon_type'        => 'fontawesome',
		'icon_fontawesome' => '',
		'icon_openiconic'  => '',
		'icon_typicons'    => '',
		'icon_entypo'      => '',
		'icon_linecons'    => '',
		'icon_pixelicons'  => '',
		'heading_text'     => '',
		'link'             => '',
		'link_text'        => __( 'Read more', 'mm-components' ),
	), $atts );

	// Clean up content - this is necessary
	$content = wpb_js_remove_wpautop( $content, true );

	// Get link array [url, title, target]
	$link_array = vc_build_link( $atts['link'] );

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	// Get icon type.
	$icon_type = $atts['icon_type'];
	switch ( $icon_type ) {
		case 'fontawesome':
			$icon = ! empty( $atts['icon_fontawesome'] ) ? $atts['icon_fontawesome'] : 'fa fa-adjust';
			break;
		case 'openiconic':
			$icon = $atts['icon_openiconic'];
			break;
		case 'typicons':
			$icon = $atts['icon_typicons'];
			break;
		case 'entypo':
			$icon = $atts['icon_entypo'];
			break;
		case 'linecons':
			$icon = $atts['icon_linecons'];
			break;
		case 'pixelicons':
			$icon = $atts['icon_pixelicons'];
			break;
		default:
			$icon = 'fa fa-adjust';
	}

	// Enqueue the icon font that we're using.
	vc_icon_element_fonts_enqueue( $icon_type );

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">

		<i class="mm-icon <?php echo $icon; ?>"></i>

		<?php if ( ! empty( $atts['heading_text'] ) ) : ?>
			<h3><?php echo $atts['heading_text']; ?></h3>
		<?php endif; ?>

		<?php if ( $content ) : ?>
			<?php echo $content; ?>
		<?php endif; ?>

		<?php
		if ( ! empty( $atts['link_text'] ) && ! empty( $link_array['url'] ) ) {
			printf( '<a href="%s" title="%s" target="%s" class="more-link">%s</a>',
				$link_array['url'],
				$link_array['title'],
				$link_array['target'],
				$atts['link_text']
			);
		}
		?>

	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_icon_box' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_icon_box() {

	// $pixel_icons is needed because VC.
	global $pixel_icons;
	$pixel_icons = array(
		array( 'vc_pixel_icon vc_pixel_icon-alert' => 'Alert' ),
		array( 'vc_pixel_icon vc_pixel_icon-info' => 'Info' ),
		array( 'vc_pixel_icon vc_pixel_icon-tick' => 'Tick' ),
		array( 'vc_pixel_icon vc_pixel_icon-explanation' => 'Explanation' ),
		array( 'vc_pixel_icon vc_pixel_icon-address_book' => 'Address book' ),
		array( 'vc_pixel_icon vc_pixel_icon-alarm_clock' => 'Alarm clock' ),
		array( 'vc_pixel_icon vc_pixel_icon-anchor' => 'Anchor' ),
		array( 'vc_pixel_icon vc_pixel_icon-application_image' => 'Application Image' ),
		array( 'vc_pixel_icon vc_pixel_icon-arrow' => 'Arrow' ),
		array( 'vc_pixel_icon vc_pixel_icon-asterisk' => 'Asterisk' ),
		array( 'vc_pixel_icon vc_pixel_icon-hammer' => 'Hammer' ),
		array( 'vc_pixel_icon vc_pixel_icon-balloon' => 'Balloon' ),
		array( 'vc_pixel_icon vc_pixel_icon-balloon_buzz' => 'Balloon Buzz' ),
		array( 'vc_pixel_icon vc_pixel_icon-balloon_facebook' => 'Balloon Facebook' ),
		array( 'vc_pixel_icon vc_pixel_icon-balloon_twitter' => 'Balloon Twitter' ),
		array( 'vc_pixel_icon vc_pixel_icon-battery' => 'Battery' ),
		array( 'vc_pixel_icon vc_pixel_icon-binocular' => 'Binocular' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_excel' => 'Document Excel' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_image' => 'Document Image' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_music' => 'Document Music' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_office' => 'Document Office' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_pdf' => 'Document PDF' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_powerpoint' => 'Document Powerpoint' ),
		array( 'vc_pixel_icon vc_pixel_icon-document_word' => 'Document Word' ),
		array( 'vc_pixel_icon vc_pixel_icon-bookmark' => 'Bookmark' ),
		array( 'vc_pixel_icon vc_pixel_icon-camcorder' => 'Camcorder' ),
		array( 'vc_pixel_icon vc_pixel_icon-camera' => 'Camera' ),
		array( 'vc_pixel_icon vc_pixel_icon-chart' => 'Chart' ),
		array( 'vc_pixel_icon vc_pixel_icon-chart_pie' => 'Chart pie' ),
		array( 'vc_pixel_icon vc_pixel_icon-clock' => 'Clock' ),
		array( 'vc_pixel_icon vc_pixel_icon-fire' => 'Fire' ),
		array( 'vc_pixel_icon vc_pixel_icon-heart' => 'Heart' ),
		array( 'vc_pixel_icon vc_pixel_icon-mail' => 'Mail' ),
		array( 'vc_pixel_icon vc_pixel_icon-play' => 'Play' ),
		array( 'vc_pixel_icon vc_pixel_icon-shield' => 'Shield' ),
		array( 'vc_pixel_icon vc_pixel_icon-video' => 'Video' ),
	);

	vc_map( array(
		'name' => __( 'Icon Box', 'mm-components' ),
		'base' => 'mm_icon_box',
		'class' => '',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Icon library', 'mm-components' ),
				'value' => array(
					__( 'Font Awesome', 'mm-components' ) => 'fontawesome',
					__( 'Open Iconic', 'mm-components' ) => 'openiconic',
					__( 'Typicons', 'mm-components' ) => 'typicons',
					__( 'Entypo', 'mm-components' ) => 'entypo',
					__( 'Linecons', 'mm-components' ) => 'linecons',
					__( 'Pixel', 'mm-components' ) => 'pixelicons',
				),
				'param_name' => 'icon_type',
				'description' => __( 'Select icon library.', 'mm-components' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_fontawesome',
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => __( 'Select icon from library.', 'mm-components' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_openiconic',
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'openiconic',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => __( 'Select icon from library.', 'mm-components' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_typicons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'typicons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'typicons',
				),
				'description' => __( 'Select icon from library.', 'mm-components' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_entypo',
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'entypo',
					'iconsPerPage' => 300, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_linecons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'linecons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => __( 'Select icon from library.', 'mm-components' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'mm-components' ),
				'param_name' => 'icon_pixelicons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'pixelicons',
					'source' => $pixel_icons,
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'pixelicons',
				),
				'description' => __( 'Select icon from library.', 'mm-components' ),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Heading', 'mm-components' ),
				'param_name' => 'heading_text',
				'admin_label' => true,
			),
			array(
				'type' => 'textarea_html',
				'heading' => __( 'Paragraph Text', 'mm-components' ),
				'param_name' => 'content',
			),
			array(
				'type' => 'vc_link',
				'heading' => __( 'Link URL', 'mm-components' ),
				'param_name' => 'link',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Link Text', 'mm-components' ),
				'param_name' => 'link_text',
			),
		)
	) );
}
