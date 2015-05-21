<?php 
require('../conf/setup.php');
$local = [
	'jsModules'=>['ui'=>true]
];

$page = new \Athill\Utils\Page($local);

$sun = new \Classes\Api\Sunlight($_ENV['api']['sunlight']['key']);

$meta = $sun->getData('openstates', '/metadata', array());

$options = array();
foreach ($meta as $item) {
	$options[] = $item['abbreviation'].'|'.$item['name'];
}

$h->oform('', 'get');
$h->label('state', 'Select a state:');
$h->select('state', $options, $h->getVal('state'), '', true);
$h->submit('s', 'View Bills');
$h->cform();

// $h->pa($meta);

if (array_key_exists('state', $_GET)) {
	$dateformat = 'm/d/Y G:ia';
	$data = $sun->getData('openstates', '/bills', array('state'=>$_GET['state']));
	// $h->pa($data);
	$tdata = array();
	foreach ($data as $item) {
		$url = 'http://openstates.org/'.$_GET['state'].'/bills/'.$item['session'].'/'.str_replace(' ', '', $item['bill_id']);
		$title = str_replace('"', '&quot;', $item['title']);
		$tdata[] = array(
			'<a href="'.$url.'" target="_blank" title="'.$item['title'].'" class="bill-link">'.$item['bill_id'].'</a>',
			date($dateformat, strtotime($item['created_at'])),
			date($dateformat, strtotime($item['updated_at'])),
			//$item['title'],
			implode(', ', $item['type']),
			(array_key_exists('subjects', $item))? implode(', ', $item['subjects']) : '',

		);
	}
	$h->simpleTable(array(
		'headers'=>array('Bill', 'Created', 'Updated', 'Type', 'Subjects'),
		'data'=>$tdata,
		'atts'=>'class="table"'
	));
}

$h->script('
$(function() {
	$(".bill-link").tooltip();
});
');

$page->end();
