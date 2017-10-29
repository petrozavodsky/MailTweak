<?php

namespace MailTweak\Classes;


class ClassHelpTab {

	private $data = [];
	private $shortcode = '';

	public function __construct() {
		$this->data = MapperHelper::$tags_descriptions_help_tab;
		add_action( 'admin_head', [ $this, 'router' ] );
		$this->shortcode = MessageMailShortcodes::$shortcode_name;
	}

	public function router() {
		$screen = get_current_screen();

		if ( "toplevel_page_mail-tweak" === $screen->base ) {
			$this->tab( $screen );
		}
	}

	public function tab( $screen ) {

		foreach ( $this->data as $key => $val ) {
			$screen->add_help_tab(
				[
					'id'      => str_replace( ' ', '_', lcfirst( $key ) ),
					'title'   => $key,
					'content' => $this->content_generate( $val )
				]
			);
		}
	}

	private function content_generate( $val ) {
		$res = '';
		foreach ( $val as $p => $b ) {
			$res .= "<p><strong>{$b}: </strong> [{$this->shortcode}  type='{$p}'] </p>";
		}

		return $res;
	}

}