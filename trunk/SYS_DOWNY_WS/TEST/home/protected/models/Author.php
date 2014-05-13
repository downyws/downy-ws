<?php

/**
 * This is the model class for table "{{contrib_author}}".
 *
 * The followings are the available columns in table '{{contrib_author}}':
 * @property integer $user_id
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
			array('organization, address', 'required', 'on' => 'insert, update'),
			array('identity', 'validatorIdentity', 'on' => 'insert, update'),
			array('gender, degree', 'validatorGenderDegree', 'on' => 'insert, update'),
			array('region_id', 'validatorRegionId', 'on' => 'insert, update'),
			array('zip', 'validatorZip', 'on' => 'insert, update'),
			array('language', 'validatorLanguage', 'on' => 'insert, update'),
			array('phone, mobile', 'validatorMobPho', 'on' => 'insert, update'),
			array('degree, organization, phone, mobile, address, title, subject', 'length', 'max' => 85),
			array('feature, brief', 'length', 'max' => 1024)
		);
	}

	public function validatorGenderDegree($attribute, $params)
	{
		$attribute_name = $this->attributeLabels()[$attribute];
		if(!isset($this->$attribute))
		{
			$this->addError($attribute, '请选择' . $attribute_name);
		}
		else if(!in_array($this->$attribute, array_keys(Yii::app()->params[$attribute])))
		{
			$this->addError($attribute, $attribute_name . '选择错误');
		}
	}

	public function validatorZip($attribute, $params)
	{
		$attribute_name = $this->attributeLabels()[$attribute];
		if(!preg_match('/^[1-9]\d{5}$/', $this['zip']))
		{
			$this->addError($attribute, $attribute_name . '格式错误');
		}
	}

	public function validatorLanguage($attribute, $params)
	{
		$attribute_name = $this->attributeLabels()[$attribute];
		if(
			isset($this->$attribute) && !empty($this->$attribute)
			&& !in_array($this->$attribute, array_keys(Yii::app()->params[$attribute]))
		)
		{
			$this->addError($attribute, $attribute_name . '选择错误');
		}
	}

	public function validatorRegionId($attribute, $params)
	{
		$attribute_name = $this->attributeLabels()[$attribute];
		if(!isset($this['region_id']))
		{
			$this->addError($attribute, '请选择' . $attribute_name);
		}
		else if(!Region::model()->findByAttributes(['id' => $this['region_id'], 'is_delete' => 0]))
		{
			$this->addError($attribute, $attribute_name . '选择错误');
		}
	}

	public function validatorMobPho($attribute, $params)
	{
		$attribute_name = $this->attributeLabels()[$attribute];
		if(
			(!isset($this['phone']) || $this['phone'] == '')
			&& (!isset($this['mobile']) || $this['mobile'] == '')
		)
		{
			$this->addError($attribute, $attribute_name . '电话和手机必须填写其一');
		}

		if(isset($this[$attribute]) && $this[$attribute] != '')
		{
			switch($attribute)
			{
				case 'phone':
					if(!preg_match('/^(\d{3,4}-)\d{7,8}(-\d{3,5})?$/', $this[$attribute]))
					{
						$this->addError($attribute, $attribute_name . '格式错误');
					}
					break;
				case 'mobile':
					if(!preg_match('/^1[34578]{1}\d{9}$/', $this[$attribute]))
					{
						$this->addError($attribute, $attribute_name . '格式错误');
					}
					break;
			}
		}
	}

	public function validatorIdentity($attribute, $params)
	{
		$attribute_name = $this->attributeLabels()[$attribute];
		$v_str = $this['identity'];

		$v_city = [
			'11', '12', '13', '14', '15',
			'21', '22', '23',
			'31', '32', '33', '34', '35', '36', '37',
			'41', '42', '43', '44', '45', '46',
			'50', '51', '52', '53', '54',
			'61', '62', '63', '64', '65',
			'71',
			'81', '82',
			'91'
		];

		if(!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $v_str))
		{
			$this->addError($attribute, $attribute_name . '格式错误');
			return;
		}
		else if(!in_array(substr($v_str, 0, 2), $v_city))
		{
			$this->addError($attribute, $attribute_name . '格式错误');
			return;
		}

		$v_str = preg_replace('/[xX]$/i', 'a', $v_str);
		$v_length = strlen($v_str);
		if($v_length == 18)
		{
			$v_birthday = substr($v_str, 6, 4) . '-' . substr($v_str, 10, 2) . '-' . substr($v_str, 12, 2);
		}
		else
		{
			$v_birthday = '19' . substr($v_str, 6, 2) . '-' . substr($v_str, 8, 2) . '-' . substr($v_str, 10, 2);
		}

		if(date('Y-m-d', strtotime($v_birthday)) != $v_birthday)
		{
			$this->addError($attribute, $attribute_name . '格式错误');
			return;
		}
		else if($v_length == 18)
		{
			$v_sum = 0;
			for($i = 17; $i >= 0; $i--)
			{
				$v_sub_str = substr($v_str, 17 - $i, 1);
				$v_sum += (pow(2, $i) % 11) * (($v_sub_str == 'a') ? 10 : intval($v_sub_str , 11));
			}
			if($v_sum % 11 != 1)
			{
				$this->addError($attribute, $attribute_name . '格式错误');
				return;
			}
		}
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
			'degree' => '学位',
			'organization' => '工作单位',
			'phone' => '电话号码',
			'mobile' => '手机号码',
			'gender' => '性别',
			'identity' => '身份证号码',
			'region_id' => '地区',
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
