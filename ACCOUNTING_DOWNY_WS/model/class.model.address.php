<?php
class ModelAddress extends Model
{
	public $_table = 'address';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function search($title, $result_size = ADDRESS_SEARCH_SIZE)
	{
		$sql =  ' SELECT * FROM ' . $this->_prefix . 'address ' .
				' WHERE title LIKE "%' . $this->escape($title) . '%" ' .
				' ORDER BY title ASC ' .
				' LIMIT 0, ' . $result_size;
		return $this->fetchRows($sql);
	}
}
