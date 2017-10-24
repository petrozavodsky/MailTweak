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
		'From'       => '',
		'FromName'   => '',
	];

	public function __construct() {
//		add_action('phpmailer_init', [ $this, 'proxy' ] );

	}


/*
	public function proxy( $phpmailer ) {

		$options = shortcode_atts(
			self::$options,
			Options::get()
		);

//		if ( is_email( $options['From'] ) !== false && '' !== $options['host'] ) {

			$phpmailer->Mailer     = $options['Mailer'];
			$phpmailer->Port       = $options['Port'];
			$phpmailer->host       = $options['host'];
			$phpmailer->SMTPSecure = $options['SMTPSecure'];
			$phpmailer->SMTPAuth   = ( Options::get( 'SMTPAuth' ) === 'yes' ? true : false );

			if ( Options::get( 'SMTPAuth' ) === 'yes' ) {
				$phpmailer->Username = $options['Username'];
				$phpmailer->Password = $options['Password'];
			}

			$phpmailer->Sender   = $options['From'];
			$phpmailer->From     = $options['From'];
			$phpmailer->FromName = $options['FromName'];
			$phpmailer->AddReplyTo( $options['From'], $options['FromName'] );
//		}

		return $phpmailer;
	}
*/
}
