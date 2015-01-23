<?php
$datas = [
	'speed' => ['速度', true, [
		'公制' => [
			'km/s' => ['千米每秒', '0.001000000'],
			'm/s' => ['米每秒', '1'],
			'km/h' => ['千米每小时', '3.600000'],
			'mm/s' => ['毫米每秒 (mm/s)', '1000'],
			'µm/s' => ['微米每秒 (µm/s)', '1000000']
		],
		'英/美' => [
			'mile_per_second' => ['英里每秒', '0.0006213712'],
			'mph' => ['英里每小时 (mph)', '2.236936'],
			'foot_per_second' => ['英尺每秒', '3.280840']
		],
		'航海' => [
			'knot' => ['节', '1.943845']
		],
		'其他' => [
			'speed_of_light' => ['光速', '3.3356409519815204957557671447492e-9'],
			'speed_of_sound' => ['音速', '0.0029154518950437317784256559766764'],
			'speed_of_walk' => ['轻快的步行路程', '0.58823529411764705882352941176471'],
			'speed_of_snail' => ['普通蜗牛的速度', '1000']
		]
	]],
	'acceleration' => ['加速', false, [
		'm/s2' => ['米每平方秒 (m/s²)', '1'],
		'ft/s2' => ['英尺每平方秒 (ft/s²)', '3.280833333333'],
		'gal' => ['伽利略', '100'],
		'milligal' => ['豪伽', '100000'],
		'standard_gravity' => ['标准重力加速度', '0.101971621298'],
		'g' => ['重力加速度 (g)', '0.101971621298']
	]]
];
$this->assign('datas', $datas);

$convert = "
	return val * to / from;
";
$this->assign('convert', $convert);

$cates = [];
foreach($datas as $k => $v)
{
	$cates[] = $k;
}
$types = [];
foreach($datas as $k => $v)
{
	if($v[1])
	{
		foreach($v[2] as $_v)
		{
			foreach($_v as $__k => $__v)
			{
				$types[] = $__k;
			}
		}
	}
	else
	{
		foreach($v[2] as $_k => $_v)
		{
			$types[] = $_k;
		}
	}
}
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'cate' => [['valid', 'in', '', '', $cates]],
	'type' => [['valid', 'in', '', '', $types]]
]);
$this->assign('params', $params);
