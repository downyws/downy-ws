<?php

/**
 * This is the model class for table "contrib_column".
 *
 * The followings are the available columns in table 'contrib_column':
 * @property integer $id
 * @property string $title
 */
class Column extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{column}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title', 'required', 'on' => 'insert, update'),
			array('title', 'length', 'max' => 85)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '编号',
			'title' => '标题'
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Column the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
