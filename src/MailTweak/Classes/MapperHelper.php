<?php

namespace MailTweak\Classes;


use MailTweak;
use WP_User;

class MapperHelper {

	public static $tags_descriptions = [];
	public static $tags_descriptions_scripts = [];
	public static $tags_descriptions_help_tab = [];

	public function __construct() {

		add_filter( 'retrieve_password_message', [ $this, 'reset_password' ], 1, 4 );
		add_action( 'retrieve_password_key', [ $this, 'new_user_register' ], 1, 2 );
		add_filter( 'MailTweak__message_mapper_fields_extractor_filter', [ $this, 'password_changed_alert' ], 1, 2 );
		add_filter( 'comment_moderation_headers', [ $this, 'comment_added' ], 1, 2 );
		add_filter( 'MailTweak__comment-approved', [ $this, 'comment_approved' ], 1, 3 );
		add_action( 'invite_user', [ $this, 'joining_confirmation' ], 1, 3 );
		add_action( 'dbdelta_queries', [ $this, 'user_register' ], 1, 1 );
		$this->tags_descriptions();
	}

	public function new_wordpress_site( $user_id ) {
		$user      = new WP_User( $user_id );
		$name      = $user->user_login;
		$login_url = wp_login_url();

		$GLOBALS['MailTweak_MapperHelper_tmp']['new_wordpress_site'] = [
			'blog_url'  => wp_guess_url(),
			'name'      => $name,
			'login_url' => $login_url
		];
	}

	public function joining_confirmation( $user_id, $role, $newuser_key ) {
		$GLOBALS['MailTweak_MapperHelper_tmp']['joining_confirmation'] = [
			'blogname'    => get_option( 'blogname' ),
			'home_url'    => home_url(),
			'user_role'   => wp_specialchars_decode( translate_user_role( $role['name'] ) ),
			'newuser_key' => home_url( "/newbloguser/$newuser_key/" )
		];

	}

	public function password_changed_alert( $value, $type ) {
		global $wpdb;

		$user_login                                                      = $wpdb->get_var(
			"SELECT user_nicename FROM {$wpdb->users} ORDER BY user_registered DESC"
		);
		$GLOBALS['MailTweak_MapperHelper_tmp']['password_changed_alert'] = [
			'login' => $user_login
		];

		return $value;
	}

	public function new_user_register( $user_login, $key ) {
		$GLOBALS['MailTweak_MapperHelper_tmp']['new_user_register'] = [
			'link_confirm' => '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '> ',
			'login'        => $user_login
		];
	}

	public function reset_password( $message, $key, $user_login, $user_data ) {
		$GLOBALS['MailTweak_MapperHelper_tmp']['reset_password'] = [
			'link_reset' => '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">",
			'login'      => $user_login
		];

		return $message;
	}

	public function comment_added( $message_headers, $comment_id ) {
		global $wpdb;

		$comments_waiting = $wpdb->get_var( "SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'" );
		$comment          = get_comment( $comment_id );
		$comment_content  = wp_specialchars_decode( $comment->comment_content );

		$GLOBALS['MailTweak_MapperHelper_tmp']['comment_added'] = [
			'link_post'              => get_permalink( $comment->comment_post_ID ),
			'count_comments_waiting' => $comments_waiting,
			'comment_content'        => $comment_content,
			'comment_author'         => $comment->comment_author,
			'comment_author_email'   => $comment->comment_author_email,
			'comment_author_url'     => $comment->comment_author_url,
			'comment_author_ip'      => $comment->comment_author_IP,
			'links_comment_approve'  => admin_url( "comment.php?action=approve&c={$comment_id}#wpbody-content" ),
			'links_comment_del'      => ( EMPTY_TRASH_DAYS ? admin_url( "comment.php?action=trash&c={$comment_id}#wpbody-content" ) : admin_url( "comment.php?action=delete&c={$comment_id}#wpbody-content" ) ),
			'links_comment_spam'     => admin_url( "comment.php?action=delete&c={$comment_id}#wpbody-content" ),
			'links_all_waiting'      => admin_url( "edit-comments.php?comment_status=moderated#wpbody-content" ),

		];


	}

	public function comment_approved( $message, $comment_author, $comment_link ) {
		$GLOBALS['MailTweak_MapperHelper_tmp']['comment_approved'] = [
			'link_comment' => $comment_link,
			'login'        => $comment_author
		];

		return $message;
	}

