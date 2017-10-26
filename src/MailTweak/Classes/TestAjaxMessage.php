<?php

namespace MailTweak\Classes;

use MailTweak;
use MailTweak\Utils\Assets;

class TestAjaxMessage {
	use Assets;

	public static $action_name = 'MailTweakTestAjaxMessage';
	private $version;
	private $page;

	function __construct( $version ) {
		$this->page;
		$this->js( 'admin_print_scripts-mail-tweak_page_mail-tweak-smtp' );

		add_action( 'MailTweak__settings_form_before', [ $this, 'ajax_form' ], 10, 1 );
		$this->admin( self::$action_name );
	}

	public function admin( $action_name, $callback = 'callback' ) {
		add_action( 'wp_ajax_' . $action_name, [ $this, $callback ] );
	}

	public function ajax_form( $page ) {
		$ajax_url = add_query_arg( [ 'action' => self::$action_name ], admin_url( 'admin-ajax.php' ) );
		if ( "mail-tweak-smtp" === $page ):
			?>
            <div class="<?php echo self::$action_name; ?>" data-action="<?php echo $ajax_url; ?>">
                <div class="<?php echo self::$action_name; ?>__wrap">
                    <div class="<?php echo self::$action_name; ?>__item">
                        <div class="<?php echo self::$action_name; ?>__alert"  data-id="alert"
                             data-text="<?php  _e('Sending...',MailTweak::$textdomine);?>">

                        </div>
                    </div>
                    <div class="<?php echo self::$action_name; ?>__item">
                        <h3>
							<?php _e( 'Send test email', MailTweak::$textdomine ); ?>
                        </h3>
                    </div>
                    <div class="<?php echo self::$action_name; ?>__item">
                        <input type="email" placeholder="<?php _e( 'Email', MailTweak::$textdomine ); ?>"
                               class="<?php echo self::$action_name; ?>__input" data-id="email"/>
                    </div>
                    <div class="<?php echo self::$action_name; ?>__item">
                        <input type="text" placeholder="<?php _e( 'Subject', MailTweak::$textdomine ); ?>"
                               class="<?php echo self::$action_name; ?>__input" data-id="subject"/>
                    </div>
                    <div class="<?php echo self::$action_name; ?>__item">

                    <textarea placeholder="<?php _e( 'Message', MailTweak::$textdomine ); ?>"
                              class="<?php echo self::$action_name; ?>__textarea" data-id="message"></textarea>
                    </div>
                    <div class="<?php echo self::$action_name; ?>__item">
                        <button type="button" class="button button-primary" data-id="button">
							<?php _e( 'Send test email', MailTweak::$textdomine ); ?>
                        </button>
                    </div>
                </div>
            </div>
		<?php
		endif;
	}

	public function js( $position ) {
		$handle = $this->addJs(
			'email-test-script',
			$position,
			[ 'jquery' ],
			$this->version
		);

	}

	public function callback() {
		$request = $_REQUEST;
		unset( $request['action'] );

		$errors = [];

		if ( false === is_email( $request['email'] ) ) {
			$errors['email'] = true;
		}
		if ( empty( $request['subject'] ) ) {
			$errors['subject'] = true;
		}
		if ( empty( $request['message'] ) ) {
			$errors['message'] = true;
		}

		$st = wp_mail( $request['email'], $request['subject'], $request['message'] );


		if ( 0 < count( $errors ) ) {
			wp_send_json_error( [
				'message'  => __( 'All fields required', MailTweak::$textdomine ),
				'validate' => false,
				'errors'   => $errors
			] );
		}

		if ( $st ) {
			wp_send_json_success(
				[
					'message' => __( 'Email success sent', MailTweak::$textdomine )
				]
			);
		}

		wp_send_json_error( [
			[
				'message'  => __( 'Error sending', MailTweak::$textdomine ),
				'validate' => true
			]
		] );

	}

}