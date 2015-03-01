<?php

class DefaultTemplate extends \Athill\Utils\Templates\DefaultTemplate {
	protected $jsModules = array('jquery', 'bootstrap', 'superfish');
	protected $css = ['/css/default.css'];
	protected $js = ['/js/site.js'];

	function __construct() {
		global $site;
		
		$titles = [
			'Buy Stuff You Don\'t Need',
			'Lose Money with This One Simple Trick',
			'Your Doctor Doesn\'t Want You to Use This Trick, Because It\'s a Scam'
		];
		$rightsidebar = [];
		foreach ($titles as $title) {
			$rightsidebar[] = [
				'type'=>'content',
				'content' => '<h5>'.$title.'</h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quis ex dapibus, suscipit sem at, commodo magna. '
			];
		};
		$site['layout']['rightsidebar'] = $rightsidebar;
		parent::__construct();
	}

	protected function heading() {
		global $h, $site;
		$h->oheader('id="header"');
		$h->odiv('class="banner"');
		$h->h1($site['sitename']);
		$h->cdiv('./banner');
		$h->odiv('class="top-nav"');
		$this->breadcrumbs();
		$this->topMenu();
		$h->cdiv('/.top-nav');
		$h->cheader('/#header');
	}	

}