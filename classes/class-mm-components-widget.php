<?php
/**
 * Mm Components Widget Class.
 *
 * This class is designed for sub-classing. It extends the main WP_Widget
 * class with functions to output various field types.
 *
 * @since  1.0.0
 */
class Mm_Components_Widget extends WP_Widget {

	/**
	 * Initialize an instance of the parent class.
	 *
	 * @since  1.0.0
	 */
	public function __construct( $id_base, $name, $widget_options = array(), $control_options = array() ) {

		parent::__construct(
			$id_base,
			$name,
			$widget_options,
			$control_options
		);
	}

	/**
	 * Output a text input.
	 *
	 * @since  1.0.0
	 */
	public function field_text( $label = '', $classes = '', $key = '', $value = '' ) {
		
		echo '<p><label>' . esc_html( $label ) . '</label>';

		printf(
			'<input type="text" class="%s" name="%s" value="%s" />',
			$classes,
			$this->get_field_name( $key ),
			$value
		);

		echo '</p>';
	}

	/**
	 * Output a textarea input.
	 *
	 * @since  1.0.0
	 */
	public function field_textarea( $label = '', $classes = '', $key = '', $value = '', $rows = '4', $cols = '4' ) {
		
		echo '<p><label>' . esc_html( $label ) . '</label>';

		printf(
			'<textarea class="%s" name="%s" rows="%s" cols="%s">%s</textarea>',
			$classes,
			$this->get_field_name( $key ),
			$rows,
			$cols,
			$value
		);

		echo '</p>';
	}

}