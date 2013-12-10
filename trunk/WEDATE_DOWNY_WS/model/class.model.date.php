<?php
class ModelDate extends Model
{
	public $_table = 'date';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}

	public function getAll()
	{
		$fields = array(
			'id', 'location', 'meet_time', 
			'FROM_UNIXTIME(meet_time, \'%Y\') AS y', 
			'FROM_UNIXTIME(meet_time, \'%m\') AS m', 
			'FROM_UNIXTIME(meet_time, \'%d\') AS d'
		);
		return $this->getObjects(null, $fields);
	}
}
