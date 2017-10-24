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
			[$this , 'option_display_settings'],
			$this->settings_url,
			$this->option_base."_smpt_settings",
			[
				'type'      => 'number',
				'id'        => 'port',
				'label_for' => 'port'
			]
		);

	}

	public function option_display_settings( $args ) {
		$type        = $args['type'];
		$id          = $args['id'];
		$desc        = $args['desc'];
		$vals        = $args['vals'];
		$option_name = $this->option_base;
		$option      = get_option( $option_name );

		switch ( $type ) {
			case 'text':
				$option[ $id ] = esc_attr( stripslashes( $option[ $id ] ) );
				echo "<label for='{$id}'>";
				echo "<input class='regular-text' type='text' id='{$id}' name='{$option_name}[{$id}]' value='{$option[$id]}' />";
				echo ( $desc != '' ) ? "<br /><span class='description'>{$desc}</span>" : "";
				echo "</label>";
				break;
			case 'number':
				$option[ $id ] = esc_attr( stripslashes( $option[ $id ] ) );
				echo "<label for='{$id}'>";
				echo "<input class='regular-text' type='text' id='{$id}' name='{$option_name}[{$id}]' value='{$number[$id]}' />";
				echo ( $desc != '' ) ? "<br /><span class='description'>{$desc}</span>" : "";
				echo "</label>";
				break;
		}
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