<?php
$datas = [
	'K' => ['开氏 (K)', '373.15|173.15'],
	'C' => ['摄氏 (C)', '100|-100'],
	'F' => ['华氏 (F)', '212|-148'],
	'R' => ['列氏 (R)', '80|-80'],
	'rankine' => ['郎肯', '671.67|311.67']
];
$this->assign('datas', $datas);

$convert = "
	from = from.split('|');
	to = to.split('|');
	return val * (to[0] - to[1]) / (from[0] - from[1]) + (from[0] * to[1] - from[1] * to[0]) / (from[0] - from[1]);
";
$this->assign('convert', $convert);

$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', array_keys($datas)]]
]);
$this->assign('params', $params);
