<?php
/**
 * Native custom fields (no ACF): registers post meta declared in
 * config/meta/*.php and renders a generic admin meta box for them.
 *
 * Config shape:
 *
 *   return array(
 *       'post_type' => 'lembaga',           // Or array( 'lembaga', 'post' ).
 *       'title'     => 'Detail lembaga',    // Meta box title.
 *       'context'   => 'side',              // Optional: normal|side|advanced. Default normal.
 *       'fields'    => array(
 *           'tahun_berdiri' => array(
 *               'label'       => 'Tahun berdiri',
 *               'type'        => 'number',   // See KELURAHAN_FIELD_TYPES.
 *               'default'     => '',
 *               'description' => 'Shown under the input.',
 *               'options'     => array(),    // select only: value => label.
 *           ),
 *       ),
 *   );
 *
 * Every field is registered with show_in_rest, so it is:
 *   - readable/writable via REST (`meta` key on the post),
 *   - bindable in block templates with the core/post-meta Block Bindings source,
 *   - readable in PHP with kelurahan_meta( 'tahun_berdiri' ).
 *
 * Meta keys must NOT start with "_" (protected keys cannot be block-bound).
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Supported field types => [ meta type, sanitize callback ].
 */
const KELURAHAN_FIELD_TYPES = array(
	'text'     => array( 'string', 'sanitize_text_field' ),
	'textarea' => array( 'string', 'sanitize_textarea_field' ),
	'url'      => array( 'string', 'esc_url_raw' ),
	'email'    => array( 'string', 'sanitize_email' ),
	'date'     => array( 'string', 'kelurahan_sanitize_date' ),
	'time'     => array( 'string', 'kelurahan_sanitize_time' ),
	'select'   => array( 'string', 'sanitize_key' ),
	'number'   => array( 'number', 'kelurahan_sanitize_number' ),
	'integer'  => array( 'integer', 'intval' ),
	'checkbox' => array( 'boolean', 'rest_sanitize_boolean' ),
);

/**
 * Return all meta configs, normalized: post_type always an array.
 *
 * @return array<string, array>
 */
function kelurahan_meta_configs(): array {
	static $configs = null;

	if ( null === $configs ) {
		$configs = array();
		foreach ( kelurahan_load_configs( 'meta' ) as $name => $config ) {
			$config['post_type'] = (array) ( $config['post_type'] ?? array() );
			$config['fields']    = $config['fields'] ?? array();
			$configs[ $name ]    = $config;
		}
	}

	return $configs;
}

add_action( 'init', 'kelurahan_register_meta', 20 );

/**
 * Register every configured field with register_post_meta().
 */
function kelurahan_register_meta(): void {
	foreach ( kelurahan_meta_configs() as $name => $config ) {
		foreach ( $config['fields'] as $key => $field ) {
			$type = $field['type'] ?? 'text';

			if ( ! isset( KELURAHAN_FIELD_TYPES[ $type ] ) ) {
				_doing_it_wrong( __FUNCTION__, esc_html( "config/meta/{$name}.php: unknown field type '{$type}' for '{$key}'." ), '0.1.0' );
				continue;
			}

			list( $meta_type, $sanitize ) = KELURAHAN_FIELD_TYPES[ $type ];

			if ( 'select' === $type && ! empty( $field['options'] ) ) {
				$allowed  = array_map( 'strval', array_keys( $field['options'] ) );
				$sanitize = static function ( $value ) use ( $allowed ) {
					$value = (string) $value;
					return in_array( $value, $allowed, true ) ? $value : '';
				};
			}

			foreach ( $config['post_type'] as $post_type ) {
				register_post_meta(
					$post_type,
					$key,
					array(
						'type'              => $meta_type,
						'label'             => $field['label'] ?? $key,
						'description'       => $field['description'] ?? '',
						'single'            => true,
						'default'           => $field['default'] ?? kelurahan_meta_type_default( $meta_type ),
						'show_in_rest'      => true,
						'sanitize_callback' => $sanitize,
						'auth_callback'     => static fn( $allowed, $meta_key, $post_id ) => current_user_can( 'edit_post', $post_id ),
					)
				);
			}
		}
	}
}

/**
 * Default value for a meta type, so REST schema validation is happy.
 *
 * @param string $meta_type string|number|integer|boolean.
 * @return mixed
 */
function kelurahan_meta_type_default( string $meta_type ) {
	return match ( $meta_type ) {
		'number'  => 0.0,
		'integer' => 0,
		'boolean' => false,
		default   => '',
	};
}

/**
 * Keep only valid Y-m-d dates.
 *
 * @param mixed $value Raw value.
 */
function kelurahan_sanitize_date( $value ): string {
	$value = sanitize_text_field( (string) $value );
	$date  = DateTimeImmutable::createFromFormat( 'Y-m-d', $value );
	return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $value : '';
}

