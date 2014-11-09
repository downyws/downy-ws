<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'from' => [['valid', 'between', '', 0, [2, 62]]],
	'to' => [['valid', 'between', '', 0, [2, 62]]]
]);

$this->assign('data', $params);
