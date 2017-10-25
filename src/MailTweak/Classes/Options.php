<?php

namespace MailTweak\Classes;


use MailTweak;

class Options {


	public static function get( $key = false, $type = 'smtp' ) {

		if ( 'smtp' === $type ) {
			$options = get_option( MailTweak::$slug, MailProxy::$options );
		} elseif ( 'messages' === $type ) {
			$options = get_option( MailTweak::$slug . '_texts_settings', [] );
		} else {
			return false;
		}

		if ( 'all' === $key ) {
			return $options;
		}

		if ( false !== $key && array_key_exists( $key, $options ) ) {
			return $options[ $key ];
		}

		return $options;
	}

	public static function update( $array = [] ) {

	}


	public static function flush() {

	}

}