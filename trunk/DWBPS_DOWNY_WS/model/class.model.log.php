<?php
class ModelLog extends Model
{
	public $_table = '';

	public function __construct()
	{
	}

	public function getAll()
	{
		$logs = array();
		$files = array('f.txt', 'm.txt');
		foreach($files as $file)
		{
			$type = str_replace('.txt', '', $file);
			$contents = file_get_contents(APP_DIR_DOC . $file);
			$contents = explode("\n", $contents);
			foreach($contents as $v)
			{
				$v = explode("\t", $v, 2);
				if(count($v) == 2)
				{
					$v[0] = strtotime($v[0]);
					$logs[] = array(
						'create_time' => $v[0], 
						'content' => $v[1], 
						'type' => $type, 
						'y' => date('Y', $v[0]), 
						'm' => date('m', $v[0]), 
						'd' => date('d', $v[0])
					);
				}
			}
		}
		return $logs;
	}
}
