<?php

namespace MailTweak\Classes;


class MailProxy {

	public static $options = [
		'Mailer'     => 'smtp',
		'Port'       => '',
		'host'       => '',
		'SMTPSecure' => '',
		'SMTPAuth'   => true,
		'Username'   => '',
		'Password'   => '',
		'Sender'     => '',
		'From'       => '',
		'AddReplyTo' => ''
	];

	public function __construct() {
		add_action( 'phpmailer_init', 'proxy' );
	}

	public function proxy( $mailer ) {
		$mailer->Mailer     = self::$options['Mailer'];
		$mailer->Port       = self::$options['Port'];
		$mailer->host       = self::$options['host'];
		$mailer->SMTPSecure = self::$options['SMTPSecure'];
		$mailer->SMTPAuth   = self::$options['SMTPAuth'];
		$mailer->Username   = self::$options['Username'];
		$mailer->Password   = self::$options['Password'];
		$mailer->Sender     = self::$options['Sender'];
		$mailer->From       = self::$options['From'];
		$mailer->AddReplyTo = self::$options['AddReplyTo'];

		return $mailer;
	}

}
