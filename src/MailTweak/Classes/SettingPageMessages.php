<?php

namespace MailTweak\Classes;


use MailTweak;

class SettingPageMessages {

	private $textdomine;
	private $slug;
	private $version;
	private $option_base;
	private $parrent_page;

	public function __construct( $page ) {
		$this->parrent_page = $page;
		$this->slug         = $page->slug;
		$this->textdomine   = MailTweak::$textdomine;
		$this->version      = $page->version;
		$this->option_base  = $this->slug . '_texts_settings';

		add_action( 'admin_init', [ $this, 'register_options' ] );
		add_action( 'admin_init', [ $this, 'sections' ] );
		add_action( 'admin_init', [ $this, 'fields' ] );
	}


private function generate_rows(){

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

	public function sections() {

		add_settings_section(
			$this->option_base,
			__( "Settings alert message templates", $this->textdomine ),
			'',
			$this->slug
		);

	}

	public function fields() {

		add_settings_field(
			'Port',
			'SMTP Port',
			[ $this, 'option_display_settings' ],
			$this->slug,
			$this->option_base,
			[
				'type' => 'texteditor',
				'id'   => 'Port',
			]
		);
	}


	public function option_display_settings( $args ) {
		return $this->parrent_page->option_display_settings( $args, $this->option_base );
	}


}