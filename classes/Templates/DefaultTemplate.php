<?php namespace Classes\Templates;

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
		$h->oheader(['id'=>'header']);
		$h->odiv(['class'=>'banner']);
		//// mobile menu toggle
		$h->a('', '<i class="fa fa-bars"></i>', [
				'id'=>'menu-toggle',
				'class'=>'hidden-md hidden-lg'
			]
		);
		//// content
		$h->odiv(['id'=>'banner-content']);
		$h->h1($site['sitename']);
		$h->cdiv('#banner-content');
		$h->cdiv('./banner');
		$h->odiv(['class'=>'top-nav']);
		//// breadcrumbs
		$this->breadcrumbs([ 
			'navatts'=>['class'=>'breadcrumbs'] 
		]);
		//// top menu
		$this->menu([
			'navatts'=>['class'=>'top-menu clearfix hidden-xs hidden-sm']
		]);
		$h->cdiv('/.top-nav');
		$h->cheader('/#header');
		//// mobile menu
		$this->menu([
			'navatts'=>['id'=>'mobile-menu', 'class'=>'accordion-menu hidden-md hidden-lg'],
			'depth'=>2
		]);

	}	

}