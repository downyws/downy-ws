<?php
$params = $this->_submit->obtain($_REQUEST, [
	'height' => [['format', 'trim']],
	'weight' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', ['who', 'asia', 'china']]]
]);

$this->assign('data', $params);