	public function tags_descriptions() {

		self::$tags_descriptions_help_tab = [
			__( 'new_wordpress_site', MailTweak::$textdomine )     => [
				'blog_url'  => __( 'Blog url', MailTweak::$textdomine ),
				'name'      => __( 'User name', MailTweak::$textdomine ),
				'login_url' => __( 'Login url', MailTweak::$textdomine )
			],
			__( 'Joining confirmation', MailTweak::$textdomine )   => [
				'blogname'    => __( 'blog name', MailTweak::$textdomine ),
				'home_url'    => __( 'Home site page', MailTweak::$textdomine ),
				'user_role'   => __( 'New user role', MailTweak::$textdomine ),
				'newuser_key' => __( 'New user activation key', MailTweak::$textdomine )
			],
			__( 'Password Changed Alert', MailTweak::$textdomine ) => [
				'password_changed_alert' => __( 'Login', MailTweak::$textdomine )
			],
			__( 'New User Register', MailTweak::$textdomine )      => [
				'link_confirm' => __( 'Сonfirmation link', MailTweak::$textdomine ),
				'login'        => __( 'Login', MailTweak::$textdomine )
			],
			__( 'Reset password', MailTweak::$textdomine )         => [
				'link_reset' => __( 'link reset', MailTweak::$textdomine ),
				'login'      => __( 'Login', MailTweak::$textdomine ),
			],
			__( 'Comment added', MailTweak::$textdomine )          => [
				'link_post'              => __( 'Link post', MailTweak::$textdomine ),
				'count_comments_waiting' => __( 'The number of comments waiting to be approved', MailTweak::$textdomine ),
				'comment_content'        => __( 'The text of the comment', MailTweak::$textdomine ),
				'comment_author'         => __( 'Nick - author of the comment', MailTweak::$textdomine ),
				'comment_author_email'   => __( 'Author email', MailTweak::$textdomine ),
				'comment_author_url'     => __( 'Author site', MailTweak::$textdomine ),
				'comment_author_ip'      => __( 'Author IP', MailTweak::$textdomine ),
				'links_comment_approve'  => __( 'To approve the review', MailTweak::$textdomine ),
				'links_comment_del'      => __( 'Link - delete comment', MailTweak::$textdomine ),
				'links_comment_spam'     => __( 'Link - spam ', MailTweak::$textdomine ),
				'links_all_waiting'      => __( 'Link - comments', MailTweak::$textdomine )
			],
			__( 'Comment Approved', MailTweak::$textdomine )       => [
				'link_comment' => __( 'Link comment', MailTweak::$textdomine ),
				'login'        => __( 'Login', MailTweak::$textdomine )
			]
		];

		self::$tags_descriptions = [
			'new_wordpress_site'=>[
				'blog_url'  => __( 'Blog url', MailTweak::$textdomine ),
				'name'      => __( 'User name', MailTweak::$textdomine ),
				'login_url' => __( 'Login url', MailTweak::$textdomine )
			],
			'joining_confirmation'=>[
				'blogname'    => __( 'blog name', MailTweak::$textdomine ),
				'home_url'    => __( 'Home site page', MailTweak::$textdomine ),
				'user_role'   => __( 'New user role', MailTweak::$textdomine ),
				'newuser_key' => __( 'New user activation key', MailTweak::$textdomine )
			],
			'password_changed_alert' => [
				'password_changed_alert' => __( 'Login', MailTweak::$textdomine )
			],
			'new_user_register'      => [
				'link_confirm' => __( 'Сonfirmation link', MailTweak::$textdomine ),
				'login'        => __( 'Login', MailTweak::$textdomine )
			],
			'reset_password'         => [
				'link_reset' => __( 'link reset', MailTweak::$textdomine ),
				'login'      => __( 'Login', MailTweak::$textdomine ),
			],
			'comment_added'          => [
				'link_post'              => __( 'Link post', MailTweak::$textdomine ),
				'count_comments_waiting' => __( 'The number of comments waiting to be approved', MailTweak::$textdomine ),
				'comment_content'        => __( 'The text of the comment', MailTweak::$textdomine ),
				'comment_author'         => __( 'Nick - author of the comment', MailTweak::$textdomine ),
				'comment_author_email'   => __( 'Author email', MailTweak::$textdomine ),
				'comment_author_url'     => __( 'Author site', MailTweak::$textdomine ),
				'comment_author_ip'      => __( 'Author IP', MailTweak::$textdomine ),
				'links_comment_approve'  => __( 'To approve the review', MailTweak::$textdomine ),
				'links_comment_del'      => __( 'Link - delete comment', MailTweak::$textdomine ),
				'links_comment_spam'     => __( 'Link - spam ', MailTweak::$textdomine ),
				'links_all_waiting'      => __( 'Link - comments', MailTweak::$textdomine )
			],
			'comment_approved'       => [
				'link_comment' => __( 'Link comment', MailTweak::$textdomine ),
				'login'        => __( 'Login', MailTweak::$textdomine )
			]
		];


		foreach ( self::$tags_descriptions as $key => $val ) {

			$editor_id = mb_strtolower(
				str_replace(
					[ '-', '_' ],
					'',
					$key
				)
			);

			$editor_id = $editor_id . "message";

			self::$tags_descriptions_scripts [ $editor_id ] = $val;
		}


	}
}