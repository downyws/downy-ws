<?php
$datas = [
	'风速' => [
		'蒲福风级' =>			[0,		1,		2,		3,		4,		5,		6,		7,		8,		9,		10,		11,		12],
		'公里每小时（最低）' =>	[0,		1.1,	5.6,	12,		20,		29,		39,		50,		62,		75,		89,		103,	118],
		'公里每小时（最高）' =>	[1,		5.5,	11,		19,		28,		38,		49,		61,		74,		88,		102,	117,	200],
		'英里每小时（最低）' =>	[0,		1,		4,		8,		13,		18,		25,		31,		39,		47,		55,		64,		73],
		'英里每小时（最高）' =>	[1,		3,		7,		12,		17,		24,		30,		38,		46,		54,		63,		72,		100],
		'节（最低）' =>			[0,		1,		3,		7,		11,		16,		21,		27,		34,		41,		48,		56,		64],
		'节（最高）' =>			[1,		2,		6,		10,		15,		20,		26,		33,		40,		47,		55,		63,		100],
		'米每秒（最低）' =>		[0,		0.3,	1.6,	3.4,	5.5,	8.0,	10.8,	13.9,	17.2,	20.8,	24.5,	28.5,	32.7],
		'米每秒（最高）' =>		[0.3,	1.5,	3.4,	5.4,	7.9,	10.7,	13.8,	17.1,	20.7,	24.4,	28.4,	32.6,	50],
		'米（最低）' =>			[0,		0.01,	0.2,	0.5,	1,		2,		3,		4,		5.5,	7,		9,		11.5,	14],
		'米（最高）' =>			[0,		0.2,	0.5,	1,		2,		3,		4,		5.5,	7.5,	10,		12.5,	16,		20],
		'尺（最低）' =>			[0,		0.01,	1,		2,		3.5,	6,		9,		13,		18,		23,		29,		37,		46],
		'尺（最高）' =>			[0,		1,		2,		3.5,	6,		9,		13,		19,		25,		32,		41,		52,		100]
	]
];

$this->assign('datas', $datas);