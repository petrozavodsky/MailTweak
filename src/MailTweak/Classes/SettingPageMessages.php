<?php

namespace MailTweak\Classes;


use MailTweak;

class SettingPageMessages {
	use FormElemetBulder;

	private $textdomine;
	private $slug;
	private $version;
	private $option_base;
	private $parent_page;
	private $patterns;

	public function __construct( $page, $patterns ) {
		$this->parent_page = $page;
		$this->slug        = $page->slug;
		$this->textdomine  = MailTweak::$textdomine;
		$this->version     = $page->version;
		$this->option_base = $this->slug . '_texts_settings';
		$this->patterns    = $patterns;

		add_action( 'admin_init', [ $this, 'register_options' ] );
		add_action( 'admin_init', [ $this, 'generate_rows' ] );


	}

	public function generate_rows() {
		$this->patterns;
		foreach ( $this->patterns as $key => $val ) {
			$this->register_row( $val[1], $val[0] );
		}
	}


	public function register_row( $section_title, $id ) {

		$section = $this->option_base . "-" . $id;

		add_settings_section(

			$section,
			$section_title,
			'',
			$this->slug
		);

		add_settings_field(
			$id . "-status",
			__( 'To use', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug,
			$section,
			[
				'type' => 'checkbox',
				'id'   => $id . "-status",
				'val'  => 'on',
			]

		);

		add_settings_field(
			$id . "-from-email",
			__( 'From email', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug,
			$section,
			[
				'type' => 'email',
				'id'   => $id . "-email",
			]
		);

		add_settings_field(
			$id . "-from-name",
			__( 'From name', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug,
			$section,
			[
				'type' => 'text',
				'id'   => $id . "-from-name",
			]
		);

		add_settings_field(
			$id . "-subject",
			__( 'Message subject', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug,
			$section,
			[
				'type' => 'text',
				'id'   => $id . "-subject",
			]
		);


		add_settings_field(
			$id . "-message",
			__( 'Message', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug,
			$section,
			[
				'type' => 'texteditor',
				'id'   => $id . "-message",
			]
		);

	}

	public function register_options() {
		register_setting(
			$this->option_base,
			$this->option_base,
			[
				'sanitize_callback' => function ( $input ) {
					$valid_input = [];
					foreach ( $input as $k => $v ) {
						$valid_input[ $k ] = trim( $v );
					}

					return $valid_input;
				}
			]
		);
	}


	public function option_display_settings( $args ) {

		$this->form_block( $args, $this->option_base, [
			'new_user_register-status'         => "off",
			'new_user_register-email'          => "",
			'new_user_register-from-name'      => "",
			'new_user_register-subject'        => "",
			'new_user_register-message'        => "",
			'create_new_user-status'           => "off",
			'create_new_user-email'            => "",
			'create_new_user-from-name'        => "",
			'create_new_user-subject'          => "",
			'create_new_user-message'          => "",
			'password_changed_alert-status'    => "off",
			'password_changed_alert-email'     => "",
			'password_changed_alert-from-name' => "",
			'password_changed_alert-subject'   => "",
			'password_changed_alert-message'   => "",
			'reset_password-status'            => "off",
			'reset_password-email'             => "",
			'reset_password-from-name'         => "",
			'reset_password-subject'           => "",
			'reset_password-message'           => "",
			'change_password_alert-status'     => "off",
			'change_password_alert-email'      => "",
			'change_password_alert-from-name'  => "",
			'change_password_alert-subject'    => "",
			'change_password_alert-message'    => "",
			'comment_added-status'             => "off",
			'comment_added-email'              => "",
			'comment_added-from-name'          => "",
			'comment_added-subject'            => "",
			'comment_added-message'            => "",

			'comment_approved-status'    => "off",
			'comment_approved-email'     => "",
			'comment_approved-from-name' => "",
			'comment_approved-subject'   => "",
			'comment_approved-message'   => "",

			'joining_confirmation-status'    => "off",
			'joining_confirmation-email'     => "",
			'joining_confirmation-from-name' => "",
			'joining_confirmation-subject'   => "",
			'joining_confirmation-message'   => "",

			'new_wordpress_site-status'    => "off",
			'new_wordpress_site-email'     => "",
			'new_wordpress_site-from-name' => "",
			'new_wordpress_site-subject'   => "",
			'new_wordpress_site-message'   => "",

			'signup_blog_notification-status'  => "off",
			'signup_blog_notification-email'     => "",
			'signup_blog_notification-from-name' => "",
			'signup_blog_notification-subject'   => "",
			'signup_blog_notification-message'   => "",

			'new_site_created-status'  => "off",
			'new_site_created-email'     => "",
			'new_site_created-from-name' => "",
			'new_site_created-subject'   => "",
			'new_site_created-message'   => "",

		] );
	}


}