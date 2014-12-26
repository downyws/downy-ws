<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'cate' => [['valid', 'in', '', '', [
		'capacitance', 'electric-charge', 'electric-current', 'electric-potential', 'electrical-conductance',
		'electrical-resistance', 'inductance'
	]]],
	'type' => [['valid', 'in', '', '', [
		'F', 'daF', 'hF', 'kF', 'MF',
		'GF', 'TF', 'PF', 'EF', 'ZF',
		'YF', 'dF', 'cF', 'mF', 'µF',
		'nF', 'pF', 'fF', 'aF', 'zF',
		'yF', 'C/V', 'abF', 'statF', 'nC',
		'µC', 'mC', 'C', 'kC', 'MC',
		'abC', 'mAh', 'Ah', 'F', 'statC',
		'e', 'nA', 'µA', 'mA', 'A',
		'kA', 'MA', 'GA', 'aA', 'C/s',
		'nV', 'µV', 'mV', 'V', 'kV',
		'MV', 'GV', 'W/A', 'abV', 'stV',
		'nS', 'µS', 'mS', 'S', 'kS',
		'MS', 'GS', 'muou', 'A/V', 'nΩ',
		'µΩ', 'mΩ', 'Ω', 'kΩ', 'MΩ',
		'GΩ', 'abΩ', 'V/A', 'nH', 'µH',
		'mH', 'H', 'kH', 'MH', 'GH',
		'abH', 'Wb/A'
	]]],
]);

$this->assign('data', $params);
