<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', [
		'S', 'cS', 'm2/s', 'cm2/s', 'mm2/s',
		'ft2/s', 'in2/s', 'Pa.s', 'P', 'cP',
		'kg/(m.s)', 'g/(cm.s)', 'N.s/m2', 'lbf.s/in2', 'lbf.s/ft2',
		'lb/(ft.s)', 'lb/(ft.h)', 'slug/(ft.s)', 'poundal_second_per_square_foot', 'dyn.s/cm2'
	]]],
]);

$this->assign('data', $params);
