<?php 
require('../setup.inc.php');
$local = [
	'layout'=>[
		'leftsidebar'=> [['type'=>'menu']],
	],
	'jsModules'=>['treemenu'=>true]
];

$page = new \Athill\Utils\Page($local);

echo $site['view'];

$page->end();