<?php

namespace MailTweak\Classes;

use MailTweak;
use MailTweak\Utils\Assets;

class AddEditorButtons {
	use Assets;

	private $button_slug = 'mail_tweak_button';

	public function __construct() {
		$this->router();
	}

	public function router() {
		add_action(
			'current_screen',
			function ( $current_screen ) {

				if ( "toplevel_page_mail-tweak" === $current_screen->base ) {
					$this->localize();
					$this->button();
					add_action('admin_print_footer_scripts', [$this,'quicktags']);
				}
			}
		);

	}

	function quicktags() {
		if ( wp_script_is( 'quicktags' ) ) :
			?>
			<script type="text/javascript">
                if (QTags) {
                    QTags.addButton(
                        '<?php echo $this->button_slug;?>',
                        '<?php _e('MailTweak shortcode',MailTweak::$textdomine);?>',
                        function() {
                            var value = prompt(
                                '<?php _e('Shortcode type (see help):',MailTweak::$textdomine);?>',
                                ''
                            );

                            if ( value ) {
                                QTags.insertContent(
                                    '['+<?php echo MessageMailShortcodes::$shortcode_name;?> +' type="'+value+'" ]'
                                );
                            }
                        }
                    );


                }
			</script>
		<?php endif;
	}

	public function button() {
		$this->addCss( 'mce-icon', 'admin' );
		add_filter( "mce_external_plugins", function ( $plugin_array ) {
			$plugin_array[ $this->button_slug ] = $this->url . "public/js/buttons-editor.js";

			return $plugin_array;
		} );

		add_filter( 'mce_buttons', function ( $buttons ) {
			array_push( $buttons, $this->button_slug );

			return $buttons;
		} );

	}

	public function localize() {

		$translation_array = [
			'button_slug' => $this->button_slug,
			'title'       => __( 'MailTweak shortcodes', MailTweak::$slug ),
			'shortcode'     => MessageMailShortcodes::$shortcode_name,
			'info' => MapperHelper::$tags_descriptions_scripts
		];
		wp_localize_script( 'jquery', 'MailTweakButton', $translation_array );
	}

}