<?php

/**
 * This is the model class for table "contrib_user".
 *
 * The followings are the available columns in table 'contrib_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $real_name
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
		return array(
			array('username, email, password, real_name', 'required', 'on' => 'insert'),
			array('username, email', 'unique', 'message' => '{attribute}已被注册', 'on' => 'insert'),
			array('username, password', 'length', 'min' => 6, 'max' => 32, 'on' => 'insert'),
			array('username', 'match', 'pattern' => '/^[a-z_0-9\-]+$/i', 'on' => 'insert'),
			array('email', 'email', 'on' => 'insert'),
			array('email, real_name', 'length', 'max' => 85)
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
			'roles' => [self::MANY_MANY, 'AuthItem', '{{auth_assignment}}(userid, itemname)', 'joinType' => 'INNER JOIN'],
			'author' => [self::HAS_ONE, 'Author', 'user_id', 'joinType' => 'INNER JOIN'],
		];
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
			'real_name' => '真实姓名',
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
