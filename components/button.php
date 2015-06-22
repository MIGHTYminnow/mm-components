<?php
/**
 * MIGHTYminnow Add-ons
 *
 * Component: Mm Button
 *
 * @package mm-add-ons
 * @since   1.0.0
 */

add_shortcode( 'mm-button', 'mm_button_shortcode' );
/**
 * Output Mm Button.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_button_shortcode( $atts, $content = null, $tag ) {

   extract( shortcode_atts( array(
      'link'         => '',
      'class'        => '',
      'style'        => 'default',
      'border_style' => '',
      'color'        => '',
      'font_style'   => '',
      'size'   => '',
      'alignment'    => '',
   ), $atts ) );

   // Omit this as it adds extra <p> tags within the button.
   // Clean up content - this is necessary
   //$content = wpb_js_remove_wpautop( $content, true );

   // Get link array [url, title, target]
   $link_array = vc_build_link( $link );

   // Setup button URL
   $href = $link_array['url'];

   // Setup Classes
   $class = '';
   $class .= ' ' . $style;
   $class .= ' ' . $border_style;
   $class .= ' ' . $color;
   $class .= ' ' . $font_style;
   $class .= ' ' . $size;

   // Get Mm classes
   $mm_classes = 'button-container';
   $mm_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $mm_classes, $tag, $atts );

   $output = '<div class="'. $mm_classes . ' text-align-' . $alignment . '">[button href="' . $link_array['url'] . '" class="' . $class . '" title="' . $link_array['title'] . '" target="' . $link_array['target'] . '"]' . do_shortcode( $content ) . '[/button]</div>';

   return do_shortcode( $output );
}

add_action( 'vc_before_init', 'mm_vc_button' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_button() {
   vc_map( array(
      'name' => __( 'Button', 'mm-add-ons' ),
      'base' => 'mm-button',
      'class' => '',
      'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
      'category' => __( 'Content', 'mm-add-ons' ),
      'params' => array(
         array(
            'type' => 'vc_link',
            'heading' => __( 'Button URL', 'mm-add-ons' ),
            'param_name' => 'link',
            'value' => '',
         ),
         array(
            'type' => 'dropdown',
            'heading' => __( 'Button Style', 'mm-add-ons' ),
            'param_name' => 'style',
            'value' => array(
               __( 'Default (solid)', 'mm-add-ons ') => 'default',
               __( 'Ghost (transparent background, white border)', 'mm-add-ons ') => 'ghost',
               __( 'Text Only (transparent background, no border)', 'mm-add-ons ') => 'text-only',
            ),
         ),
         array(
            'type' => 'dropdown',
            'heading' => __( 'Border Style', 'mm-add-ons' ),
            'param_name' => 'border_style',
            'value' => array(
               __( 'Thin', 'mm-add-ons ') => 'thin',
               __( 'Thick', 'mm-add-ons ') => 'thick',
            ),
            'dependency' => array(
               'element' => 'style',
               'value' => array(
                  'ghost',
               ),
            ),
         ),
         array(
            'type' => 'dropdown',
            'heading' => __( 'Color', 'mm-add-ons' ),
            'param_name' => 'color',
            'value' => array(
               __( 'White', 'mm-add-ons ') => 'white',
               __( 'Pink', 'mm-add-ons ') => 'pink',
               __( 'Gray', 'mm-add-ons ') => 'gray',
            ),
            'dependency' => array(
               'element' => 'style',
               'value' => array(
                  'ghost',
               ),
            ),
         ),
         array(
            'type' => 'dropdown',
            'heading' => __( 'Button Weight', 'mm-add-ons' ),
            'param_name' => 'font_style',
            'value' => array(
               __( 'Default', 'mm-add-ons ') => 'default',
               __( 'Strong', 'mm-add-ons ') => 'strong',
               __( 'Soft', 'mm-add-ons ') => 'soft',
            ),
         ),
         array(
            'type' => 'dropdown',
            'heading' => __( 'Button Size', 'mm-add-ons' ),
            'param_name' => 'size',
            'value' => array(
               __( 'Normal', 'mm-add-ons ') => 'normal-size',
               __( 'Large', 'mm-add-ons ') => 'large',
            ),
         ),
         array(
            'type' => 'dropdown',
            'heading' => __( 'Button Alignment', 'mm-add-ons' ),
            'param_name' => 'alignment',
            'value' => array(
               __( 'Default', 'mm-add-ons ') => 'default',
               __( 'Left', 'mm-add-ons ') => 'left',
               __( 'Center', 'mm-add-ons ') => 'center',
               __( 'Right ', 'mm-add-ons ') => 'right',
            ),
         ),
         array(
            'type' => 'textarea_html',
            'heading' => __( 'Button Text', 'mm-add-ons' ),
            'param_name' => 'content',
            'admin_label' => true,
            'value' => '',
         ),
      )
   ) );
}