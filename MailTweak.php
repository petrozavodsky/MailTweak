<?php
/*
Plugin Name: Mail Tweak
Plugin URI: https://github.com/petrozavodsky/MailTweak
Description: Plugin to work with default email alerts
Author: Petrozavodsky
Author URI: http://alkoweb.ru
Version: 1.0.0
Requires PHP: 5.6
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( "includes/Autoloader.php" );

use MailTweak\Autoloader;

new Autoloader( __FILE__, 'MailTweak' );


use MailTweak\Base\Wrap;
use MailTweak\Classes\AddEditorButtons;
use MailTweak\Classes\ClassHelpTab;
use MailTweak\Classes\GetTextParser;
use MailTweak\Classes\MailProxy;
use MailTweak\Classes\MailTemplate;
use MailTweak\Classes\MapperHelper;
use MailTweak\Classes\MessageMailShortcodes;
use MailTweak\Classes\MessageMapper;
use MailTweak\Classes\SettingPageMessages;
use MailTweak\Classes\SettingsCommonElements;
use MailTweak\Classes\SettingsPage;
use MailTweak\Classes\CommentApprovedAlert;
use MailTweak\Classes\TestAjaxMessage;

class MailTweak extends Wrap {
	public $version = '1.0.0';
	public static $textdomine;
	public static $slug = 'mail-tweak';
	public $patterns = [];
	public $mapper_state = [];

	public function __construct() {
		self::$textdomine = $this->setTextdomain();
		new MailProxy();
		new CommentApprovedAlert();
		$parser         = new GetTextParser();
		$this->patterns = $parser->patterns;
		$this->admin_menu();
		new MessageMailShortcodes();
		new MapperHelper();
		new MessageMapper();
		new AddEditorButtons();
		new TestAjaxMessage( $this->version );
		new ClassHelpTab();

		add_filter( 'plugin_action_links', [ $this, 'settings_link' ], 10, 2 );

		new MailTemplate(MailTweak::$slug.SettingsCommonElements::$suffix);

	}

	public function admin_menu() {
		$menu_page = new SettingsPage( self::$slug );
		new SettingPageMessages( $menu_page, $this->patterns );
		new SettingsCommonElements(self::$slug);
	}

	function settings_link( $action_links, $plugin_file ) {
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$url              = add_query_arg( [ 'page' => 'mail-tweak-smtp' ], admin_url( "admin.php" ) );
			$ws_settings_link = "<a href='{$url}'>" . __( "SMTP Settings", self::$textdomine ) . "</a>";
			array_unshift( $action_links, $ws_settings_link );
		}

		return $action_links;
	}


	public static function uninstall() {
		delete_option( MailTweak::$slug );
		delete_option( MailTweak::$slug . "_texts_settings" );
	}


}

register_uninstall_hook( __FILE__, [ 'MailTweak', 'uninstall' ] );

function MailTweak__init() {
	new MailTweak();
}

add_action( 'plugins_loaded', 'MailTweak__init' );