<?php
require_once('../inc/conf/setup.php');

$page = new Page(array(
	'jsModules'=>array('ui'=>true),
));

$headers = array(
	'state'=>'State',
	'corporate'=>'State corporate income tax collections',
	'income'=>'State individual income tax collections',
	'sales'=>'State general sales tax collections',
	'property'=>'State property tax collections',
	'total'=>'State total tax collections',
);
$sequence = array('state', 'corporate', 'income', 'sales', 'property', 'total');
$seqlen = count($sequence); 

$lines = file('rawnumbers.txt');
$state = '';

$states = array();
foreach ($lines as $i => $line) {
	$line = preg_replace('/[$,]/', '', trim($line));
	$col = $i % $seqlen;
	if ($col == 0) {
		$state = $line;
		$states[$state] = array();
	} else {
		$states[$state][$sequence[$col]] = $line;
	}
}

file_put_contents('data.json', json_encode($states));

$page->end();
?>

Source: US Census Bureau, 2011 reports.
* The US Census Bureau does not classify revenue from Texas’s margin tax as corporate income tax revenue.
Note: “$0” means no tax was collected or the amount was too insignificant to count.
State Tax Collections per Capita by Category, 2011

http://mercatus.org/publication/primer-state-and-local-tax-policy-trade-offs-among-tax-instruments

http://bl.ocks.org/mbostock/4090848