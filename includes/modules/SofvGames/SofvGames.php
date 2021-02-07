<?php
echo "<br><br><br><br><br>---";

require_once(WP_PLUGIN_DIR.'/divi_sofv/vendor/autoload.php');

use Silentx\Sofv;
use DateTimeZone;

class SOFV_SofvGames extends ET_Builder_Module {

	public $slug       = 'sofv_sofv_games';
	public $vb_support = 'on';
	public $use_raw_content = true;
	public $timezone;

	protected $module_credits = array(
		'module_uri' => 'https://wyssinet.ch/diviSofv',
		'author'     => 'Raffael Wyss',
		'author_uri' => 'https://wyssinet.ch',f
	);

	public function init() {
		$this->name = esc_html__( 'Sofv Games', 'test' );
		$this->timezone = new DateTimeZone(wp_timezone_string());
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

		$url = "https://www.sofv.ch/solothurner-fussballverband/vereine-sofv/verein-sofv.aspx/v-1831/a-as/";
		$games = new Sofv\Games($url);

		$gamedays = $games->getGames();

		echo "<pre>";
		print_r($gamedays);
		echo "</pre>";

		$content = $this->renderGridDay($gamedays);

		return $this->content = $content;
	}

	private function renderGame($game)
	{
		$result = '	<div class="game">
						<div class="type">
							'.$game['type'].'
						</div>
						<div class="status">
							'.$game['status'].'
						</div>
						<div class="time">
							'.$game['date']->format('H:i').'
						</div>
						<div class="teamA">
							<div class="name">
								'.$game['teamA'].'
							</div>
							<div class="result">
								'.$game['resultA'].'
							</div>
						</div>
						<div class="teamB">
							<div class="name">
								'.$game['teamB'].'
							</div>
							<div class="result">
								'.$game['resultB'].'
							</div>
						</div>
					</div>';

		return $result;
	}

	private function renderGameDay(string $gamedaykey, array $gameday)
	{
		/** @var DateTime $date */
		$date = new DateTime($gamedaykey, $this->timezone);
		$headerText = $date->format('l d.m.Y');
		$bodyText = '';
		foreach ($gameday as $game)
			$bodyText .= $this->renderGame($game);
		$result =   '<div class="sofvGameDays">
						<div class="header">
							'.$headerText.'
						</div>
						<div class="body">
							'.$bodyText.'
						</div>
					</div>';
		return $result;
	}

	private function renderGridDay($gamedays)
	{
		$result = '';
		foreach ($gamedays as $gamedaykey => $gameday) {
			$result .= $this->renderGameDay($gamedaykey, $gameday);
		}

		return $result;
	}
}

new SOFV_SofvGames();
