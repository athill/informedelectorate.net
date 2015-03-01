<?php
$bower = '/bower_components';
$localjs = '/js';
$localcss = '/css';

return [
	'sequence'=>['jquery', 'bootstrap', 'superfish', 'treemenu'],
	'modules'=>[
		'jquery' => array(
			'root'=>$bower.'/jquery/dist',
			'js'=>array('/jquery.min.js')
		),
		'bootstrap'=>array(
			'js'=>array('/bootstrap/dist/js/bootstrap.min.js'),
			'css'=>array('/css/bootstrap.css')
		),
		'superfish'=>array(
			'js'=>[$bower.'/superfish/dist/js/superfish.min.js'],
			'css'=>[$bower.'/superfish/dist/css/superfish.css'],
		),
		'treemenu'=>array(
			'root'=>$bower.'/jquery.treeview',
			'js'=>['/jquery.treeview.js', '/demo/jquery.cookie.js'],
			'css'=>['/jquery.treeview.css']
		)
	]
];