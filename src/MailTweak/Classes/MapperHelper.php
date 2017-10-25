<?php

namespace MailTweak\Classes;


class MapperHelper {

	private $tmp = [];

	public function __construct() {

		add_filter( 'retrieve_password_message', [ $this, 'reset_password' ], 10, 4 );

	}

	public function reset_password( $message, $key, $user_login, $user_data ) {
		$this->tmp ['reset_password'] = [
			'link_reset' => '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '> ',
			'login'      => $user_login
		];
	}


	public function set_state( $state ) {

		return $state;
	}


}