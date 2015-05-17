<?php namespace Classes\Templates;

class DefaultTemplate extends \Athill\Utils\Templates\DefaultTemplate {
	protected $jsModules = array('jquery', 'bootstrap', 'superfish', 'metisMenu', 'fontawesome',
		'jquery-equal-width-children');
	protected $css = ['http://fonts.googleapis.com/css?family=UnifrakturMaguntia', '/css/default.css'];
	protected $js = ['/js/site.js'];

	function __construct() {
		global $site;
		parent::__construct();
	}

	protected function heading() {
		global $h, $site;
		$h->oheader(['id'=>'header']);
		$h->odiv(['class'=>'banner']);
		$h->odiv(['class'=>'row']);
		//// mobile menu toggle
		$h->odiv(['class'=>'hidden-md hidden-lg col-xs-2', 'id'=>'menu-toggle']);
		$h->a('', '<i class="fa fa-bars"></i>', []);
		$h->cdiv();
		//// content
		$h->odiv(['class'=>'banner-content col-xs-10 col-md-12']);
		// $h->h1($site['sitename']);
		$h->h1($site['sitename'], 'class="page-title"');
		$h->div('<q>Whenever the people are well-informed, they can be trusted with their own government</q> &ndash;<cite>Thomas Jefferson, Letter to Richard Price (8 January 1789)</cite>', 
				['class'=>'citation']);		
		$h->cdiv('/.banner-content');
		$h->cdiv('/.row');
		$h->cdiv('./banner');
		$h->odiv(['class'=>'top-nav']);
		//// top menu
		$this->menu([
			'navatts'=>['class'=>'top-menu clearfix hidden-xs hidden-sm']
		]);
		//// breadcrumbs
		$this->breadcrumbs([ 
			'navatts'=>['class'=>'breadcrumbs'] 
		]);		
		$h->cdiv('/.top-nav');
		$h->cheader('/#header');
		//// mobile menu
		$this->menu([
			'navatts'=>['id'=>'mobile-menu', 'class'=>'accordion-menu hidden-md hidden-lg'],
			'depth'=>2
		]);

	}

	protected function beginContent() {
		global $site, $h;
		$this->messages();
	}		

}