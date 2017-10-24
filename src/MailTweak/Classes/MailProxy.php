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
		add_action( 'phpmailer_init', [ $this, 'proxy' ] );

		$this->proxy(
			get_option( 'tt' )
		);
	}

	public function proxy( $mailer ) {

		$options = shortcode_atts(
			self::$options,
			Options::get()
		);

		d($options['From']);

		if ( is_email( $options['From'] ) !== false  && '' !== $options['host'] ) {

			$mailer->Mailer     = $options['Mailer'];
			$mailer->Port       = $options['Port'];
			$mailer->host       = $options['host'];
			$mailer->SMTPSecure = $options['SMTPSecure'];
			$mailer->SMTPAuth   = ( Options::get( 'SMTPAuth' ) === 'yes' ? true : false );

			if ( Options::get( 'SMTPAuth' ) === 'yes' ) {
				$mailer->Username = $options['Username'];
				$mailer->Password = $options['Password'];
			}

			$mailer->Sender   = $options['From'];
			$mailer->From     = $options['From'];
			$mailer->FromName = $options['FromName'];
//			$mailer->AddReplyTo( $options['From'], $options['FromName'] );
		}


		return $mailer;
	}

}
