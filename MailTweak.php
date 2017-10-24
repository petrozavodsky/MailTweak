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
use MailTweak\Classes\SettingsPage;

class MailTweak extends Wrap {
	public $version = '1.0.0';
	public static $textdomine;
	public static $slug = 'mail-tweak';

	function __construct() {
		self::$textdomine = $this->setTextdomain();
		new MailProxy();
		new SettingsPage( self::$slug );
//		new GetTextParser();
	}

}

function MailTweak__init() {
	new MailTweak();
}



