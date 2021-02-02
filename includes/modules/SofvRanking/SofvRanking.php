<?php

class SOFV_SofvRanking extends ET_Builder_Module {

	public $slug       = 'sofv_sofv_ranking';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://wyssinet.ch/diviSofv',
		'author'     => 'Raffael Wyss',
		'author_uri' => 'https://wyssinet.ch',
	);

	public function init() {
		$this->name = esc_html__( 'Sofv Ranking', 'divi-sofv_sofv' );
	}

	public function get_fields() {
		return [];
	}

	public function render( $attrs, $content = null, $render_slug ) {
		return sprintf( '<h1>%1$s</h1>', $this->props['content'] );
	}
}

new SOFV_SofvRanking;
