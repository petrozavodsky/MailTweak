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
		add_action('phpmailer_init',[$this, 'wp_smtp']);
	}

	function wp_smtp($phpmailer){
		$wsOptions = get_option("wp_smtp_options");;
		if( !is_email($wsOptions["from"]) || empty($wsOptions["host"]) ){
			return;
		}
		$phpmailer->Mailer = "smtp";
		$phpmailer->From = $wsOptions["from"];
		$phpmailer->FromName = $wsOptions["fromname"];
		$phpmailer->Sender = $phpmailer->From; //Return-Path
		$phpmailer->AddReplyTo($phpmailer->From,$phpmailer->FromName); //Reply-To
		$phpmailer->Host = $wsOptions["host"];
		$phpmailer->SMTPSecure = $wsOptions["smtpsecure"];
		$phpmailer->Port = $wsOptions["port"];
		$phpmailer->SMTPAuth = ($wsOptions["smtpauth"]=="yes") ? TRUE : FALSE;
		if($phpmailer->SMTPAuth){
			$phpmailer->Username = $wsOptions["username"];
			$phpmailer->Password = $wsOptions["password"];
		}
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
