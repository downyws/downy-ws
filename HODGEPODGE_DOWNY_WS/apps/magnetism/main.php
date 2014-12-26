<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', [
		'pT', 'nT', 'µT', 'mT', 'T',
		'kT', 'megatesla', 'Wb/m2', 'G', 'Mw/cm2',
		'line_per_square_centimetre', 'γ', 'A/m', 'AT/m', 'Oe',
		'Gi/m', 'nWb', 'μWb', 'mWb', 'Wb',
		'Vs', 'Txm2', 'Mw', 'Gxcm2', 'magnetic_flux_quantum',
		'AT', 'Gi'
	]]],
]);

$this->assign('data', $params);
