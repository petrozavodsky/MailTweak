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


class MailTweak extends Wrap {
	public $version = '1.0.0';
	public static $textdomine;

	function __construct() {
		self::$textdomine = $this->setTextdomain();

	}

}

function MailTweak__init() {
	new MailTweak();
}

add_action( 'plugins_loaded', 'MailTweak__init', 30 );