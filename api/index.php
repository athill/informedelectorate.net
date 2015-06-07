<?php
require_once('../conf/setup.php');
$data = [];
$sun = new \Classes\Api\Sunlight($_ENV['api']['sunlight']['key']);
if (isset($_GET['words'])) {
	$data = $sun->getData('capitolwords', 'phrases/legislator', array('phrase'=>$_GET['words']));	
	
} else if (isset($_GET['legislator'])) {
	$data = $sun->getData('congress3', '/legislators', array('bioguide_id'=>$_GET['legislator']));
} else if (isset($_GET['legislators'])) {
	// $data = $sun->getData('congress3', '/legislators', array('bioguide_id'=>$_GET['legislator']));
	$legislators = explode(',', $_GET['legislators']);
	$data = $sun->getLegislatorData($legislators);
}

header('Content-Type: application/json');

echo json_encode($data);