<?php

namespace MailTweak\Classes;


class SetContentType {

	public function __construct() {
		add_filter('wp_mail_content_type', [$this, 'content_type'],10 ,1);
	}

	public function content_type($content_type){

		return "text/html";
	}
}