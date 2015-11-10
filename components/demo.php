<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Demo
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Demo component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_demo( $args ) {

    $component  = 'mm-demo';

    // Set our defaults and use them as needed.
    $defaults = array(
        'text_field'           => '',
        'text_area_field'      => '',
        'select_field'         => '',
        'checkbox_field'       => '',
        'multi_checkbox_field' => '',
        'radio_field'          => '',
        'alpha_color_field'    => '',
        'single_media_field'   => '',
        'multi_media_field'    => '',
        'custom_field'         => '',
    );
    $args = wp_parse_args( (array)$args, $defaults );

    // Get clean param values.
    $text_field            = $args['text_field'];
    $text_area_field       = $args['text_area_field'];
    $select_field          = $args['select_field'];
    $checkbox_field        = $args['checkbox_field'];
    $multi_checkbox_field  = $args['multi_checkbox_field'];
    $radio_field           = $args['radio_field'];
    $alpha_color_field     = $args['alpha_color_field'];
    $single_media_field    = $args['single_media_field'];
    $multi_media_field     = $args['multi_media_field'];
    $custom_field          = $args['custom_field'];

    // Get Mm classes.
    $mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

    ob_start(); ?>

    <div class="<?php echo $mm_classes; ?>">
        <ul>
            <li><?php echo __( 'Text Field:', 'mm-components' ) . ' ' . esc_html( $text_field ); ?></li>
            <li><?php echo __( 'Text Area Field:', 'mm-components' ) . ' ' . esc_html( $text_area_field ); ?></li>
            <li><?php echo __( 'Select Field:', 'mm-components' ) . ' ' . esc_html( $select_field ); ?></li>
            <li><?php echo __( 'Checkbox Field:', 'mm-components' ) . ' ' . esc_html( $checkbox_field ); ?></li>
            <li><?php echo __( 'Multi Checkbox Field:', 'mm-components' ) . ' ' . esc_html( $multi_checkbox_field ); ?></li>
            <li><?php echo __( 'Radio Field:', 'mm-components' ) . ' ' . esc_html( $radio_field ); ?></li>
            <li><?php echo __( 'Alpha Color Field:', 'mm-components' ) . ' ' . esc_html( $alpha_color_field ); ?></li>
            <li><?php echo __( 'Single Media Field:', 'mm-components' ) . ' ' . esc_html( $single_media_field ); ?></li>
            <li><?php echo __( 'Multi Media Field:', 'mm-components' ) . ' ' . esc_html( $multi_media_field ); ?></li>
            <li><?php echo __( 'Custom Field:', 'mm-components' ) . ' ' . esc_html( $custom_field ); ?></li>
        </ul>
    </div>

    <?php

    return ob_get_clean();
}

add_shortcode( 'mm_demo', 'mm_demo_shortcode' );
/**
 * Demo shortcode.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_demo_shortcode( $atts ) {

    return mm_demo( $atts );
}

add_action( 'widgets_init', 'mm_components_register_demo_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function mm_components_register_demo_widget() {

    register_widget( 'mm_demo_widget' );
}

/**
 * Demo widget.
 *
 * @since  1.0.0
 */
