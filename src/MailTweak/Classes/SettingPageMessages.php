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
				'type' => 'select',
				'id'   => $id . "-status",
				'vals' => [
					'on'  => 'On',
					'off' => 'Off'
				]
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
			$id."-message",
			__( 'Message', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug,
			$section,
			[
				'type' => 'texteditor',
				'id'   => $id."-message",
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

		$this->form_block($args, $this->option_base ,[]);
	}


}