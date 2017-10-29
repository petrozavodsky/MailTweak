<?php

namespace MailTweak\Classes;


trait FormElemetBulder {

	public function form_block( $args, $option_name = false, $defaults = [] ) {
		$args = shortcode_atts(
			[
				'desc' => false,
				'type' => false,
				'id'   => false,
				'vals' => [],
				'val'=> 'on'
			],
			$args
		);

		$option = get_option( $option_name, $defaults );

		if ( 0 < count( $defaults ) ) {
			$option = shortcode_atts(
				$defaults,
				$option
			);
		}

		$option[ $args['id'] ] = esc_attr( stripslashes( $option[ $args['id'] ] ) );

		if ( false !== $args['type'] ) {
			switch ( $args['type'] ) {
				case 'texteditor':
					$editor_id = mb_strtolower( str_replace( [ '-', '_' ], '', $args['id'] ) );
					wp_editor(
						$option[ $args['id'] ],
						$editor_id,
						[
							'wpautop'       => false,
							'media_buttons' => false,
							'textarea_name' => "{$option_name}[{$args['id']}]",
							'textarea_rows' => 8,
							'tinymce'       => true
						]
					);
					break;
				case 'password':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='password' id='{$args['id']}' name='{$option_name}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'email':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='email' id='{$args['id']}' name='{$option_name}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'text':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='text' id='{$args['id']}' name='{$option_name}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'number':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='number' id='{$args['id']}' name='{$option_name}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'select':
					echo "<select id='{$args['id']}' name='{$option_name}[{$args['id']}]'>";
					foreach ( $args['vals'] as $key => $val ) {
						$selected = selected( $option[ $args['id'] ], $key, false );
						echo "<option value='{$key}' {$selected}>{$val}</option>";
					}
					echo ( false !== $args['desc'] ) ? $args['desc'] : "";
					echo "</select>";
					break;
				case 'checkbox':
					echo "<label for='{$args['id']}'>";
					$checked = checked( $option[ $args['id'] ], $args['val'] , false );
					echo "<input type='checkbox' id='{$args['id']}' {$checked} name='{$option_name}[{$args['id']}]' value='{$args["val"]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
			}
		}
	}
}