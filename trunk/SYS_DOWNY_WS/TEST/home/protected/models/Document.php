<?php

/**
 * This is the model class for table "{{contrib_document}}".
 *
 * The followings are the available columns in table '{{contrib_document}}':
 * @property integer $id
 * @property integer $column
 * @property string $title
 * @property string $content
 * @property string $code
 * @property integer $create_time
 * @property integer $update_time
 */
class Document extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{document}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('column, title, content, code, create_time, update_time', 'required'),
			array('column, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('title, code', 'length', 'max' => 85),
			array('code', 'unique', 'message' => '{attribute}已经存在', 'on' => 'insert, update')
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
			'column0' => array(self::BELONGS_TO, 'Column', 'column'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '编号',
			'column' => '栏目',
			'title' => '标题',
			'content' => '内容',
			'code' => '标识',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Document the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
