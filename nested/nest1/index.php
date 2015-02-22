<?php 
require('../../setup.inc.php');
$local = [
	'layout'=>[
		'leftsidebar'=> [['type'=>'content', 'content'=>'left side bar']],
	],
];

$page = new \Athill\Utils\Page($local);

$page->end();
