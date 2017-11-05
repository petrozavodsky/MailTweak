<?php

namespace MailTweak\Classes;

use MailTweak;

class GetTextParser {

	public $percent_similar = 90;

	public $patterns = [];


	public function __construct() {
		add_filter( 'gettext', [ $this, "get_data" ], 10, 2 );

		$this->patterns = [
			'[%s] Your username and password info'    => [ 'new_user_register', __( 'New user create (admin alert)', MailTweak::$textdomine ) ],
			'[%s] New User Registration'              => [ 'create_new_user', __( 'New user register (admin alert)', MailTweak::$textdomine ) ],
			'Password changed for user: %s'           => [ 'password_changed_alert', __( 'User password change (admin alert)', MailTweak::$textdomine ) ],
			'[%s] Password Reset'                     => [ 'reset_password', __( 'Resset password (user alert)', MailTweak::$textdomine ) ],
			'[%s] Notice of Password Change'          => [ 'change_password_alert', __( 'New password created (user alert)', MailTweak::$textdomine ) ],
			'[%1$s] Please moderate: "%2$s"'          => [ 'comment_added', __( 'Comment added (admin alert)', MailTweak::$textdomine ) ],
			'Hi %1$s, comment %2$s has been approved' => [ 'comment_approved', __( 'Comment Approved (user alert)', MailTweak::$textdomine ) ],
			'[%s] Joining confirmation'               => [ 'joining_confirmation', __( 'Joining confirmation (user alert)', MailTweak::$textdomine ) ],
			'New WordPress Site'                      => [ 'new_wordpress_site', __( 'New WordPress Site', MailTweak::$textdomine ) ],
			'[%s] New Site Created'                   => [ 'new_site_created', __( 'New site created', MailTweak::$textdomine ) ],
			];

	}

	public function get_data( $translation, $text ) {
		$patterns = $this->patterns;

		if ( array_key_exists( $text, $patterns ) ) {

			add_filter( 'wp_mail', function ( $array ) use ( $patterns, $text ) {
				$array = apply_filters( 'MailTweak__message_send', $array, $patterns[ $text ][0], $text );

				return $array;
			} );
		}

		return $translation;
	}
}