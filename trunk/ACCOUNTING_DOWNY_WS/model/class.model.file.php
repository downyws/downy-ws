<?php
class ModelFile extends Model
{
	public $_table = 'file';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function upload($file)
	{
		$data = fread(fopen($file['tmp_name'], 'r'), filesize($file['tmp_name']));
		$type = $file['type'];
		$hash = md5($data);
		unlink($_FILES['file']['tmp_name']);

		$now = time();
		$id = $this->insert(['detail_id' => 0, 'title' => $file['name'], 'data' => $data, 'type' => $type, 'hash' => $hash, 'create_time' => $now]);
		$result = ($id > 0) ? ['id' => $id, 'create_time' => $now, 'title' => $file['name']] : ['error' => '保存失败'];

		return $result;
	}
}
