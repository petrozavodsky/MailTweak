<?php

namespace MailTweak\Classes;


class MailTemplate {
	public static $options = [
		'header-message' => '',
		'footer-message' => '',
	];

	private $option = [];

	public function __construct( $slug ) {
		$this->option = get_option( $slug, self::$options );
		add_filter( 'wp_mail', [ $this, "message_filter" ], 20 );

	}

	public function message_filter( $args ) {

		if ( ! empty( $this->option['header-message'] ) ) {
			$args['message'] = $this->option['header-message'] . "\r\n" . $args['message'];
		}

		if ( ! empty( $this->option['footer-message'] ) ) {
			$args['message'] = $args['message'] . "\r\n" . $this->option['footer-message'];
		}

		return $args;
	}

}