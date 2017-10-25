<?php

namespace MailTweak\Classes;


class CommentApprovedAlert {
	private $textdomine;
	public $mail_subject;

	public function __construct() {
		$this->textdomine = \MailTweak::$textdomine;

		$this->mail_subject = sprintf( get_bloginfo( 'name' ), __( 'Comment has been approved', $this->textdomine ) );

		add_action( 'comment_unapproved_to_approved', [ $this, "approved" ] );
	}

	public function approved( $comment ) {

		if ( false === is_email( $comment->comment_author_email ) ) {
			return;
		}

		$message = sprintf( __( 'Hi %1$s, comment %2$s has been approved', $this->textdomine ), $comment->comment_author, get_comment_link( $comment ) );


		wp_mail(
			$comment->comment_author_email,
			$this->mail_subject,
			apply_filters( 'MailTweak__comment-approved', $message, $comment->comment_author, get_comment_link( $comment ) )
		);
	}
}