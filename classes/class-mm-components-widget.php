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

	/**
	 * Output a select dropdown.
	 *
	 * @since  1.0.0
	 */
	public function field_select( $label = '', $classes = '', $key = '', $value = '', $options = array() ) {

		echo '<p><label>' . esc_html( $label ) . '</label>';

		printf(
			'<select class="%s" name="%s">',
			$classes,
			$this->get_field_name( $key )
		);

		// Test whether we have an associative or indexed array.
		if ( array_values( $options ) === $options ) {

			// We have an indexed array.
			foreach ( $options as $option ) {

				printf(
					'<option value="%s" %s>%s</option>',
					$option,
					selected( $value, $option, false ),
					$option
				);
			}

		} else {

			// We have an associative array.
			foreach ( $options as $option_value => $option_display_name ) {

				printf(
					'<option value="%s" %s>%s</option>',
					$option_value,
					selected( $value, $option_value, false ),
					$option_display_name
				);
			}
		}

		echo '</select>';

		echo '</p>';
	}

	/**
	 * Output a checkbox.
	 *
	 * @since  1.0.0
	 */
	public function field_checkbox( $label = '', $classes = '', $key = '', $value = '' ) {

		$val = (int)mm_true_or_false( $value );

		echo '<p>';

			printf(
				'<input type="checkbox" class="%s" name="%s" value="1" %s /> <label class="%s">%s</label><br />',
				$classes,
				$this->get_field_name( $key ),
				checked( $val, 1, false ),
				'radio-label',
				$label
			);

		echo '</p>';
	}

	/**
	 * Output a group of checkboxes.
	 *
	 * @since  1.0.0
	 */
	public function field_multi_checkbox( $label = '', $classes = '', $key = '', $value = '', $options = array() ) {

		if ( ! is_array( $value ) ) {
			$values = ( strpos( $value, ',' ) ) ? explode( ',', $value ) : (array)$value;
		} else {
			$values = $value;
		}

		echo '<p><label class="multi-checkbox-group-label">' . esc_html( $label ) . '</label><br />';

			echo '<span class="mm-multi-checkbox-wrap">';

			// Test whether we have an associative or indexed array.
			if ( array_values( $options ) === $options ) {

				// We have an indexed array.
				foreach ( $options as $option_value ) {

					$val = ( in_array( $option_value, $values ) ) ? 1 : 0;
					$option_display_name = ucwords( str_replace( '_', ' ', $option_value ) );

					printf(
						'<input type="checkbox" class="%s" value="%s" %s /> <label class="%s">%s</label><br />',
						esc_attr( $classes ),
						esc_attr( $option_value ),
						checked( $val, 1, false ),
						'multi-checkbox-label',
						esc_html( $option_display_name )
					);
				}

			} else {

				// We have an associative array.
				foreach ( $options as $option_value => $option_display_name ) {

					$val = ( in_array( $option_value, $values ) ) ? 1 : 0;

					printf(
						'<input type="checkbox" class="%s" value="%s" %s /> <label class="%s">%s</label><br />',
						esc_attr( $classes ),
						esc_attr( $option_value ),
						checked( $val, 1, false ),
						'multi-checkbox-label',
						esc_html( $option_display_name )
					);
				}
			}

			printf(
				'<input type="hidden" class="%s" name="%s" value="%s" />',
				'multi-checkbox-hidden-input',
				$this->get_field_name( $key ),
				esc_attr( (string)$value )
			);

			echo '</span>';

		echo '</p>';
	}

	/**
	 * Output a group of radio buttons.
	 *
	 * @since  1.0.0
	 */
	public function field_radio( $label = '', $classes = '', $key = '', $value = '', $options = array() ) {

		echo '<p><label class="radio-group-label">' . esc_html( $label ) . '</label><br />';

		// Test whether we have an associative or indexed array.
		if ( array_values( $options ) === $options ) {

			// We have an indexed array.
			foreach ( $options as $option ) {

				printf(
					'<input type="radio" class="%s" name="%s" value="%s" %s /> <label class="%s">%s</label><br />',
					$classes,
					$this->get_field_name( $key ),
					$option,
					checked( $value, $option, false ),
					'radio-option-label',
					$option
				);
			}

		} else {

			// We have an associative array.
			foreach ( $options as $option_value => $option_display_name ) {

				printf(
					'<input type="radio" class="%s" name="%s" value="%s" %s /> <label class="%s">%s</label><br />',
					$classes,
					$this->get_field_name( $key ),
					$option_value,
					checked( $value, $option_value, false ),
					'radio-option-label',
					$option_display_name
				);
			}
		}

		echo '</p>';
	}

	/**
	 * Output an alpha color picker.
	 *
	 * @since  1.0.0
	 */
	public function field_alpha_color_picker( $label = '', $classes = '', $key = '', $value = '', $palette = true, $default = '#222', $show_opacity = true ) {

		// Process the palette.
		if ( is_array( $palette ) ) {
			$palette = implode( '|', $palette );
		} else {
			$palette = ( mm_true_or_false( $palette ) ) ? 'true' : 'false';
		}

		$show_opacity = ( mm_true_or_false( $show_opacity ) ) ? 'true' : 'false';

		echo '<p><label>' . esc_html( $label ) . '</label><br />';

		printf(
			'<input class="%s" type="text" name="%s" value="%s" data-palette="%s" data-show-opacity="%s" data-default-color="%s" />',
			esc_attr( $classes ) . ' alpha-color-picker',
			$this->get_field_name( $key ),
			esc_attr( $value ),
			esc_attr( $palette ),
			esc_attr( $show_opacity ),
			esc_attr( $default )
		);

		echo '</p>';
	}

	/**
	 * Output a single media item upload field.
	 *
	 * @since  1.0.0
	 */
	public function field_single_media( $label = '', $classes = '', $key = '', $value = '' ) {

		if ( is_int( $value ) ) {
			$image = wp_get_attachment_image_src( $value, 'large' )[0];
		} else {
			$image = '';
		}

		echo '<p><label>' . esc_html( $label ) . '</label><br />';

		?>
		<span class="mm-single-media-wrap">
			<span class="mm-single-media-image-preview-wrap <?php echo ( empty( $value ) ) ? 'no-image' : ''; ?>">
				<span class="mm-single-media-no-image"><?php _e( 'No File Selected', 'mm-components' ); ?></span>
				<img class="mm-single-media-image-preview" src="<?php echo esc_url( $image ); ?>" title="<?php _e( 'Media Item', 'mm-components' ); ?>" alt="<?php _e( 'Media Item', 'mm-components' ); ?>" />
			</span>
			<input type="hidden" name="<?php echo $this->get_field_name( $key ); ?>" class="mm-single-media-image" class="regular-text" value="<?php echo esc_attr( $value ); ?>" />
			<input type="button" name="upload-btn" class="upload-btn button-secondary" value="<?php _e( 'Select Image', 'mm-components' ); ?>" />
			<input type="button" name="clear-btn" class="clear-btn button-secondary" value="<?php _e( 'Clear', 'mm-components' ); ?>" />
		</span>
		<?php

		echo '</p>';
	}

	/**
	 * Output a multiple media items upload field.
	 *
	 * @since  1.0.0
	 */
	public function field_multi_media( $label = '', $classes = '', $key = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$image_ids = explode( ',', $value );
			$images    = array();
			foreach ( $image_ids as $image_id ) {
				$images[ $image_id ] = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
			}
		} else {
			$images = array();
		}

		echo '<p><label>' . esc_html( $label ) . '</label><br />';

		?>
		<span class="mm-multi-media-wrap">
			<span class="mm-multi-media-images-preview-wrap <?php echo ( empty( $value ) ) ? 'no-images' : ''; ?>">
				<span class="mm-multi-media-no-images"><?php _e( 'No Files Selected', 'mm-components' ); ?></span>
				<?php
				if ( ! empty( $images ) ) {

					foreach ( $images as $image_id => $image_url ) {
						printf(
							'<img src="%s" class="%s" title="%s" alt="%s" />',
							esc_url( $image_url ),
							'mm-multi-media-images-preview',
							__( 'Media Items', 'mm-components' ),
							__( 'Media Items', 'mm-components' )
						);
					}
				}
				?>
			</span>
			<input type="hidden" name="<?php echo $this->get_field_name( $key ); ?>" class="mm-multi-media-images" class="regular-text" value="<?php echo esc_attr( $value ); ?>" />
			<input type="button" name="upload-btn" class="upload-btn button-secondary" value="<?php _e( 'Select Images', 'mm-components' ); ?>" />
			<input type="button" name="clear-btn" class="clear-btn button-secondary" value="<?php _e( 'Clear', 'mm-components' ); ?>" />
		</span>
		<?php

		echo '</p>';
	}

}
