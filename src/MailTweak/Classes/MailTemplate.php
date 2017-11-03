<?php

namespace MailTweak\Classes;


class MailTemplate {
	public static $options = [
		'header-message' => '',
		'footer-message' => '',
	];

	public function __construct() {
		add_filter( 'wp_mail', [ $this, "message_filter" ], 20 );
	}

	public function message_filter($args) {

		$args['message'] = "Header " .$args['message']." footer";

		return $args;
	}

}