class Mm_Demo_Widget extends Mm_Components_Widget {

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
            'classname'   => 'mm-demo',
            'description' => __( 'A Demo', 'mm-components' ),
        );

        parent::__construct(
            'mm_demo_widget',
            __( 'Mm Demo', 'mm-components' ),
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
            'title'                => '',
            'text_field'           => '',
            'text_area_field'      => '',
            'select_field'         => '',
            'checkbox_field'       => '',
            'multi_checkbox_field' => '',
            'radio_field'          => '',
            'alpha_color_field'    => '',
            'single_media_field'   => '',
            'multi_media_field'    => '',
            'custom_field'         => '',
        );

        // Use our instance args if they are there, otherwise use the defaults.
        $instance = wp_parse_args( $instance, $defaults );

        // At this point all instance options have been sanitized.
        $title                = apply_filters( 'widget_title', $instance['title'] );
        $text_field           = $instance['text_field'];
        $text_area_field      = $instance['text_area_field'];
        $select_field         = $instance['select_field'];
        $checkbox_field       = $instance['checkbox_field'];
        $multi_checkbox_field = $instance['multi_checkbox_field'];
        $radio_field          = $instance['radio_field'];
        $alpha_color_field    = $instance['alpha_color_field'];
        $single_media_field   = $instance['single_media_field'];
        $multi_media_field    = $instance['multi_media_field'];
        $custom_field         = $instance['custom_field'];

        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo mm_demo( $instance );

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
            'title'                => '',
            'text_field'           => '',
            'text_area_field'      => '',
            'select_field'         => '',
            'checkbox_field'       => '',
            'multi_checkbox_field' => '',
            'radio_field'          => '',
            'alpha_color_field'    => '',
            'single_media_field'   => '',
            'multi_media_field'    => '',
            'custom_field'         => '',
            'demo_field'           => '',
        );

        // Use our instance args if they are there, otherwise use the defaults.
        $instance = wp_parse_args( $instance, $defaults );

        $title                = $instance['title'];
        $text_field           = $instance['text_field'];
        $text_area_field      = $instance['text_area_field'];
        $select_field         = $instance['select_field'];
        $checkbox_field       = $instance['checkbox_field'];
        $multi_checkbox_field = $instance['multi_checkbox_field'];
        $radio_field          = $instance['radio_field'];
        $alpha_color_field    = $instance['alpha_color_field'];
        $single_media_field   = $instance['single_media_field'];
        $multi_media_field    = $instance['multi_media_field'];
        $custom_field         = $instance['custom_field'];
        $classname            = $this->options['classname'];

        // Text.
        $this->field_text(
            __( 'Text Field', 'mm-components' ),
            '',
            $classname . '-text widefat',
            'text_field',
            $text_field
        );

        // Text Area.
        $this->field_textarea(
            __( 'Text Area Field', 'mm-components' ),
            '',
            $classname . '-text-area widefat',
            'text_area_field',
            $text_area_field
        );

        // Dropdown Select.
        $this->field_select(
            __( 'Select Field', 'mm-components' ),
            '',
            $classname . '-select widefat',
            'select_field',
            $select_field,
            array(
                __( 'Option 1', 'mm-components' ),
                __( 'Option 2', 'mm-components' ),
                __( 'Option 3', 'mm-components' ),
            )
        );

        // Checkbox.
        $this->field_checkbox(
            __( 'Checkbox Field', 'mm-components' ),
            '',
            $classname . '-checkbox widefat',
            'checkbox_field',
            $checkbox_field
        );

        // Multi Checkbox.
        $this->field_multi_checkbox(
            __( 'Multi Checkbox Field', 'mm-components' ),
            '',
            $classname . '-multi-checkbox widefat',
            'multi_checkbox_field',
            $multi_checkbox_field,
            array(
                0 => __( 'Option 1', 'mm-components' ),
                1 => __( 'Option 2', 'mm-components' ),
                2 => __( 'Option 3', 'mm-components' ),
            )
        );

        // Radio.
        $this->field_radio(
            __( 'Radio Field', 'mm-components' ),
            '',
            $classname . '-radio widefat',
            'radio_field',
            $radio_field,
                array(
                0 => __( 'Option 1', 'mm-components' ),
                1 => __( 'Option 2', 'mm-components' ),
                2 => __( 'Option 3', 'mm-components' ),
            )
        );

        // Alpha Color Picker.
        $this->field_alpha_color_picker(
            __( 'Alpha Color Field', 'mm-components' ),
            '',
            $classname . '-alpha-color-field',
            'alpha_color_field',
            $alpha_color_field,
            array(
                'default'      => '#00CC99',
                'show_opacity' => 'true',
                'palette'      => array(
                    'rgba(255, 0, 0, 0.7)',
                    'rgba(54, 0, 170, 0.8)',
                    '#FFCC00',
                    'rgba( 20, 20, 20, 0.8 )',
                    '#00CC77',
                )
            )
        );

        // Single Media Upload.
        $this->field_single_media(
            __( 'Single Media Field', 'mm-components' ),
            '',
            $classname . '-single-media-field',
            'single_media_field',
            $single_media_field
        );

        // Multi Media Upload.
        $this->field_multi_media(
            __( 'Multi Media Field', 'mm-components' ),
            '',
            $classname . '-multi-media-field',
            'multi_media_field',
            $multi_media_field
        );

        // Custom Field.
        $this->field_custom(
            __( 'Custom Field', 'mm-components' ),
            '',
            __( 'This custom field can support <em>any</em> html.', 'mm-components')
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
     * @return  array  The sanitized settings.
     */
    public function update( $new_instance, $old_instance ) {

        $instance                         = $old_instance;
        $instance['title']                = wp_kses_post( $new_instance['title'] );
        $instance['text_field']           = wp_kses_post( $new_instance['text_field'] );
        $instance['text_area_field']      = wp_kses_post( $new_instance['text_area_field'] );
        $instance['select_field']         = sanitize_text_field( $new_instance['select_field'] );
        $instance['checkbox_field']       = sanitize_text_field( $new_instance['checkbox_field'] );
        $instance['multi_checkbox_field'] = sanitize_text_field( $new_instance['multi_checkbox_field'] );
        $instance['radio_field']          = sanitize_text_field( $new_instance['radio_field'] );
        $instance['alpha_color_field']    = sanitize_text_field( $new_instance['alpha_color_field'] );
        $instance['single_media_field']   = ( ! empty( $new_instance['single_media_field'] ) ) ? (int)$new_instance['single_media_field'] : '';
        $instance['multi_media_field']    = $new_instance['multi_media_field'];
        $instance['custom_field']         = $new_instance['custom_field'];

        return $instance;
    }
}