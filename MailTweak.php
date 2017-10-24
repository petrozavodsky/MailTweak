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
use MailTweak\Classes\MailProxy;
use MailTweak\Classes\Options;
use MailTweak\Classes\SettingsPage;

class MailTweak extends Wrap {
	public $version = '1.0.0';
	public static $textdomine;
	public static $slug ='mail-tweak';

	function __construct() {
		self::$textdomine = $this->setTextdomain();
		new MailProxy();
		new Options(MailProxy::$options , self::$slug );
		new SettingsPage( self::$slug);


	}

}

function MailTweak__init() {
	new MailTweak();
}

add_action( 'plugins_loaded', 'MailTweak__init', 30 );