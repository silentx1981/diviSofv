<?php
echo "<br><br><br><br><br>---";

require_once(WP_PLUGIN_DIR.'/divi_sofv/vendor/autoload.php');

use Silentx\Sofv;

class SOFV_SofvGames extends ET_Builder_Module {

	public $slug       = 'sofv_sofv_games';
	public $vb_support = 'on';
	public $use_raw_content = true;

	protected $module_credits = array(
		'module_uri' => 'https://wyssinet.ch/diviSofv',
		'author'     => 'Raffael Wyss',
		'author_uri' => 'https://wyssinet.ch',f
	);

	public function init() {
		$this->name = esc_html__( 'Sofv Games', 'test' );
	}

	public function get_fields() {
		return [
			'content'     => array(
				'label'           => esc_html__( 'Content', 'simp-simple-extension' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear below the heading text.', 'simp-simple-extension' ),
				'toggle_slug'     => 'main_content',
			),
		];
	}

	public function render( $attrs, $content = null, $render_slug ) {

		$games = new Sofv\Games();

		return $this->content = '<div>'.$games->getCurrentGames().'</div>';
	}
}

new SOFV_SofvGames;
