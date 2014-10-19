<?php
class Code39
{
	private static $binMap = [
		' ' => '011000100', '$' => '010101000', '%' => '000101010', '*' => '010010100', '+' => '010001010',
		'|' => '010000101', '.' => '110000100', '/' => '010100010', '-' => '010000101', '0' => '000110100',
		'1' => '100100001', '2' => '001100001', '3' => '101100000', '4' => '000110001', '5' => '100110000',
		'6' => '001110000', '7' => '000100101', '8' => '100100100', '9' => '001100100', 'A' => '100001001',
		'B' => '001001001', 'C' => '101001000', 'D' => '000011001', 'E' => '100011000', 'F' => '001011000',
		'G' => '000001101', 'H' => '100001100', 'I' => '001001100', 'J' => '000011100', 'K' => '100000011',
		'L' => '001000011', 'M' => '101000010', 'N' => '000010011', 'O' => '100010010', 'P' => '001010010',
		'Q' => '000000111', 'R' => '100000110', 'S' => '001000110', 'T' => '000010110', 'U' => '110000001',
		'V' => '011000001', 'W' => '111000000', 'X' => '010010001', 'Y' => '110010000', 'Z' => '011010000',
	];

	private static function getMap($char)
	{
		return self::$binMap[$char] ?: self::$this->binMap[' '];
	}

	public static function getDigit($text, $ext = null)
	{
		$text = '*' . strtoupper(ltrim(rtrim(trim($text), '*'), '*')) . '*';

		$digit = '';

		// Lengths per type
		$narrowBar = 1;
		$wideBar = 3;
		$quietBar = 2;

		$charAry = str_split($text);

		$white = 0;
		$color = $black = 1;

		foreach($charAry as $_k => $char)
		{
			$code = str_split(self::getMap($char));
			$color = $black; 

			foreach($code as $k => $bit)
			{
				// Narrow bar
				if ($bit == '0')
				{
					$digit .= str_pad('', $narrowBar, $color, STR_PAD_RIGHT);
				}
				// Wide Bar
				else if($bit == '1')
				{
					$digit .= str_pad('', $wideBar, $color, STR_PAD_RIGHT);
				}
				$color = ($color == $black) ? $white : $black;
			}

			if($_k == (sizeof($charAry) - 1))
			{
				$digit .= '1';
				break;
			}

			$digit .= str_pad('', $quietBar, $color, STR_PAD_RIGHT);
		}

		return $digit;
	}
}
