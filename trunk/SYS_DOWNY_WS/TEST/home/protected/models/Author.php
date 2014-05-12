<?php

/**
 * This is the model class for table "{{_author}}".
 *
 * The followings are the available columns in table '{{_author}}':
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
 * @property string $profesion
 * @property string $feature
 * @property string $brief
 */
class Author extends CActiveRecord
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
			array('user_id, real_name, degree, organization, phone, mobile, gender, birthday, identity, province, address, zip, title, language, subject, profesion, feature, brief', 'required'),
			array('user_id, gender, birthday, province, language', 'numerical', 'integerOnly'=>true),
			array('real_name, degree, organization, phone, mobile, address, title, subject, profesion', 'length', 'max'=>85),
			array('identity', 'length', 'max'=>18),
			array('zip', 'length', 'max'=>6),
			array('feature, brief', 'length', 'max'=>1024),
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
			'user_id' => 'User',
			'real_name' => 'Real Name',
			'degree' => 'Degree',
			'organization' => 'Organization',
			'phone' => 'Phone',
			'mobile' => 'Mobile',
			'gender' => 'Gender',
			'birthday' => 'Birthday',
			'identity' => 'Identity',
			'province' => 'Province',
			'address' => 'Address',
			'zip' => 'Zip',
			'title' => 'Title',
			'language' => 'Language',
			'subject' => 'Subject',
			'profesion' => 'Profesion',
			'feature' => 'Feature',
			'brief' => 'Brief',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Author the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
