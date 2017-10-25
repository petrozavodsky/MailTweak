<?php

namespace MailTweak\Classes;


use MailTweak;

class GetTextParser {

	public $percent_similar = 90;

	public $paterns = [];


	public function __construct() {
		add_filter( 'gettext', [ $this, "get_data" ], 10, 3 );

		$this->paterns = [
			'[%s] New User Registration'              => [ 'new_user_register', __( 'New user create', MailTweak::$textdomine ) ],
			'[%s] Password Reset'                     => [ 'new_password', __( 'New user register', MailTweak::$textdomine ) ],
			'[%s] Notice of Password Change'          => [ 'change_password_alert', __( 'New password created (user alert)', MailTweak::$textdomine ) ],
			'[%s] Your username and password info'    => [ 'create_new_user', __( 'New password register (admin alert)', MailTweak::$textdomine ) ],
			'[%1$s] Please moderate: \"%2$s\"'        => [ 'comment_added', __( 'Comment added', MailTweak::$textdomine ) ],
			'Hi %1$s, comment %2$s has been approved' => [ 'comment_approved', __( 'Comment Approved', MailTweak::$textdomine ) ]
		];

	}

	public function get_data( $translation, $text, $domain ) {
		$paterns = $this->paterns;

		if ( array_key_exists( $text, $paterns ) ) {

			add_filter( 'wp_mail', function ( $array ) use ( $paterns, $text ) {
				$array['message'] = apply_filters( 'MailTweak__message_send', $paterns[ $text ], $text );

				return $array;
			} );
		}

		return $translation;
	}
}