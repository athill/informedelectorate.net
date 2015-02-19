<?php

class DefaultTemplate extends \Athill\Utils\Templates\DefaultTemplate {
	protected $jsModules = array('jquery', 'bootstrap', 'superfish');
	protected $css = ['/css/default.css'];
	protected $js = ['/js/site.js'];

}