<?php
/**
 * MIGHTYminnow Components
 *
 * Template Builder for Mm Posts.
 *
 * @package mm-components
 * @since   1.0.0
 */

class Mm_Posts_Template_Builder {

	public $slug;

	public $name;

	public $display_name;

	public $option_group;

	public function __construct() {

		$this->slug         = 'mm-posts-template-builder';
		$this->name         = 'mm_posts_template_builder';
		$this->display_name = __( 'Mm Posts Template Builder', 'mm-components' );
		$this->option_group = 'mm_posts_template_builder_options';
	}

	public function initialize() {

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function admin_enqueue_scripts( $hook ) {

		if ( 'settings_page_mm-posts-template-builder' == $hook ) {

			wp_enqueue_style(
				$this->slug,
				MM_COMPONENTS_URL . 'css/mm-posts-template-builder.css',
				array(),
				MM_COMPONENTS_VERSION
			);

			wp_enqueue_script(
				$this->slug,
				MM_COMPONENTS_URL . 'js/mm-posts-template-builder.js',
				array(
					'jquery',
					'jquery-ui-sortable',
					'jquery-ui-draggable',
					'jquery-ui-droppable'
				),
				MM_COMPONENTS_VERSION,
				true
			);
		}
	}

	public function admin_init() {

		register_setting(
			$this->option_group,
			$this->option_group,
			array( $this, 'sanitize_options' )
		);

		add_settings_section(
			$this->name . '_hidden_inputs',
			'',
			array( $this, 'hidden_inputs_section' ),
			$this->slug
		);

		add_settings_field(
			$this->name . '_template_json',
			'',
			array( $this, 'template_json_input' ),
			$this->slug,
			$this->name . '_hidden_inputs'
		);
	}

	public function admin_menu() {

		add_options_page(
			$this->display_name,
			$this->display_name,
			'manage_options',
			$this->slug,
			array( $this, 'template_builder_page' )
		);
	}

	public function template_builder_page() {

		$options = get_option( $this->option_group );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$templates_json = ( isset( $options['templates_json'] ) ) ? json_decode( $options['templates_json'] ) : '';

		if ( is_object( $templates_json ) && isset( $templates_json->templates ) ) {
			$templates = $templates_json->templates;
		} else {
			$templates = array();
		}

		$template_components = mm_get_mm_posts_template_components();

		ob_start(); ?>

		<div class="wrap <?php echo $this->slug . '-options-page' ?>">

			<h1><?php echo $this->display_name ?></h1>

			<form action="options.php" method="post">

				<?php settings_fields( $this->option_group ); ?>

				<?php do_settings_sections( $this->slug ); ?>

				<?php submit_button( __( 'Save Template', 'mm-components' ) ); ?>

			</form>

			<div class="template-builder-wrap">
				<div class="template-components-wrap postbox alternate">
					<ul class="template-components">
						<?php
						foreach ( $template_components as $component_key => $component_name ) {
							printf(
								'<li class="%s" data-key="%s">%s</li>',
								'template-component',
								esc_attr( $component_key ),
								esc_html( $component_name )
							);
						} ?>
					</ul>
				</div>
				<div class="template-drop-area-wrap postbox alternate">
					<div class="template-select-wrap">
						<?php _e( 'Template:', 'mm-components' ); ?>
						<select class="template-select">
							<?php
							foreach ( $templates as $template ) {
								printf(
									'<option value="%s">%s</option>',
									esc_attr( $template->name ),
									esc_html( $template->name )
								);
							} ?>
						</select>
					</div>
					<input class="current-template" value="template-name" />
					<div class="header-drop-area-wrap">
						<label><?php _e( 'Header', 'mm-components' ); ?></label>
						<div class="header-drop-area drop-area">
							<div class="placeholder"><?php _e( 'Drop Template Components here...', 'mm-components' ); ?></div>
						</div>
					</div>
					<div class="content-drop-area-wrap">
						<label><?php _e( 'Content', 'mm-components' ); ?></label>
						<div class="content-drop-area drop-area">
							<div class="placeholder"><?php _e( 'Drop Template Components here...', 'mm-components' ); ?></div>
						</div>
					</div>
					<div class="footer-drop-area-wrap">
						<label><?php _e( 'Footer', 'mm-components' ); ?></label>
						<div class="footer-drop-area drop-area">
							<div class="placeholder"><?php _e( 'Drop Template Components here...', 'mm-components' ); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php

		echo ob_get_clean();
	}

	public function hidden_inputs_section() {
		echo '<div>This is the hidden input section</div>';
	}

	public function template_json_input() {

		$options = get_option( $this->option_group );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$templates = ( isset( $options['templates_json'] ) ) ? $options['templates_json'] : '';

		printf(
			'<textarea id="%s" name="%s">%s</textarea>',
			esc_attr( $this->slug . '-template-json' ),
			esc_attr( $this->option_group . '[templates_json]' ),
			$templates
		);
	}

	public function sanitize_options( $options ) {

		return $options;
	}
}

// Only load in the admin.
if ( is_admin() ) {

	$mm_posts_template_builder = new Mm_Posts_Template_Builder();
	$mm_posts_template_builder->initialize();
}