<?php
require_once('setup.inc.php');


$_SESSION['flash']['info'][] = 'test';
$local = [
	'layout'=>['rightsidebar'=>[]]
];

$page = new \Athill\Utils\Page($local);
//// content
$h->p('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sit amet tellus ultricies, malesuada ex non, porta odio. Nunc elementum bibendum vestibulum. Cras commodo tincidunt libero at suscipit. Vestibulum suscipit justo in pellentesque blandit. Vivamus hendrerit est lobortis eros aliquet bibendum. Ut sodales diam nibh, et tempor magna posuere ac. In tristique efficitur ornare. Phasellus pretium, sapien eu placerat vehicula, felis ex placerat sapien, vel venenatis felis felis non leo. Vivamus lorem lacus, consequat id euismod interdum, mollis a augue. ');

$h->a('/', 'link');

$menu = $site['utils']['utils']->readJson('menu.json');
//$h->pa($menu);
$template = '<?php 
require(\'%ssetup.inc.php\');
$local = [];

$page = new \Athill\Utils\Page($local);

$page->end();
';
$site['utils']['menu']->generateFileStructure(['template'=>$template]);

// foreach ($menu as $entry) {
// 	$href = $entry['href'];
// 	if ($href == '/') {
// 		continue;
// 	}
// 	$h->div($site['fileroot'].$href);
// }

$page->end();