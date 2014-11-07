<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']]
]);

$this->assign('data', $params);
