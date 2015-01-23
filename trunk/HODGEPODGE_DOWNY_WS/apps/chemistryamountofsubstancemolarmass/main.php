<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', [
		'mmol', 'mol', 'kmol', 'lb-mol', 'g/mol',
		'kg/mol', 'H', 'O', 'S', 'Cl',
		'Fe', 'H2', 'H2O', 'NaCl', 'Cl2',
		'S8', 'C12H22O11'
	]]]
]);

$this->assign('data', $params);
