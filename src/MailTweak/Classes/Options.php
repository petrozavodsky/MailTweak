<?php

namespace MailTweak\Classes;


use MailTweak;

class Options {


	public static function get( $key = false ) {

		$options = get_option( MailTweak::$slug, MailProxy::$options );

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