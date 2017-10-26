<?php

namespace MailTweak\Classes;


use MailTweak;

class MessageMapper {

	private $options_smpt = [];

	public function __construct() {
		$this->options_smpt = Options::get( 'all' );
		add_filter( 'MailTweak__message_send', [ $this, 'init' ], 10, 3 );

//		$tt = get_option( 'tt' );
//		$this->init( $tt[0], $tt[1], $tt[2] );
	}

	public function init( $mail_args, $type, $raw_text ) {
		global $MailTweak_MapperHelper_tmp;

		$options = get_option( MailTweak::$slug . "_texts_settings" );

		if ( 'on' === $this->option_extractor( 'status', $type, $options ) ) {

			update_option( 'vv', $MailTweak_MapperHelper_tmp );

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
		global $MailTweak_MapperHelper_tmp;

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

			return apply_filters(
				'MailTweak__message_mapper_fields_extractor_filter',
				$options[ $type . '-' . $field ],
				$type,
				$field,
				$MailTweak_MapperHelper_tmp
			);
		}

		return false;
	}
}