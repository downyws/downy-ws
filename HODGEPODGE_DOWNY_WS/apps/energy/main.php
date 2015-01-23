<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'cate' => [['valid', 'in', '', '', [
		'energy', 'fuel-consumption', 'power'
	]]],
	'type' => [['valid', 'in', '', '', [
		'kWh', 'MJ', 'kJ', 'J', 'Ws',
		'eV', 'quad', 'therm', 'BTU', 'foot-pound',
		'kcal', 'cal', 'th',
		'l/km', 'l/10km', 'l/100km', 'km/l', 'mpg',
		'gallons_per_100_miles', 'mpg:1', 'mpl', 'gallons_per_100_miles:1',
		'Milliwatt', 'W', 'kW', 'MW', 'J/s',
		'hp', 'mhp', 'ehp', 'bhp', 'foot-pound/min',
		'foot-pound/s', 'dBm', 'cal/h', 'kcal/h', 'BTU/h',
		'BTU/s', 'ton of refrigeration'
	]]]
]);

$this->assign('data', $params);
