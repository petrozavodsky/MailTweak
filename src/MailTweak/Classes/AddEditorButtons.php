<?php

namespace MailTweak\Classes;

class AddEditorButtons {


	public function __construct() {

		add_action(
			'current_screen',
			function ( $current_screen ) {
				d( $current_screen );
			}
		);
	}
}