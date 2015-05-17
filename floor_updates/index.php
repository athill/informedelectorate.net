<?php 
require('../setup.inc.php');
$local = [];

$page = new \Athill\Utils\Page($local);


$sun = new \Classes\Api\Sunlight($_ENV['api']['sunlight']['key']);




$data = $sun->getData('congress3', '/floor_updates', array());
// $h->pa($data['floor_updates']);

// $h->pa($data);


$tdata = array();
foreach ($data['results'] as $update) {
	// $h->cotr();
	// $date = formatDate($update['legislative_day']);
	$bills = array();
	foreach ($update['bill_ids'] as $bill) {
		$bills[] = array('href'=>'http://www.opencongress.org/bill/'.$bill.'/show', 
				'display'=>$bill,
				'atts'=>'target="_blank"'

		);
	}
	$tdata[] = array(
		getTime($update['timestamp']),
		ucfirst($update['chamber']),
		$update['update'],
		trim($h->rtn('linkList', array($bills)))
	);
}	

$h->simpleTable(array(
	'headers'=>array('Date', 'Chamber', 'Event', 'Bills'),
	'data'=>$tdata,
	'atts'=>'class="data-table"'
));


$page->end();


function formatDate($datestring) {
	// return $datestring;
	return preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$2/$3/$1', $datestring);
	return date('m/d/Y', strftime($datestring));
}

function getTime($timestamp) {
	return date('m/d/Y n:ia', strtotime($timestamp));
}
