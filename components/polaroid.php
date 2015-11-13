<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Polaroid
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_polaroid', 'mm_polaroid_shortcode' );
/**
 * Output Polaroid.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_polaroid_shortcode( $atts, $content = null, $tag ) {

	$atts = mm_shortcode_atts( array(
		'title'        => '',
		'image'        => '',
		'author_image' => '',
		'link'         => '',
		'link_text'    => __( 'Visit campaign', 'mm-components' ),
		'link_target'  => '',
		'banner_text'  => '',
		'class'        => '',
	), $atts );

	// Fix wpautop issues in $content.
	if ( function_exists( 'wpb_js_remove_wpautop' ) ) {
		$content = wpb_js_remove_wpautop( $content, true );
	}

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

	// Get clean params.
	$title = $atts['title'];
	$image = $atts['image'];
	$author_image = $atts['author_image'];
	$link_text = $atts['link_text'];
	$banner_text = $atts['banner_text'];
	$class = $atts['class'];

	/**
	 * Parse images.
	 *
	 * These can be passed either as an attachment ID (VC method), or manually
	 * as a URL.
	 */

	// Main image.
	if ( is_numeric( $image ) ) {
		$image_array = wp_get_attachment_image_src( $image, 'polaroid' );
		$image = $image_array[0];
	}

	// Author image.
	if ( is_numeric( $author_image ) ) {
		$author_image_array = wp_get_attachment_image_src( $author_image, 'thumbnail' );
		$author_image = $author_image_array[0];
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $tag, $atts );
	$mm_classes .= ' ' . $class;

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">

		<?php if ( $title ) : ?>
			<h4><?php echo esc_html( $title ); ?></h4>
		<?php endif; ?>

		<div class="polaroid-wrap">
			<?php if ( $image ) : ?>
				<?php if ( ! empty( $link_url ) ) : ?>
					<a href="<?php echo esc_url( $link_url ); ?>" title="<?php echo esc_attr( $link_title ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
				<?php endif; ?>
				<img src="<?php echo esc_url( $image ); ?>" class="main-image" />
				<?php if ( ! empty( $link_url ) ) : ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>

			<div class="text-wrap">
				<?php if ( $author_image ) : ?>
					<img src="<?php echo esc_url( $author_image ); ?>" class="author-image" />
				<?php endif; ?>

				<?php if ( $content ) : ?>
					<?php echo wp_kses_post( $content ); ?>
				<?php endif; ?>

				<?php if ( ! empty( $link_url ) ) : ?>
					<a class="more-link" href="<?php echo esc_url( $link_url ); ?>" title="<?php echo esc_attr( $link_title ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_text ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $banner_text ) : ?>
			<div class="banner-front"><?php echo wp_kses_post( $banner_text ); ?></div>
			<div class="banner-back">
				<div class="banner-back-left"></div>
				<div class="banner-back-right"></div>
			</div>
		<?php endif; ?>

	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_polaroid' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_polaroid() {

	vc_map( array(
		'name' => __( 'Polaroid', 'mm-components' ),
		'base' => 'mm_polaroid',
		'class' => '',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'mm-components' ),
				'param_name' => 'title',
				'admin_label' => true,
				'value' => '',
			),
			array(
				'type' => 'attach_image',
				'heading' => __( 'Main Image', 'mm-components' ),
				'param_name' => 'image',
				'value' => '',
				'mm_image_size_for_desc' => 'polaroid',
			),
			array(
				'type' => 'attach_image',
				'heading' => __( 'Author Image', 'mm-components' ),
				'param_name' => 'author_image',
				'value' => '',
				'mm_image_size_for_desc' => 'thumbnail',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Link Text', 'mm-components' ),
				'param_name' => 'link_text',
				'value' => '',
				'description' => __( 'Defaults to "Visit campaign".', 'mm-components' )
			),
			array(
				'type' => 'vc_link',
				'heading' => __( 'Link URL', 'mm-components' ),
				'param_name' => 'link',
				'value' => '',
			),
			array(
				'type' => 'textarea_html',
				'heading' => __( 'Text', 'mm-components' ),
				'param_name' => 'content',
				'value' => '',
			)
		)
	) );
}
