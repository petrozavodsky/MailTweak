<?php
/*
Plugin Name: Mail Tweak
Author: Petrozavodsky
Author URI: http://alkoweb.ru
*/

require_once( "includes/Autoloader.php" );

use MailTweak\Autoloader;

new Autoloader( __FILE__, 'MailTweak' );


use MailTweak\Base\Wrap;
use MailTweak\Classes\GetTextParser;
use MailTweak\Classes\MailProxy;
use MailTweak\Classes\MapperHelper;
use MailTweak\Classes\MessageMapper;
use MailTweak\Classes\SettingPageMessages;
use MailTweak\Classes\SettingsPage;
use MailTweak\Classes\CommentApprovedAlert;

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
		new MapperHelper();

		new MessageMapper();
	}

	public function admin_menu() {
		$menu_page = new SettingsPage( self::$slug );
		new SettingPageMessages( $menu_page, $this->patterns );
	}

}

function MailTweak__init() {
	new MailTweak();
}

add_action( 'plugins_loaded', 'MailTweak__init' );