<?php
$params = $this->_submit->obtain($_REQUEST, [
	'content' => [['format', 'trim']],
	'cate' => [['valid', 'in', '', '', [
		'bandwidth', 'data-storage'
	]]],
	'type' => [['valid', 'in', '', '', [
		'bit/s', 'kbit/s', 'Mbit/s', 'Gbit/s', 'Tbit/s',
		'Kibit/s', 'Mibit/s', 'Gibit/s', 'Tibit/s', 'B/s',
		'kB/s', 'MB/s', 'GB/s', 'TB/s', 'KiB/s',
		'MiB/s', 'GiB/s', 'TiB/s', 'bit_per_hour', 'kilobit_per_hour',
		'megabit_per_hour', 'gigabit_per_hour', 'terabit_per_hour', 'kibibit_per_hour', 'mebibit_per_hour',
		'gibibit_per_hour', 'tebibit_per_hour', 'byte_per_hour', 'kilobyte_per_hour', 'megabyte_per_hour',
		'gigabyte_per_hour', 'terabyte_per_hour', 'kibibyte_per_hour', 'mebibyte_per_hour', 'gibibyte_per_hour',
		'tebibyte_per_hour', 'bit_per_day', 'kilobit_per_day', 'megabit_per_day', 'gigabit_per_day',
		'terabit_per_day', 'kibibit_per_day', 'mebibit_per_day', 'gibibit_per_day', 'tebibit_per_day',
		'byte_per_day', 'kilobyte_per_day', 'megabyte_per_day', 'gigabyte_per_day', 'terabyte_per_day',
		'kibibyte_per_day', 'mebibyte_per_day', 'gibibyte_per_day', 'tebibyte_per_day',

		'bit', 'nibble', 'kilobit', 'megabit', 'gigabit',
		'terabit', 'petabit', 'exabit', 'B', 'kB',
		'MB', 'GB', 'TB', 'PB', 'EB'
	]]]
]);

$this->assign('data', $params);
