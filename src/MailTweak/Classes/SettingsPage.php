<?php

namespace MailTweak\Classes;


use MailTweak;

class SettingsPage {

	private $textdomine;
	private $slug;
	private $slug_smtp_menu;
	private $version;
	private $settings_url;
	private $option_base;

	public function __construct( $slug ) {
		$this->slug           = $slug;
		$this->textdomine     = MailTweak::$textdomine;
		$this->version        = MailTweak::$textdomine;
		$this->slug_smtp_menu = $this->slug . '-smtp';
		$this->settings_url   = $this->slug . '-settings';
		$this->option_base    = $this->slug;

		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_options' ] );
		add_action( 'admin_init', [ $this, 'sections' ] );
		add_action( 'admin_init', [ $this, 'fields' ] );
	}


	public function register_options() {

		register_setting(
			$this->option_base . "_smpt_settings",
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
			$this->option_base . "_smpt_settings",
			__( "SMTP Settings", $this->textdomine ),
			'',
			$this->slug_smtp_menu
		);

	}

	public function fields() {

		add_settings_field(
			'Port',
			'SMTP Port',
			[ $this, 'option_display_settings' ],
			$this->slug_smtp_menu,
			$this->option_base . "_smpt_settings",
			[
				'type' => 'number',
				'id'   => 'Port',
			]
		);

		add_settings_field(
			'host',
			'SMTP host',
			[ $this, 'option_display_settings' ],
			$this->slug_smtp_menu,
			$this->option_base . "_smpt_settings",
			[
				'type' => 'text',
				'id'   => 'host'
			]
		);

		add_settings_field(
			'SMTPSecure',
			'SMTP encryption',
			[ $this, 'option_display_settings' ],
			$this->slug_smtp_menu,
			$this->option_base . "_smpt_settings",
			[
				'type' => 'select',
				'id'   => 'SMTPSecure',
				'vals' => [
					'none' => 'None',
					'ssl'  => 'SSL',
					'tls'  => 'TLS'
				]
			]
		);

		add_settings_field(
			'SMTPAuth',
			'SMTP auth',
			[ $this, 'option_display_settings' ],
			$this->slug_smtp_menu,
			$this->option_base . "_smpt_settings",
			[
				'type' => 'select',
				'id'   => 'SMTPAuth',
				'vals' => [
					'none' => 'None',
					'yes'  => 'Yes'
				]
			]
		);

		if ( Options::get( 'SMTPAuth' ) === 'yes' ) {

			add_settings_field(
				'Username',
				'SMTP user',
				[ $this, 'option_display_settings' ],
				$this->slug_smtp_menu,
				$this->option_base . "_smpt_settings",
				[
					'type' => 'text',
					'id'   => 'Username'
				]
			);

			add_settings_field(
				'Password',
				'SMTP password',
				[ $this, 'option_display_settings' ],
				$this->slug_smtp_menu,
				$this->option_base . "_smpt_settings",
				[
					'type' => 'password',
					'id'   => 'Password'
				]
			);
		}

		add_settings_field(
			'From',
			'From',
			[ $this, 'option_display_settings' ],
			$this->slug_smtp_menu,
			$this->option_base . "_smpt_settings",
			[
				'type' => 'text',
				'id'   => 'From'
			]
		);

		add_settings_field(
			'FromName',
			'From name',
			[ $this, 'option_display_settings' ],
			$this->slug_smtp_menu,
			$this->option_base . "_smpt_settings",
			[
				'type' => 'text',
				'id'   => 'FromName'
			]
		);
	}

	public function option_display_settings( $args ) {
		$args = shortcode_atts(
			[
				'desc' => false,
				'type' => false,
				'id'   => false,
				'vals' => []
			],
			$args
		);

		$option = get_option( $this->option_base, MailProxy::$options );

		$option = shortcode_atts(
			MailProxy::$options,
			$option
		);

		$option[ $args['id'] ] = esc_attr( stripslashes( $option[ $args['id'] ] ) );

		if ( false !== $args['type'] ) {
			switch ( $args['type'] ) {
				case 'password':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='password' id='{$args['id']}' name='{$this->option_base}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'text':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='text' id='{$args['id']}' name='{$this->option_base}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'number':
					echo "<label for='{$args['id']}'>";
					echo "<input class='regular-text' type='number' id='{$args['id']}' name='{$this->option_base}[{$args['id']}]' value='{$option[$args['id']]}' />";
					echo ( false !== $args['desc'] ) ? "<br /><span class='description'>{$args['desc']}</span>" : "";
					echo "</label>";
					break;
				case 'select':
					echo "<select id='{$args['id']}' name='{$this->option_base}[{$args['id']}]'>";
					foreach ( $args['vals'] as $key => $val ) {
						$selected = selected( $option[ $args['id'] ], $key, false );
						echo "<option value='$key' $selected>$val</option>";
					}
					echo ( false !== $args['desc'] ) ? $args['desc'] : "";
					echo "</select>";
					break;
			}
		}
	}

	public function add_settings_page() {

		$page = add_menu_page(
			__( 'Mail Tweak Settings', $this->textdomine ),
			__( 'Mail Tweak', $this->textdomine ),
			'activate_plugins',
			$this->slug,
			function () {
				$url   = admin_url( 'options.php' );
				$title = get_admin_page_title();
				echo "<div class='wrap'>";
				echo "<h2>{$title}</h2>";
				echo "<form method='POST' action='{$url}'>";
				do_action( 'MailTweak__settings_form_before', $this->slug );
				settings_fields( $this->option_base . "_texts_settings" );
				do_settings_sections( $this->settings_url );
				do_action( 'MailTweak__settings_form_after', $this->slug );
				submit_button();
				echo "</form>";
				echo "</div>";
			},
			'dashicons-format-chat',
			85
		);
		$this->add_submenu_page();

		new SettingsPageAssets( $page, $this->version );
	}


	public function add_submenu_page() {
		add_submenu_page(
			$this->slug,
			__( 'SMTP Settings', $this->textdomine ),
			__( 'SMTP', $this->textdomine ),
			'activate_plugins',
			$this->slug_smtp_menu,
			function () {
				$url   = admin_url( 'options.php' );
				$title = get_admin_page_title();
				echo "<div class='wrap'>";
				echo "<h2>{$title}</h2>";
				echo "<form method='POST' action='{$url}'>";
				do_action( 'MailTweak__settings_form_before', $this->slug_smtp_menu );
				settings_fields( $this->option_base . "_smpt_settings" );
				do_settings_sections( $this->slug_smtp_menu );
				do_action( 'MailTweak__settings_form_after', $this->slug_smtp_menu );
				submit_button();
				echo "</form>";
				echo "</div>";
			}

		);
	}
}