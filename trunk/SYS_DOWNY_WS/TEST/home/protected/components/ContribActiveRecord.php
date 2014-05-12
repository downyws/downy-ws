<?php

class ContribActiveRecord extends CActiveRecord
{
	public function insert($attributes = null)
	{
		if(isset($this->getMetaData()->columns['create_time']))
		{
			if(empty($attributes['create_time']) and empty($this->create_time))
			{
				$this->create_time = time();
			}
		}

		if(isset($this->getMetaData()->columns['update_time']))
		{
			if(empty($attributes['update_time']) and empty($this->update_time))
			{
				$this->update_time = time();
			}
		}

		return parent::insert($attributes);
	}

	public function update($attributes = null)
	{
		if(isset($this->getMetaData()->columns['update_time']))
		{
			$this->update_time = time();
		}

		return parent::update($attributes);
	}
}