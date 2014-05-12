<?php

/**
 * This is the model class for table "{{contrib_author}}".
 *
 * The followings are the available columns in table '{{contrib_author}}':
 * @property integer $user_id
 * @property string $real_name
 * @property string $degree
 * @property string $organization
 * @property string $phone
 * @property string $mobile
 * @property integer $gender
 * @property integer $birthday
 * @property string $identity
 * @property integer $province
 * @property string $address
 * @property string $zip
 * @property string $title
 * @property integer $language
 * @property string $subject
 * @property string $feature
 * @property string $brief
 */
class Author extends ContribActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{author}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, real_name, degree, organization, phone, mobile, gender, birthday, identity, province, address, zip, title, language, subject, feature, brief', 'required'),
			array('user_id, gender, birthday, province, language', 'numerical', 'integerOnly' => true),
			array('real_name, degree, organization, phone, mobile, address, title, subject', 'length', 'max' => 85),
			array('identity', 'length', 'max' => 18),
			array('zip', 'length', 'max' => 6),
			array('feature, brief', 'length', 'max' => 1024),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
			'user' => [self::BELONGS_TO, 'User', 'user_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => '用户编号',
			'real_name' => '真实姓名',
			'degree' => '学位',
			'organization' => '工作单位',
			'phone' => '电话号码',
			'mobile' => '手机号码',
			'gender' => '性别',
			'identity' => '身份证号码',
			'region_id' => '地区编号',
			'address' => '通讯地址',
			'zip' => '邮编',
			'title' => '职称',
			'language' => '工作语言',
			'subject' => '研究方向',
			'feature' => '专长',
			'brief' => '个人简历',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Author the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
