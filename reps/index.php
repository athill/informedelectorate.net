<?php
require_once('../conf/setup.php');
$page = new \Athill\Utils\Page();

$curl = new \Classes\Curl();

$h->h1('Find Your Representatives');
$h->p('I use <a href="https://developers.google.com/maps/" target="_blank">Google Maps API</a> to translate the address to 
	latitude and longitude and then use data from <a href="http://sunlightfoundation.com/api/" target="_blank">the 
	Sunlight Foundation</a>.');


$h->oform('', 'get');
$h->label('addr', 'Search by address:');
$h->intext('addr', $h->getVal('addr'));
$h->input('submit', 's', 'Search');
$h->cform();

if (array_key_exists('addr', $_GET)) {
	//// get latitude and longitude
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($_GET['addr']).'&sensor=false';
	$json = $curl->get($url);
	$data = json_decode($json, true);

	if (count($data['results']) == 0) {
		$h->div('Invalid Address. Please try again.', 'class="alert"');

	} else {
		$results = $data['results'][0];
		$h->div('Showing results for '.$results['formatted_address']);
		$lat = $results['geometry']['location']['lat'];
		$lng = $results['geometry']['location']['lng'];
		//// Federal legislators
		$sun = new \Classes\Api\Sunlight($_ENV['api']['sunlight']['key']);
		$data = $sun->getFederalLegislatorsByLatLong($lat, $lng);
		$h->div('<strong>Your federal representatives:</strong>');
		$fieldsCallback = function($item) {
			global $sun;
			$fields = array(
				'Name' => $sun->getFullName($item),
				'Chamber' => ucfirst($item['chamber']),
				'District' => $item['district'],
				'Party' => $item['party'],
				'Address' => $item['office'],
				'Website' => $item['website'],
				'Phone' => $item['phone'],
				'YouTube' => 'https://www.youtube.com/user/'.$item['youtube_id'],
				'OpenCongress' => 'http://www.opencongress.org/people/show/'.$item['govtrack_id'],
				'Twitter' => strlen($item['twitter_id']) ? 'https://twitter.com/'.$item['twitter_id'] : '',
				'Facebook' => (strlen($item['facebook_id'])) ? 'https://www.facebook.com/'.$item['facebook_id'] : ''
			);
			return $fields;
		};
		foreach ($data['results'] as $leg) {
			renderLegislator($leg, $fieldsCallback);
			$h->hr();
		}		

		$data = $sun->getStateLegislatorsByLatLong($lat, $lng);
		$h->div('<strong>Your state representatives:</strong>');
		$fieldsCallback = function($leg) {
			$committees = array();
			foreach ($leg['roles'] as $role) {
				if (array_key_exists('committee', $role)) {
					$committees[] = $role['committee'];
				}
			}
			$fields = array(
				'<img src="'.$leg['photo_url'].'" width="50" />'=>$leg['full_name'],
				'District'=>$leg['district'],
				'Party'=>$leg['party'],
				'Website'=>$leg['url'],
				'Chamber'=>ucfirst($leg['chamber']),
				'Phone'=>$leg['offices'][0]['phone'],
				'Committees'=>implode(',', $committees)
			);			
			return $fields;
		};
		foreach ($data as $leg) {
			renderLegislator($leg, $fieldsCallback);
			$h->hr();
		}
	}

}

function renderLegislator($item, $fieldsCallback) {
	global $h, $sun;
	$fields = $fieldsCallback($item);
	$count = 0;
	$h->otable();
	foreach ($fields as $label => $value) {
		if ($count > 0) $h->cotr();
		$h->th($label.': ', 'align="left"');
		if (preg_match('/^https?:/', $value)) {
			$h->startBuffer();
			$h->a($value, $value, 'target=_blank');
			$value = $h->endBuffer();
		}
		$h->td($value);
		$count++;
	}
	$h->ctable();	
}

function render($item) {
	global $h, $sun;
	$h->otable();
	$fields = array(
		'Name' => $sun->getFullName($item),
		'Chamber' => ucfirst($item['chamber']),
		'District' => $item['district'],
		'Party' => $item['party'],
		'Address' => $item['office'],
		'Website' => $item['website'],
		'Phone' => $item['phone'],
		'YouTube' => 'https://www.youtube.com/user/'.$item['youtube_id'],
		'OpenCongress' => 'http://www.opencongress.org/people/show/'.$item['govtrack_id'],
		'Twitter' => strlen($item['twitter_id']) ? 'https://twitter.com/'.$item['twitter_id'] : '',
		'Facebook' => (strlen($item['facebook_id'])) ? 'https://www.facebook.com/'.$item['facebook_id'] : ''
	);
	$count = 0;
	foreach ($fields as $label => $value) {
		if ($count > 0) $h->cotr();
		$h->th($label.': ', 'align="left"');
		if (preg_match('/^https?:/', $value)) {
			$h->startBuffer();
			$h->a($value, $value, 'target=_blank');
			$value = $h->endBuffer();
		}
		$h->td($value);
		$count++;
	}
	$h->ctable();
}
$page->end();
