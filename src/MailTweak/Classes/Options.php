<?php

namespace MailTweak\Classes;


class Options {

	private $option_base;
	private $options = [];

	public function __construct( $options, $slug ) {
		$this->options     = $options;
		$this->option_base = $slug;
	}

	public function get() {
		$options = get_option( $this->option_base, $this->options );

	}

	public function update( $array = [] ) {

	}


	public function flush() {

	}

}