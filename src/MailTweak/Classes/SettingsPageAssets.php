<?php

namespace MailTweak\Classes;

use MailTweak\Utils\Assets;

class SettingsPageAssets {
	use Assets;

	private $version;

	public function __construct( $page, $version = '1.0.0' ) {
		$this->version = $version;
		$this->AddPageCss( 'admin_print_styles-' . $page );
	}

	public function AddPageCss( $position ) {
		$this->addCss( 'page-style', $position );
	}

}