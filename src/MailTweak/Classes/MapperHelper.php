<?php

namespace MailTweak\Classes;


class MapperHelper {

	private $tmp = [];

	public function __construct() {

//		add_filter( 'retrieve_password_message', [ $this, 'reset_password' ], 10, 4 );
//		add_action( 'retrieve_password_key', [ $this, 'create_new_user' ] );
//		add_filter( 'comment_moderation_headers', [ $this, 'comment_added' ], 10, 2 );
//		add_filter('MailTweak__comment-approved', [$this,'comment_approved'], 10, 3);

		//new_user_register
		//change_password_alert
	}

	public function create_new_user( $user_login, $key ) {
		$this->tmp ['create_new_user'] = [
			'link_reset' => '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '> ',
			'login'      => $user_login
		];
	}

	public function reset_password( $message, $key, $user_login, $user_data ) {
		$this->tmp ['reset_password'] = [
			'link_reset' => '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">",
			'login'      => $user_login
		];

		 return $message;
	}

	public function comment_added( $message_headers, $comment_id ) {
		global $wpdb;
		$comments_waiting = $wpdb->get_var( "SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'" );

		$this->tmp ['comment_added'] = [
			'link_post'         => '',
			'link_approve'      => '',
			'link_delete'       => '',
			'link_spam'         => '',
			'links_all_waiting' => ''
		];
	}

	public function comment_approved($message, $comment_author, $comment_link){
		$this->tmp ['comment_approved'] = [
			'link_comment' => $comment_link,
			'login'      => $comment_author
		];
	}

	public function set_state( $state ) {
		$state = $this->tmp;
		return $state;
	}


}