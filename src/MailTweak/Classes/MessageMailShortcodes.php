<?php

namespace MailTweak\Classes;


class MessageMailShortcodes {

	private $shortcode_name = 'mt_variable';

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
			return $attrs['type'];
		}

		return "";

	}

	public function filter( $value, $type, $field, $vars ) {

		if ( in_array( $field, [ 'message', 'subject' ] ) ) {
			return do_shortcode( $value );
		}

		return $value;
	}

}