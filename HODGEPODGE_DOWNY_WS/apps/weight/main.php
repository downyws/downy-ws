<?php
$datas = [
	'公制' => [
		't' => ['吨 (t)', '0.001|0'],
		'kN' => ['千牛顿 (kN)', '0.009806652|0'],
		'kg' => ['公斤 (kg)', '1|0'],
		'hg' => ['百克 (hg)', '10'],
		'g' => ['克 (g)', '1000|0'],
		'karat' => ['克拉', '5000|0'],
		'centigram' => ['厘克', '100000|0'],
		'mg' => ['毫克 (mg)', '1000000|0'],
		'µg' => ['微克 (µg)', '1000000000|0'],
		'ng' => ['纳克 (ng)', '1000000000000|0'],
		'u' => ['原子质量单位 (u)', '6.022045e26|0']
	],
	'美制重量' => [
		'long_ton' => ['长吨 (英吨)', '0.0009842065|0'],
		'short_ton' => ['短吨 (美吨)', '0.001102311|0'],
		'long_hundredweight' => ['长担 (英担)', '0.01968412|0'],
		'short_hundredweight' => ['短担 (美担)', '0.02204621|0'],
		'stone' => ['英石', '0.1574729|0'],
		'lb' => ['磅 (lb)', '2.204621|0'],
		'ounce:1' => ['盎司', '35.27394|0'],
		'dr' => ['打兰 (dr)', '564.3830|0'],
		'gr' => ['格令 (gr)', '15432.34875|0']
	],
	'金衡制' => [
		'pound' => ['磅', '2.6792288850259588209325612878095|0'],
		'ounce' => ['盎司', '32.150747395564405727514185231258|0'],
		'pennyweight' => ['本尼威特', '642.9506|0'],
		'carat' => ['克拉', '4877.561'],
		'grain' => ['谷', '15432.35837774952280857155301778'],
		'mite' => ['Mite（重量单位）', '308616.4'],
		'doite' => ['文', '7406796']
	],
	'日本' => [
		'koku' => ['石', '0.005542993'],
		'kann' => ['卡恩', '0.2666401'],
		'kinn' => ['日斤', '1.666501'],
		'monnme' => ['两（中国制）', '266.6402']
	],
	'中国制' => [
		'tael' => ['两', '26.46430'],
		'ku_ping' => ['库平两', '26.79796']
	],
	'旧瑞典' => [
		'skeppspund' => ['Skeppspund（旧瑞典重量单位）', '0.005881768'],
		'lispund' => ['Lispund（丹麦及挪威质量单位）', '0.1176077'],
		'skålpund' => ['Skålpund（瑞典重量单位）', '2.352707'],
		'mark' => ['马克', '4.705414'],
		'uns' => ['107元素', '35.83873'],
		'lod' => ['Lod（重量单位）', '75.18049']
	]
];
$this->assign('datas', $datas);

$convert = "
	if(to == from){
		return val;
	}else if(to.indexOf('|') != -1 && from.indexOf('|') != -1){
		to = to.split('|');
		from = from.split('|');
		return to[0] / from[0] * val;
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
