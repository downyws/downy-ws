<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'time' => [['valid', 'in', '', '', ['s', 'i', 'h', 'd', 'y']]],
	'type' => [['valid', 'in', '', '', [
		'km3', 'm3', 'dm3', 'cm3', 'mm3',
		'in3', 'ft3', 'gallons_per_us_liquid', 'gallons_per_imperial', 'l',
		'cubic_miles_per', 'acre-feet_per', 'bushels_per_us', 'bushels_per_imperial', 'mg',
		'g', 'kg', 't', 'ounce', 'lb',
		'short ton', 'long ton'
	]]]
]);

$this->assign('data', $params);
