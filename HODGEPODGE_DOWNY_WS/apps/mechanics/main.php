<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'cate' => [['valid', 'in', '', '', [
		'force', 'torque', 'pressure'
	]]],
	'type' => [['valid', 'in', '', '', [
		'nN', 'µN', 'mN', 'N', 'kN',
		'meganewton', 'GN', 'dyn', 'pdl', 'J/m',
		'Pa/m2', 'kp', 'sn', 'kip', 'kgf',
		'tnf', 'lbf', 'stnf', 'ltnf', 'ozf',
		'gravet-force', 'mGf', 'Gf',
		'µN.m', 'millinewton_metre', 'Nm', 'kN.m', 'MN.m',
		'pound-force_foot', 'lbf.in', 'ozf.ft', 'ozf.in', 'kgf.m',
		'kp.m', 'gf.cm', 'metre_kg-force', 'ft.lb', 'centimetre_kg-force',
		'inch_ounce-force', 'dyn_cm',
		'MPa', 'bar', 'kgf/cm2', 'kPa', 'hPa',
		'millibar', 'kgf/m2', 'Pa', 'ksi', 'psi',
		'psf', 'mH2O', 'inH2O', 'cmH2O', 'atm',
		'technical_atmosphere', 'inHg', 'cmHg', 'mmHg', 'torr'
	]]]
]);

$this->assign('data', $params);
