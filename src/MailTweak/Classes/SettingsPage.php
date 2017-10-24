<?php

namespace MailTweak\Classes;


use MailTweak;

class SettingsPage {

	private $textdomine;
	private $slug;
	private $version;
	private $settings_url;
	private $option_base;

	public function __construct( $slug ) {
		$this->slug         = $slug;
		$this->textdomine   = MailTweak::$textdomine;
		$this->version      = MailTweak::$textdomine;
		$this->settings_url = $this->slug . '-settings';
		$this->option_base  = $this->slug;

		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'sections' ] );
	}

	public function sections() {

		register_setting(
			$this->option_base."_smpt_settings",
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

		add_settings_section(
			$this->option_base."_smpt_settings",
			__("SMTP Settings",$this->textdomine),
			'',
			$this->settings_url
		);


		add_settings_field(
			'Port',
			'SMTP Port',
			function ($args){

			},
			$this->settings_url,
			$this->option_base."_smpt_settings",
			[
				'type'      => 'text',
				'id'        => 'port',
				'label_for' => 'port'
			]
		);

	}

	public function add_settings_page() {
		$page = add_options_page(
			__( 'SMTP SETTINGS', $this->textdomine ),
			__( 'SMTP SETTINGS', $this->textdomine ),
			'activate_plugins',
			$this->settings_url,
			function () {
				$url   = admin_url( 'options.php' );
				$title = get_admin_page_title();
				echo "<div class='wrap'>";
				echo "<h2>{$title}</h2>";
				echo "<form method='POST' action='{$url}'>";
				do_action( 'MailTweak__settings_form_before' );
				settings_fields( $this->option_base );
				do_settings_sections( $this->settings_url );
				do_action( 'MailTweak__settings_form_after' );
				submit_button();
				echo "</form>";
				echo "</div>";
			}
		);

		new SettingsPageAssets( $page, $this->version );
	}
}