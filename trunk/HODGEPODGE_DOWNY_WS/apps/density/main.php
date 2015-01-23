<?php
$datas = [
	'公制' => [
		'g/cm3' => ['克每立方厘米 (g/cm³)', '0.001'],
		'kg/m3' => ['千克每立方米 (kg/m³)', '1'],
		'g/m3' => ['克每立方米 (g/m³)', '1000'],
		'mg/m3' => ['毫克每立方米 (mg/m³)', '1000000']
	],
	'英/美' => [
		'oz/gal' => ['盎司每加仑 (oz/gal)', '0.1335264712'],
		'lb/ft3' => ['磅每立方英尺 (lb/ft³)', '0.06242796058'],
		'lb/in3' => ['磅每立方英寸 (lb/in³)', '0.000036127292']
	]
];
$this->assign('datas', $datas);

$convert = "
	return to * val / from;
";
$this->assign('convert', $convert);

$types = [];
foreach($datas as $v)
{
	foreach($v as $_k => $_v)
	{
		$types[] = $_k;
	}
}
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'type' => [['valid', 'in', '', '', $types]]
]);
$this->assign('params', $params);