/**
 * Keep only valid H:i times.
 *
 * @param mixed $value Raw value.
 */
function kelurahan_sanitize_time( $value ): string {
	$value = sanitize_text_field( (string) $value );
	return preg_match( '/^([01]\d|2[0-3]):[0-5]\d$/', $value ) ? $value : '';
}

/**
 * Cast to float.
 *
 * @param mixed $value Raw value.
 */
function kelurahan_sanitize_number( $value ): float {
	return is_numeric( $value ) ? (float) $value : 0.0;
}

/*
 * ---------------------------------------------------------------------------
 * Admin meta box (works in both block and classic editor).
 * ---------------------------------------------------------------------------
 */

add_action( 'add_meta_boxes', 'kelurahan_add_meta_boxes' );

/**
 * Add one meta box per meta config.
 */
function kelurahan_add_meta_boxes(): void {
	foreach ( kelurahan_meta_configs() as $name => $config ) {
		if ( empty( $config['fields'] ) || false === ( $config['meta_box'] ?? true ) ) {
			continue;
		}

		add_meta_box(
			'kelurahan-meta-' . $name,
			$config['title'] ?? __( 'Details', 'kelurahan' ),
			'kelurahan_render_meta_box',
			$config['post_type'],
			$config['context'] ?? 'normal',
			'default',
			array( 'config_name' => $name )
		);
	}
}

/**
 * Render inputs for a meta config.
 *
 * @param WP_Post $post Current post.
 * @param array   $box  Meta box args.
 */
function kelurahan_render_meta_box( WP_Post $post, array $box ): void {
	$name   = $box['args']['config_name'];
	$config = kelurahan_meta_configs()[ $name ];

	wp_nonce_field( 'kelurahan_meta_' . $name, 'kelurahan_meta_nonce_' . $name );

	echo '<div class="kelurahan-fields" style="display:grid;gap:12px;">';

	foreach ( $config['fields'] as $key => $field ) {
		$type  = $field['type'] ?? 'text';
		$value = get_post_meta( $post->ID, $key, true );
		$id    = 'kelurahan-field-' . $key;
		$label = $field['label'] ?? $key;

		echo '<p style="margin:0;">';

		if ( 'checkbox' === $type ) {
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s /> %4$s</label>',
				esc_attr( $id ),
				esc_attr( $key ),
				checked( (bool) $value, true, false ),
				esc_html( $label )
			);
		} else {
			printf( '<label for="%s"><strong>%s</strong></label><br />', esc_attr( $id ), esc_html( $label ) );

			if ( 'textarea' === $type ) {
				printf(
					'<textarea id="%1$s" name="%2$s" rows="4" class="widefat">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $key ),
					esc_textarea( (string) $value )
				);
			} elseif ( 'select' === $type ) {
				printf( '<select id="%1$s" name="%2$s" class="widefat">', esc_attr( $id ), esc_attr( $key ) );
				echo '<option value="">' . esc_html__( '— Select —', 'kelurahan' ) . '</option>';
				foreach ( $field['options'] ?? array() as $opt_value => $opt_label ) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( (string) $opt_value ),
						selected( (string) $value, (string) $opt_value, false ),
						esc_html( $opt_label )
					);
				}
				echo '</select>';
			} else {
				$input_type = match ( $type ) {
					'number', 'integer' => 'number',
					'url', 'email', 'date', 'time' => $type,
					default => 'text',
				};
				printf(
					'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="widefat" %5$s />',
					esc_attr( $input_type ),
					esc_attr( $id ),
					esc_attr( $key ),
					esc_attr( (string) $value ),
					'number' === $type ? 'step="any"' : ''
				);
			}
		}

		if ( ! empty( $field['description'] ) ) {
			printf( '<br /><span class="description">%s</span>', esc_html( $field['description'] ) );
		}

		echo '</p>';
	}

	echo '</div>';
}

add_action( 'save_post', 'kelurahan_save_meta_box', 10, 2 );

/**
 * Save meta box values. Sanitization happens in register_post_meta's callback.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function kelurahan_save_meta_box( int $post_id, WP_Post $post ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( kelurahan_meta_configs() as $name => $config ) {
		if ( ! in_array( $post->post_type, $config['post_type'], true ) ) {
			continue;
		}

		$nonce_field = 'kelurahan_meta_nonce_' . $name;
		if ( ! isset( $_POST[ $nonce_field ] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), 'kelurahan_meta_' . $name )
		) {
			continue;
		}

		foreach ( $config['fields'] as $key => $field ) {
			if ( 'checkbox' === ( $field['type'] ?? 'text' ) ) {
				update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) );
				continue;
			}

			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}

			// Raw value is sanitized by the sanitize_callback registered above.
			$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( '' === $raw ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $raw );
			}
		}
	}
}
