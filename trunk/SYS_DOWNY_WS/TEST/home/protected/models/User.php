<?php

/**
 * This is the model class for table "contrib_user".
 *
 * The followings are the available columns in table 'contrib_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $role_id
 * @property integer $create_time
 * @property integer $visit_time
 */
class User extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, role_id', 'required'),
			array('id, role_id, create_time, visit_time', 'numerical', 'integerOnly' => true),
			array('username, password', 'length', 'max' => 32),
			array('email', 'length', 'max' => 85)
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
			'username' => '会员名',
			'password' => '密码',
			'email' => '邮箱',
			'role_id' => '角色编号',
			'create_time' => '创建时间',
			'visit_time' => '访问时间'
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
}
