<?php
$datas = [
	'degree' => ['度', '360|0'],
	'radian' => ['弧度', '6.283185307179586476925286766558'],
	'circle' => ['圆周', '1'],
	'turn' => ['次', '1'],
	'cycle' => ['周期', '1'],
	'rotation' => ['旋转', '1'],
	'revolution' => ['圈', '1'],
	'right_angle' => ['直角', '4'],
	'mil_nato' => ['千分之一寸（北约）', '6400'],
	'mil_soviet_union' => ['千分之一寸（苏联）', '6000'],
	'mil_sweden' => ['千分之一寸（瑞典）', '6300'],
	'grad' => ['度', '400'],
	'gon' => ['哥恩', '400'],
	'point' => ['点', '32'],
	'hour_angle' => ['时角', '24']
];
$this->assign('datas', $datas);

$convert = "
	if(to == from){
		return val;
	}else if(to.indexOf('|') != -1){
		to = to.split('|');
		return val / from * to[0];
	}else if(from.indexOf('|') != -1){
		from = from.split('|');
		return val / from[0] * to;
	}else{
		return (to * val / from);
	}
";
$this->assign('convert', $convert);

$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', array_keys($datas)]]
]);
$this->assign('params', $params);
