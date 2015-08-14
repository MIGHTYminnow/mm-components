<?php
/**
 * Mm Components Visual Composer Functionality.
 *
 * @since 1.0.0
 *
 * @package mm-components
 */

/**
 * Apply custom classes to VC components.
 *
 * @since  1.0.0
 */
add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'mm_shortcode_custom_classes', 10, 3 );

add_action( 'init', 'mm_vc_custom_component_atts', 15 );
/**
 * Add shared Mm parameters/atts to all VC components.
 *
 * Uses priority 5 to ensure params are added before VC auto mapping,
 * which occurs on priority 10 in VC v4.5.2+.
 *
 * @since  1.0.0
 */
function mm_vc_custom_component_atts() {

	// Get all available VC components.
	$components = WPBMap::getShortCodes();

	// Create custom group title.
	$custom_group = __( 'Mm Custom Settings', 'mm-components' );

	// Text color.
	$atts[] = array(
		'type'       => 'dropdown',
		'heading'    => __( 'Text Color Scheme', 'mm-components' ),
		'param_name' => 'mm_class_text_color',
		'group' => $custom_group,
		'value' => array(
			__( 'Default', 'mm-components ') => '',
			__( 'Dark', 'mm-components ')    => 'dark',
			__( 'Light', 'mm-components ')   => 'light',
			__( 'Medium', 'mm-components ')  => 'medium',
		),
	);

	// Text alignment.
	$atts[] = array(
		'type'       => 'dropdown',
		'heading'    => __( 'Text Alignment', 'mm-components' ),
		'param_name' => 'mm_class_text_align',
		'group'      => $custom_group,
		'value' => array(
			__( 'Default', 'mm-components ') => '',
			__( 'Left', 'mm-components ')    => 'left',
			__( 'Center', 'mm-components ')  => 'center',
			__( 'Right', 'mm-components ')   => 'right',
		),
	);

	// Custom Class.
	$atts[] = array(
		'type'       => 'textfield',
		'heading'    => __( 'Custom Class', 'mm-components' ),
		'param_name' => 'mm_custom_class',
		'group'      => $custom_group,
	);

	// Add each param to each VC component.
	foreach ( $atts as $att ) {
		foreach ( $components as $component ) {
			vc_add_param( $component['base'], $att );
		}
	}
}

add_filter( 'vc_single_param_edit', 'mm_filter_vc_field_descriptions', 10, 2 );
/**
 * Add custom image upload description to VC fields.
 *
 * Note: makes use of the 'mm_image_size_for_desc' param for any image upload
 * fields, attempting to calculate 2x the image size being used and output
 * this in the field's description.
 *
 * @since     1.0.0
 *
 * @param     array    $param    Visual composer field array.
 * @param     mixed    $value    Field value.
 *
 * @return    array    $param    Updated field.
 */
function mm_filter_vc_field_descriptions( $param, $value ) {

	// Append custom description to image upload field.
	if ( 'attach_image' == $param['type'] || 'attach_images' == $param['type'] ) {
		$image_size = isset( $param['mm_image_size_for_desc'] ) ? $param['mm_image_size_for_desc'] : '';
		$custom_description = mm_custom_image_field_description( $image_size );
		$param['description'] = isset( $param['description'] ) ? $param['description'] . ' ' . $custom_description : $custom_description;
	}

	return $param;
}

add_action( 'vc_load_default_templates_action', 'mm_vc_register_demo_template' );
/**
 * Register a demo template that will include all of our Mm VC elements.
 *
 * @since  1.0.0
 */
function mm_vc_register_demo_template() {

	$data                 = array();
	$data['name']         = __( 'Mm Components Demo', 'mm-components' );
	$data['weight']       = 99;
  	$data['image_path']   = MM_COMPONENTS_ASSETS_URL . 'template_icon.png';
	$data['custom_class'] = 'mm_components_demo_template';
	$data['content']      = <<<CONTENT
		[vc_row][vc_column][vc_text_separator title="Blockquote"][mm_blockquote quote="This is a blockquote. This is the quote portion of the blockquote." citation="MIGHTYminnow"][mm_blockquote quote="This is a really long blockquote. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula tristique purus, sollicitudin accumsan libero congue nec. Donec in sapien elit. Ut tincidunt in risus sit amet elementum. Aliquam ullamcorper neque quis rhoncus interdum. Quisque convallis vel orci sit amet tincidunt." citation="This is a really long citation lorem ipsum dolor sit amet consectetur adipiscing elit"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Button"][mm_button alignment="center"]Button Text[/mm_button][/vc_column][/vc_row]
CONTENT;
  
	vc_add_default_templates( $data );
}