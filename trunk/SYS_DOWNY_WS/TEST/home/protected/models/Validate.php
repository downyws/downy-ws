<?php

/**
 * This is the model class for table "contrib_validate".
 *
 * The followings are the available columns in table 'contrib_validate':
 * @property integer $id
 * @property integer $user_id
 * @property string $code
 * @property integer $create_time
 * @property integer $visit_time
 */
class Validate extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{validate}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive region inputs.
		return array(
			['user_id, code, create_time, visit_time', 'required']
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
			'user_id' => '用户编号',
			'code' => '代码',
			'create_time' => '创建时间',
			'visit_time' => '访问时间'
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Validate the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
