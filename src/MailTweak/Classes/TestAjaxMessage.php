<?php

namespace MailTweak\Classes;

use MailTweak;
use MailTweak\Utils\Assets;
use MailTweak\Utils\Ajax;

class TestAjaxMessage extends Ajax {
	use Assets;

	public static $action_name = 'MailTweakTestAjaxMessage';
	private $version;
	private $page;


	function __construct( $page, $version ) {
		$this->page;
		$this->js( 'admin_print_scripts-mail-tweak_page_mail-tweak-smtp-' . $page );
		parent::__construct( self::$action_name, 'admin' );

		add_action( 'MailTweak__settings_form_before', [ $this, 'ajax_form' ], 10, 1 );
	}

	public function ajax_form( $page ) {
		if ( "mail-tweak-smtp" === $page ):
			?>
            <div class="<?php echo self::$action_name; ?>" data-action="<?php echo $this->ajax_url_action;?>" >
            <div class="<?php echo self::$action_name; ?>__wrap">
                <div class="<?php echo self::$action_name; ?>__item">
                    <h3>
						<?php _e( 'Send test email',MailTweak::$textdomine ); ?>
                    </h3>
                </div>
                <div class="<?php echo self::$action_name; ?>__item">
                    <input type="email" placeholder="<?php _e( 'Email', MailTweak::$textdomine ); ?>"
                           class="<?php echo self::$action_name; ?>__input" data-id="subject"/>
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

	/**
	 * @param string $request
	 */
	public function callback( $request ) {
		unset( $request['action'] );
		var_dump( $request );
		die;
	}
}