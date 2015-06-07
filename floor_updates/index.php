<?php 
require('../conf/setup.php');
$local = [];

$page = new \Athill\Utils\Page($local);


$sun = new \Classes\Api\Sunlight($_ENV['api']['sunlight']['key']);
//// get the floor updates
$data = $sun->getCurrentFederalFloorUpdates();


//// build results table
$tdata = array();
foreach ($data['results'] as $update) {
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

//// display results
$h->simpleTable(array(
	'headers'=>array('Date', 'Chamber', 'Event', 'Bills'),
	'data'=>$tdata,
	'atts'=>'class="table data-table"'
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
