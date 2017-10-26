<?php

namespace MailTweak\Classes;


use MailTweak;

class MessageMapper {

	private $options_smpt = [];

	public function __construct() {

		$this->options_smpt = Options::get( 'all' );
		add_filter( 'MailTweak__message_send', [ $this, 'init' ], 10, 3 );
	}

	public function init( $mail_args, $type, $raw_text ) {

		$options = get_option( MailTweak::$slug . "_texts_settings" );

		if ( 'on' === $this->option_extractor( 'status', $type, $options ) ) {

			$from_name = $this->options_smpt['FromName'];
			$from      = $this->options_smpt['From'];

			$message = $this->option_extractor( 'message', $type, $options );
			$subject = $this->option_extractor( 'subject', $type, $options );

			if ( $message ) {
				$mail_args['message'] = $message;
			}

			if ( $subject ) {
				$mail_args['subject'] = $subject;
			}

			if ( false !== $this->option_extractor( 'from-name', $type, $options ) ) {
				$from_name = $this->option_extractor( 'from-name', $type, $options );
			}

			if ( false !== $this->option_extractor( 'email', $type, $options ) ) {
				$from = $this->option_extractor( 'email', $type, $options );
			}

			add_action( 'MailTweak__phpmailer', function ( $phpmailer ) use ( $from_name, $from ) {
				if ( false !== $from ) {
					$phpmailer->From = $from;
				}

				if ( false !== $from_name ) {
					$phpmailer->FromName = $from_name;
				}

				$phpmailer->AddReplyTo( $phpmailer->From, $phpmailer->FromName );

			} );

		}


		return $mail_args;
	}

	public function option_extractor( $field, $type, $options = [] ) {

		if ( is_array( $options ) && 0 < count( $options ) && array_key_exists( $type . '-' . $field, $options ) ) {

			if ( 'email' === $field ) {
				if ( empty( trim( $options[ $type . '-' . $field ] ) ) || false === is_email( $options[ $type . '-' . $field ] ) ) {
					return false;
				}
			} else if ( in_array( $field, [ 'subject', 'from-name', 'message' ] ) ) {
				if ( empty( trim( $options[ $type . '-' . $field ] ) ) ) {
					return false;
				}
			} else if ( 'status' === $field ) {
				if ( empty( trim( $options[ $type . '-' . $field ] ) ) ) {
					return false;
				}
			}


			return $options[ $type . '-' . $field ];
		}

		return false;
	}
}