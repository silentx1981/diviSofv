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

		if ($game['status'])
			$status = '	<div class="status">
							<i class="fas fa-exclamation-triangle"></i> '.$game['status'].'
						</div>';
		if ($game['hometeam'] === 'teamA')
			$game['homeA'] = 'home';
		if ($game['hometeam'] === 'teamB')
			$game['homeB'] = 'home';


		$result = '	<div class="game">
						<div class="type">
							'.$game['type'].'
						</div>
						'.($status ?? null).'
						<div class="time">
							<div class="icon">
								<i class="far fa-2x fa-clock"></i>
							</div>
							<div class="text">
								'.$game['date']->format('H:i').'
							</div>
						</div>
						<div class="team">
							<div class="name '.($game['homeA'] ?? null).'">
								'.$game['teamA'].'
							</div>
							<div class="result">
								'.$game['resultA'].'
							</div>
						</div>
						<div class="team">
							<p class="name '.($game['homeB'] ?? null).'">
								'.$game['teamB'].'
							</p>
							<p class="result">
								'.$game['resultB'].'
							</p>
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
		foreach ($gameday as $key => $game) {
			$bodyText .= $this->renderGame($game);
			if (isset($gameday[$key + 1]))
				$bodyText .= '<hr>';
		}
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
		$content = '';
		foreach ($gamedays as $gamedaykey => $gameday) {
			$content .= '<div>';
			$content .= $this->renderGameDay($gamedaykey, $gameday);
			$content .= '</div>';
		}

		$result = '	<div class="sofvGameGrid">
						'.$content.'
					</div>';

		return $result;
	}
}

new SOFV_SofvGames();
