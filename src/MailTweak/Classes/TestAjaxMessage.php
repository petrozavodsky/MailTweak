<?php

namespace MailTweak\Classes;

use MailTweak\Utils\Assets;
use MailTweak\Utils\Ajax;

class TestAjaxMessage extends Ajax {
	use Assets;

	private $version;
	private $page;


	function __construct( $page, $version ) {
		$this->page;
		$this->js( 'admin_print_styles-' . $page );
		parent::__construct( "MailTweakTestAjaxMessage", 'admin' );
	}


	public function js( $position ) {
		$handle = $this->addJs(
			'email-test-script',
			$position,
			[ 'jquery' ],
			$this->version
		);

		$this->vars_ajax(
			$handle,
			[
				'ajax_url'        => $this->ajax_url,
				'ajax_url_action' => $this->ajax_url_action,
			]
		);
	}

	/**
	 * @param string $request
	 */
	public function callback( $request ) {
		unset( $request['action'] );
		var_dump( $request );
		die;
	}
}