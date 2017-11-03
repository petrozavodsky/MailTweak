<?php

namespace MailTweak\Classes;


use MailTweak;

class SettingsCommonElements {
	use FormElemetBulder;

	public $slug;
	public $version;
	private $textdomine;
	private $slug_sub_menu;
	private $settings_url;
	private $option_base;

	public function __construct( $slug ) {
		$this->slug          = $slug;
		$this->textdomine    = MailTweak::$textdomine;
		$this->version       = "1.0.0";
		$this->slug_sub_menu = $this->slug . '-template_mail';
		$this->settings_url  = $this->slug . '-settings';
		$this->option_base   = $this->slug;


		add_action( 'admin_menu', [ $this, 'add_submenu_page' ] );
		add_action( 'admin_init', [ $this, 'register_options' ] );
		add_action( 'admin_init', [ $this, 'sections' ] );
		add_action( 'admin_init', [ $this, 'fields' ] );
	}

	public function register_options() {

		register_setting(
			$this->option_base . "_template_mail",
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
			$this->option_base . "_template_mail",
			__( "Email template options", $this->textdomine ),
			'',
			$this->slug_sub_menu
		);

	}

	public function fields() {

		$section = $this->option_base . "_template_mail";


		add_settings_field(
			 "header-message",
			__( 'Mail header', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug_sub_menu,
			$section,
			[
				'type' => 'texteditor',
				'id'   => "header-message",
			]
		);

		add_settings_field(
			 "footer-message",
			__( 'Mail footer', $this->textdomine ),
			[ $this, 'option_display_settings' ],
			$this->slug_sub_menu,
			$section,
			[
				'type' => 'texteditor',
				'id'   => "footer-message",
			]
		);
	}

	public function option_display_settings( $args, $option_name = false, $defaults = false ) {
		if ( false == $option_name ) {
			$option_name = $this->option_base;
		}

		if ( false == $defaults ) {
			$defaults = MailTemplate::$options;
		}

		$this->form_block( $args, $option_name, $defaults );
	}

	public function add_submenu_page() {
		add_submenu_page(
			$this->slug,
			__( 'Email template', $this->textdomine ),
			__( 'Template', $this->textdomine ),
			'activate_plugins',
			$this->slug_sub_menu,
			function () {
				$url   = admin_url( 'options.php' );
				$title = get_admin_page_title();
				echo "<div class='wrap'>";
				echo "<h2>{$title}</h2>";
				echo "<form method='POST' action='{$url}'>";
				do_action( 'MailTweak__settings_form_before', $this->slug_sub_menu );
				settings_fields( $this->option_base . "_template_mail" );
				do_settings_sections( $this->slug_sub_menu );
				do_action( 'MailTweak__settings_form_after', $this->slug_sub_menu );
				submit_button();
				echo "</form>";
				echo "</div>";
			}

		);
	}
}