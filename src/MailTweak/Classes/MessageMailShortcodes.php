<?php

namespace MailTweak\Classes;


class MessageMailShortcodes {

	private $shortcode_name = 'mt_variable';
	private $vars = [];

	public function __construct() {
		add_shortcode( $this->shortcode_name, [ $this, 'shortcode' ] );
		add_action( 'MailTweak__message_mapper_fields_extractor_filter', [ $this, 'filter' ], 10, 4 );
	}

	public function shortcode( $attrs ) {
		$attrs = shortcode_atts(
			[
				'type' => false,
			],
			$attrs
		);

		if ( false !== $attrs['type'] ) {
			if ( array_key_exists( $attrs['type'], $this->vars ) ) {
				return $this->vars[ $attrs['type'] ];
			}
		}

		return "";

	}

	public function filter( $value, $type, $field, $vars ) {
		$this->vars = $vars[$type];

		if ( in_array( $field, [ 'message', 'subject' ] ) ) {
			return do_shortcode( $value );
		}

		return $value;
	}

}