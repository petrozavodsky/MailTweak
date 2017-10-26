<?php

namespace MailTweak\Classes;


class MessageMailShortcodes {

	private $shortcode_name = 'mt_variable';
	private $type;

	public function __construct() {

		add_filter( 'MailTweak__message_mapper_fields_extractor_filter', [ $this, 'filter' ], 10, 4 );
		add_shortcode( $this->shortcode_name, [ $this, 'shortcode' ] );

	}

	public function shortcode( $attrs ) {
		$GLOBALS['MailTweak_MapperHelper_tmp'];
		$attrs = shortcode_atts(
			[
				'type' => false,
			],
			$attrs
		);

		$vars = $GLOBALS['MailTweak_MapperHelper_tmp'][ $this->type ];



		if ( false !== $attrs['type'] ) {
			if ( array_key_exists( $attrs['type'], $vars ) ) {
				return $vars[ $attrs['type'] ];
			}
		}

		return "";

	}

	public function filter( $value, $type, $field ) {

		$this->type = $type;

		if ( in_array( $field, [ 'message', 'subject' ] ) ) {
			return do_shortcode( $value );
		}

		return $value;
	}

}