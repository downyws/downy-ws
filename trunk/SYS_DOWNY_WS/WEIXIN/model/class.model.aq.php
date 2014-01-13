<?php
class ModelAQ extends Model
{
	public $_table = '';

	public function __construct()
	{
		parent::__construct($GLOBALS['CONFIG']['DB']);
	}
}
