<?php

namespace MailTweak\Classes;


class GetTextParser {

	public $percent_similar = 90;

	public static $paterns = [
		'[%s] New User Registration'              => 'new_user_register',
		'[%s] Password Reset'                     => 'new_password',
		'[%s] Notice of Password Change'          => 'change_password_alert',
		'[%s] Your username and password info'    => 'create_new_user',
		'[%1$s] Please moderate: \"%2$s\"'        => 'comment_added',
		'Hi %1$s, comment %2$s has been approved' => 'comment_approved'
	];

	public function __construct() {
		add_filter( 'gettext', [ $this, "get_data" ], 10, 3 );
	}


	public function get_data( $translation, $text, $domain ) {
		$paterns = self::$paterns;
		if ( array_key_exists( $text, $paterns ) ) {

			add_filter( 'wp_mail', function ( $array ) use ( $paterns, $text ) {
				$array['message'] = $paterns[ $text ];

				return $array;
			} );
		}

		return $translation;
	}
}