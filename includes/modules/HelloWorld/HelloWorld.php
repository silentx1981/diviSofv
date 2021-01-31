<?php

class DIVI_HelloWorld extends ET_Builder_Module {

	public $slug       = 'divi_hello_world';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://wyssinet.ch/diviSofv',
		'author'     => 'Raffael Wyss',
		'author_uri' => 'https://wyssinet.ch',
	);

	public function init() {
		$this->name = esc_html__( 'Hello World', 'divi-divi_sofv' );
	}

	public function get_fields() {
		return array(
			'content' => array(
				'label'           => esc_html__( 'Content', 'divi-divi_sofv' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'divi-divi_sofv' ),
				'toggle_slug'     => 'main_content',
			),
		);
	}

	public function render( $attrs, $content = null, $render_slug ) {
		return sprintf( '<h1>%1$s</h1>', $this->props['content'] );
	}
}

new DIVI_HelloWorld;
