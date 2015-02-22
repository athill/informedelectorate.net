<?php
require_once('setup.inc.php');
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

$local = [
	'layout'=>[
		'leftsidebar'=> [['type'=>'content', 'content'=>'left side bar']],
		'rightsidebar'=> $rightsidebar,
	],
];

$page = new \Athill\Utils\Page($local);


//// move to heading
$menuUtils = new \Athill\Utils\MenuUtils('/nested/nest2/nest2.2/nest2.2.1.php');
$breadcrumbs = $menuUtils->getBreadcrumbs();
// $h->pa($breadcrumbs);
$h->onav('id="breadcrumbs"');
$lastbc = count($breadcrumbs) - 1;
// $delim = '&gt;';
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
$h->onav('id="top-menu" class="clearfix"');
$menuUtils->renderMenu();
$h->cnav('.#top-menu');
//// content
$h->h2($site['pagetitle']);
$h->p('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sit amet tellus ultricies, malesuada ex non, porta odio. Nunc elementum bibendum vestibulum. Cras commodo tincidunt libero at suscipit. Vestibulum suscipit justo in pellentesque blandit. Vivamus hendrerit est lobortis eros aliquet bibendum. Ut sodales diam nibh, et tempor magna posuere ac. In tristique efficitur ornare. Phasellus pretium, sapien eu placerat vehicula, felis ex placerat sapien, vel venenatis felis felis non leo. Vivamus lorem lacus, consequat id euismod interdum, mollis a augue. ');




////// generate directory structure
// $template = '<?php 
// require(\'%ssetup.inc.php\');
// $local = [
// 	\'layout\'=>[
// 		\'leftsidebar\'=> [[\'type\'=>\'content\', \'content\'=>\'left side bar\']],
// 	],
// ];

// $page = new \Athill\Utils\Page($local);

// $page->end();
// ';

// $menuUtils->generateFileStructure(['template'=>$template]);
$page->end();