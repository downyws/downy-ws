<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', 'l', ['l', 'u', 'fu']]]
]);

$this->assign('data', $params);
