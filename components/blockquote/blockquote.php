<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Blockquote
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Blockquote component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_blockquote( $args ) {

	$component  = 'mm-blockquote';

	// Set our defaults and use them as needed.
	$defaults = array(
		'content'              => '',
		'citation'             => '',
		'citation_link'        => '',
		'citation_link_title'  => '',
		'citation_link_target' => '_self',
		'citation_link_text'   => '',
		'image_id'             => '',
		'template'             => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$quote                = $args['content'];
	$citation             = $args['citation'];
	$citation_link        = $args['citation_link'] == '||' ? '' : $args['citation_link'];
	$citation_link_target = $args['citation_link_target'];
	$citation_link_title  = $args['citation_link_title'];
	$citation_link_text   = $args['citation_link_text'];
	$image_id             = $args['image_id'];
	$template             = $args['template'];

	// Handle a VC link array.
	if ( 'url' === substr( $args['citation_link'], 0, 3 ) && function_exists( 'vc_build_link' ) ) {
		$link_array           = vc_build_link( $args['citation_link'] );
		$citation_link        = $link_array['url'];
		$citation_link_target = $link_array['target'];
		$citation_link_title  = $link_array['title'];
	}

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	if ( $template ) {
		$mm_classes = "$mm_classes $template";
	}

	ob_start();

	$image_output = wp_get_attachment_image( (int)$image_id, 'thumbnail' );

	?>

	<div class="mm-blockquote-wrapper <?php echo esc_attr( $mm_classes ); ?>">

		<?php if( 'image-left' === $template ) {
			if ( 0 !== (int)$image_id ) {
				echo $image_output;
			}
		} ?>

		<blockquote>

			<?php if( '' === $template ) {
				if ( 0 !== (int)$image_id ) {
					echo $image_output;
				}
			} ?>

			<p><?php echo wp_kses_post( $quote ); ?></p>

			<?php if ( ! empty( $citation ) ) {

				if ( ! empty( $citation_link ) && ! empty( $citation_link_text ) ) {
					printf( '<cite>%s<a href="%s" title="%s" target="%s">%s</a></cite>',
						esc_html( $citation ),
						esc_url( $citation_link ),
						esc_attr( $citation_link_title ),
						esc_attr( $citation_link_target ),
						esc_html( $citation_link_text )
						);
				} elseif ( ! empty( $citation_link ) ) {
					printf( '<cite><a href="%s" title="%s" target="%s">%s</a></cite>',
						esc_url( $citation_link ),
						esc_attr( $citation_link_title ),
						esc_attr( $citation_link_target ),
						esc_html( $citation )
						);
				} else {
					echo '<cite>';
					echo esc_html( $citation );
					echo '</cite>';
				}

			}

			?>

		</blockquote>

	</div>

	<?php return ob_get_clean();

}

add_shortcode( 'mm_blockquote', 'mm_blockquote_shortcode' );
/**
 * Blockquote shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_blockquote_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['content'] = $content;
	}

	return mm_blockquote( $atts );
}

add_action( 'vc_before_init', 'mm_vc_blockquote' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_blockquote() {

	$templates      = mm_get_mm_blockquote_templates_for_vc( 'mm-blockquote' );

	vc_map( array(
		'name'     => __( 'Blockquote', 'mm-components' ),
		'base'     => 'mm_blockquote',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Template', 'mm-components' ),
				'param_name'  => 'template',
				'description' => __( 'Select a custom template for custom output', 'mm-components' ),
				'value'       => $templates,
			),
			array(
				'type'        => 'attach_image',
				'heading'     => __( 'Image', 'mm-components' ),
				'param_name'  => 'image_id',
				'description' => __( 'Select an image from the library.', 'mm-components' ),
			),
			array(
				'type'       => 'textarea_html',
				'heading'    => __( 'Quote', 'mm-components' ),
				'param_name' => 'content',
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Citation', 'mm-components' ),
				'param_name' => 'citation',
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Link Citation?', 'mm-components' ),
				'param_name' => 'citation_link_option',
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Citation URL', 'mm-components' ),
				'param_name' => 'citation_link',
				'dependency' => array(
					'element'   => 'citation_link_option',
					'not_empty' => true,
				),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Citation URL Text', 'mm-components' ),
				'param_name' => 'citation_link_text',
				'dependency' => array(
					'element'   => 'citation_link_option',
					'not_empty' => true,
				),
			),
		)
	) );
}

add_action( 'register_shortcode_ui', 'mm_components_mm_blockquote_shortcode_ui' );
/**
 * Register UI for Shortcake.
 *
 * @since  1.0.0
 */
function mm_components_mm_blockquote_shortcode_ui() {

	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	$templates    = mm_get_mm_blockquote_templates_for_vc( 'mm-blockquote' );
	$link_targets = mm_get_link_targets( 'mm-blockquote' );

	shortcode_ui_register_for_shortcode(
		'mm_blockquote',
		array(
			'label'         => esc_html__( 'Mm Blockquote', 'mm-components' ),
			'listItemImage' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
			'attrs'         => array(
				array(
					'label'       => esc_html__( 'Image', 'mm-components' ),
					'attr'        => 'image_id',
					'type'        => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => esc_html__( 'Select Image', 'mm-components' ),
					'frameTitle'  => esc_html__( 'Select Image', 'mm-components' ),
				),
				array(
					'label'   => esc_html( 'Template', 'mm-components' ),
					'attr'    => 'templates',
					'type'    => 'select',
					'options' => $templates,
				),
				array(
					'label' => esc_html__( 'Quote', 'mm-components' ),
					'attr'  => 'content',
					'type'  => 'textarea',
				),
				array(
					'label' => esc_html__( 'Citation', 'mm-components' ),
					'attr'  => 'citation',
					'type'  => 'text',
				),
				array(
					'label' => esc_html__( 'Citation URL', 'mm-components' ),
					'attr'  => 'citation_link',
					'type'  => 'url',
				),
				array(
					'label' => esc_html( 'Citation Link Title', 'mm-components' ),
					'attr'  => 'citation_link_title',
					'type'  => 'text',
				),
				array(
					'label' => esc_html( 'Citation URL Text', 'mm-components' ),
					'attr'  => 'citation_link_text',
					'type'  => 'text',
				),
				array(
					'label'   => esc_html( 'Citation Link Target', 'mm-components' ),
					'attr'    => 'citation_link_target',
					'type'    => 'select',
					'options' => $link_targets,
				),
			),
		)
	);
}

add_action( 'widgets_init', 'mm_components_register_blockquote_widget' );
/**
 * Register the widget.
 *
 * @since  1.0.0
*/
function mm_components_register_blockquote_widget() {

	register_widget( 'mm_blockquote_widget' );
}

/**
 * Blockquote widget.
 *
 * @since  1.0.0
 */
class Mm_Blockquote_Widget extends Mm_Components_Widget {

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
			'classname'   => 'mm-blockquote',
			'description' => __( 'A Blockquote', 'mm-components' ),
		);

		parent::__construct(
			'mm_blockquote_widget',
			__( 'Mm Blockquote', 'mm-components' ),
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
			'title'              => '',
			'content'            => '',
			'citation'           => '',
			'image_id'           => '',
			'citation_link_text' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// Grab the title and run it through the right filter.
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo mm_blockquote( $instance );

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
			'title'              => '',
			'template'           => '',
			'content'            => '',
			'citation'           => '',
			'link_citation'      => '',
			'image_id'           => '',
			'citation_link'      => '',
			'citation_link_text' => '',
		);

		// Use our instance args if they are there, otherwise use the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		$title              = $instance['title'];
		$template           = $instance['template'];
  		$quote              = $instance['content'];
		$citation           = $instance['citation'];
		$link_citation      = $instance['link_citation'];
		$citation_link      = $instance['citation_link'];
		$citation_link_text = $instance['citation_link_text'];
		$image_id           = $instance['image_id'];
		$classname          = $this->options['classname'];

		$templates = mm_get_mm_blockquote_templates( 'mm-blockquote' );

		// Title.
		$this->field_text(
			__( 'Title', 'mm-components' ),
			'',
			$classname . '-title widefat',
			'title',
			$title
		);

		// Template
		$this->field_select(
			__( 'Template', 'mm-components' ),
			'',
			$classname . '-template widefat',
			'template',
			$template,
			$templates
		);

		// Quote.
		$this->field_textarea(
			__( 'Quote', 'mm-components' ),
			'',
			$classname . '-quote widefat',
			'content',
			$quote
		);

		// Citation.
		$this->field_text(
			__( 'Citation', 'mm-components' ),
			'',
			$classname . '-citation widefat',
			'citation',
			$citation
		);

		$this->field_checkbox(
			__( 'Link citation?', 'mm-components' ),
			'',
			$classname . '-link-citation widefat',
			'link_citation',
			$link_citation
		);

		// Citation Link.
		$this->field_text(
			__( 'Citation Link', 'mm-components' ),
			'',
			$classname . '-citation-link widefat',
			'citation_link',
			$citation_link
		);

		// Citation URL
		$this->field_text(
			__( 'Citation Link Text', 'mm-components' ),
			'',
			$classname . '-citation-link-text widefat',
			'citation_link_text',
			$citation_link_text
		);

		// Image.
		$this->field_single_media(
			__( 'Image', 'mm-components' ),
			__( 'Select an image from the library.', 'mm-components'),
			$classname . '-image widefat',
			'image_id',
			$image_id
		);
	}

	/**
	 * Update the widget settings.
	 *
	 * @since   1.0.0
	 *
	 * @param   array  $new_instance  The new settings for the widget instance.
	 * @param   array  $old_instance  The old settings for the widget instance.
	 *
	 * @return  array  The sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                       = $old_instance;
		$instance['title']              = sanitize_text_field( $new_instance['title'] );
		$instance['template']           = sanitize_text_field( $new_instance['template'] );
		$instance['content']            = wp_kses_post( $new_instance['content'] );
		$instance['citation']           = sanitize_text_field( $new_instance['citation'] );
		$instance['link_citation']      = sanitize_text_field( $new_instance['link_citation'] );
		$instance['citation_link']      = ( '' !== $new_instance['citation_link'] ) ? esc_url( $new_instance['citation_link'] ) : '';
		$instance['citation_link_text'] = sanitize_text_field( $new_instance['citation_link_text'] );
		$instance['image_id']           = ( ! empty( $new_instance['image_id'] ) ) ? intval( $new_instance['image_id'] ) : '';

		return $instance;
	}
}
