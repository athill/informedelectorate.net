<?php
require_once('../conf/setup.php');

$local = [
	'jsModules'=>['d3'=>true]
];

$page = new \Athill\Utils\Page($local);

$debug = false;

$h->h2('Key Words');

$h->p('Use this page to see how many times legislators have used key terms. For example, "free market" or "iraq". 
		Uses the Sunlight <a href="http://sunlightfoundation.com/api/" target="_blank">Capital Words API</a> for the 
		data and <a href="http://d3js.org/" target="_blank">D3.js</a> to render the graph.');
$h->oform('', 'get');
$h->label('words', "Search for key words:");
$h->intext('words', $h->getVal('words'));
$h->submit('s', "Search");
$h->cform();

if (array_key_exists('words', $_GET)) {
	$h->h4('Results for "'.$_GET['words'].'"');
	$h->div('', 'id="chart"');
	$h->scriptfile('/words/scripts.js');
}
$page->end();