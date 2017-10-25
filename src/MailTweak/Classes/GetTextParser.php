<?php

namespace MailTweak\Classes;


class GetTextParser {

	public $percent_similar = 90;

	public $paterns =[
		'[%s] Password Reset' => 'new_password',
		'[%s] Notice of Password Change' => 'change_password_alert',
		'[%s] Your username and password info' => 'create_new_user'
	];

	public function __construct() {
		add_filter( 'gettext', [ $this, "get_data" ], 10, 3 );
	}


	public function get_data( $translation, $text, $domain ) {
		if ( array_key_exists($text , $this->paterns)    ) {

			add_filter( 'wp_mail', function ( $array ) use ( $text ) {
				$array['message'] = $this->paterns[$text];
				return $array;
			} );

		}

		return $translation;
	}

	private function prepare( $string ) {
		$string = trim( $string );
		$string = preg_replace( '|[\s]+|s', ' ', $string );

		return $string;
	}


}