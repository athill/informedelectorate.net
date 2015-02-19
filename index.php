<?php
require_once('setup.inc.php');
$local = [
	'layout'=>[
		'leftsidebar'=> [['type'=>'content', 'content'=>'left side bar']],
		'rightsidebar'=> [['type'=>'content', 'content'=>'right side bar']],
	],
];

$page = new \Athill\Utils\Page($local);
$h->h2($site['pagetitle']);

$h->p('content');
$h->a('http://andyhill.us', 'andyhill.us');

$menu = new \Athill\Utils\MenuUtils('/nested/nest2/nest2.2/nest2.2.1.php');
$breadcrumbs = $menu->getBreadcrumbs();
$h->pa($breadcrumbs);
$h->onav('id="breadcrumbs"');
$lastbc = count($breadcrumbs) - 1;
$delim = '&gt;';
$h->otag('ul');
//// TODO: should this be a list? handled through js/css?
foreach ($breadcrumbs as $i => $breadcrumb){
	if ($i == $lastbc) {
		$h->li($breadcrumb['display']);
	} else {
		$h->li($h->rtn('a', [$breadcrumb['href'], $breadcrumb['display']]));
	}


}
$h->ctag('ul');
$h->cnav('/#breadcrumbs');
$h->onav('id="top-menu"');
$menu->renderMenu();
$h->cnav('.#top-menu');

$page->end();