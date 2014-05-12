<?php

/**
 * This is the model class for table "contrib_region".
 *
 * The followings are the available columns in table 'contrib_region':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $level
 * @property string $region_sn
 * @property string $region_name
 * @property string $zip
 * @property integer $is_delete
 * @property integer $create_time
 */
class Region extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{region}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive region inputs.
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '编号',
			'parent_id' => '父节点编号',
			'level' => '等级',
			'region_sn' => '序号',
			'region_name' => '名称',
			'zip' => '邮编',
			'is_delete' => '是否是删除',
			'create_time' => '创建时间'
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getRegionInfo($id)
	{
		static $data = false;
		if($data === false)
		{
			$data = Yii::app()->cache->get('region_name');
		}
		if($data === false)
		{
			$temp = Region::model()->findAllByAttributes(['is_delete' => 0]);
			$data = [];
			foreach($temp as $v)
			{
				$data[$v['id']] = ['id' => $v['id'], 'level' => $v['level'], 'parent_id' => $v['parent_id'], 'region_name' => $v['region_name']];
			}
			Yii::app()->cache->set('region_name', $data, 864000);
		}
		return isset($data[$id]) ? $data[$id] : null;
	}

	public static function getFullRegionName($id = 0)
	{
		if(empty($id))
		{
			if(empty($this->id))
			{
				return '';
			}
			$id = $this->id;
		}
		
		static $data = false;
		if($data === false)
		{
			$data = Yii::app()->cache->get('full_region_name');
		}
		if($data === false)
		{
			$regions = Region::model()->findAllByAttributes(['is_delete' => 0]);
			$temp = [];
			foreach($regions as $v)
			{
				$temp[$v['id']] = ['id' => $v['id'], 'level' => $v['level'], 'parent_id' => $v['parent_id'], 'region_name' => $v['region_name']];
			}
			$data = [];
			foreach($temp as $v)
			{
				$r = $v['region_name'];
				$t = $v;
				while($t['level'] > 2)
				{
					$t = $temp[$t['parent_id']];
					$r = $t['region_name'] . ' ' . $r;
				}
				$data[$v['id']] = $r;
			}
			Yii::app()->cache->set('full_region_name', $data, 864000);
		}

		return isset($data[$id]) ? $data[$id] : '';
	}
}
